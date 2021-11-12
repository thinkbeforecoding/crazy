#r @"..\Fable\lib\fcs\FSharp.Compiler.Service.dll"
#load "./.paket/load/netStandard2.0/Transpiler/FSharp.Compiler.Service.fsx"
#I @"..\Fable\src\Fable.AST"
#I @"..\Fable\src\Fable.Core"
#I @"..\fable\src\Fable.Transforms"
#I @"..\fable\src\fcs-fable\src\fsharp\symbols\"
#I @"..\fable\src\fcs-fable\src\fsharp\"
#load @"Fable.Core.Types.fs" @"Global\Prelude.fs" @"Common.fs" @"Fable.fs" @"Plugins.fs" @"Global\Compiler.fs" @"MonadicTrampoline.fs" @"Transforms.Util.fs" @"OverloadSuffix.fs" "FSharp2Fable.Util.fs" @"ReplacementsInject.fs" @"Replacements.fs" @"Inject.fs" @"FSharp2Fable.fs" @"Inject.fs" @"FableTransforms.fs" "Global/Metadata.fs" "State.fs"

open System
open Fable
open Fable.AST
open FSharp.Compiler.SourceCodeServices
open System.Collections.Generic
open System.Collections.Concurrent
open System.IO
open Fable.Transforms.State

type ImplFile =
    {
        Ast: FSharpImplementationFileContents
        RootModule: string
        Entities: IReadOnlyDictionary<string, Fable.Entity>
    }

type Project(projectOptions: FSharpProjectOptions,
             checkResults: FSharpCheckProjectResults,
             errors: FSharpErrorInfo array,
             ?optimizeFSharpAst,
             ?assemblies) =
    let projectFile = Path.normalizePath projectOptions.ProjectFileName
    let inlineExprs = ConcurrentDictionary<string, InlineExpr>()
    let optimizeFSharpAst = defaultArg optimizeFSharpAst false


    let implFiles =
        (if optimizeFSharpAst then checkResults.GetOptimizedAssemblyContents().ImplementationFiles
         else checkResults.AssemblyContents.ImplementationFiles)
        |> Seq.map (fun file ->
            let entities = Dictionary()
            let rec loop (ents: FSharpEntity seq) =
                for e in ents do
                    if e.IsFSharpAbbreviation then ()
                    else
                        let fableEnt = Fable.Transforms.FSharp2Fable.FsEnt e :> Fable.Entity
                        entities.Add(fableEnt.FullName, fableEnt)
                        loop e.NestedEntities
            Fable.Transforms.FSharp2Fable.Compiler.getRootFSharpEntities file |> loop
            let key = Path.normalizePathAndEnsureFsExtension file.FileName
            key, { Ast = file
                   RootModule = Fable.Transforms.FSharp2Fable.Compiler.getRootModule file
                   Entities = entities })
        |> dict

    let assemblies =
        match assemblies with
        | Some assemblies -> assemblies
        | None -> Assemblies((fun _ -> failwith "Plugins not supported"), checkResults)

    //let rootModules =
    //    implFiles |> Seq.map (fun kv ->
    //        kv.Key, FSharp2Fable.Compiler.getRootModuleFullName kv.Value) |> dict
    member __.ImplementationFiles = implFiles
    member __.InlineExprs = inlineExprs
    member __.Assemblies = assemblies



type Compiler(currentFile, project: Project, options, fableLibraryDir: string) =
    let mutable id = 0
    let logs = ResizeArray<Log>()
    let fableLibraryDir = fableLibraryDir.TrimEnd('/')
    let plugins =  { CompilerPlugins.MemberDeclarationPlugins = Map.empty }
    interface Fable.Compiler with
        member __.Options = options
        member __.LibraryDir = fableLibraryDir
        member __.CurrentFile = currentFile
        member this.GetRootModule(fileName) =
            let fileName = Path.normalizePathAndEnsureFsExtension fileName
            match project.ImplementationFiles.TryGetValue(fileName) with
            | true, file -> file.RootModule
            | false, _ ->
                let msg = sprintf "Cannot find root module for %s. If this belongs to a package, make sure it includes the source files." fileName
                (this :> Fable.Compiler).AddLog(msg, Severity.Warning, fileName=currentFile)
                "" // failwith msg
        member __.GetOrAddInlineExpr(fullName, generate) =
            project.InlineExprs.GetOrAdd(fullName, fun _ -> generate())
        member __.AddLog(msg, severity, ?range, ?fileName:string, ?tag: string) = printfn $"%s{msg}"
        member __.AddWatchDependency _ = ()
        member __.GetEntity entityRef = 
            match entityRef.Path with
            //| Fable.CoreAssemblyName name ->  // project.Assemblies.GetEntityByCoreAssemblyName(name, entityRef)
            | Fable.AssemblyPath path -> project.Assemblies.GetEntityByAssemblyPath(path, entityRef)
            | Fable.SourcePath fileName ->
                // let fileName = Path.normalizePathAndEnsureFsExtension fileName
                match project.ImplementationFiles.TryGetValue(fileName) with
                | true, file ->
                    match file.Entities.TryGetValue(entityRef.FullName) with
                    | true, e -> e
                    | false, _ -> failwithf "Cannot find entity %s in %s" entityRef.FullName fileName
                | false, _ -> failwith ("Cannot find implementation file " + fileName)
            | _ ->
                failwithf "GetEntity %A" entityRef.Path

        member __.GetImplementationFile(fileName) =
           let fileName = Path.normalizePathAndEnsureFsExtension fileName
           match project.ImplementationFiles.TryGetValue(fileName) with
           | true, file -> file.Ast
           | false, _ -> failwith ("Cannot find implementation file " + fileName)
 
        member __.Plugins = plugins

           



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
    | PhpIdent of cls: string option * string
    | PhpGlobal of string
    | PhpConst of PhpConst
    | PhpUnaryOp of string * PhpExpr
    | PhpBinaryOp of string *PhpExpr * PhpExpr
    | PhpProp of PhpExpr * Prop * typ: PhpType option
    | PhpArrayAccess of PhpExpr * PhpExpr
    | PhpNew of ty:PhpType * args:PhpExpr list
    | PhpArray of args: (PhpArrayIndex * PhpExpr) list
    | PhpCall of f: PhpExpr * args: PhpExpr list
    | PhpMethod of this: PhpExpr * func:PhpExpr * args: PhpExpr list
    | PhpTernary of gard: PhpExpr * thenExpr: PhpExpr * elseExpr: PhpExpr
    | PhpIsA of expr: PhpExpr * PhpTypeRef
    | PhpAnonymousFunc of args: string list * uses: Capture list * body: PhpStatement list
    | PhpMacro of macro: string * args: PhpExpr list
   
and PhpStatement =
    | Return of PhpExpr
    | Expr of PhpExpr
    | Switch of PhpExpr * (PhpCase * PhpStatement list) list
    | Break
    | Assign of target:PhpExpr * value:PhpExpr
    | If of guard: PhpExpr * thenCase: PhpStatement list * elseCase: PhpStatement list
    | Throw of string * PhpExpr list
    | TryCatch of body: PhpStatement list * catch: (string * PhpStatement list) option * finallizer: PhpStatement list 
    | WhileLoop of guard: PhpExpr * body: PhpStatement list
    | ForLoop of ident: string * start: PhpExpr * limit: PhpExpr * isUp: bool * body: PhpStatement list
    | Do of PhpExpr
and PhpCase =
    | IntCase of int
    | StringCase of string
    | DefaultCase
and PhpTypeRef =
    | ExType of string
    | InType of PhpType
    | ArrayRef of PhpTypeRef

and PhpFun = 
    { Name: string
      Args: string list
      Matchings: PhpStatement list
      Body: PhpStatement list
      Static: bool
    }

and PhpType =
    { Namespace: string option
      Name: string
      Fields: PhpField list;
      Methods: PhpFun list
      Abstract: bool
      BaseType: PhpType option
      Interfaces: PhpType list
      File: string
    }


type PhpDecl =
    | PhpFun of PhpFun
    | PhpDeclValue of name:string * PhpExpr
    | PhpType of PhpType

