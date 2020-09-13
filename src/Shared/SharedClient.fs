module SharedClient

open Shared
open Fable.React
open Fable.React.Props
open Elmish.React
open Globalization

type HayBaleAction = Place | Remove

type CardExt =
| NoExt
| HayBales of HayBaleAction * added:Path list * removed:Path list

type CardAction = 
    { Index: int
      Card: Card
      Hidden: bool
      Ext: CardExt }


type Chat =
    { Entries: ChatEntry list 
      Show: bool
      Pop: bool }

// The model holds data that you want to keep track of while the application is running
// in this case, we are keeping track of a counter
// we mark it as optional, because initially it will not be available from the client
// the initial value will be requested from server
type Model =
  { Board: UndoableBoard
    LocalVersion: int
    Synched: UndoableBoard
    Version: int
    PlayerId: string option
    CardAction: CardAction option
    Moves: Move list 
    Message: string
    Error : string
    DashboardOpen: bool
    PlayedCard: Card option
    Chat: Chat
    ShowVictory: bool
    }


// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg =
    | Move of Direction * Crossroad
    | PlayCard of PlayCard
    | SelectFirstCrossroad of Crossroad
    | SelectCard of Card * int
    | RemoveHayBale of Path
    | SelectHayBale of Path
    | CancelCard
    | DiscardCard of Card
    | SwitchDashboard
    | EndTurn
    | Remote of ClientMsg
    | ConnectionLost
    | Go
    | HidePlayedCard
    | SendMessage of string
    | ToggleChat
    | HidePop
    | HideVictory
    | Undo
    | Quit
    | CommandNotSent of int * UndoableBoard



module Pix =
    let size = 5.75
    let offsetX = 48.5
    let offsetY = 47.8

    let ofPoint (Axe(q,r)) = 
        let x = size * (3./2. * float q ) + offsetX
        let y = size * (sqrt 3./2. * float q  +  sqrt 3. * float r) + offsetY
        x,y

    let ofTile pos =
        let x,y = ofPoint pos  
        x - size /2. , y - size /2.

    let playerSize = 0.
    let ofPlayer (Crossroad (tile,corner)) =
        let tx,ty = ofPoint tile 
        let x = 
            match corner with
            | CLeft  -> tx - size * 0.9 - playerSize/2.
            | CRight -> tx + size * 1.1 - playerSize/2.
        let y = ty - playerSize/2.
        x,y
          

    let fenceWidth = 2.
    let ofFence (Path(tile,border)) =
        let tx,ty = ofPoint tile
        
        match border with
        | BN -> tx - size * 0.1,ty-size*0.73
        | BNW -> tx - size*0.77,ty-size*0.31
        | BNE -> tx + size*0.74,ty-size*0.27



    let teta = -0.063
    let cost = cos teta 
    let sint = sin teta
    let rotate (x,y) =
        let cx = x-offsetX
        let cy = y-offsetY
        offsetX + cx * cost + cy * sint, offsetY - cx * sint + cy * cost 

    let translate (dx: float,dy: float) (x: float,y: float) =
        x+dx, y+dy

let (==>) x y = x, box y
module String =
    open System.Text.RegularExpressions
    let fmtRx = Regex("{(\w+)}")
    let format fmt args =
        fmtRx.Replace(fmt, MatchEvaluator (fun m ->
            match Map.tryFind m.Groups.[1].Value args with
            | Some v -> string v
            | None -> m.Value ) )


let colorName color = 
    match color with
    | Blue -> "blue"
    | Yellow -> "yellow"
    | Purple -> "purple"
    | Red -> "red"
    
let translatedColorName color = 
    match color with
    | Blue -> translate "blue"
    | Yellow -> translate "yellow"
    | Purple -> translate "purple"
    | Red -> translate "red"
   
let (%%) x y =
    let m = x % y
    if m >= 0 then m else m + y

