module Draw

open Xunit
open Shared
open Swensen.Unquote
open Shared.Player
open Shared.Board
open Testing

[<Fact>]

let ``Going back to position twice and ending turn should raise equality``() =
    """
    [
    ["Started",{"Players":[["Blue","2323672","thinkbeforecoding0",["Parcel",["Axe",0,-2]]],["Yellow","2323673","thinkbeforecoding1",["Parcel",["Axe",0,2]]]],"DrawPile":[["Nitro","One"],"Helicopter",["HayBale","One"],["Nitro","One"],"Watchdog",["Nitro","One"],["HayBale","Two"],["Nitro","Two"],"Helicopter","Dynamite","Helicopter","Watchdog","Dynamite",["HayBale","Two"],["HayBale","One"],["HayBale","One"],["HayBale","Two"],"Dynamite","HighVoltage","Helicopter","Rut",["Nitro","Two"],["Nitro","Two"],["Nitro","One"],["HayBale","One"],"Bribe",["Nitro","One"],"Helicopter","Bribe","Helicopter","Bribe","HighVoltage",["Nitro","One"],"HighVoltage","Dynamite","Rut"],"Barns":[["Parcel",["Axe",0,0]],["Parcel",["Axe",0,-3]],["Parcel",["Axe",0,3]],["Parcel",["Axe",3,-3]],["Parcel",["Axe",-3,0]],["Parcel",["Axe",3,0]],["Parcel",["Axe",-3,3]],["Parcel",["Axe",-2,1]],["Parcel",["Axe",2,-1]],["Parcel",["Axe",1,-2]],["Parcel",["Axe",-1,-1]],["Parcel",["Axe",1,1]],["Parcel",["Axe",-1,2]]],"Goal":["Common",27],"Undo":"FullUndo","UseGameOver":false}],
    ["Played","2323672",["FirstCrossroadSelected",{"Crossroad":["Crossroad",["Axe","0","-2"],"CRight"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Horizontal","Path":["Path",["Axe",1,-2],"BN"],"Crossroad":["Crossroad",["Axe",2,-3],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-2],"BNE"],"Crossroad":["Crossroad",["Axe",1,-2],"CRight"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",2,-2],"BNW"],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FirstCrossroadSelected",{"Crossroad":["Crossroad",["Axe","0","2"],"CRight"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Horizontal","Path":["Path",["Axe",1,2],"BN"],"Crossroad":["Crossroad",["Axe",2,1],"CLeft"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,1],"BNW"],"Crossroad":["Crossroad",["Axe",1,1],"CRight"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",1,1],"BNE"],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,0],"BNW"],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Horizontal","Path":["Path",["Axe",2,0],"BN"],"Crossroad":["Crossroad",["Axe",3,-1],"CLeft"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Horizontal","Loop":["Fence",[[["Path",["Axe",2,0],"BN"],"Horizontal"]]],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FenceDrawn",{"Move":"Horizontal","Path":["Path",["Axe",2,0],"BN"],"Crossroad":["Crossroad",["Axe",3,-1],"CLeft"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Horizontal","Loop":["Fence",[[["Path",["Axe",2,0],"BN"],"Horizontal"]]],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",2,0],"BNW"],"Up"]]],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,0],"BNW"],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Horizontal","Path":["Path",["Axe",1,-1],"BN"],"Crossroad":["Crossroad",["Axe",0,-1],"CRight"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Horizontal","Loop":["Fence",[[["Path",["Axe",1,-1],"BN"],"Horizontal"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",2,-2],"BNW"],"Down"]]],"Crossroad":["Crossroad",["Axe",1,-2],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",2,0],"BNW"],"Up"]]],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",1,1],"BNE"],"Up"]]],"Crossroad":["Crossroad",["Axe",1,1],"CRight"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",2,1],"BNW"],"Up"]]],"Crossroad":["Crossroad",["Axe",2,1],"CLeft"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,1],"BNW"],"Crossroad":["Crossroad",["Axe",1,1],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",2,-2],"BNW"],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",1,1],"BNE"],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",1,1],"BNE"],"Up"]]],"Crossroad":["Crossroad",["Axe",1,1],"CRight"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",1,1],"BNE"],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    ["Played","2323672",["FenceLooped",{"Move":"Up","Loop":["Fence",[[["Path",["Axe",1,-1],"BNE"],"Down"]]],"Crossroad":["Crossroad",["Axe",2,-2],"CLeft"]}]],
    ["Played","2323672",["FenceDrawn",{"Move":"Down","Path":["Path",["Axe",1,-1],"BNE"],"Crossroad":["Crossroad",["Axe",1,-1],"CRight"]}]],
    "Next",
    "UndoCheckPointed",
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,0],"BNW"],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    ["Played","2323673",["FenceLooped",{"Move":"Down","Loop":["Fence",[[["Path",["Axe",2,0],"BNW"],"Up"]]],"Crossroad":["Crossroad",["Axe",2,0],"CLeft"]}]],
    ["Played","2323673",["FenceDrawn",{"Move":"Up","Path":["Path",["Axe",2,0],"BNW"],"Crossroad":["Crossroad",["Axe",1,0],"CRight"]}]],
    ]
    """
    |> readEvents
    |> List.fold Board.evolve Board.initialState 
    |> Board.decide (Board.Play("2323673", Player.EndTurn))
    |> fun es ->
        test <@ es = [ Board.GameEndedByRepetition ]
             @>