type PhpFile =
    { Filename: string
      Namespace: string option
      Require: (string option * string) list
      Uses: PhpType list
      Decls: (int * PhpDecl) list }


module Output =
    type Writer =
        { Writer: TextWriter
          Indent: int
          Precedence: int
          UsedTypes: PhpType Set
          CurrentNamespace: string option }

    let indent ctx =
        { ctx with Indent = ctx.Indent + 1}

    module Writer =
        let create w =
            { Writer = w; Indent = 0; Precedence = Int32.MaxValue; UsedTypes = Set.empty; CurrentNamespace = None }

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
            | "(void)" -> 10
            | op -> failwithf "Unknown unary operator %s" op

        let _new = 0 
        let instanceOf = 1
        let ternary = 14 
        let assign = 15
            

        let clear ctx = { ctx with Precedence = Int32.MaxValue} 

    let withPrecedence ctx prec f =
        let useParens = prec > ctx.Precedence || (prec = 14 && ctx.Precedence = 14)
        let subCtx = { ctx with Precedence = prec }
        if useParens then
            write subCtx "("

        f subCtx

        if useParens then
            write subCtx ")"

    let rec writeTypeRef ctx ref =
        match ref with
        | InType t -> write ctx t.Name
        | ExType t -> write ctx t
        | ArrayRef t ->
            writeTypeRef ctx t
            write ctx "[]"

    let writeStr ctx (str: string) =
        write ctx "'"
        write ctx (str.Replace(@"\",@"\\").Replace("'",@"\'"))
        write ctx "'"


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
            | PhpConstString s -> writeStr ctx s
            | PhpConstBool true -> write ctx "true"
            | PhpConstBool false -> write ctx "false"
            | PhpConstNull -> write ctx "NULL"
            | PhpConstUnit -> write ctx "NULL"
        | PhpVar (v,_) -> 
            write ctx "$"
            write ctx v
        | PhpGlobal v -> 
            write ctx "$GLOBALS['"
            //write ctx "$"
            write ctx v
            write ctx "']"
        | PhpProp(l,r, _) ->
            writeExpr ctx l
            write ctx "->"
            match r with
            | Field r -> write ctx r.Name
            | StrField r -> write ctx r
        | PhpIdent(ns,i) ->
            match ns with
            | Some ns ->
                write ctx @"\"
                write ctx ns
                write ctx @"\"
            | None -> ()
            write ctx i
        | PhpNew(t,args) ->
            withPrecedence ctx (Precedence._new)
                (fun subCtx ->
                    write subCtx "new "

                    if not (Set.contains t ctx.UsedTypes) then
                        match t.Namespace with
                        | None -> write subCtx @"\"
                        | Some ns  ->
                            if t.Namespace <> ctx.CurrentNamespace then
                                write subCtx @"\"
                                write subCtx ns
                                write subCtx @"\"
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
            match f with
            | PhpConst(PhpConstString f) -> write ctx f
            | _ -> writeExpr ctx f
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
                    writeTypeRef ctx t)
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
            writei ctx "}"
            if List.isEmpty elseCase then
                writeiln ctx ""
            else
                writeln ctx " else {"
                for st in elseCase do
                    writeStatement body st
                writeiln ctx "}"
        | Throw(cls,args) ->
            writei ctx "throw new "
            write ctx cls
            write ctx "("
            writeArgs ctx args
            writeln ctx ");"
        | PhpStatement.Do (PhpConst PhpConstUnit)-> ()
        | PhpStatement.Do (expr) ->
            writei ctx ""
            writeExpr (Precedence.clear ctx) expr
            writeln ctx ";"
        | PhpStatement.TryCatch(body, catch, finallizer) ->
            writeiln ctx "try {"
            let bodyind = indent ctx
            for st in body do
                writeStatement bodyind st
            writeiln ctx "}"

            match catch with
            | Some(var, sts) ->
                writeiln ctx "catch (exception $" 
                write ctx var
                writeln ctx ") {"
                for st in sts do
                    writeStatement bodyind st
                writeiln ctx "}"
            | None -> ()

            match finallizer with
            | [] -> ()
            | _ ->
                writeiln ctx "finally {"
                for st in finallizer do
                    writeStatement bodyind st
                writeiln ctx "}"
        | PhpStatement.WhileLoop(guard, body) ->
            writei ctx "while ("
            writeExpr ctx guard
            writeln ctx ") {"
            let bodyctx = indent ctx
            for st in body do
                writeStatement bodyctx st
            writeiln ctx "}"
        | PhpStatement.ForLoop(ident, start, limit, isUp, body) ->
            writei ctx "for ($"
            write ctx ident
            write ctx " = "
            writeExpr ctx start
            write ctx "; $"
            write ctx ident 
            write ctx " <= "
            writeExpr ctx limit
            write ctx "; $"
            write ctx ident
            if isUp then
                write ctx "++"
            else
                write ctx "--"
            writeln ctx ") {"
            let bodyctx = indent ctx
            for st in body do
                writeStatement bodyctx st
            writeiln ctx "}"



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
        file.Namespace |> Option.iter (fun ns ->
            write ctx "namespace "
            write ctx ns
            writeln ctx ";"
            writeln ctx ""
        )


        if not (List.isEmpty file.Require) then
            //writeln ctx "define('__ROOT__',dirname(__FILE__));"
            for v,r in file.Require do
                write ctx "require_once("
                match v with
                | Some var -> 
                    write ctx var
                    write ctx "."
                | None -> ()

                writeStr ctx r
                writeln ctx ");"
            writeln ctx ""
       
        if not (List.isEmpty file.Uses) then
            for u in file.Uses do
                write ctx "use "
                match u.Namespace with
                | Some ns ->
                    write ctx @"\"
                    write ctx ns
                | None -> ()
                write ctx @"\"
                write ctx u.Name
                writeln ctx ";"
            writeln ctx ""
            
        let ctx =
            { ctx with 
                UsedTypes = set file.Uses
                CurrentNamespace = file.Namespace }


        for i,d in file.Decls do
            writeln ctx ( "#" + string i)
            writeDecl ctx d
            writeln ctx ""



open Fable.AST

module PhpList =
    let list  = { Namespace = Some "FSharpList"; Name = "FSharpList"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []; File = "fable-library/List.php" }
    let value = { Name = "value"; Type = "" }
    let next = { Name = "next"; Type = "FSharpList" }
    let cons = { Namespace = Some "FSharpList"; Name = "Cons"; Fields = [ value; next ]; Methods = []; Abstract = false; BaseType = Some list; Interfaces = []; File = "fable-library/List.php" } 
    let nil = { Namespace = Some "FSharpList"; Name = "Nil"; Fields = []; Methods = []; Abstract = false; BaseType = Some list; Interfaces = []; File = "fable-library/List.php" }

module PhpResult =
    let result = { Namespace = None; Name = "Result"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []; File = "fable-library/FSharp.Core.php"}
    let okValue = { Name = "ResultValue"; Type = ""}
    let ok = { Namespace = None; Name = "Result_Ok"; Fields = [okValue]; Methods = []; Abstract = true; BaseType = Some result; Interfaces = []; File = "fable-library/FSharp.Core.php" }
    let errorValue = { Name = "ErrorValue"; Type = ""}
    let error = { Namespace = None; Name = "Result_Error"; Fields = [errorValue] ; Methods = []; Abstract = true; BaseType = Some result; Interfaces = []; File = "fable-library/FSharp.Core.php" }

module PhpUnion =
    let fSharpUnion = { Namespace = None; Name = "FSharpUnion"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = []; File = "fable-library/FSharp.Core.php"}

module Core =
    let icomparable = { Namespace = None; Name = "IComparable"; Fields = []; Methods = []; Abstract = true; BaseType = None; Interfaces = [] ; File = "fable-library/FSharp.Core.php"}



let fixExt path = Path.ChangeExtension(path, Path.GetExtension(path).Replace("js", "php").Replace("fs", "fs.php"))

type PhpCompiler =
    { mutable Types: Map<string,PhpType> 
      mutable Unions: Map<string, Map<int, string>>
      mutable DecisionTargets: (Fable.Ident list * Fable.Expr) list
      mutable LocalVars: string Set
      mutable CapturedVars: Capture Set
      mutable MutableVars: string Set
      mutable Id: int
      mutable IsImportValue: Map<string, bool>
      mutable ClassNames: Map<string,string>
      mutable BasePath: string
      mutable Require: Set<string option * string>
      mutable NsUse: Set<PhpType>
      mutable File: string
      mutable Namespace: string
      mutable thisArgument: string option 
    }
    static member create() =

        { Types = [ PhpList.list
                    PhpList.cons
                    PhpList.nil
                    PhpResult.result
                    PhpResult.ok
                    PhpResult.error ] 
                  |> List.map (fun t -> t.Name, t)
                  |> Map.ofList
          Unions = Map.empty
          DecisionTargets = []
          LocalVars = Set.empty
          CapturedVars = Set.empty
          MutableVars = Set.empty
          Id = 0
          IsImportValue = Map.empty
          ClassNames = Map.empty
          BasePath = ""
          Require = Set.empty
          NsUse = Set.empty
          Namespace = ""
          File = ""
          thisArgument = None
          }
    member this.AddType(phpType: PhpType) =
        this.Types <- Map.add phpType.Name phpType this.Types
        phpType

    member this.AddUnion(name,union) =
        this.Unions <- Map.add name union this.Unions

    member this.AddLocalVar(var, isMutable) =
        if isMutable then
            this.MutableVars <- Set.add var this.MutableVars

        if this.CapturedVars.Contains(Capture.ByRef var) then
            ()
        elif this.CapturedVars.Contains(Capture.ByValue var) then
            this.CapturedVars <- this.CapturedVars |> Set.remove (Capture.ByValue var)  |> Set.add(ByRef var)
        else
            this.LocalVars <- Set.add var this.LocalVars

    member this.UseVar(var) =
        if not (Set.contains var this.LocalVars) && not (Set.contains (ByRef var) this.CapturedVars) then
            if Set.contains var this.MutableVars then
                this.CapturedVars <- Set.add (ByRef var) this.CapturedVars
            else
                this.CapturedVars <- Set.add (ByValue var) this.CapturedVars

    member this.UseVarByRef(var) =
        this.MutableVars <- Set.add var this.MutableVars
        if not (Set.contains var this.LocalVars) && not (Set.contains (ByRef var) this.CapturedVars) then
            this.CapturedVars <- Set.add (ByRef var) (Set.remove (ByValue var) this.CapturedVars)

    member this.UseVar(var) =
        match var with 
        | ByValue name -> this.UseVar name
        | ByRef name -> this.UseVarByRef name

    member this.MakeUniqueVar(name) =
        this.Id <- this.Id + 1
        "_" + name + "__" + string this.Id

    member this.NewScope() =
        { this with 
            LocalVars = Set.empty
            CapturedVars = Set.empty }

    member this.AddImport(name, isValue) = 
        this.IsImportValue <- Map.add name isValue this.IsImportValue

    member this.AddEntityName(entity: Fable.Entity, name) =
        this.ClassNames <- Map.add entity.FullName name this.ClassNames

    member this.GetEntityName(e: Fable.Entity) =
        match Map.tryFind e.FullName this.ClassNames with
        | Some n -> n
        | None -> e.DisplayName

    member this.AddRequire(file: string) =

        if file.Contains "fable-library" then
            let path = Path.GetFileName (fixExt file)
            this.Require <- Set.add (Some "__FABLE_LIBRARY__",  "/" + path) this.Require

        else
            let fullPhpPath = 
                let p = fixExt file
                if Path.IsPathRooted p then
                    p
                else
                    Path.GetFullPath(Path.Combine(Path.GetDirectoryName(this.File), p))

            if fullPhpPath <> this.File then
                let path = 
                    let p = Path.getRelativePath this.BasePath fullPhpPath
                    if p.StartsWith "./" then
                        p.Substring 2
                    else
                        p

                this.Require <- Set.add (Some "__ROOT__" , "/" + path) this.Require

    member this.AddRequire(typ: PhpType) =
        this.AddRequire(typ.File)

    member this.ClearRequire(basePath) =
        this.BasePath <- basePath
        this.Require <- Set.empty
        this.NsUse <- Set.empty

    member this.AddUse(typ: PhpType) =
        this.AddRequire(typ)
        this.NsUse <- Set.add typ this.NsUse;

    member this.SetFile(file) =
        this.File <- file
    member this.SetNamespace(ns) =
        this.Namespace <- ns

let rec convertType (com: Fable.Compiler) (ctx: PhpCompiler) (t: Fable.Type) =
    match t with
    | Fable.Type.Number Int32 -> "int"
    | Fable.Type.String -> "string"
    | Fable.DeclaredType(ref,args) -> 
        let ent = com.GetEntity(ref)
        ctx.GetEntityName(ent)
        

    | Fable.Type.List t ->
        convertType com ctx t + "[]"
    
    | _ -> ""

    //if (t.IsAbbreviation) then
    //    t.Format(FSharpDisplayContext.Empty.WithShortTypeNames(true))
    //else
    //    match t with
    //    | Symbol.TypeWithDefinition entity ->
    //        match entity.CompiledName with
    //        | "FSharpSet`1" -> "Set"
    //        | name -> name
    //    | _ ->
    //        failwithf "%A" t
       

let fixName (name: string) =
    match name.Replace('$','_') with
    | "empty" -> "_empty"
    | n -> n


let caseName (ctx: PhpCompiler) (entity: Fable.Entity) (case: Fable.UnionCase) =
    if entity.UnionCases.Length = 1 then
        case.Name
    else
        ctx.GetEntityName entity + "_" + case.Name

let caseNameOfTag ctx (entity: Fable.Entity) tag =
    caseName ctx entity entity.UnionCases.[tag]
        
    //let entity = case. ReturnType.TypeDefinition
    //if entity.UnionCases.Count = 1 then
    //    case.Name
    //elif entity.CompiledName = "FSharpResult`2" then
    //    if case.Name = "Ok" then
    //        case.Name
    //    else
    //        "ResultError"

    //else
    //    entity.CompiledName + "_" + case.Name


let convertUnion (com: Fable.Compiler) (ctx: PhpCompiler) (info: Fable.Entity) = 
    if info.UnionCases.Length = 1 then
        let case = info.UnionCases.[0] 
        [ let t =
            { Namespace = Some (ctx.Namespace)
              Name = case.Name
              Fields = [ for e in case.UnionCaseFields do 
                            { Name = e.Name 
                              Type  = convertType com ctx e.FieldType } ]
              Methods = [ 
                  { PhpFun.Name = "get_FSharpCase"
                    PhpFun.Args = []
                    PhpFun.Matchings = []
                    PhpFun.Static = false
                    PhpFun.Body = 
                      [ PhpStatement.Return(PhpConst(PhpConstString(case.Name)))] } 
                  { PhpFun.Name = "get_Tag"
                    PhpFun.Args = []
                    PhpFun.Matchings = []
                    PhpFun.Static = false
                    PhpFun.Body =
                      [ PhpStatement.Return(PhpConst(PhpConstNumber(0.)))] }
                  { PhpFun.Name = "CompareTo"
                    PhpFun.Args = ["other"]
                    PhpFun.Matchings = []
                    PhpFun.Static = false
                    PhpFun.Body =
                                      [ for e in case.UnionCaseFields do 
                                            let cmp = PhpVar(ctx.MakeUniqueVar "cmp",None)
                                            match e.FieldType with
                                            | Fable.Type.Number _ -> 
                                                Assign(cmp, 
                                                    PhpTernary( PhpBinaryOp(">", 
                                                                    PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                    PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ),
                                                                    PhpConst(PhpConstNumber 1.),
                                                                       PhpTernary(
                                                                           PhpBinaryOp("<", 
                                                                               PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                               PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None)),
                                                                               PhpConst(PhpConstNumber -1.), 
                                                                                PhpConst(PhpConstNumber 0.)
                                                                        
                                                    
                                                   ) ) )
                                            | _ ->
                                                Assign(cmp, 
                                                    PhpMethod(PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                              PhpConst(PhpConstString "CompareTo"),
                                                              [PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ])
                                                
                                                )
                                            If(PhpBinaryOp("!=", cmp, PhpConst(PhpConstNumber 0.) ),
                                                [PhpStatement.Return cmp],
                                                []
                                            )
                                        PhpStatement.Return (PhpConst (PhpConstNumber 0.))
                                      ]
                    }
              ]
              Abstract = false
              BaseType = None
              Interfaces = [ PhpUnion.fSharpUnion; Core.icomparable  ]
              File = ctx.File
              }
          ctx.AddUse(Core.icomparable)
          ctx.AddUse(PhpUnion.fSharpUnion )
          ctx.AddType(t) |> PhpType ]
    else
    [ let baseType =
            { Namespace = Some ctx.Namespace
              Name = ctx.GetEntityName(info)
              Fields = []
              Methods = []
              Abstract = true 
              BaseType = None
              Interfaces = [ PhpUnion.fSharpUnion ]
              File = ctx.File }
      
      ctx.AddUse(PhpUnion.fSharpUnion)
      ctx.AddType(baseType) |> PhpType

      for i, case in Seq.indexed info.UnionCases do
        let t = 
            { Namespace = Some ctx.Namespace
              Name = caseName ctx info case
              Fields = [ for e in case.UnionCaseFields do 
                            { Name = e.Name 
                              Type  = convertType com ctx e.FieldType } ]
              Methods = [ { PhpFun.Name = "get_FSharpCase";
                            PhpFun.Args = []
                            PhpFun.Matchings = []
                            PhpFun.Static = false
                            PhpFun.Body = 
                                [ PhpStatement.Return(PhpConst(PhpConstString(case.Name)))] } 
                          { PhpFun.Name = "get_Tag"
                            PhpFun.Args = []
                            PhpFun.Matchings = []
                            PhpFun.Static = false
                            PhpFun.Body =
                                [ PhpStatement.Return(PhpConst(PhpConstNumber (float i)))] }
                          { PhpFun.Name = "CompareTo"
                            PhpFun.Args = ["other"]
                            PhpFun.Matchings = []
                            PhpFun.Static = false
                            PhpFun.Body =
                                              [ let cmp = PhpVar(ctx.MakeUniqueVar "cmp",None)
                                                Assign(cmp, 
                                                    PhpTernary( PhpBinaryOp(">", 
                                                                    PhpMethod(PhpVar("this",None), PhpConst(PhpConstString "get_Tag"), []),
                                                                    PhpMethod(PhpVar("other", None), PhpConst(PhpConstString "get_Tag"), []) ),
                                                                    PhpConst(PhpConstNumber 1.),
                                                                       PhpTernary(
                                                                           PhpBinaryOp("<", 
                                                                               PhpMethod(PhpVar("this",None), PhpConst(PhpConstString "get_Tag"), []),
                                                                               PhpMethod(PhpVar("other", None), PhpConst(PhpConstString "get_Tag") , [])),
                                                                               PhpConst(PhpConstNumber -1.), 
                                                                                PhpConst(PhpConstNumber 0.))))
                                                if List.isEmpty case.UnionCaseFields then
                                                    PhpStatement.Return(cmp)
                                                else
                                                    If(PhpBinaryOp("!=", cmp, PhpConst(PhpConstNumber 0.) ),
                                                        [PhpStatement.Return cmp],
                                                        []
                                                    )
                                                    for e in case.UnionCaseFields do 
                                                        let cmp = PhpVar(ctx.MakeUniqueVar "cmp",None)
                                                        match e.FieldType with
                                                        | Fable.Type.Number _ -> 
                                                            Assign(cmp, 
                                                                PhpTernary( PhpBinaryOp(">", 
                                                                                PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                                PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ),
                                                                                PhpConst(PhpConstNumber 1.),
                                                                                   PhpTernary(
                                                                                       PhpBinaryOp("<", 
                                                                                           PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                                           PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None)),
                                                                                           PhpConst(PhpConstNumber -1.), 
                                                                                            PhpConst(PhpConstNumber 0.)
                                                                                    
                                                                
                                                               ) ) )
                                                        | _ ->
                                                            Assign(cmp, 
                                                                PhpMethod(PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                          PhpConst(PhpConstString "CompareTo"),
                                                                          [PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ])
                                                            
                                                            )
                                                        If(PhpBinaryOp("!=", cmp, PhpConst(PhpConstNumber 0.) ),
                                                            [PhpStatement.Return cmp],
                                                            []
                                                        )
                                                    PhpStatement.Return (PhpConst (PhpConstNumber 0.))
                                              ]
                            }

                            ]
              Abstract = false
              BaseType = Some baseType
              Interfaces = [ Core.icomparable ]
              File = ctx.File
              }

        let union = 
            [ for i,case in Seq.indexed info.UnionCases ->
                i, caseName ctx info case
            ] |> Map.ofList
        ctx.AddUse(Core.icomparable)
        ctx.AddUnion(t.Name, union)
        ctx.AddType(t) |> PhpType ]

