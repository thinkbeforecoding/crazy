module SharedJoin
open Shared
type Game =
   | InitialState
   | Setup of Setup
   | Started of Setup
and Setup =
    { Initiator: string
      Players: Map<Color,string*string>
      Goal: GoalType
      }


type Command =
    | Create of Create
    | SetPlayer of Color * string * string
    | SetGoal of GoalType
    | Start 
and Create =
    { GameId: string
      Initiator: string}

type Event =
    | Created of Created
    | PlayerSet of PlayerSet
    | GoalSet of GoalType
    | Started of Started
and Created =
    { GameId: string
      Initiator: string}
and PlayerSet =
    { Color: Color
      PlayerId: string
      Name: string }
and Started =
    { Players: PlayerSet list
      Goal: Goal }
let decide cmd state =
    match state, cmd with
    | InitialState, Create cmd ->
        [ Created { GameId = cmd.GameId; Initiator = cmd.Initiator } ]
    | Setup s, SetPlayer (color, id, name) ->
        match Map.tryFind color s.Players with
        | None ->
            [ PlayerSet { Color = color; PlayerId = id; Name = name } ]
        | _ -> []
    | Setup s, SetGoal goal ->
        [ GoalSet goal ]

    | Setup s, Start ->
        match Goal.fromType s.Players.Count s.Goal  with
        | Some goal ->
            [ Started 
                { Players = 
                    [ for c,(p,n) in Map.toSeq s.Players do
                        { Color = c; PlayerId = p; Name = n} ] 
                  Goal = goal
                    }]
        | None -> []
    | _ -> []

let evolve state event =
    match state, event with
    | InitialState, Created c ->
        Setup { 
            Initiator = c.Initiator
            Players = Map.empty 
            Goal = Regular
            }
    | Setup s, PlayerSet p ->
        Setup { s with Players = 
                            s.Players
                            |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                            |> Map.add p.Color (p.PlayerId, p.Name)  }
    | Setup s, GoalSet goal ->
        Setup { s with Goal = goal }
    | Setup s, Started _ ->
        Game.Started s
    | _ ->  state
    

type ServerMsg =
    | CreateGame
    | JoinGame of string
    | SelectColor of Color
    | SelectGoal of GoalType
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



