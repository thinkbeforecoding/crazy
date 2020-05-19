module SharedJoin
open Shared
type Game =
   | InitialState
   | Setup of Setup
   | Started of Setup
   | Cancelled
and Setup =
    { Initiator: string
      Players: Map<Color,string*string>
      Goal: GoalType
      Public: bool
      }


type Command =
    | Create of Create
    | SetPlayer of Color * string * string
    | Leave of string
    | SetGoal of GoalType
    | MakePublic
    | MakePrivate
    | Start 
and Create =
    { GameId: string
      Initiator: string}

type Event =
    | Created of Created
    | PlayerSet of PlayerSet
    | Leaved of string
    | GoalSet of GoalType
    | MadePublic
    | MadePrivate
    | Started of Started
    | Cancelled
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
    | Setup s, Leave id ->
        if s.Initiator = id then
            [ Cancelled ]
        else
            if Map.exists (fun _ (i,_) -> i = id) s.Players then
                [ Leaved id]
            else
                []
    | Setup s, SetGoal goal ->
        [ GoalSet goal ]
    | Setup { Public = false}, MakePublic ->
        [ MadePublic ]
    | Setup { Public = true}, MakePrivate ->
        [ MadePrivate ]
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
            Public = false
            }
    | Setup s, PlayerSet p ->
        Setup { s with Players = 
                            s.Players
                            |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                            |> Map.add p.Color (p.PlayerId, p.Name)  }
    | Setup s, Leaved p ->
        Setup { s with Players =
                            s.Players
                            |> Map.filter (fun _ (pid,_) -> pid <> p) }
    | Setup s, GoalSet goal ->
        Setup { s with Goal = goal }
    | Setup s, MadePublic ->
        Setup { s with Public = true }
    | Setup s, MadePrivate ->
        Setup { s with Public = false }
    | Setup s, Started _ ->
        Game.Started s
    | Setup s, Cancelled ->
        Game.Cancelled
    | _ ->  state
    

type ServerMsg =
    | CreateGame
    | Select
    | JoinGame of string
    | SelectColor of Color
    | SelectGoal of GoalType
    | Leave
    | MakePublic
    | MakePrivate
    | Start
    | Login of string
    | Register of string * string


type PublicGame =
    { Id: string
      Goal: GoalType
      Players: (string*string)[]
      Created: System.DateTime
    }
type ClientMsg =
    | Events of Event list * int
    | SyncCreate of string * Game * int
    | SyncJoin of string * Game * int
    | SyncStarted of string * Game * int
    | LoggedIn of string * string
    | ShouldLogin
    | StartCheck of string
    | UpdatePublicGames of PublicGame[]