let convertRecord (com: Fable.Compiler) (ctx: PhpCompiler) (info: Fable.Entity) = 
    [ let t =
        { Namespace = Some ctx.Namespace
          Name = ctx.GetEntityName(info)
          Fields = [ for e in info.FSharpFields do 
                        { Name = e.Name 
                          Type  = convertType com ctx e.FieldType } ]
          Methods = [ 
              { PhpFun.Name = "CompareTo"
                PhpFun.Args = ["other"]
                PhpFun.Matchings = []
                PhpFun.Static = false
                PhpFun.Body =
                                  [ for e in info.FSharpFields do
                                        let cmp = PhpVar(ctx.MakeUniqueVar "cmp",None)
                                        match e.FieldType with
                                        | Fable.Number _
                                        | Fable.String -> 
                                            Assign(cmp, 
                                                PhpTernary( PhpBinaryOp(">", 
                                                                PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ),
                                                                PhpConst(PhpConstNumber 1.),
                                                                   PhpTernary(
                                                                       PhpBinaryOp("<", 
                                                                           PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                                           PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None)),
                                                                           PhpConst(PhpConstNumber -1.), 
                                                                            PhpConst(PhpConstNumber 0.)
                                                                    
                                                
                                               ) ) )
                                        | _ ->
                                            Assign(cmp, 
                                                PhpMethod(PhpProp(PhpVar("this",None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None),
                                                          PhpConst(PhpConstString "CompareTo"),
                                                          [PhpProp(PhpVar("other", None), Prop.Field { Name = e.Name; Type = convertType com ctx e.FieldType }, None) ])
                                            
                                            )
                                        If(PhpBinaryOp("!=", cmp, PhpConst(PhpConstNumber 0.) ),
                                            [PhpStatement.Return cmp],
                                            []
                                        )
                                    PhpStatement.Return (PhpConst (PhpConstNumber 0.))
                                  ] }
            
          ]
          Abstract = false
          BaseType = None
          Interfaces = [ Core.icomparable ]
          File = ctx.File
          }
      ctx.AddUse(Core.icomparable)
      ctx.AddType(t) |> PhpType ]