let parcel (Parcel pos) attr = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    let (Axe(q,r)) = pos
    div ([ Class (sprintf "tile t%d" ((q + r * 3) %% 8 ))
           Style [ Left (sprintf "%f%%" x)
                   Top (sprintf "%f%%" y)
                   ]]  @ attr)
          
        []
let tile (Parcel pos) f = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div ([ ClassName "tile empty cf-click"
           Style [ Left (sprintf "%f%%" x)
                   Top (sprintf "%f%%" y)
                   ]
           OnClick f
                   ]   )
          
        []

let tooltip txt =
    div [ ClassName "tooltiptext" ] [ str txt ]

let tileWithTooltip (Parcel pos) text = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div ([ ClassName "tile empty"
           Style [ Left (sprintf "%f%%" x)
                   Top (sprintf "%f%%" y)
                   ]
         ]   )
        [ tooltip text ]

let barn (Parcel pos) occupied = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div [ classBaseList "barn" [ "occupied", occupied ]
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y)
                   ]] 
        []

let key (Crossroad(Axe(q,r),s)) =
    sprintf "c-%d-%d-%s" q r (match s with CLeft -> "l" | CRight -> "r")

let drawplayer selected (x,y) =
    div [ classBaseList "player" ["selected", selected]
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y)
                   ]] 
        []

let drawplayerOnCrossroad pos selected (x,y) =
    div [ classBaseList "player" [key pos, true; "selected", selected]
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y)
                   ]] 
        []

let player active pos =
    Pix.ofPlayer pos
    |> Pix.rotate
    |> drawplayerOnCrossroad pos active

let drawcrossroad pos f =
    let x,y = Pix.ofPlayer pos |> Pix.rotate
    let key = key pos

    div [ classBaseList "crossroad" [key, true; "cf-click", match f with Ok _ -> true | _ -> false  ]
          Key key
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y) ]
          match f with
          | Ok f -> OnClick f
          | _ -> () ] 
        [ match f with
          | Ok _ -> ()
          | Error Tractor -> tooltip (translate "The crossroad is occupied by another tractor")
          | Error HayBaleOnPath -> tooltip (translate "The path is blocked by a hay bale")
          | Error PhytosanitaryProducts -> tooltip (translate "You cannot cut a fence just after using an Helicopter: with phytosanitary products, it could explode")
          | Error Protection -> tooltip (translate "You cannot cut on the two fences behind a tractor. Too close, you'd be shotgunned !")
          | Error HighVoltageProtection -> tooltip (translate "The fence is under High Voltage. You'd be reduced to ashes !")
        ]




let warnPlayer currentPlayer color pos  =
    let x,y = Pix.ofPlayer pos |> Pix.rotate
    let key = key pos
    div [ ClassName (colorName color)]
        [ div [ ClassName ("player warn " + key)
                Style [ Left (sprintf "%f%%" x)
                        Top (sprintf "%f%%" y) ]] 
                      [
                         ]
          div [ ClassName ("warntip " + key)
                Style [ Left (sprintf "%f%%" x)
                        Top (sprintf "%f%%" y) ]] 
                      [
                           if currentPlayer then 
                                tooltip (translate ("If you end the turn here, the game finishes in a draw"))
                           else
                                tooltip (translate ("If you stay in current position and this player finishes their turn on this crossroad, the game finishes in a draw "))
                      ]
              ]
         

let crossroad pos f = drawcrossroad pos (Ok f)

let blockedCrossroad pos e = drawcrossroad pos (Error e)

let fenceClass (Path(Axe(q,r), side)) =
    let s =
        match side with
        | BNW -> "w"
        | BN -> "n"
        | BNE -> "e"

    sprintf "b-%d-%d-%s" q r s

let singleFence path =
    let x,y = Pix.ofFence path |> Pix.rotate
    let rot =
        match path with
        | Path(_,BN) -> "rotate(4deg)"
        | Path(_,BNW) -> "rotate(-56deg)"
        | Path(_,BNE) -> "rotate(64deg)"

    
    div [ ClassName ("fence " + fenceClass path)
          Style [ Transform rot
                  Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y) ]] 
        []

