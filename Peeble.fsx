#load "c:/Development/crazy/.paket/load/netStandard2.0/Transpiler/FSharp.Compiler.Service.fsx"
#I @"C:\development\Fable\src\Fable.Transforms"
#load @"Global\Fable.Core.fs" @"Global\Prelude.fs" @"Global\Compiler.fs" @"AST\AST.Common.fs" @"AST\AST.Fable.fs" @"MonadicTrampoline.fs" @"Transforms.Util.fs" @"OverloadSuffix.fs" @"FSharp2Fable.Util.fs" @"ReplacementsInject.fs" @"Replacements.fs" @"Inject.fs" @"FSharp2Fable.fs" @"FableTransforms.fs"
open System
open Fable
open FSharp.Compiler.SourceCodeServices
open System.Collections.Generic
open System.Collections.Concurrent
open System.IO

type Project(projectOptions: FSharpProjectOptions,
                implFiles: IDictionary<string, FSharpImplementationFileContents>,
                errors: FSharpErrorInfo array) =
    let projectFile = Path.normalizePath projectOptions.ProjectFileName
    let inlineExprs = ConcurrentDictionary<string, InlineExpr>()
    //let rootModules =
    //    implFiles |> Seq.map (fun kv ->
    //        kv.Key, FSharp2Fable.Compiler.getRootModuleFullName kv.Value) |> dict
    member __.ImplementationFiles = implFiles
    member __.RootModules = dict [] //rootModules
    member __.InlineExprs = inlineExprs
    member __.Errors = errors
    member __.ProjectOptions = projectOptions
    member __.ProjectFile = projectFile
    member __.GetOrAddInlineExpr(fullName, generate) =
        inlineExprs.GetOrAdd(fullName, fun _ -> generate())


type Log =
    { Message: string
      Tag: string
      Severity: Severity
      Range: SourceLocation option
      FileName: string option }

type Compiler(currentFile, project: Project, options, fableLibraryDir: string) =
    let mutable id = 0
    let logs = ResizeArray<Log>()
    let fableLibraryDir = fableLibraryDir.TrimEnd('/')
    member __.GetLogs() =
        logs |> Seq.toList
    member __.GetFormattedLogs() =
        let severityToString = function
            | Severity.Warning -> "warning"
            | Severity.Error -> "error"
            | Severity.Info -> "info"
        logs
        |> Seq.groupBy (fun log -> severityToString log.Severity)
        |> Seq.map (fun (severity, logs) ->
            logs |> Seq.map (fun log ->
                match log.FileName with
                | Some file ->
                    match log.Range with
                    | Some r -> sprintf "%s(%i,%i): (%i,%i) %s %s: %s" file r.start.line r.start.column r.``end``.line r.``end``.column severity log.Tag log.Message
                    | None -> sprintf "%s(1,1): %s %s: %s" file severity log.Tag log.Message
                | None -> log.Message)
            |> Seq.toArray
            |> Tuple.make2 severity)
        |> Map
    member __.Options = options
    member __.CurrentFile = currentFile
    interface ICompiler with
        member __.Options = options
        member __.LibraryDir = fableLibraryDir
        member __.CurrentFile = currentFile
        member x.GetRootModule(fileName) =
            let fileName = Path.normalizePathAndEnsureFsExtension fileName
            match project.RootModules.TryGetValue(fileName) with
            | true, rootModule -> rootModule
            | false, _ ->
                let msg = sprintf "Cannot find root module for %s. If this belongs to a package, make sure it includes the source files." fileName
                (x :> ICompiler).AddLog(msg, Severity.Warning)
                "" // failwith msg
        member __.GetOrAddInlineExpr(fullName, generate) =
            project.InlineExprs.GetOrAdd(fullName, fun _ -> generate())
        member __.AddLog(msg, severity, ?range, ?fileName:string, ?tag: string) =
            { Message = msg
              Tag = defaultArg tag "FABLE"
              Severity = severity
              Range = range
              FileName = fileName }
            |> logs.Add
        // TODO: If name includes `$$2` at the end, remove it
        member __.GetUniqueVar(name) =
            id <- id + 1
            Naming.getUniqueName (defaultArg name "var") id



type PhpConst =
    | PhpConstNumber of float
    | PhpConstString of string
    | PhpConstBool of bool
    | PhpConstNull
    | PhpConstUnit

type PhpArrayIndex =
    | PhpArrayNoIndex
    | PhpArrayInt of int
    | PhpArrayString of string
type PhpField =
    { Name: string 
      Type: string }

type Capture =
    | ByValue of string
    | ByRef of string

type Prop =
    | Field of PhpField
    | StrField of string

and PhpExpr =
    | PhpVar of string * typ: PhpType option
    | PhpGlobal of string
    | PhpConst of PhpConst
    | PhpUnaryOp of string * PhpExpr
    | PhpBinaryOp of string *PhpExpr * PhpExpr
    | PhpProp of PhpExpr * Prop * typ: PhpType option
    | PhpArrayAccess of PhpExpr * PhpExpr
    | PhpNew of ty:PhpType * args:PhpExpr list
    | PhpArray of args: (PhpArrayIndex * PhpExpr) list
    | PhpCall of f: PhpExpr * args: PhpExpr list
    | PhpMethod of this: PhpExpr * func:string * args: PhpExpr list
    | PhpTernary of gard: PhpExpr * thenExpr: PhpExpr * elseExpr: PhpExpr
    | PhpIsA of expr: PhpExpr * PhpType
    | PhpAnonymousFunc of args: string list * uses: Capture list * body: PhpStatement list
    | PhpMacro of macro: string * args: PhpExpr list
   
and PhpStatement =
    | Return of PhpExpr
    | Expr of PhpExpr
    | Switch of PhpExpr * (PhpCase * PhpStatement list) list
    | Break
    | Assign of target:PhpExpr * value:PhpExpr
    | If of guard: PhpExpr * thenCase: PhpStatement list * elseCase: PhpStatement list
    | Throw of string
    | Do of PhpExpr