type ReturnStrategy =
    | Return
    | Let of string
    | Do
    | Target of string

let rec convertTypeRef  (com: Fable.Compiler) (ctx: PhpCompiler) (t: Fable.Type) =
    match t with
    | Fable.String -> ExType "string"
    | Fable.Number (Int32|Int16|Int8|UInt16|UInt32|UInt8) -> ExType "int" 
    | Fable.Number (Float32 | Float64) -> ExType "float" 
    | Fable.Boolean  -> ExType "bool" 
    | Fable.Char  -> ExType "char" 
    | Fable.AnonymousRecordType _ -> ExType "object" 
    | Fable.Any -> ExType "object" 
    | Fable.DelegateType _ -> ExType "object" 
    | Fable.LambdaType _ -> ExType "object" 
    | Fable.GenericParam _ -> ExType "object" 
    | Fable.Enum ref -> 
        let ent = com.GetEntity(ref)
        match Map.tryFind ent.DisplayName ctx.Types with
        | Some phpType -> InType phpType
        | None -> ExType ent.DisplayName
    | Fable.Array t -> ArrayRef (convertTypeRef com ctx t)
    | Fable.List _ -> ExType "FSharpList"
    | Fable.Option t -> ExType "object"
    | Fable.DeclaredType(ref, _) -> 
        let ent = com.GetEntity(ref)
        match Map.tryFind ent.DisplayName ctx.Types with
        | Some phpType -> InType phpType
        | None -> ExType ent.DisplayName
    | Fable.MetaType ->
        failwithf "MetaType not supported"
    | Fable.Regex ->
        failwithf "Regex not supported"
    | Fable.Tuple _ ->
        ExType "object" 
    | Fable.Unit ->
        ExType "void"

