#r "c:/Users/jerem/.nuget/packages/newtonsoft.json/12.0.3/lib/netstandard2.0/Newtonsoft.Json.dll"
#r "c:/Users/jerem/.nuget/packages/fable.jsonconverter/1.0.8/lib/net45/Fable.JsonConverter.dll"
#r "c:/Users/jerem/.nuget/packages/fable.remoting.json/2.6.0/lib/netstandard2.0/Fable.Remoting.Json.dll"
#r "c:/Users/jerem/.nuget/packages/fable.core/3.1.5/lib/netstandard2.0/Fable.Core.dll"
#r "c:/Development/packages/FSharp.Data/lib/net45/FSharp.Data.dll"
#I  "c:/Development/crazy/src/Shared/"
#load "Shared.fs" "SharedGame.fs"
#load "../Server/SharedServer.fs"


open System
open Shared
open Fable
open Fable.Remoting.Json
open Newtonsoft.Json
open FSharp.Data

//type Envelope =
//    { data: Data }
//and Data =
//    { data: Notif [] }
//and Notif = 
//    { channel: string
//      data: NotifData[] }
//and NotifData =
//    { args: Args}

//and Args =
//    { version: int
//      events: Board.Event []}


let s =
    let js = Newtonsoft.Json.JsonSerializer()
    js.Converters.Add (FableJsonConverter())
    js
//let data = 
//    use json = IO.File.OpenText @"C:\Users\jerem\Downloads\gamelog.json"
//    s.Deserialize<Envelope>(new JsonTextReader(json))

let events = 
    Http.RequestStream("http://localhost/convert3.php")
    |> fun stream -> s.Deserialize<Board.Event []>(new JsonTextReader(new IO.StreamReader(stream.ResponseStream)))
    |> Array.toList
    |> List.takeWhile(function
                        | Board.Played(_, Player.CardPlayed(PlayBribe _)) -> false
                        | _ -> true )


let state = List.fold Board.evolve Board.initialState events

//let es = Board.decide (Board.Play("63097441", Player.PlayCard(PlayBribe(Parcel(Axe(-1,1))) ))) state
let es = Board.decide (Board.Play("2323673", Player.PlayCard(PlayBribe(Parcel(Axe(2,1))) ))) state

//let events = 
//    data.data.data
//    |> Array.collect(fun d -> if d.channel.StartsWith @"/table/" then  d.data else [||])
//    |> Array.filter    (fun d -> if isNull(box d) || isNull (box d.args) || isNull(d.args.events) then false else d.args.version < 200 )
//    |> Array.collect(fun d -> d.args.events)
//    |> Array.toList


//let start = Board.Start { Players = [ Color.Blue, "88302919", "TEMPVSCANIS"; Color.Yellow, "88218789", "Ryochou"];
//                          Goal = Common 27
//                          Undo = FullUndo
//                          UseGameOver = false}

//let started = Board.decide start Board.initialState
//let startedBoard = List.fold Board.evolve Board.initialState started |> SharedServer.privateUndoableBoard ""

//let state =
//    List.fold Board.evolve startedBoard (SharedServer.privateEvents "" events) 

//let cmd = Board.Play("87747553", Player.Move { Direction = Down; Destination = Crossroad(Axe(-2,2), CRight)})

//let result = Board.decide cmd state

//let finalState =
//    List.fold Board.evolve state ( SharedServer.privateEvents "" result)