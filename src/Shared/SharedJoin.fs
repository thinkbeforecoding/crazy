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
    | PlayerSet of Color * string * string
    | Started of (Color * (string*string)) list
and Created =
    { GameId: string
      Initiator: string}


let decide cmd state =
    match state, cmd with
    | InitialState, Create cmd ->
        [ Created { GameId = cmd.GameId; Initiator = cmd.Initiator } ]
    | Setup s, SetPlayer (color, id, name) ->
        match Map.tryFind color s.Players with
        | None ->
            [ PlayerSet(color,id, name) ]
        | _ -> []
    | Setup s, Start ->
        [ Started (Map.toList s.Players)  ]
    | _ -> []


let evolve state event =
    match state, event with
    | InitialState, Created c ->
        Setup { 
            Initiator = c.Initiator
            Players = Map.empty  }
    | Setup s, PlayerSet(color,id, name) ->
        Setup { s with Players = 
                            s.Players
                            |> Map.filter (fun _ (p,_) -> p <> id)
                            |> Map.add color (id, name)  }
    | Setup s, Started _ ->
        Game.Started s
    | _ ->  state
    

type ServerMsg =
    | CreateGame
    | JoinGame of string
    | SelectColor of Color
    | Start

type ClientMsg =
    | Events of Event list
    | SyncCreate of string * Game
    | SyncJoin of string * Game
    | SyncStarted of string * Game