let convertTest (com: Fable.Compiler) (ctx: PhpCompiler) test phpExpr =
    let phpIsNull phpExpr = PhpCall(PhpConst (PhpConstString "is_null"), [phpExpr])
    match test with
    | Fable.TestKind.UnionCaseTest(tag) ->
        PhpBinaryOp("==",PhpMethod(phpExpr, PhpConst(PhpConstString "get_Tag"), []), PhpConst(PhpConstNumber(float tag)))
    | Fable.TestKind.ListTest(isCons) ->
        if isCons then
            ctx.AddUse(PhpList.cons)
            PhpIsA(phpExpr, InType PhpList.cons)
        else
            ctx.AddUse(PhpList.nil)
            PhpIsA(phpExpr, InType PhpList.nil)
    | Fable.OptionTest(isSome) ->
       if isSome then
           PhpUnaryOp("!",phpIsNull phpExpr)
       else
           phpIsNull  phpExpr
    | Fable.TypeTest(t) ->
        let phpType = convertTypeRef com ctx t
        PhpIsA(phpExpr, phpType)


let rec getExprType =
    function
    | PhpVar(_, t) -> t
    | PhpProp(_,_, t) -> t
    | _ -> None

let rec convertExpr (com: Fable.Compiler) (ctx: PhpCompiler) (expr: Fable.Expr) =
    match expr with
    | Fable.Value(value,_) ->
        convertValue com ctx value

    | Fable.Operation(Fable.Binary(op, left,right), t, _) ->
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
            | BinaryOperator.BinaryXorBitwise -> "^"
            | BinaryOperator.BinaryEqual -> "=="
            | BinaryOperator.BinaryUnequal -> "!="
            | BinaryOperator.BinaryEqualStrict -> "==="
            | BinaryOperator.BinaryUnequalStrict -> "!=="
            | BinaryOperator.BinaryModulus -> "%"
            | BinaryOperator.BinaryDivide -> "/"
            | BinaryOperator.BinaryExponent -> "**"
            | BinaryOperator.BinaryShiftLeft -> "<<"
            | BinaryOperator.BinaryShiftRightSignPropagating -> ">>"
            | BinaryOperator.BinaryShiftRightZeroFill -> failwithf "BinaryShiftRightZeroFill not supported"
            | BinaryOperator.BinaryIn -> failwithf "BinaryIn not supported"
            | BinaryOperator.BinaryInstanceOf -> failwithf "BinaryInstanceOf not supported"
        PhpBinaryOp(opstr, convertExpr com ctx left, convertExpr com ctx right)
    | Fable.Operation(Fable.Unary(op, expr),_,_) ->
        match op with
        | UnaryOperator.UnaryVoid ->
            PhpCall(PhpIdent(None, "void"), [convertExpr com ctx expr])
        | _ ->
            let opStr = 
                match op with
                | UnaryOperator.UnaryNot -> "!"
                | UnaryOperator.UnaryMinus -> "-"
                | UnaryOperator.UnaryPlus -> "+"
                | UnaryOperator.UnaryNotBitwise -> "~"
                | UnaryOperator.UnaryDelete -> failwith "UnaryDelete not supported"
                | UnaryOperator.UnaryTypeof -> failwith "UnaryTypeof not supported"
                | UnaryOperator.UnaryVoid -> failwith "Should not happen"

            PhpUnaryOp(opStr, convertExpr com ctx expr)
    //| Fable.Expr.Call(ex, { ThisArg = None; Args = args; CallMemberInfo = Some { IsInstance = false; CompiledName = s} }, ty,_) ->
    //    //match k,p with
    //    //| Fable.ImportKind.Library, Fable.Value(Fable.StringConstant cls,_) ->
    //    //    match s with
    //    //    | "op_UnaryNegation_Int32" -> PhpUnaryOp("-", convertExpr ctx args.Args.[0])
    //    //    | "join" -> PhpCall(PhpConst(PhpConstString "join"), convertArgs ctx args)
    //    //    | _ -> 
    //    //        let phpCls =
    //    //            match cls with
    //    //            | "List" -> "FSharpList"
    //    //            | "Array" -> "FSharpArray"
    //    //            | _ -> cls


    //    //        PhpCall(PhpConst(PhpConstString (phpCls + "::" + fixName s)), convertArgs ctx args)
    //    //| _ ->
    //        printfn $"Call %A{ex} / CompiledName: %s{s}"
    //        PhpCall(PhpConst(PhpConstString (fixName s)), convertArgs com ctx args)
    | Fable.Expr.Call(callee, ({ ThisArg = None; Args = args;  } as i) , ty,_) ->
     
        match callee with
        | Fable.Import({Selector = "op_UnaryNegation_Int32"},_,_) -> PhpUnaryOp("-", convertExpr com ctx args.[0])
        | Fable.Get((Fable.Get(_,_,ty,_) as this), Fable.ByKey(Fable.KeyKind.ExprKey(Fable.Value(Fable.StringConstant m, None))),_,_)
                when match ty with Fable.Array _ -> true | _ -> false
                ->
            PhpCall(PhpIdent(Some "FSharpArray",  m), convertArgs com ctx (args @ [this])  )
        | Fable.Get(Fable.IdentExpr { Name = "Math" }, Fable.ByKey(Fable.KeyKind.ExprKey(Fable.Value(Fable.StringConstant m, None))),_,_)
                ->
            PhpCall(PhpConst(PhpConstString(m)), convertArgs com ctx (args)  )
        | Fable.Get(target , Fable.ByKey(Fable.KeyKind.ExprKey(Fable.Value(Fable.StringConstant m, None))),_,_) ->
            let meth = m.Substring(m.LastIndexOf(".")+1)
            PhpMethod(convertExpr com ctx target, PhpConst(PhpConstString meth),  convertArgs com ctx (args))

        | _ ->
            let phpCallee = convertExpr com ctx callee
            match phpCallee with
            | PhpVar(name,_) ->
                ctx.UseVarByRef(name)
            | _ -> ()

            PhpCall(phpCallee, convertArgs com ctx args)

    //| Fable.ExprCall(Fable.StaticCall(Fable.Get(Fable.IdentExpr(i),Fable.ExprGet(Fable.Value(Fable.StringConstant(m),_)),_,_)),args),_,_) ->
    //    let f = 
    //        match i.Name ,m with
    //        | "Math", "abs" -> "abs"
    //        | name, m -> fixName name + "::" + fixName m
    //    PhpCall(PhpConst(PhpConstString (f)), convertArgs ctx args)
    //| Fable.Operation(Fable.Call(Fable.StaticCall(Fable.IdentExpr(i)),args),_,_) ->
    //    //PhpCall(PhpConst(PhpConstString (fixName i.Name)), convertArgs ctx args)
    //    let name = fixName i.Name
    //    ctx.UseVarByRef(name)
    //    PhpCall(PhpVar(name, None), convertArgs ctx args)

    | Fable.Expr.Call(Fable.Import({ Selector = name; Path = "." }, _,_), { ThisArg = Some this; Args = args  }, ty,_) ->
        let methodName =
            match this.Type with
            | Fable.DeclaredType(entref,_) ->
                let ent = ctx.GetEntityName(com.GetEntity(entref))
                name.Substring(ent.Length + 2)
            | _ -> name



        PhpMethod(convertExpr com ctx this, PhpConst (PhpConstString methodName), convertArgs com ctx args)
        //PhpCall(PhpConst(PhpConstString name),  convertArgs com ctx  (this::args))
    | Fable.Expr.Call(callee, { ThisArg = Some this; Args = args }, ty,_) ->
        
        let phpCallee = convertExpr com ctx callee

        PhpMethod(convertExpr com ctx this, phpCallee, convertArgs com ctx args)

    | Fable.CurriedApply(expr, args,_,_) ->
        PhpCall(convertExpr com ctx expr, [for arg in args -> convertExpr com ctx arg]) 
      
    | Fable.Emit(info,_,_) ->
        PhpMacro(info.Macro, [for arg in info.CallInfo.Args -> convertExpr com ctx arg])
    | Fable.Get(expr, kind ,tex,_) ->
        let phpExpr = convertExpr com ctx expr
        match kind with 
        | Fable.UnionField(fieldIndex,t, field) ->
            PhpProp(phpExpr, StrField field, None)
        | Fable.OptionValue ->
            phpExpr
        | Fable.ByKey (Fable.KeyKind.FieldKey field) ->
            let fieldName = field.Name
            match getExprType phpExpr with
            | Some phpType ->
                let field = phpType.Fields |> List.find (fun f -> f.Name = fieldName)
                PhpProp(phpExpr, Field field, Map.tryFind field.Type ctx.Types ) 
            | None -> PhpProp(phpExpr, StrField fieldName, None)
         
        | Fable.GetKind.TupleIndex(id) ->
            PhpArrayAccess(phpExpr, PhpConst(PhpConstNumber (float id))) 
        | Fable.ByKey(Fable.KeyKind.ExprKey expr') ->
            let prop = convertExpr com ctx expr'
            match prop with
            | PhpConst(PhpConstString "length") ->
                PhpCall(PhpConst(PhpConstString "count"), [phpExpr])
            | _ -> PhpArrayAccess(phpExpr, prop)
        | Fable.ListHead ->
            PhpProp(phpExpr, Field PhpList.value, getExprType phpExpr)
        | Fable.ListTail ->
            PhpProp(phpExpr, Field PhpList.next, getExprType phpExpr)
        | Fable.UnionTag ->
            PhpMethod(phpExpr, PhpConst(PhpConstString ("get_Tag")), [])

    | Fable.IdentExpr(id) ->
        let phpType = 
            match id.Type with
            | Fable.Type.DeclaredType(e,_) ->
                Map.tryFind e.FullName ctx.Types

            | _ -> None 
        
        let name =
            if id.IsThisArgument then
                "this"
            else
                let name = fixName id.Name
                if Some name = ctx.thisArgument then 
                    "this"
                else
                    ctx.UseVar(name)
                    name


        PhpVar(name, phpType)
    | Fable.Import(info,t,_) ->
        let fixNsName = function
            | "List" -> "FSharpList"
            | "Array" -> "FSharpArray"
            | n -> n
            
            
        match fixNsName(IO.Path.GetFileNameWithoutExtension(info.Path)) with
        | "" ->
            match Map.tryFind info.Selector ctx.IsImportValue with
            | Some true ->
                let name = fixName info.Selector
                PhpGlobal(name)
            | _ ->
                //let name = 
                //    let sepPos = info.Selector.IndexOf("$$")
                //    if sepPos >= 0 then
                //        fixName (info.Selector.Substring(sepPos+2))
                //    else
                //        fixName info.Selector

                PhpIdent(None, fixName info.Selector)

        | cls ->
            match Map.tryFind info.Selector ctx.IsImportValue with
            | Some true ->
                let name = fixName info.Selector
                PhpGlobal(name)
            | _ ->
                ctx.AddRequire(info.Path)
                let sepPos = info.Selector.IndexOf("__")
                if sepPos >= 0 then
                    PhpIdent(None, fixName (info.Selector.Substring(sepPos+2)))
                else
                    PhpIdent(Some cls, fixName info.Selector)

    | Fable.DecisionTree(expr,targets) ->
        let upperTargets = ctx.DecisionTargets
        ctx.DecisionTargets <- targets
        let phpExpr = convertExpr com ctx expr
        ctx.DecisionTargets <- upperTargets
        phpExpr

    | Fable.IfThenElse(guard, thenExpr, elseExpr,_) ->
        PhpTernary(convertExpr com ctx guard,
                    convertExpr com ctx thenExpr,
                    convertExpr com ctx elseExpr )
            

    | Fable.Test(expr, test , _ ) ->
        let phpExpr = convertExpr com ctx expr
        convertTest com ctx test phpExpr
            
        
    | Fable.DecisionTreeSuccess(index,[],_) ->
        let _,target = ctx.DecisionTargets.[index]
        convertExpr com ctx target
    | Fable.DecisionTreeSuccess(index,boundValues,_) ->
        let bindings,target = ctx.DecisionTargets.[index]

        let args = List.map (convertExpr com ctx) boundValues

        let innerCtx = ctx.NewScope()
        for id in bindings do
            innerCtx.AddLocalVar(fixName id.Name, id.IsMutable)
        let body = convertExprToStatement com innerCtx target Return
        for capturedVar in innerCtx.CapturedVars do
            ctx.UseVar(capturedVar)
        PhpCall(
            PhpAnonymousFunc([ for id in bindings -> fixName id.Name ],
                Set.toList innerCtx.CapturedVars, body),
                args )


    | Fable.ObjectExpr(members, t, baseCall) ->
        PhpArray [
            for m in members do
                PhpArrayString m.Name , convertExpr com ctx m.Body
        ]
    | Fable.Expr.Lambda(arg,body,_) ->
        convertFunction com ctx body [arg]
    | Fable.Expr.Delegate(args, body, _) ->
        convertFunction com ctx body args

      
    | Fable.Let(id, expr, body) ->
        let innerCtx = ctx.NewScope()
        innerCtx.AddLocalVar(fixName id.Name, id.IsMutable)
        let phpExpr = convertExpr com ctx expr
        let phpBody = convertExprToStatement com innerCtx body Return
        for capturedVar in innerCtx.CapturedVars do
            ctx.UseVar(capturedVar)
        PhpCall(PhpAnonymousFunc([id.Name], Set.toList innerCtx.CapturedVars , phpBody),[phpExpr])

    | Fable.Expr.TypeCast(expr, t,_) ->
        convertExpr com ctx expr
    | Fable.Expr.Sequential([Fable.Value(Fable.UnitConstant, _) ; body]) ->
        convertExpr com ctx body
    | _ ->
        failwithf "Unknown expr:\n%A" expr
        


and convertArgs com ctx (args: Fable.Expr list) =
    [ for arg in args do 
        match arg with
        | Fable.IdentExpr({ Name = "Array"; IsCompilerGenerated = true}) -> ()
        | _ ->
            match arg.Type with
            | Fable.Unit -> ()
            | _ -> convertExpr com ctx arg
    ]
//and convertArgsThisLast ctx (args: Fable.ArgInfo) =
//       [ 
//         for arg in args.Args do 
//           match arg with
//           | Fable.IdentExpr({ Name = "Array"; Kind = Fable.CompilerGenerated }) -> ()
//           | _ -> convertExpr ctx arg
//         match args.ThisArg with
//         | Some arg -> convertExpr ctx arg
//         | None -> ()
//       ]
            
        
and convertFunction (com: Fable.Compiler) (ctx: PhpCompiler) body (args: Fable.Ident list) =
    let scope = ctx.NewScope()
    let args = 
        [ for arg in args do
            let argName = fixName arg.Name
            scope.AddLocalVar(argName, arg.IsMutable)
            argName ]
 
    let phpBody = convertExprToStatement com scope body Return

    for capturedVar in scope.CapturedVars do
        ctx.UseVar(capturedVar)
    PhpAnonymousFunc(args, Set.toList scope.CapturedVars , phpBody ) 

and convertValue (com: Fable.Compiler) (ctx:PhpCompiler) (value: Fable.ValueKind) =
    match value with
    | Fable.NewUnion(args,tag,ent,_) ->
        let ent = com.GetEntity(ent)
        let t =
            let name = caseNameOfTag ctx ent tag
            match Map.tryFind name ctx.Types with
            | Some t -> t
            | None -> failwithf $"Cannot find type {name}"

        ctx.AddRequire(t)

        PhpNew(t, [for arg in args do convertExpr com ctx arg ])
    | Fable.NewTuple(args) ->
        
        PhpArray([for arg in args do (PhpArrayNoIndex, convertExpr com ctx arg)])
    | Fable.NewRecord(args, e , _) ->
        let t = ctx.Types.[ctx.GetEntityName(com.GetEntity e)]
        ctx.AddRequire(t)
        PhpNew(t, [ for arg in args do convertExpr com ctx arg ] )
        

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
        ctx.AddUse(PhpList.cons)
        PhpNew(PhpList.cons, [convertExpr com ctx head; convertExpr com ctx tail])
    | Fable.NewList(None,_) ->
        ctx.AddRequire(PhpList.nil)
        PhpGlobal("NIL")
    | Fable.NewArray(values,_) ->
        PhpArray([for v in values -> (PhpArrayNoIndex, convertExpr com ctx v)])

    | Fable.NewOption(opt,_) ->
        match opt with
        | Some expr -> convertExpr com ctx expr
        | None -> PhpConst(PhpConstNull)
    



and canBeCompiledAsSwitch evalExpr tree =
    match tree with
    | Fable.IfThenElse(Fable.Test(caseExpr, Fable.UnionCaseTest(tag),_), Fable.DecisionTreeSuccess(index,_,_), elseExpr,_) 
        when caseExpr = evalExpr ->
        canBeCompiledAsSwitch evalExpr elseExpr
    | Fable.DecisionTreeSuccess(index, _,_) ->
        true
    | _ -> false

and findCasesNames evalExpr tree =

    [ match tree with
      | Fable.IfThenElse(Fable.Test(caseExpr, Fable.UnionCaseTest(tag),_), Fable.DecisionTreeSuccess(index,bindings,_), elseExpr,_)
            when caseExpr = evalExpr ->
            Some tag, bindings, index
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


and convertMatching com ctx input guard thenExpr elseExpr expr returnStrategy =
    if (canBeCompiledAsSwitch expr input) then
        let tags = findCasesNames expr input 
        let inputExpr = convertExpr com ctx expr
        [ Switch(PhpMethod(inputExpr, PhpConst(PhpConstString("get_Tag")), []),
            [ for tag,bindings, i in tags ->
                let idents,target = ctx.DecisionTargets.[i]
                let phpCase =
                    match tag with
                    | Some t -> IntCase t
                    | None -> DefaultCase


                phpCase, 
                    [ for ident, binding in List.zip idents bindings do
                        ctx.AddLocalVar(fixName ident.Name, ident.IsMutable)
                        Assign(PhpVar(fixName ident.Name, None), convertExpr com ctx binding)
                      match returnStrategy with
                      | Target t -> 
                            ctx.AddLocalVar(fixName t, false)
                            Assign(PhpVar(fixName t, None), PhpConst(PhpConstNumber(float i)))
                            Break;
                      | Return _ ->
                            yield! convertExprToStatement com ctx target returnStrategy
                      | _ -> 
                            yield! convertExprToStatement com ctx target returnStrategy
                            Break
                    ]] 
            )
        
        ]
    else
        [ If(convertExpr com ctx guard, convertExprToStatement com ctx thenExpr returnStrategy, convertExprToStatement com ctx elseExpr returnStrategy) ]

and convertExprToStatement (com: Fable.Compiler) ctx expr returnStrategy =
    match expr with
    | Fable.DecisionTree(input, targets) ->

        let upperTargets = ctx.DecisionTargets 
        ctx.DecisionTargets <- targets
        let phpExpr = convertExprToStatement com ctx input returnStrategy
        ctx.DecisionTargets <- upperTargets
        phpExpr
    | Fable.IfThenElse(Fable.Test(expr, Fable.TestKind.UnionCaseTest(tag), _) as guard, thenExpr , elseExpr, _) as input ->
        let groupCases = hasGroupedCases Set.empty input
        if groupCases then
            let targetName = ctx.MakeUniqueVar("target")
            let targetVar = PhpVar(targetName, None)
            let switch1 = convertMatching com ctx input guard thenExpr elseExpr expr (Target targetName)

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
                                yield! convertExprToStatement com ctx expr returnStrategy
                            | None -> ()
                            match returnStrategy with
                            | Return _ -> ()
                            | _ -> Break;
                        ]
                    
                    ]
                )
            switch1 @ [ switch2 ]
                
        else
            convertMatching com ctx input guard thenExpr elseExpr expr returnStrategy


    | Fable.IfThenElse(guardExpr, thenExpr, elseExpr, _) ->
        let guard = convertExpr com ctx guardExpr

        [ If(guard, convertExprToStatement com ctx thenExpr returnStrategy,
                    convertExprToStatement com ctx elseExpr returnStrategy) ]
    | Fable.DecisionTreeSuccess(index,boundValues,_) ->
        match returnStrategy with
        | Target target -> [ Assign(PhpVar(target,None), PhpConst(PhpConstNumber (float index))) ]
        | _ ->
            let idents,target = ctx.DecisionTargets.[index]
            [ for ident, boundValue in List.zip idents boundValues do
                ctx.AddLocalVar(fixName ident.Name, ident.IsMutable)
                Assign(PhpVar(fixName ident.Name, None), convertExpr com ctx boundValue)
              yield! convertExprToStatement com ctx target returnStrategy ]

    | Fable.Let(ident, expr,body) ->
        [ 
          let name = fixName ident.Name
          ctx.AddLocalVar(name, ident.IsMutable)
          yield! convertExprToStatement com ctx expr (Let name)
          yield! convertExprToStatement com ctx body returnStrategy ]

    | Fable.Sequential(exprs) ->
        if List.isEmpty exprs then
            []
        else
            [ for expr in exprs.[0..exprs.Length-2] do
                    yield! convertExprToStatement com ctx expr Do
              yield! convertExprToStatement com ctx exprs.[exprs.Length-1] returnStrategy
                    ]
    | Fable.Set(expr,kind,value,_) ->
        let left = convertExpr com ctx expr
        match left with
        | PhpVar(v,_) -> 
            ctx.AddLocalVar(v, true)
        | _ -> ()
        [ Assign(left, convertExpr com ctx value)]
    | Fable.TryCatch(body,catch,finallizer,_) ->
        [TryCatch(convertExprToStatement com ctx body returnStrategy,
                    (match catch with
                    | Some(id,expr) -> Some(id.DisplayName, convertExprToStatement com ctx expr returnStrategy)
                    | None -> None),
                    match finallizer with
                    | Some expr -> convertExprToStatement com ctx expr returnStrategy
                    | None -> []
            )]
            
    | Fable.WhileLoop(guard, body, _) -> 
        [ WhileLoop(convertExpr com ctx guard, convertExprToStatement com ctx body Do ) ]
    | Fable.ForLoop(ident, start, limit, body, isUp, _) ->
        let id = fixName ident.Name
        let startExpr =  convertExpr com ctx  start
        ctx.AddLocalVar(id, false)
        let limitExpr = convertExpr com ctx  limit
        let bodyExpr = convertExprToStatement com ctx body Do

        [ ForLoop(id,startExpr, limitExpr, isUp, bodyExpr)]
        
        

    | Fable.Emit({ Macro = "throw $0"; CallInfo = { Args = [ Fable.Call( Fable.IdentExpr { Name = cls }, { Args = args },_,_ ) ] }},_,_) ->
        [ Throw(cls, [ for arg in args -> convertExpr com ctx arg]) ]

    | _ ->
        match returnStrategy with
        | Return -> [ PhpStatement.Return (convertExpr com ctx expr) ]
        | Let(var) -> 
            ctx.AddLocalVar(var, false)
            [ Assign(PhpVar(var,None), convertExpr com ctx expr) ]
        | Do -> [ PhpStatement.Do (convertExpr com ctx expr) ]
        | Target _ -> failwithf "Target should be assigned by decisiontree success"