let haybale p =
    let x,y = Pix.ofFence p |> Pix.translate (0.2,-0.8) |> Pix.rotate
    div [ ClassName "hay-bale"
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y) ]
          ]
        []

let path p f =
    let x,y = Pix.ofFence p |> Pix.translate (0.2,-0.8) |> Pix.rotate
    div [ ClassName "path cf-click"
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y) ]
          OnClick f

          ]
        []

let pathWithTooltip p text =
    let x,y = Pix.ofFence p |> Pix.translate (0.2,-0.8) |> Pix.rotate
    div [ ClassName "path"
          Style [ Left (sprintf "%f%%" x)
                  Top (sprintf "%f%%" y) ]
          ]
        [ tooltip text]





let sameField x y =
    match x,y with
    | Playing px, Playing py -> px.Field = py.Field
    | Starting px, Starting py -> px = py 
    | _ -> false

let sameFence x y = 
    match x,y with
    | Playing px, Playing py -> px.Fence = py.Fence
    | Starting _, Starting _ -> true
    | _ -> false

let playerField  p =
        match p with
        | Playing p ->
            let principal =
                Field.principalField p.Field p.Fence p.Tractor
            let fallow = p.Field - principal

            div [ ClassName (colorName p.Color)]
                [ 
                  for p in Field.parcels principal do
                        parcel p []
                 
                  div [ ClassName "fallow" ]
                      [ for p in Field.parcels fallow do
                          parcel p [] ]
                        
                ]
        | Starting { Parcel = p; Color = color } ->
            div [ ClassName (colorName color) ]
                [ parcel p [] ] 
        | Ko _ -> 
            null

let playerFences  p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color) ]
            [ let (Fence paths) = p.Fence
              for p,_ in paths do
                singleFence p ]
    | Starting _ 
    | Ko _ ->
        null

let playerTractor selected p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color) ]
            [ player selected p.Tractor ]
    | Starting { Parcel = Parcel p; Color = color } ->
        div [ ClassName (colorName color) ]
            [ Pix.ofTile p
              |> Pix.rotate
              |> Pix.translate (3.3,3.3)
              |> drawplayer selected ]
    | Ko _ -> null



let moveView dispatch move =
    match move with
    | Move.Move (dir,c) -> crossroad c (fun _ -> dispatch (Move (dir, c)))
    | Move.ImpossibleMove (_,c,e) -> blockedCrossroad   c e
    | Move.SelectCrossroad(c) -> crossroad c (fun _ -> dispatch (SelectFirstCrossroad c))
       