and PhpCase =
    | IntCase of int
    | StringCase of string
    | DefaultCase

and PhpFun = 
    { Name: string
      Args: string list
      Matchings: PhpStatement list
      Body: PhpStatement list
      Static: bool
    }

and PhpType =
    { Name: string
      Fields: PhpField list;
      Methods: PhpFun list
      Abstract: bool
      BaseType: PhpType option
      Interfaces: PhpType list
    }


type PhpDecl =
    | PhpFun of PhpFun
    | PhpDeclValue of name:string * PhpExpr
    | PhpType of PhpType

type PhpFile =
    { Decls: (int * PhpDecl) list }


module Output =
    type Writer =
        { Writer: TextWriter
          Indent: int
          Precedence: int }

    let indent ctx =
        { ctx with Indent = ctx.Indent + 1}

    module Writer =
        let create w =
            { Writer = w; Indent = 0; Precedence = Int32.MaxValue }

    let writeIndent  ctx =
        for _ in 1 .. ctx.Indent do
            ctx.Writer.Write("    ")

    let write ctx txt =
        ctx.Writer.Write(txt: string)


    let writeln ctx txt =
         ctx.Writer.WriteLine(txt: string)

    let writei ctx txt =
        writeIndent ctx
        write ctx txt

    let writeiln ctx txt =
        writeIndent ctx
        writeln ctx txt
      
    let writeVarList ctx vars =
        let mutable first = true
        for var in vars do
            if first then
                first <- false
            else
                write ctx ", "
            write ctx "$"
            write ctx var
    let writeUseList ctx vars =
        let mutable first = true
        for var in vars do
            if first then
                first <- false
            else
                write ctx ", "
            match var with
            | ByValue v ->
                write ctx "$"
                write ctx v
            | ByRef v ->
                write ctx "&$"
                write ctx v
             
    module Precedence =
        let binary =
            function
            | "*" | "/" | "%"         -> 3
            | "+" | "-" | "."         -> 4
            | "<<" | ">>"             -> 5
            | "<" | "<=" | ">=" | ">" -> 7
            | "==" | "!=" | "===" 
            | "!==" | "<>" | "<=>"    -> 7
            | "&" -> 8
            | "^" -> 9
            | "|" -> 10
            | "&&" -> 11
            | "||" -> 12
            | "??" -> 13
            | op -> failwithf "Unknown binary operator %s" op


        let unary =
            function
            | "!" -> 2
            | "-" -> 4
            | "&" -> 8
            | op -> failwithf "Unknown unary operator %s" op

        let _new = 0 
        let instanceOf = 1
        let ternary = 14 
        let assign = 15
            

        let clear ctx = { ctx with Precedence = Int32.MaxValue} 

    let withPrecedence ctx prec f =
        let useParens = prec > ctx.Precedence
        let subCtx = { ctx with Precedence = prec }
        if useParens then
            write subCtx "("

        f subCtx

        if useParens then
            write subCtx ")"

    let rec writeExpr ctx expr =
        match expr with
        | PhpBinaryOp(op, left, right) ->
            withPrecedence ctx (Precedence.binary op)
                (fun subCtx ->
                    writeExpr subCtx left
                    write subCtx " "
                    write subCtx op
                    write subCtx " "
                    writeExpr subCtx right)

        | PhpUnaryOp(op, expr) ->
            withPrecedence ctx (Precedence.unary op)
                (fun subCtx ->
                    write subCtx op
                    writeExpr subCtx expr )
        | PhpConst cst -> 
            match cst with
            | PhpConstNumber n -> write ctx (string n)
            | PhpConstString s -> 
                write ctx "'"
                write ctx (s.Replace("'",@"\'"))
                write ctx "'"
            | PhpConstBool true -> write ctx "true"
            | PhpConstBool false -> write ctx "false"
            | PhpConstNull -> write ctx "NULL"
            | PhpConstUnit -> write ctx "NULL"
        | PhpVar (v,_) -> 
            write ctx "$"
            write ctx v
        | PhpGlobal v -> 
            write ctx "$GLOBALS['"
            write ctx v
            write ctx "']"
        | PhpProp(l,r, _) ->
            writeExpr ctx l
            write ctx "->"
            match r with
            | Field r -> write ctx r.Name
            | StrField r -> write ctx r
        | PhpNew(t,args) ->
            withPrecedence ctx (Precedence._new)
                (fun subCtx ->
                    write subCtx "new "
                    write subCtx t.Name
                    write subCtx "("
                    writeArgs subCtx args
                    write subCtx ")")
        | PhpArray(args) ->
            write ctx "[ "
            let mutable first = true
            for key,value in args do
                if first then
                    first <- false
                else
                    write ctx ", "
                writeArrayIndex ctx key
                writeExpr ctx value
            write ctx "]"
        | PhpArrayAccess(array, index) ->
            writeExpr ctx array
            write ctx "["
            writeExpr ctx index
            write ctx "]"

        | PhpCall(f,args) ->
            let anonymous = match f with PhpAnonymousFunc _ -> true | _ -> false
            if anonymous then
                write ctx "("
            match f with
            | PhpConst (PhpConstString f) ->
                write ctx f
            | _ -> writeExpr ctx f
            if anonymous then
                write ctx ")"
            write ctx "("
            writeArgs ctx args
            write ctx ")"
        | PhpMethod(this,f,args) ->
            writeExpr ctx this
            write ctx "->"
            write ctx f
            write ctx "("
            writeArgs ctx args
            write ctx ")"
        | PhpTernary (guard, thenExpr, elseExpr) ->
            withPrecedence ctx (Precedence.ternary)
                (fun ctx ->
                    writeExpr ctx guard
                    write ctx " ? "
                    writeExpr ctx thenExpr
                    write ctx " : "
                    writeExpr ctx elseExpr)
        | PhpIsA (expr, t) ->
            withPrecedence ctx (Precedence.instanceOf)
                (fun ctx ->
                    writeExpr ctx expr
                    write ctx " instanceof "
                    write ctx t.Name)
        | PhpAnonymousFunc(args, uses, body) ->
            write ctx "function ("
            writeVarList ctx args
            write ctx ")"
            match uses with
            | [] -> ()
            | _ ->
                write ctx " use ("
                writeUseList ctx uses
                write ctx ")"
            
            write ctx " { "
            let multiline = body.Length > 1 
            let bodyCtx =
                if multiline then
                    writeln ctx ""
                    indent ctx
                else
                    ctx
            for st in  body do
                writeStatement bodyCtx st
            if multiline then
                writei ctx "}"
            else
                write ctx " }"
        | PhpMacro(macro, args) ->
            let regex = System.Text.RegularExpressions.Regex("\$(?<n>\d)(?<s>\.\.\.)?")
            let matches = regex.Matches(macro)
            let mutable pos = 0
            for m in matches do
                let n = int m.Groups.["n"].Value
                write ctx (macro.Substring(pos,m.Index-pos))
                if m.Groups.["s"].Success then
                    match args.[n] with
                    | PhpArray items ->
                       let mutable first = true
                       for _,value in items do
                           if first then
                               first <- false
                           else
                               write ctx ", "
                           writeExpr ctx value 


                    | _ -> failwith "Splice param should be a array"

                else
                    writeExpr ctx args.[n]
                pos <- m.Index + m.Length
            write ctx (macro.Substring(pos))


    and writeArgs ctx args =
        let mutable first = true
        for arg in args do
            if first then
                first <- false
            else
                write ctx ", "
            writeExpr ctx arg
    and writeArrayIndex ctx index =
        match index with
        | PhpArrayString s  ->
            write ctx "'"
            write ctx s
            write ctx "' => "
        | PhpArrayInt n  ->
            write ctx (string n)
            write ctx " => "
        | PhpArrayNoIndex ->
            ()

        
    and writeStatement ctx st =
        match st with
        | PhpStatement.Return expr ->
            writei ctx "return "
            writeExpr (Precedence.clear ctx) expr
            writeln ctx ";"
        | Expr expr ->
            writei ctx ""
            writeExpr (Precedence.clear ctx) expr
            writeln ctx ";"
        | Assign(name, expr) ->
            writei ctx ""
            writeExpr (Precedence.clear ctx)  name
            write ctx " = "
            writeExpr (Precedence.clear ctx)  expr
            writeln ctx ";"
        | Switch(expr, cases) ->
            writei ctx "switch ("
            writeExpr (Precedence.clear ctx)  expr
            writeln ctx ")"
            writeiln ctx "{"
            let casesCtx = indent ctx
            let caseCtx = indent casesCtx
            for case,sts in cases do
                match case with
                | IntCase i -> 
                    writei casesCtx "case "
                    write casesCtx (string i)
                | StringCase s -> 
                    writei casesCtx "case '"
                    write casesCtx s
                    write casesCtx "'"
                | DefaultCase ->
                    writei casesCtx "default"
                writeln casesCtx ":"
                for st in sts do
                    writeStatement caseCtx st

            writeiln ctx "}"
        | Break ->
            writeiln ctx "break;"
        | If(guard, thenCase, elseCase) ->
            writei ctx "if ("
            writeExpr (Precedence.clear ctx) guard
            writeln ctx ") {"
            let body = indent ctx
            for st in thenCase do
                writeStatement body st
            writeiln ctx "} else {"
            for st in elseCase do
                writeStatement body st
            writeiln ctx "}"
        | Throw s ->
            writei ctx "throw new Exception('"
            write ctx s
            writeln ctx "');"
        | PhpStatement.Do (PhpConst PhpConstUnit)-> ()
        | PhpStatement.Do (expr) ->
            writei ctx ""
            writeExpr (Precedence.clear ctx) expr
            writeln ctx ";"


    let writeFunc ctx (f: PhpFun) =
        writei ctx ""
        if f.Static then
            write ctx "static "
        
        write ctx "function "
        write ctx f.Name
        write ctx "("
        let mutable first = true
        for arg in f.Args do
            if first then
                first <- false
            else
                write ctx ", "
            write ctx "$"
            write ctx arg
        writeln ctx ") {"
        let bodyCtx = indent ctx
        for s in f.Matchings do
            writeStatement bodyCtx s

        for s in f.Body do
            writeStatement bodyCtx s
        writeiln ctx "}"
            
    let writeField ctx (m: PhpField) =
        writei ctx "public $"
        write ctx m.Name
        writeln ctx ";"

    let writeCtor ctx (t: PhpType) =
        writei ctx "function __construct("
        let mutable first = true
        for p in t.Fields do
            if first then
                first <- false
            else
                write ctx ", "
            //write ctx p.Type
            write ctx "$"
            write ctx p.Name
        writeln ctx ") {"
        let bodyctx = indent ctx
        for p in t.Fields do
            writei bodyctx "$this->"
            write bodyctx p.Name
            write bodyctx " = $"
            write bodyctx p.Name
            writeln bodyctx ";"

        writeiln ctx "}"

    let writeType ctx (t: PhpType) =
        writei ctx ""
        if t.Abstract then
            write ctx "abstract "
        write ctx "class "
        write ctx t.Name
        match t.BaseType with
        | Some t ->
            write ctx " extends "
            write ctx t.Name
        | None -> ()

        if t.Interfaces <> [] then 
            write ctx " implements "
            let mutable first = true
            for itf in t.Interfaces do
                if first then
                    first <- false
                else
                    write ctx ", "
                write ctx itf.Name

        writeln ctx " {" 
        let mbctx = indent ctx
        for m in t.Fields do
            writeField mbctx m

        if not t.Abstract then
            writeCtor mbctx t

        for m in t.Methods do
            writeFunc mbctx m

        writeiln ctx "}"


    let writeAssign ctx n expr =
        writei ctx "$GLOBALS['"
        write ctx n
        write ctx "'] = "
        writeExpr ctx expr
        writeln ctx ";"


    let writeDecl ctx d =
        match d with
        | PhpType t -> writeType ctx t
        | PhpFun t -> writeFunc ctx t
        | PhpDeclValue(n,expr) -> writeAssign ctx n expr

    let writeFile ctx (file: PhpFile) =
        writeln ctx "<?php"
        for i,d in file.Decls do
            writeln ctx ( "#" + string i)
            writeDecl ctx d
            writeln ctx ""