let convertDecl (com: Fable.Compiler) (ctx: PhpCompiler) decl =
    match decl with
    | Fable.Declaration.ClassDeclaration decl -> 
        //let ent = decl.Entity
        let ent = com.GetEntity(decl.Entity)
        if ent.IsFSharpUnion then
            let parts = ent.FullName.Split('.')
            let name = parts.[parts.Length - 1]
            ctx.AddEntityName(ent, name)
            convertUnion com ctx ent
        elif ent.IsFSharpRecord then
            let parts = ent.FullName.Split('.')
            let name = parts.[parts.Length - 1]
            ctx.AddEntityName(ent, name)
            convertRecord com ctx ent
        else
            [PhpType {
                Namespace = Some ctx.Namespace
                Name = decl.Name
                Fields = []
                Methods = []
                Abstract = false
                BaseType = None
                Interfaces = []
                File = ctx.File
             }]
    | Fable.Declaration.MemberDeclaration decl ->
        ctx.AddImport(decl.Name, decl.Info.IsValue)
        if decl.Info.IsValue then
            [ PhpDeclValue(fixName decl.Name, convertExpr com ctx decl.Body) ]
        else
            if decl.Info.IsInstance then
                let typ =
                    match decl.Args.[0].Type with
                    | Fable.DeclaredType(_, Fable.DeclaredType(entref,_)  :: _)
                    | Fable.DeclaredType(entref,[])-> 
                        
                        let entName = ctx.GetEntityName(com.GetEntity(entref))
                        ctx.Types.[entName]
                    | t -> failwithf $"Unknow type {t}"

                let name = 
                    decl.Name.Substring(typ.Name.Length + 2) |> fixName

                ctx.thisArgument <- Some (fixName decl.Args.[0].Name)

                let meth =
                    { PhpFun.Name = name;
                      PhpFun.Args = [ for arg in decl.Args.[1..] do
                                        match arg.Type with
                                        | Fable.Unit -> ()
                                        | _ -> fixName arg.Name ]
                      PhpFun.Matchings = []
                      PhpFun.Static = false
                      PhpFun.Body = convertExprToStatement com ctx decl.Body Return } 
                ctx.thisArgument <- None
                let newType =
                    { typ with
                            Methods = typ.Methods @ [ meth ]
                    }

                ctx.AddType(newType) |> ignore
                [ ]
            else

                

                let body = convertExprToStatement com ctx decl.Body Return 
                [{ PhpFun.Name = fixName decl.Name
                   Args = [ for arg in decl.Args do 
                            fixName arg.Name ]
                   Matchings = []
                   Body = body
                   Static = false
                   
                   } |> PhpFun ]
    //| Fable.Declaration.ActionDeclaration(decl) ->
    //    [ PhpDecl.Expr( convertExpr com ctx decl.Body ) ]
    | _ -> failwithf "Cannot convertDecl %A" decl

            
    //| Fable.Declaration.ConstructorDeclaration(Fable.UnionConstructor(info),_) -> 
    //    convertUnion ctx info
    //| Fable.Declaration.ConstructorDeclaration(Fable.CompilerGeneratedConstructor(info),_) -> 
    //    convertRecord ctx info
    //| Fable.Declaration.ValueDeclaration(Fable.Function(Fable.FunctionKind.Delegate(args), body, Some name),decl) ->
    //   [{ PhpFun.Name = fixName name
    //      Args = [ for arg in args do 
    //                fixName arg.Name ]
    //      Matchings = []
    //      Body = convertExprToStatement ctx body Return 
    //      Static = false } |> PhpFun ]
    //| Fable.Declaration.ValueDeclaration(expr , decl) ->
    //    [ PhpDeclValue(fixName decl.Name, convertExpr ctx expr) ]
    //| _ -> [] 