let handView dispatch title playerId board cardAction hand =
    let player = Board.currentPlayer board
    let active =  Option.exists (fun p -> p = board.Table.Player) playerId
    let otherPlayers = Board.currentOtherPlayers board
    let cancel = a [ ClassName "cancel"; Href "#"; OnClick (fun e -> dispatch CancelCard; e.preventDefault())] [ str (translate "Cancel") ]
    let discard card =  
        [ span [] []
          a [ ClassName "discard"; Href "#"; OnClick (fun e -> dispatch (DiscardCard card); e.preventDefault())] [ str (translate "Discard") ] ]
    let go = button [ OnClick (fun _ -> dispatch Go )]  [str (translate "Go") ]
    let action title texts buttons =
        div [ClassName "action" ]
            [ h2 [] [ str title ]
              for t in texts do
                p [] [ t ]


              if active then
                  div [ ClassName "buttons" ] [ yield! buttons; yield cancel ] 
              else
                  p [] [ str (translate ("You can only play cards during your turn")) ]
                  div [ ClassName "buttons" ]
                    [ cancel ]
            ]

    match hand with 
    | PublicHand cards -> 
        div [ ClassName "cards" ]
            [ 
              if title then
                  div [ ClassName "hand-title"] [ str (translate ("Your hand")) ]

              if title && List.isEmpty cards then
                  div [ ClassName "empty-hand"] [ 
                    p [] [ str (translate "Your hand is empty") ]
                    p [] [ str (translate "Take over barns to get bonus cards") ]  ]
              else
                  div [ ClassName "hand"] [
                      for i,c in List.indexed cards do
                       div [ClassName "card-container" ]
                           [
                                div [ ClassName ("card " + Client.cardName c)
                                      match cardAction with
                                      | Some { Index = index } when index = i -> 
                                            OnClick (fun _ -> dispatch CancelCard)
                                      | _ -> OnClick (fun _ -> dispatch (SelectCard(c,i))) ] [
                                        div [ ClassName "tooltiptext" ]
                                            [ p [] [ str
                                                  (match c with
                                                   | Nitro One -> translate "Nitro +1"
                                                   | Nitro Two -> translate  "Nitro +2"
                                                   | Rut -> translate "Rut"
                                                   | HayBale One -> translate  "1 Hay Bale"
                                                   | HayBale Two -> translate "2 Hay Bales"
                                                   | Dynamite -> translate "Dynamite"
                                                   | HighVoltage -> translate "High Voltage"
                                                   | Watchdog -> translate "Watchdog" 
                                                   | Helicopter -> translate "Helicopter"
                                                   | Bribe -> translate "Bribe"
                                                   | GameOver -> translate "Game over")]
                                              p [] [ str (translate " (click for full description)")] ] ]

                                match cardAction with
                                | Some { Index = index; Card = Nitro power; Hidden = false} when index = i ->
                                    action ( match power with One -> translate "Nitro +1" | Two -> translate  "Nitro +2") 
                                        [ str (String.format (translate "Gives you {moves} extra move(s) during this turn.") (Map.ofList [ "moves" ==> match power with One -> 1 | Two -> 2  ]))
                                          Standard.i [] [str (translate "(Reminder: max. 5 moves per turn)") ] ]
                                         [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayNitro power))) ] [ str (translate "Play") ] 
                                           yield! discard (Nitro power)]
                                | Some { Index = index; Card = Rut; Hidden = false } when index = i ->
                                    action (translate "Rut")
                                        [ str (translate "Choose an opponent; he/she will have two fewer moves during his next turn") ]
                                        [ for playerId, player in otherPlayers do
                                              div [ OnClick (fun _ -> dispatch (PlayCard (PlayRut playerId)))
                                                    ClassName (colorName (Player.color player)) ] [
                                                        div [ ClassName "player"] [] ] 
                                          yield! discard Rut]
                                | Some { Index = index; Card = HighVoltage; Hidden = false } when index = i ->
                                    action (translate "High Voltage")
                                        [ str (translate "Protects the entire length of the fence, even the starting point until your next turn. Other tractors cannot go through or cut your fence.") ]
                                        [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayHighVoltage))) ] [ str (translate "Play") ] 
                                          yield! discard HighVoltage ]
                                | Some { Index = index; Card = Watchdog; Hidden = false } when index = i ->
                                    action (translate "Watchdog")
                                        [ str (translate "Protects your plots and barns from being annexed until next turn. Annexations by opponents leave your plots and barns in place.") ]
                                        [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayWatchdog))) ] [ str (translate "Play") ] 
                                          yield! discard Watchdog ] 
                                | Some { Index = index; Card = Helicopter; Hidden = false } when index = i ->
                                    let canUseHelicopter = Player.canUseHelicopter player
                                    action (translate "Helicopter")
                                        [ str (translate "Moves your tractor to any point in your field. The point of arrival must be in the field or at the edge. Once moved, you cannot cut any more fences until the end of the turn: crop protection agents + electicity... I could explode!")
                                          if canUseHelicopter then
                                            str (translate "Select a destination in your field")
                                          else
                                            str (translate "Cannot be played with a fence") ]
                                        [ if canUseHelicopter then
                                            go 
                                          yield! discard Helicopter ]
                                | Some { Index = index; Card = HayBale One; Hidden = false; Ext = HayBales(act,_,_) } when index = i ->
                                    action (translate "1 Hay Bale")
                                        [ str (translate "Hay bales block the path for all players until blasted out with dynamite. You cannot place a Hay Bale on a fence in progress or on the edge of the board. It is forbiddent to lock in an opponent.")
                                          match act with
                                          | Place -> str (translate "Select a free path for the hay bale")
                                          | Remove -> str (translate "There are already 8 hay bales, select one to move") ]
                                        [ go 
                                          yield! discard (HayBale One)]
                                | Some { Index = index; Card = HayBale Two; Hidden = false; Ext = HayBales(act,add,_) } when index = i ->
                                    action (translate "2 Hay Bales")
                                        [ str (translate "Hay bales block the path for all players until blasted out with dynamite. You cannot place a Hay Bale on a fence in progress or on the edge of the board. It is forbiddent to lock in an opponent.")
                                          match act, add with
                                          | Place, [] -> str (translate "Select a  free paths for the first hay bale")
                                          | Place, _ ->  str (translate "Select a free paths for the second hay bales")
                                          | Remove, _ -> str (translate "There are already 8 hay bales, select one to move") 
                                          ]
                                        [ go 
                                          yield! discard (HayBale Two)]
                                | Some { Index = index; Card = Dynamite; Hidden = false } when index = i ->
                                     action (translate "Dynamite")
                                        [ str (translate "Remove 1 Hay Bale of your choice")
                                          str (translate "Select a hay bale to blow up") ]
                                        [ go
                                          yield! discard Dynamite]
                                | Some { Index = index; Card =  Bribe; Hidden = false } when index = i ->
                                     action (translate "Bribe")
                                        [ str (translate "It wasn't clear on the plan... slipping a small bill should do the trick. The choose a plot of an opponent's field that has a common edge with yours... now it belongs to you! Careful, it needs to be discreet. You cannot take a plot of land from which a fence starts, it would cut it off, hence a bit conspicuous... You cannot take a barn either, hard to hide... You cannot place your last plot using this bonus, it would be a bit much!")
                                          match Board.bribeParcels board with
                                          | Ok _ -> str (translate "Select a parcel on the border of your field to take over")
                                          | Error Board.InstantVictory -> str (translate "You cannot bribe to take the last parcel ! That would be too visible !")
                                          | Error Board.NoParcelsToBribe -> str (translate "There is no parcel to bribe")
                                        ]
                                        [ go 
                                          yield! discard Bribe]
                                | _ -> ()
                        ]
                    ]
              let cardCount = Hand.count board.DrawPile
              if cardCount > 0 then
                div [ ClassName "drawpile" ]
                    [ 
                        h3 [] [ str (translate "Draw pile" ) ]

                        div [ClassName "card-info"]
                          [ div [ ClassName "card-count" ]
                                [ 
                                  div [] [span [][str (string (cardCount))]] 
                                   ]
                            tooltip (String.format (Globalization.translate "{cards} cards in the draw pile") (Map.ofList [ "cards" ==> cardCount])) ]

                    ]
           ]
    | PrivateHand cards ->
        div [ ClassName "cards"]
            [ div [ ClassName "hand"] [

                  for c in 1..cards do
                   div [ClassName "card-container" ]
                       [ div [ ClassName (sprintf "card back z%d" c) ] [] ]
              ]

              let cardCount = Hand.count board.DrawPile
              if cardCount > 0 then
                 div [ ClassName "drawpile" ]
                     [ 
                         h3 [] [ str (translate "Draw pile" ) ]

                         div [ClassName "card-info"]
                           [ div [ ClassName "card-count" ]
                                 [ 
                                   div [] [span [][str (string (cardCount))]] 
                                    ]
                             tooltip (String.format (Globalization.translate "{cards} cards in the draw pile") (Map.ofList [ "cards" ==> cardCount])) ]

                     ]

            ]