open Fable.AST

module PhpList =
    let list  = { Name = "FSharpList"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = [] }
    let value = { Name = "value"; Type = "" }
    let next = { Name = "next"; Type = "FSharpList" }
    let cons = { Name = "Cons"; Fields = [ value; next ]; Methods = []; Abstract = false; BaseType = Some list; Interfaces = [] } 
    let nil = { Name = "Nil"; Fields = []; Methods = []; Abstract = false; BaseType = Some list; Interfaces = [] }

module PhpResult =
    let result = { Name = "Result"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []}
    let ok = { Name = "Ok"; Fields = []; Methods = []; Abstract = true; BaseType = Some result; Interfaces = [] }
    let errorValue = { Name = "ErrorValue"; Type = ""}
    let error = { Name = "Error"; Fields = [errorValue] ; Methods = []; Abstract = true; BaseType = Some result; Interfaces = [] }

module PhpUnion =
    let union = { Name = "Union"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []}
    let fSharpUnion = { Name = "FSharpUnion"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []}



type PhpCompiler =
    { mutable Types: Map<string,PhpType> 
      mutable DecisionTargets: (Fable.Ident list * Fable.Expr) list
      mutable LocalVars: string Set
      mutable CapturedVars: Capture Set
      mutable Id: int
    }
    static member empty =

        { Types = Map.ofList [ "List" , PhpList.list
                               "Cons" , PhpList.cons
                               "Nil", PhpList.nil
                               "Result", PhpResult.result
                               "Ok", PhpResult.ok
                               "Error", PhpResult.error
                               ]  
          DecisionTargets = []
          LocalVars = Set.empty
          CapturedVars = Set.empty
          Id = 0
          }
    member this.AddType(phpType: PhpType) =
        this.Types <- Map.add phpType.Name phpType this.Types
        phpType

    member this.AddLocalVar(var) =
        this.LocalVars <- Set.add var this.LocalVars

    member this.UseVar(var) =
        if not (Set.contains var this.LocalVars) then
            this.CapturedVars <- Set.add (ByValue var) this.CapturedVars

    member this.UseVarByRef(var) =
        if not (Set.contains var this.LocalVars) then
            this.CapturedVars <- Set.add (ByRef var) this.CapturedVars
    member this.UseVar(var) =
        let name = match var with ByValue n | ByRef n -> n
        if not (Set.contains name this.LocalVars) then
            this.CapturedVars <- Set.add var this.CapturedVars

    member this.MakeUniqueVar(name) =
        this.Id <- this.Id + 1
        "_" + name + "__" + string this.Id

    member this.NewScope() =
        { this with 
            LocalVars = Set.empty
            CapturedVars = Set.empty }


