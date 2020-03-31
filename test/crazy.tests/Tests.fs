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

    test <@ path = ([ Path(Axe.SE,BN)
                      Path(Axe.E2,BNW)
                      Path(Axe.NE,BNE)
                      Path(2*Axe.NE, BNW)
                      Path(2*Axe.NE, BN)
                      Path(2*Axe.NE, BNE) ],
                    Crossroad(2*Axe.NE, CRight))  @>

[<Fact>]
let ``Fence move``() =
    test <@
            Player.start (Parcel Axe.center) (Crossroad (Axe.center, CLeft))
            |> Player.move Up
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
             =  Playing
                { Tractor = Crossroad(Axe.NW, CLeft)
                  Fence = Fence [ Path (Axe.NW, BNW), Down
                                  Path (Axe.NW, BN), Horizontal
                                  Path(Axe.NW, BNE), Up
                                  Path (Axe.center, BNW), Up]
                  Field = Field.create (Parcel Axe.center)}

                      @>

[<Fact>]
let ``Fence move reverse``() =
    test <@
            Player.start (Parcel Axe.center) (Crossroad (Axe.center, CLeft))
            |> Player.move Up
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
            |> Player.move Up
            |> Player.move Horizontal
             =  Playing
                { Tractor = Crossroad(Axe.N, CLeft)
                  Fence = Fence [ Path (Axe.NW, BNE), Up
                                  Path (Axe.center, BNW), Up]
                  Field = Field.create (Parcel Axe.center)} @>


[<Fact>]
let ``Loops are deleted``() =
    test <@
            Player.start  (Parcel Axe.center) (Crossroad (Axe.center, CRight))
            |> Player.move Horizontal
            |> Player.move Up
            |> Player.move Horizontal
            |> Player.move Down
            |> Player.move Down
            |> Player.move Horizontal
            |> Player.move Up
             =  Playing
                { Tractor = Crossroad(Axe.E2, CLeft)
                  Fence = Fence [ Path (Axe.SE, BN), Horizontal ]
                  Field = Field.create (Parcel Axe.center) } @>