let barnsView barns =
    div [] 
        [ for parcel in Field.parcels barns.Free do
            barn parcel false
          for parcel in Field.parcels barns.Occupied do
            barn parcel true ]

let hayBalesView cardAction board =
    div []
        [   let hb =
                match cardAction with
                | Some { Ext = HayBales(_,added,removed) } -> board.HayBales - set removed + set added
                | _ -> board.HayBales
        
            for p in hb do
                haybale p
        ]

let boardView cardAction board =
   [ 

   
     for _,p in Map.toSeq board.Players do
         playerField p




     lazyView barnsView board.Barns

     for _,p in Map.toSeq board.Players do
         lazyViewWith sameFence playerFences  p

     let boardPos = History.createPos board
     for playerid,p in Map.toSeq board.Players do

             for opp, cross in History.findDangerousPositions playerid boardPos board.History  do
               warnPlayer (opp = board.Table.Player) (Player.color board.Players.[opp]) cross

             playerTractor (Table.isCurrent playerid board.Table)  p 
         
     hayBalesView cardAction  board

     //if not (Board.currentPlayer board |> Player.moves |> Moves.canMove) then
     //    match History.positionRepetitions board.Table.Player boardPos board.History with
     //    | 1 -> div [] [ str "Danger ! Deja une fois" ]
     //    | 2 -> div [] [ str "Danger ! Deja deux fois" ]
     //    | _ -> null
     ]