let convertType (t: FSharpType) =
    if (t.IsAbbreviation) then
        t.Format(FSharpDisplayContext.Empty.WithShortTypeNames(true))
    else
        match t with
        | Symbol.TypeWithDefinition entity ->
            match entity.CompiledName with
            | "FSharpSet`1" -> "Set"
            | name -> name
        | _ ->
            failwithf "%A" t
       

let fixName (name: string) =
    name.Replace('$','_')

let caseName (case: FSharpUnionCase) =
    let entity = case.ReturnType.TypeDefinition
    if entity.UnionCases.Count = 1 || entity.CompiledName = "FSharpResult`2" then
        case.Name
    else
        entity.CompiledName + "_" + case.Name


let convertUnion (ctx: PhpCompiler) (info: Fable.UnionConstructorInfo) = 
    if info.Entity.UnionCases.Count = 1 then
        let case = info.Entity.UnionCases.[0] 
        [ let t =
            { Name = case.Name
              Fields = [ for e in case.UnionCaseFields do 
                            { Name = e.Name 
                              Type  = convertType e.FieldType } ]
              Methods = [ 
                  { PhpFun.Name = "get_FSharpCase";
                    PhpFun.Args = []
                    PhpFun.Matchings = []
                    PhpFun.Static = false
                    PhpFun.Body = 
                      [ PhpStatement.Return(PhpConst(PhpConstString(case.Name)))] } 
              ]
              Abstract = false
              BaseType = None
              Interfaces = [ PhpUnion.fSharpUnion ]
              }
          ctx.AddType(t) |> PhpType ]
    else
    [ let baseType =
            { Name = info.Entity.CompiledName
              Fields = []
              Methods = []
              Abstract = true 
              BaseType = None
              Interfaces = [PhpUnion.union; PhpUnion.fSharpUnion ]}
      ctx.AddType(baseType) |> PhpType

      for case in info.Entity.UnionCases do
        let t = 
            { Name = caseName case
              Fields = [ for e in case.UnionCaseFields do 
                            { Name = e.Name 
                              Type  = convertType e.FieldType } ]
              Methods = [ { PhpFun.Name = "get_Case";
                            PhpFun.Args = []
                            PhpFun.Matchings = []
                            PhpFun.Static = false
                            PhpFun.Body = 
                                [ PhpStatement.Return(PhpConst(PhpConstString(caseName case)))]
                            } 
                          { PhpFun.Name = "get_FSharpCase";
                            PhpFun.Args = []
                            PhpFun.Matchings = []
                            PhpFun.Static = false
                            PhpFun.Body = 
                                [ PhpStatement.Return(PhpConst(PhpConstString(case.Name)))]
                            } 
                            ]
              Abstract = false
              BaseType = Some baseType
              Interfaces = [] }
        ctx.AddType(t) |> PhpType ]