let files = 
    [  __SOURCE_DIRECTORY__ + @"\src\Shared\Shared.fs"
       __SOURCE_DIRECTORY__ + @"\src\Shared\SharedGame.fs"
       __SOURCE_DIRECTORY__ + @"\src\Server\SharedServer.fs"
      ]

let opts   =
    let projOptions: FSharpProjectOptions =
             {
                 ProjectId = None
                 ProjectFileName = __SOURCE_DIRECTORY__ + @"\src\Game\Game.fsproj"
                 SourceFiles = List.toArray files 
                 OtherOptions = [| @"-r:" +  __SOURCE_DIRECTORY__ + @"\..\Fable\src\Fable.Core\bin\Debug\netstandard2.0\Fable.Core.dll"|]
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
result.Errors

let proj = Project(opts ,result,[||])
let compOptions = CompilerOptionsHelper.Make(optimizeFSharpAst=true)
    //{ CompilerOptions.TypedArrays = false
    //  CompilerOptions.clampByteArrays = false
    //  CompilerOptions.debugMode = false
    //  CompilerOptions.outputPublicInlinedFunctions = false
    //  CompilerOptions.precompiledLib = None
    //  CompilerOptions.verbosity = Verbosity.Normal
    //  CompilerOptions.classTypes = false
    //  CompilerOptions.typescript = false }

let phpComp = PhpCompiler.create()
(*
let file = files.[1]
*)
let asts =
    [ for file in files do
        let com = Compiler(file, proj, compOptions, __SOURCE_DIRECTORY__ + @"/../Fable/src/fable-library/bin/Debug/netstandard2.0/")
        let ast =
            Fable.Transforms.FSharp2Fable.Compiler.transformFile com 
            |> Fable.Transforms.FableTransforms.transformFile com 

(*
        let decl = ast.Declarations.[215]
*)
        phpComp.ClearRequire(__SOURCE_DIRECTORY__ + @"/src/")
        phpComp.SetFile(file + ".php")
        phpComp.SetNamespace(Path.GetFileNameWithoutExtension(file))
        let decls = 
            [
                for i,decl in List.indexed ast.Declarations do
                    let decls =
                        try 
                            convertDecl com phpComp decl
                        with
                        |    ex -> 
                            eprintfn "Error while transpiling decl %d: %O" i ex
                            reraise()
                    for d in decls  do
                        i,d
            ]
            |> List.map (fun (i,d) -> 
                match d with
                | PhpType p ->
                    match Map.tryFind p.Name phpComp.Types with
                    | Some t -> i, PhpType t
                    | None -> i, d
                | _ -> i,d)


        { Filename = file + ".php" 
          Namespace = Some phpComp.Namespace
          Require = Set.toList phpComp.Require
          Uses = Set.toList phpComp.NsUse
          Decls = decls }
   ]

//let (Fable.MemberDeclaration decl) = decl
//let (Fable.MemberDeclaration d2) = ast.Declarations.[123]
//d2.Body

//asts.[1].Declarations.[11]
//asts.[0].UsedNamesInRootScope |> Set.toList

//proj.ImplementationFiles
//let fs = 
//    [ 
//      for ast in asts do
//        //try 
//            yield! convertDecl comp phpComp decl
//        //with
//        //| ex -> 
//        //    eprintfn "Error while transpiling decl %d" i
//        //    reraise()
//    ]
//convertDecl com phpComp asts.[1].Declarations.[243]

for file in asts do
    let w = new StringWriter()
    let ctx = Output.Writer.create w
    Output.writeFile ctx file

    let fix =  
        (string w).Replace("$Barns, $Goal, $Undo, $UseGameOver)", "$Barns, $Goal, $Undo=NULL, $UseGameOver=false)")
                  .Replace("$this->Undo = $Undo;", "$this->Undo = $Undo ?? new UndoType_FullUndo();")

    //IO.File.WriteAllText(@"C:\dev\crazy\bga\modules\crazyfarmers.php", fix)

    IO.File.WriteAllText(file.Filename, fix)



let deployTo phpDir root = 
    for phpFile in Directory.EnumerateFiles(root, "*.fs.php", SearchOption.AllDirectories) do
        let dest = Path.GetFullPath(Path.Combine(phpDir, Path.getRelativePath root phpFile))
        printfn "%s" dest
        let dir = Path.GetDirectoryName dest
        if not (Directory.Exists dir) then
            Directory.CreateDirectory(dir) |> ignore

        File.Copy(phpFile, dest, true)

        
deployTo (__SOURCE_DIRECTORY__ + "/php") (__SOURCE_DIRECTORY__ + "/src")
deployTo (__SOURCE_DIRECTORY__ + "/bga/modules") (__SOURCE_DIRECTORY__ + "/src")