let goalView board =
    match board.Goal with
    | Common c ->
        div []
            [ str (String.format (translate "{parcels} parcels left") (Map.ofList [ "parcels" ==> c - Board.totalSize board])) ]
    | Individual c ->
        div []
            [ for playerid, player in Map.toSeq board.Players do
                p [] [ str (String.format (translate "{player}: {parcels} parcels left") (Map.ofList [ "player" ==> board.Table.Names.[playerid]; "parcels" ==> (c - Player.fieldTotalSize player)])) ]
            ]

let endTurnView dispatch playerId board =
    if playerId = Some board.Table.Player then
        match  board.Players.[board.Table.Player] with
        | Playing player when 
             not (Moves.canMove player.Moves) && Hand.canPlay player.Hand || not (Player.canMove playerId (Board board)) ->
               [ crossroad player.Tractor (fun _ -> dispatch EndTurn) ]
                
        | _ -> []
    else
        []

let helicopterDestinations player board =
    Field.crossroads (Player.field player) 
    - (set [ for _,p in Map.toSeq board.Players do
                match p with
                | Playing p ->
                      p.Tractor
                      yield! Fence.fenceCrossroads p.Tractor p.Fence
                | _ -> ()
                      ])


let boardCardActionView dispatch board  cardAction =

    match cardAction with
    | Some { Card = Helicopter} ->
        let player = Board.currentPlayer board
        [ for c in helicopterDestinations player board do
            crossroad c (fun _ -> dispatch (PlayCard (PlayHelicopter c))) ]
    | Some { Card =  HayBale One; Ext = HayBales(action,_,rm) }  ->
        match action with
        | Place ->
            [ for p, result in HayBales.hayBaleDestinationsWithComment (Map.toSeq board.Players) board.HayBales do
                match result with
                | Ok _ -> path p (fun _ -> dispatch (PlayCard (PlayHayBale([ p ], rm))))
                | Error HayBales.FenceBlocker -> pathWithTooltip p (translate "You cannot place a hay bale on a fence")
                | Error HayBales.CutPathBlocker -> pathWithTooltip  p (translate "You cannot block a crossroad with hay bales")
                | Error HayBales.BorderBlocker -> pathWithTooltip  p (translate "You cannot block a border path")
                ]

        | Remove ->
            [ for p in board.HayBales do
                path p (fun _ -> dispatch (RemoveHayBale  p )) ]
            
    | Some {Card = HayBale Two; Ext = HayBales(action, added,rm)  } ->
        match action, added with
        | Remove, _ ->
            [ for p in board.HayBales do
                path p (fun _ -> dispatch (RemoveHayBale  p )) ]
        | Place, [] ->
            [ for p, result in HayBales.hayBaleDestinationsWithComment (Map.toSeq board.Players) (board.HayBales - set rm + set added)  do
                match result with
                | Ok _ -> path p (fun _ -> dispatch (SelectHayBale p))
                | Error HayBales.FenceBlocker -> pathWithTooltip p (translate "You cannot place a hay bale on a fence")
                | Error HayBales.CutPathBlocker -> pathWithTooltip  p (translate "You cannot block a crossroad with hay bales")
                | Error HayBales.BorderBlocker -> pathWithTooltip  p (translate "You cannot block a border path") ]
        | Place, _ ->
            [ for p, result in HayBales.hayBaleDestinationsWithComment (Map.toSeq board.Players) (board.HayBales - set rm + set added)  do
                match result with
                | Ok _ -> path p (fun _ -> dispatch (PlayCard (PlayHayBale( p::added , rm))))
                | Error HayBales.FenceBlocker -> pathWithTooltip p (translate "You cannot place a hay bale on a fence")
                | Error HayBales.CutPathBlocker -> pathWithTooltip  p (translate "You cannot block a crossroad with hay bales")
                | Error HayBales.BorderBlocker -> pathWithTooltip  p (translate "You cannot block a border path") ]
    | Some { Card =  Dynamite}  ->
        [ for p in board.HayBales do
            path p (fun _ -> dispatch (PlayCard (PlayDynamite p))) ]
    | Some { Card =  Bribe}  ->

            [ match Board.bribeParcels board with
              | Ok parcels ->
                for p in Field.parcels parcels do
                    tile p (fun _ -> dispatch (PlayCard (PlayBribe p)))
              | Error _ -> ()
              for p, blocker in Board.bribeParcelsBlockers board do
                match blocker with
                | Board.BribeParcelBlocker.BarnBlocker -> tileWithTooltip p (translate "You cannot bribe a barn")
                | Board.BribeParcelBlocker.FenceBlocker -> tileWithTooltip p (translate "You cannot bribe a parcel that would cut a fence or where the player is")
                | Board.BribeParcelBlocker.LastParcelBlocker -> tileWithTooltip p (translate "You cannot bribe another player's last parcel")
                | Board.BribeParcelBlocker.WatchedBlocker -> tileWithTooltip p (translate "You cannot bribe parcel protected by a watchdog")
                | Board.BribeParcelBlocker.FallowBlocker -> tileWithTooltip p (translate "You cannot bribe parcel that with split a fallow land")
                | Board.BribeParcelBlocker.BridgeBlocker -> tileWithTooltip p (translate "You cannot bribe parcel that would create a hole in your field") ]
    | _ ->
        []

