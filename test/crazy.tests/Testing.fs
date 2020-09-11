module Testing
open Fable.Remoting.Json
open Newtonsoft.Json
open System
open Shared


let s =
    let js = Newtonsoft.Json.JsonSerializer()
    js.Converters.Add (FableJsonConverter())
    js

let readEvents text = 
    s.Deserialize<Board.Event []>(new JsonTextReader(new IO.StringReader(text)))
    |> Array.toList



