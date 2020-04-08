module Tests

open System
open Xunit
open Shared
open Swensen.Unquote

[<Fact>]
let ``IsInField corner``() =
    let field =
        set [ Parcel Axe.NE; Parcel Axe.SE]
        |> Field

    test <@ Crossroad (2 * Axe.SE, CLeft) |> Crossroad.isInField field @>


[<Fact>]
let ``IsInField border``() =
    let field =
        set [ Parcel Axe.NE; Parcel Axe.SE]
        |> Field

    test <@  Crossroad (Axe.center, CRight) |> Crossroad.isInField field @>

[<Fact>]
let ``IsInField inside``() =
    let field =
        set [ Parcel.center; Parcel Axe.NE; Parcel Axe.SE]
        |> Field

    test <@ Crossroad (Axe.center, CRight) |> Crossroad.isInField field @>
    
[<Fact>]
let ``not IsInField``() =
    let field =
        set [ Parcel Axe.NE; Parcel Axe.SE]
        |> Field

    test <@ not (Crossroad (2 * Axe.SE, CRight) |> Crossroad.isInField field) @>


[<Fact>]
let ``Path of moves``() =

    let path =
        Crossroad (Axe.center,CRight)
        |> Path.ofMoves [ Horizontal; Up; Up; Up; Horizontal; Down ]

    test <@ path = ([ Path(Axe.SE,BN), Horizontal
                      Path(Axe.E2,BNW), Up
                      Path(Axe.NE,BNE), Up
                      Path(2*Axe.NE, BNW), Up
                      Path(2*Axe.NE, BN), Horizontal
                      Path(2*Axe.NE, BNE), Down ],
                    Crossroad(2*Axe.NE, CRight))  @>

[<Fact>]
let ``Fence move``() =
    test <@
            Player.start Blue (Parcel Axe.center) (Crossroad (Axe.center, CLeft))
            |> Player.move Up
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
             =  Playing
                { Color = Blue
                  Tractor = Crossroad(Axe.NW, CLeft)
                  Fence = Fence [ Path (Axe.NW, BNW), Down
                                  Path (Axe.NW, BN), Horizontal
                                  Path(Axe.NW, BNE), Up ]
                  Field = Field.create (Parcel Axe.center)
                  Power = PowerUp}

                      @>

[<Fact>]
let ``Fence move reverse``() =
    test <@
            Player.start Blue (Parcel Axe.center) (Crossroad (Axe.center, CLeft))
            |> Player.move Up
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
            |> Player.move Up
            |> Player.move Horizontal
             =  Playing
                { Color = Blue
                  Tractor = Crossroad(Axe.N, CLeft)
                  Fence = Fence [ Path (Axe.NW, BNE), Up ]
                  Field = Field.create (Parcel Axe.center)
                  Power = PowerUp} @>


[<Fact>]
let ``Loops are deleted``() =
    test <@
            Player.start Blue (Parcel Axe.center) (Crossroad (Axe.center, CRight))
            |> Player.move Horizontal
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
            |> Player.move Down
            |> Player.move Horizontal
            |> Player.move Up
             =  Playing
                { Color = Blue
                  Tractor = Crossroad(Axe.E2, CLeft)
                  Fence = Fence [ Path (Axe.SE, BN), Horizontal ]
                  Field = Field.create (Parcel Axe.center)
                  Power = PowerUp} @>

[<Fact>]
let ``Find boder``() =

    let field = Field.ofParcels [ Parcel.center; Parcel.center + Axe.NE ]

    let start = Crossroad(Axe.SW, CRight)

    let end' = Crossroad(Axe.E2, CLeft) 

    let border =
        Field.borderBetween start end' field

    test <@ border = [ Path(Axe.S,BN), Horizontal
                       Path(Axe.SE,BNW), Up
                       Path(Axe.SE, BN), Horizontal ] @>
    
[<Fact>]
let ``Find boder other direction``() =

    let field = Field.ofParcels [ Parcel.center; Parcel.center + Axe.NE ]

    let end' = Crossroad(Axe.SW, CRight)

    let start = Crossroad(Axe.E2, CLeft) 

    let border =
        Field.borderBetween start end' field

    test <@ border = [ Path(Axe.E2,BNW), Up
                       Path(Axe.NE,BNE), Up
                       Path(Axe.NE,BN), Horizontal
                       Path(Axe.NE,BNW), Down
                       Path(Axe.center, BN), Horizontal
                       Path(Axe.center, BNW), Down
                       Path(Axe.SW, BNE), Down ] @>
[<Fact>]
let ``To oriented``() =

    let start = Crossroad(Axe.SW, CRight)

    let paths, end' = Path.ofMoves [Down; Down; Horizontal; Up; Horizontal; Up ; Up] start

    let fence = Fence(List.rev paths)

    let orienteds,_ = Fence.toOriented end' fence 

    test <@ orienteds = [ DSW; DSE; DE; DNE; DE; DNE; DNW ] @>

[<Fact>]
let ``Fill path``() =
    let  start = Crossroad(Axe.SW, CRight)


    let paths,end' = Path.ofMoves [Down; Down; Horizontal; Up; Horizontal; Up ; Up; Up; Up; Horizontal; Down; Horizontal; Down; Down] start

    let field = Field.fill paths

    test <@
        start = end'
        && field = Field(set [ Parcel.center
                               Parcel.center + Axe.NE
                               Parcel.center + Axe.SE
                               Parcel.center + Axe.S]) @>