let flash active =
    div [ classBaseList "flash" [ "inactive", not active] ]
        []

let bars = i [ ClassName "fas fa-bars"] [
 svg [ AriaHidden true; Role "img"; ViewBox "0 0 448 512" ]
    [ Fable.React.Standard.path [ SVGAttr.Fill "currentColor"; D "M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z" ] []]
    ]

let bonusMarkers bonus =
    div [ ClassName "markers"]
        [
          for _ in  1 .. bonus.Rutted do
            div [ ClassName "rut-marker" ] [
                tooltip (translate "Rut: The player looses 2 moves")
            ]
          for _ in 1 .. bonus.NitroOne do
            div [ ClassName "nitro-1-marker"] [
                tooltip (translate "Nitro+1: The player get 1 extra move")
            ]
          for _ in 1 .. bonus.NitroTwo do
            div [ ClassName "nitro-2-marker"] [
                tooltip (translate "Nitro+2: The player get 2 extra moves")
            ]
          if bonus.HighVoltage then
            div [ ClassName "highvoltage-marker" ] [
                tooltip (translate "High Voltage: The player's fence is protected until next turn")
            ]
          if bonus.Watched then
            div [ ClassName "watchdog-marker" ] [
                tooltip (translate "Watchdog: The player's field is protected until next turn")
            ]
          if bonus.Heliported > 0 then
            div [ ClassName "helicopter-marker" ] [
                tooltip (translate "Helicopter: The player cannot cut fences until the end of the turn")
            ]

        ]