let convertRecord (ctx: PhpCompiler) (info: Fable.CompilerGeneratedConstructorInfo) = 
    [ let t =
        { Name = info.Entity.CompiledName
          Fields = [ for e in info.Entity.FSharpFields do 
                        { Name = e.Name 
                          Type  = convertType e.FieldType } ]
          Methods = [ ]
          Abstract = false
          BaseType = None
          Interfaces = []}
      ctx.AddType(t) |> PhpType ]

type ReturnStrategy =
    | Return
    | Let of string
    | Do
    | Target of string


let convertTest ctx test phpExpr =
    match test with
    | Fable.TestKind.UnionCaseTest(case,_) ->
        let t = Map.find (caseName case) ctx.Types
        PhpIsA(phpExpr, t)
    | Fable.TestKind.ListTest(isCons) ->
        PhpIsA(phpExpr, if isCons then PhpList.cons else PhpList.nil)
    | Fable.OptionTest(isSome) ->
       let isNull = PhpCall(PhpConst (PhpConstString "is_null"), [phpExpr])
       if isSome then
           PhpUnaryOp("!",isNull)
       else
           isNull 


let rec getExprType =
    function
    | PhpVar(_, t) -> t
    | PhpProp(_,_, t) -> t
    | _ -> None

let rec convertExpr (ctx: PhpCompiler) (expr: Fable.Expr) =
    match expr with
    | Fable.Value(value,_) ->
        convertValue ctx value

    | Fable.Operation(Fable.BinaryOperation(op,left,right),t,_) ->
        let opstr =
            match op with
            | BinaryOperator.BinaryMultiply -> "*"
            | BinaryOperator.BinaryPlus ->
                match t with
                | Fable.Type.String -> "."
                | _ -> "+"
            | BinaryOperator.BinaryMinus -> "-"
            | BinaryOperator.BinaryLess -> "<"
            | BinaryOperator.BinaryGreater -> ">"
            | BinaryOperator.BinaryLessOrEqual -> "<="
            | BinaryOperator.BinaryGreaterOrEqual -> ">="
            | BinaryOperator.BinaryAndBitwise -> "&"
            | BinaryOperator.BinaryOrBitwise -> "|"
            | BinaryOperator.BinaryEqual -> "=="
            | BinaryOperator.BinaryEqualStrict -> "==="
            | BinaryOperator.BinaryUnequalStrict -> "!=="
            | BinaryOperator.BinaryModulus -> "%"
            | BinaryOperator.BinaryDivide -> "/"
        PhpBinaryOp(opstr, convertExpr ctx left, convertExpr ctx right)
    | Fable.Operation(Fable.UnaryOperation(op, expr),_,_) ->
        let opStr = 
            match op with
            | UnaryOperator.UnaryNot -> "!"
            | UnaryOperator.UnaryMinus -> "-"
            | UnaryOperator.UnaryPlus -> "+"

        PhpUnaryOp(opStr, convertExpr ctx expr)

    | Fable.Operation(Fable.Call(Fable.StaticCall(Fable.Import(Fable.Value(Fable.StringConstant s, _ ) ,p,k,ty,_)), args),t,_) ->
        match k,p with
        | Fable.ImportKind.Library, Fable.Value(Fable.StringConstant cls,_) ->
            match s with
            | "op_UnaryNegation_Int32" -> PhpUnaryOp("-", convertExpr ctx args.Args.[0])
            | "join" -> PhpCall(PhpConst(PhpConstString "join"), convertArgs ctx args)
            | _ -> 
                let phpCls =
                    match cls with
                    | "List" -> "FSharpList"
                    | "Array" -> "FSharpArray"
                    | _ -> cls


                PhpCall(PhpConst(PhpConstString (phpCls + "::" + fixName s)), convertArgs ctx args)
        | _ -> PhpCall(PhpConst(PhpConstString (fixName s)), convertArgs ctx args)
    | Fable.Operation(Fable.Call(Fable.StaticCall(Fable.Get(Fable.IdentExpr(i),Fable.ExprGet(Fable.Value(Fable.StringConstant(m),_)),_,_)),args),_,_) ->
        let f = 
            match i.Name ,m with
            | "Math", "abs" -> "abs"
            | name, m -> fixName name + "::" + fixName m
        PhpCall(PhpConst(PhpConstString (f)), convertArgs ctx args)
    | Fable.Operation(Fable.Call(Fable.StaticCall(Fable.IdentExpr(i)),args),_,_) ->
        //PhpCall(PhpConst(PhpConstString (fixName i.Name)), convertArgs ctx args)
        let name = fixName i.Name
        ctx.UseVarByRef(name)
        PhpCall(PhpVar(name, None), convertArgs ctx args)


        
    | Fable.Operation(Fable.Call(Fable.InstanceCall( Some (Fable.Value(Fable.StringConstant s, _ ))),{ Args = args; ThisArg = Some this}), _, _) ->
        PhpMethod(convertExpr ctx this,fixName s, [for arg in args -> convertExpr ctx arg ] )
    | Fable.Operation(Fable.CurriedApply(expr, args),_,_) ->
        PhpCall(convertExpr ctx expr, [for arg in args -> convertExpr ctx arg]) 

    | Fable.Operation(Fable.Emit(macro,args),_,_) ->
        match args with
        | None -> PhpMacro(macro, [])
        | Some args ->
            PhpMacro(macro, [for arg in args.Args -> convertExpr ctx arg])
    | Fable.Get(expr, kind ,t,_) ->
        let phpExpr = convertExpr ctx expr
        match kind with 
        | Fable.UnionField(f,case,_) ->
            let name = caseName case
                
            let t = Map.find name ctx.Types
            let field = t.Fields |> List.tryFind (fun ff -> ff.Name = f.Name)
            match field with
            | Some field ->
                let fieldType = Map.tryFind field.Type ctx.Types
                PhpProp(phpExpr, Field field, fieldType)
            | None -> PhpProp(phpExpr, StrField f.Name, None)
        | Fable.OptionValue ->
            phpExpr
        | Fable.FieldGet(fieldName,_,_) ->
            match getExprType phpExpr with
            | Some phpType ->
                let field = phpType.Fields |> List.find (fun f -> f.Name = fieldName)
                PhpProp(phpExpr, Field field, Map.tryFind field.Type ctx.Types ) 
            | None -> PhpProp(phpExpr, StrField fieldName, None)
         
        | Fable.GetKind.TupleGet(id) ->
            PhpArrayAccess(phpExpr, PhpConst(PhpConstNumber (float id))) 
        | Fable.ExprGet(expr') ->
            let prop = convertExpr ctx expr'
            match prop with
            | PhpConst(PhpConstString "length") ->
                PhpCall(PhpConst(PhpConstString "count"), [phpExpr])
            | _ -> PhpArrayAccess(phpExpr, prop)
        | Fable.ListHead ->
            PhpProp(phpExpr, Field PhpList.value, getExprType phpExpr)
        | Fable.ListTail ->
            PhpProp(phpExpr, Field PhpList.next, getExprType phpExpr)
        | Fable.UnionTag ->
            PhpCall(PhpConst(PhpConstString ("get_class")), [phpExpr])



    | Fable.IdentExpr(id) ->
        let name = fixName id.Name
        ctx.UseVar(name)
        let phpType = 
            match id.Type with
            | Fable.Type.DeclaredType(e,_) ->
                Map.tryFind e.CompiledName ctx.Types

            | _ -> None 
        
        PhpVar(name, phpType)
    | Fable.Import(expr,p,k,t,_) ->
        match convertExpr ctx expr,t with
        | PhpConst (PhpConstString s), Fable.Any  ->
            match p with
            | Fable.Value(Fable.StringConstant p, _) when p <> "." -> PhpConst (PhpConstString ( p + "::" + fixName s))
            | _ -> PhpConst (PhpConstString (fixName s))
        | PhpConst (PhpConstString s), _ -> PhpGlobal (fixName s)
        | exp, _ -> exp

    | Fable.DecisionTree(expr,targets) ->
        let upperTargets = ctx.DecisionTargets
        ctx.DecisionTargets <- targets
        let phpExpr = convertExpr ctx expr
        ctx.DecisionTargets <- upperTargets
        phpExpr

    | Fable.IfThenElse(guard, thenExpr, elseExpr,_) ->
        PhpTernary(convertExpr ctx guard,
                convertExpr ctx thenExpr,
                convertExpr ctx elseExpr )
    | Fable.Test(expr, test , _ ) ->
        let phpExpr = convertExpr ctx expr
        convertTest ctx test phpExpr
            
        
    | Fable.DecisionTreeSuccess(index,_,_) ->
        let _,target = ctx.DecisionTargets.[index]
        convertExpr ctx target

    | Fable.ObjectExpr(members, t, baseCall) ->
         PhpArray [
            for m in members do
                match m with
                | Fable.ObjectMember(Fable.Value(Fable.StringConstant key,_) ,value,kind) ->
                    PhpArrayString key , convertExpr ctx value
         ]
    | Fable.Function(kind,body,_) ->
        convertFunction ctx kind body

      
    | Fable.Let([], body) ->
        convertExpr ctx body
    | Fable.Let(bindings, body) ->
        let innerCtx = ctx.NewScope()
        for id,_ in bindings do
            innerCtx.AddLocalVar(fixName id.Name)
        let body = convertExprToStatement innerCtx expr Return
        for capturedVar in innerCtx.CapturedVars do
            ctx.UseVar(capturedVar)
        PhpCall(PhpAnonymousFunc([], Set.toList innerCtx.CapturedVars , body),[])

    | Fable.Expr.TypeCast(expr, t) ->
        convertExpr ctx expr
        


and convertArgs ctx (args: Fable.ArgInfo) =
    [ match args.ThisArg with
      | Some arg -> convertExpr ctx arg
      | None -> ()
      for arg in args.Args do 
        match arg with
        | Fable.IdentExpr({ Name = "Array"; Kind = Fable.CompilerGenerated }) -> ()
        | _ -> convertExpr ctx arg
    ]
        
        
and convertFunction (ctx: PhpCompiler) kind body =
    let scope = ctx.NewScope()
    let args = 
        match kind with
        | Fable.Lambda(arg) ->
            let argName = fixName arg.Name
            scope.AddLocalVar argName
            [argName]
        | Fable.Delegate(args) ->
            [ for arg in args do
                let argName = fixName arg.Name
                scope.AddLocalVar argName
                argName ]
 
    let phpBody = convertExprToStatement scope body Return

    for capturedVar in scope.CapturedVars do
        ctx.UseVar(capturedVar)
    PhpAnonymousFunc(args, Set.toList scope.CapturedVars , phpBody ) 

and convertValue (ctx:PhpCompiler) (value: Fable.ValueKind) =
    match value with
    | Fable.NewUnion(args,case,_,_) ->
        let t = Map.find (caseName case) ctx.Types
        PhpNew(t, [for arg in args do convertExpr ctx arg ])
    | Fable.NewTuple(args) ->
        
        PhpArray([for arg in args do (PhpArrayNoIndex, convertExpr ctx arg)])
    | Fable.NewRecord(args, Fable.DeclaredRecord(e), _) ->
        let t = ctx.Types.[e.CompiledName]
        PhpNew(t, [ for arg in args do convertExpr ctx arg ] )
        

    | Fable.NumberConstant(v,_) ->
        PhpConst(PhpConstNumber v)
    | Fable.StringConstant(s) ->
        PhpConst(PhpConstString s)
    | Fable.BoolConstant(b) ->
        PhpConst(PhpConstBool b)
    | Fable.UnitConstant ->
        PhpConst(PhpConstUnit)
    | Fable.Null _ ->
        PhpConst(PhpConstNull)
    | Fable.NewList(Some(head,tail),_) ->
        PhpNew(PhpList.cons, [convertExpr ctx head; convertExpr ctx tail])
    | Fable.NewList(None,_) ->
        PhpCall(PhpConst(PhpConstString("FSharpList::get_Nil")),[])
    | Fable.NewArray(Fable.NewArrayKind.ArrayValues(values),_) ->
        PhpArray([for v in values -> (PhpArrayNoIndex, convertExpr ctx v)])

    | Fable.NewOption(opt,_) ->
        match opt with
        | Some expr -> convertExpr ctx expr
        | None -> PhpConst(PhpConstNull)
    



and canBeCompiledAsSwitch evalExpr tree =
    match tree with
    | Fable.IfThenElse(Fable.Test(caseExpr, Fable.UnionCaseTest(case,e),_), Fable.DecisionTreeSuccess(index,_,_), elseExpr,_) 
        when caseExpr = evalExpr ->
        canBeCompiledAsSwitch evalExpr elseExpr
    | Fable.DecisionTreeSuccess(index, _,_) ->
        true
    | _ -> false

and findCasesNames evalExpr tree =

    [ match tree with
      | Fable.IfThenElse(Fable.Test(caseExpr, Fable.UnionCaseTest(case,e),_), Fable.DecisionTreeSuccess(index,bindings,_), elseExpr,_)
            when caseExpr = evalExpr ->
            Some case, bindings, index
            yield! findCasesNames evalExpr elseExpr
      | Fable.DecisionTreeSuccess(index, bindings,_) ->
            None, bindings, index
      | _ -> ()
    ]

and hasGroupedCases indices tree =
    match tree with
    | Fable.IfThenElse(Fable.Test(_, _, _), Fable.DecisionTreeSuccess(index,_,_), elseExpr,_) ->
        if Set.contains index indices then
            true
        else
            hasGroupedCases (Set.add index indices) elseExpr
    | Fable.DecisionTreeSuccess(index, _, _) ->
        if Set.contains index indices then
            true
        else
            false
    | Fable.IfThenElse(Fable.Test(_, _, _), _,_,_) ->
        false

and getCases cases tree =
    match tree with
    | Fable.IfThenElse(Fable.Test(_, _, _), Fable.DecisionTreeSuccess(index,boundValues,_), elseExpr,_) ->
        getCases (Map.add index boundValues cases) elseExpr
    | Fable.DecisionTreeSuccess(index, boundValues, _) ->
        Map.add index boundValues cases
    | Fable.IfThenElse(Fable.Test(_, _, _), _,_,_) ->
        cases


and convertMatching ctx input guard thenExpr elseExpr expr returnStrategy =
    if (canBeCompiledAsSwitch expr input) then
        let cases = findCasesNames expr input 
        let inputExpr = convertExpr ctx expr
        [ Switch(PhpCall(PhpConst(PhpConstString("get_class")), [inputExpr]),
            [ for case,bindings, i in cases ->
                let idents,target = ctx.DecisionTargets.[i]
                let phpCase =
                    match case with
                    | Some c -> StringCase (caseName c)
                    | None -> DefaultCase


                phpCase, 
                    [ for ident, binding in List.zip idents bindings do
                        ctx.AddLocalVar(fixName ident.Name)
                        Assign(PhpVar(fixName ident.Name, None), convertExpr ctx binding)
                      match returnStrategy with
                      | Target t -> 
                            ctx.AddLocalVar(fixName t)
                            Assign(PhpVar(fixName t, None), PhpConst(PhpConstNumber(float i)))
                            Break;
                      | Return _ ->
                            yield! convertExprToStatement ctx target returnStrategy
                      | _ -> 
                            yield! convertExprToStatement ctx target returnStrategy
                            Break
                    ]] 
            )
        
        ]
    else
        [ If(convertExpr ctx guard, convertExprToStatement ctx thenExpr returnStrategy, convertExprToStatement ctx elseExpr returnStrategy) ]

and convertExprToStatement ctx expr returnStrategy =
    match expr with
    | Fable.DecisionTree(input, targets) ->

        let upperTargets = ctx.DecisionTargets 
        ctx.DecisionTargets <- targets
        let phpExpr = convertExprToStatement ctx input returnStrategy
        ctx.DecisionTargets <- upperTargets
        phpExpr
    | Fable.IfThenElse(Fable.Test(expr, Fable.TestKind.UnionCaseTest(case,entity), _) as guard, thenExpr , elseExpr, _) as input ->
        let groupCases = hasGroupedCases Set.empty input
        if groupCases then
            let targetName = ctx.MakeUniqueVar("target")
            let targetVar = PhpVar(targetName, None)
            let switch1 = convertMatching ctx input guard thenExpr elseExpr expr (Target targetName)

            let cases = getCases Map.empty input
            let switch2 =
                Switch(targetVar,
                    [ for i, (idents,expr) in  List.indexed ctx.DecisionTargets do
                        IntCase i, [
                            match Map.tryFind i cases with
                            | Some case ->
                                // Assigns have already been made in switch 1
                                //for id, b in List.zip idents case do
                                //    ctx.AddLocalVar(fixName id.Name)
                                //    Assign(PhpVar(fixName id.Name, None), convertExpr ctx b)
                                yield! convertExprToStatement ctx expr returnStrategy
                            | None -> ()
                            match returnStrategy with
                            | Return _ -> ()
                            | _ -> Break;
                        ]
                    
                    ]
                )
            switch1 @ [ switch2 ]
                
        else
            convertMatching ctx input guard thenExpr elseExpr expr returnStrategy


    | Fable.IfThenElse(guardExpr, thenExpr, elseExpr, _) ->
        let guard = convertExpr ctx guardExpr

        [ If(guard, convertExprToStatement ctx thenExpr returnStrategy,
                    convertExprToStatement ctx elseExpr returnStrategy) ]
    | Fable.DecisionTreeSuccess(index,boundValues,_) ->
        match returnStrategy with
        | Target target -> [ Assign(PhpVar(target,None), PhpConst(PhpConstNumber (float index))) ]
        | _ ->
            let idents,target = ctx.DecisionTargets.[index]
            [ for ident, boundValue in List.zip idents boundValues do
                ctx.AddLocalVar(fixName ident.Name)
                Assign(PhpVar(fixName ident.Name, None), convertExpr ctx boundValue)
              yield! convertExprToStatement ctx target returnStrategy ]

    | Fable.Let(bindings,body) ->
        [ 
          for ident, expr in bindings do 
              let name = fixName ident.Name
              ctx.AddLocalVar(name)
              yield! convertExprToStatement ctx expr (Let name)
          yield! convertExprToStatement ctx body returnStrategy ]

    | Fable.Sequential(exprs) ->
        if List.isEmpty exprs then
            []
        else
            [ for expr in exprs.[0..exprs.Length-2] do
                    yield! convertExprToStatement ctx expr Do
              yield! convertExprToStatement ctx exprs.[exprs.Length-1] returnStrategy
                    ]
    | Fable.Throw(Fable.Operation(Fable.Call(Fable.ConstructorCall(_ ),{ Args = [ Fable.Value(Fable.StringConstant s, _)]}),_,_) ,_,_) ->
        [ Throw(s) ]

    | Fable.Set(expr,kind,value,_) ->
        let left = convertExpr ctx expr
        match left with
        | PhpVar(v,_) -> ctx.AddLocalVar(v) 
        | _ -> ()
        [ Assign(left, convertExpr ctx value)]
            

    | _ ->
        match returnStrategy with
        | Return -> [ PhpStatement.Return (convertExpr ctx expr) ]
        | Let(var) -> 
            ctx.AddLocalVar(var)
            [ Assign(PhpVar(var,None), convertExpr ctx expr) ]
        | Do -> [ PhpStatement.Do (convertExpr ctx expr) ]
        | Target _ -> failwithf "Target should be assigned by decisiontree success"

let convertDecl ctx decl =
    match decl with
    | Fable.Declaration.ConstructorDeclaration(Fable.UnionConstructor(info),_) -> 
        convertUnion ctx info
    | Fable.Declaration.ConstructorDeclaration(Fable.CompilerGeneratedConstructor(info),_) -> 
        convertRecord ctx info
    | Fable.Declaration.ValueDeclaration(Fable.Function(Fable.FunctionKind.Delegate(args), body, Some name),decl) ->
       [{ PhpFun.Name = fixName name
          Args = [ for arg in args do 
                    fixName arg.Name ]
          Matchings = []
          Body = convertExprToStatement ctx body Return 
          Static = false } |> PhpFun ]
    | Fable.Declaration.ValueDeclaration(expr , decl) ->
        [ PhpDeclValue(fixName decl.Name, convertExpr ctx expr) ]
    | _ -> [] 


let files = 
    [ @"C:\development\crazy\src\Shared\Shared.fs"
      @"C:\development\crazy\src\Shared\SharedGame.fs"
      @"C:\development\crazy\src\Server\SharedServer.fs"
      ]

let opts   =
    let projOptions: FSharpProjectOptions =
             {
                 ProjectId = None
                 ProjectFileName = @"C:\development\crazy\src\Game\Game.fsproj"
                 SourceFiles = List.toArray files 
                 OtherOptions = [| @"-r:C:\development\crazy\packages\Fable.Core\lib\netstandard2.0\Fable.Core.dll"|]
                 ReferencedProjects = [||] //p2pProjects |> Array.ofList
                 IsIncompleteTypeCheckEnvironment = false
                 UseScriptResolutionRules = false
                 LoadTime = DateTime.Now
                 UnresolvedReferences = None;
                 OriginalLoadReferences = []
                 ExtraProjectInfo = None
                 Stamp = None
             }
    projOptions


let checker = FSharpChecker.Create(keepAssemblyContents = true)
let result = checker.ParseAndCheckProject(opts) |> Async.RunSynchronously
let impls =
    [ for imp in result.AssemblyContents.ImplementationFiles do 
        imp.FileName, imp
        ]
    |> dict

let proj = Project(opts ,impls,[||])
let compOptions =
    { CompilerOptions.typedArrays = false
      CompilerOptions.clampByteArrays = false
      CompilerOptions.debugMode = false
      CompilerOptions.outputPublicInlinedFunctions = false
      CompilerOptions.precompiledLib = None
      CompilerOptions.verbosity = Verbosity.Normal}

let asts =
    [ for file in files do
        let com = Compiler(file, proj, compOptions, "")
        Fable.Transforms.FSharp2Fable.Compiler.transformFile com proj.ImplementationFiles
        |> Fable.Transforms.FableTransforms.optimizeFile com ]

let phpComp = PhpCompiler.empty
let fs = 
    [ 
      for ast in asts do
          for i,decl in List.indexed ast.Declarations do
            for d in convertDecl phpComp decl do
                i,d
    ]

convertDecl phpComp asts.[2].Declarations.[8]

let w = new StringWriter()
let ctx = Output.Writer.create w
let file = { Decls = fs }
Output.writeFile ctx file
w.ToString()

IO.File.WriteAllText(@"C:\development\crazy\bga\modules\crazyfarmers.php", string w)
IO.File.WriteAllText(@"C:\development\crazy\php\lib.php", string w)
        

