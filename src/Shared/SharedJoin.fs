module SharedJoin
open Shared


type Game =
   | InitialState
   | Setup of Setup
   | Started of Setup
and Setup =
    { Initiator: string
      Players: Map<Color,string*string> }

type Command =
    | Create of Create
    | SetPlayer of Color * string * string
    | Start 
and Create =
    { GameId: string
      Initiator: string}

type Event =
    | Created of Created
    | PlayerSet of PlayerSet
    | Started of PlayerSet list
and Created =
    { GameId: string
      Initiator: string}
and PlayerSet =
    { Color: Color
      PlayerId: string
      Name: string }


let decide cmd state =
    match state, cmd with
    | InitialState, Create cmd ->
        [ Created { GameId = cmd.GameId; Initiator = cmd.Initiator } ]
    | Setup s, SetPlayer (color, id, name) ->
        match Map.tryFind color s.Players with
        | None ->
            [ PlayerSet { Color = color; PlayerId = id; Name = name } ]
        | _ -> []
    | Setup s, Start ->
        [ Started 
            [ for c,(p,n) in Map.toSeq s.Players do
                { Color = c; PlayerId = p; Name = n} ] ]
    | _ -> []


let evolve state event =
    match state, event with
    | InitialState, Created c ->
        Setup { 
            Initiator = c.Initiator
            Players = Map.empty  }
    | Setup s, PlayerSet p ->
        Setup { s with Players = 
                            s.Players
                            |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                            |> Map.add p.Color (p.PlayerId, p.Name)  }
    | Setup s, Started _ ->
        Game.Started s
    | _ ->  state
    

type ServerMsg =
    | CreateGame
    | JoinGame of string
    | SelectColor of Color
    | Start
    | Login of string
    | Register of string * string

type ClientMsg =
    | Events of Event list * int
    | SyncCreate of string * Game * int
    | SyncJoin of string * Game * int
    | SyncStarted of string * Game * int
    | LoggedIn of string * string
    | ShouldLogin
    | StartCheck of string