let commonGoal board goal =
   div [ ClassName "common-goal" ]
       [ let colors = [ for KeyValue(_,p) in board.Players -> Player.color p]
         for c in colors do
           div [ ClassName ("stack " + colorName c)]
               [ div [ ClassName ("tile")] [] ]
         div [ClassName "tile-count"]
           [ let totalSize = Board.totalSize board
             str (string (goal - totalSize))
             tooltip (String.format (translate "Common goal: {goal} parcels / Remaining: {parcels} parcels") (Map.ofList [ "goal" ==> goal; "parcels" ==> (goal - totalSize)]) ) ]
       ]

type PlayerInfo = 
    { Name: string option
      Player: CrazyPlayer
      IsActive: bool
      Goal: Goal option
      PlayingBoard: PlayingBoard
    }

let playerInfo info (cards: ReactElement) dispatch =
    div [ ClassName "player-top"]
        [
            let color = Player.color info.Player
            div [ ClassName "description"]
              [ div [ ClassName (colorName color) ]
                    [ div [ classBaseList "player" [ "selected", info.IsActive ; "ko", Player.isKo info.Player ] ] []]
                
               
                match info.Name with
                | Some name -> div [ ClassName "name"] [ str name ] 
                | _ -> ()
                
                div [ ClassName "moves" ] 
                    [ if info.IsActive then
                        match info.Player with
                        | Starting _->
                            for _ in 1 .. 3 do
                                flash false 
                        | Playing p ->
                            for i in 1 .. p.Moves.Capacity do
                                flash  (i <= p.Moves.Done)
                        | Ko _ -> ()


                        tooltip 
                            (String.concat " " [ 
                                match info.Player with
                                | Starting _ -> translate "3 base moves"
                                | Playing p ->
                                    translate "3 (base moves)"
                                    if p.Moves.Acceleration then
                                        translate "+1 (because >= 4 fences at the begining of the turn)"
                                    for _ in 1 .. p.Bonus.Rutted do
                                        translate "-2 (Rut)"
                                    for _ in 1 .. p.Bonus.NitroOne do
                                        translate "+1 (Nitro+1)"
                                    for _ in 1 .. p.Bonus.NitroTwo do
                                        translate "+2 (Nitro+2)"

                                    if p.Moves.Capacity = 5 then
                                        translate "(max 5 moves)"

                                    String.format (translate "/ {moves} moves done") (Map.ofList [ "moves" ==> p.Moves.Done ])

                                | _ -> ()

                            ])
                    ]
                ]

            
            bonusMarkers (Player.bonus info.Player)
            match info.Player, info.Goal with
            | Ko _, _ -> ()
            | _, Some (Individual goal) ->
               div [ ClassName "individual-goal" ]
                   [ div [ ClassName ("stack " + colorName color)]
                         [ div [ ClassName ("tile")] []  ]
                     div [ClassName "tile-count"]
                         [ let totalSize = Player.fieldTotalSize info.Player
                           str (string (goal - totalSize))
                           tooltip (String.format (translate "Individual goal: {goal} parcels / Remaining: {parcels} parcels") (Map.ofList [ "goal" ==> goal; "parcels" ==> (goal - totalSize)]))
                           ] 
                   ]
            | _, Some (Common goal) ->
                commonGoal info.PlayingBoard goal
            | _ -> ()
            cards
        ]

      
let playedCard dispatch card =
    match card with
    | Some c ->
      div [ClassName "played-card"
           OnAnimationEnd (fun _ -> dispatch HidePlayedCard) ]
          [ div [ClassName ("card " + Client.cardName c)] [] ]
    | None -> null

