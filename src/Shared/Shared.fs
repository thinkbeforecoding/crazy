namespace Shared


type Color =
    | Blue
    | Yellow
    | Purple
    | Red

type Goal =
    | Common of int
    | Individual of int


type GoalType =
    | Fast
    | Regular
    | Expert


module Goal =
    let fromType playerCount goal =
        match playerCount, goal with
        | 2, Fast -> Common 23 |> Some
        | 3, Fast -> Individual 9 |> Some
        | 4, Fast -> Individual 8 |> Some
        | 2, Regular -> Common 27 |> Some
        | 3, Regular -> Individual 11 |> Some
        | 4, Regular -> Individual 9 |> Some
        | 2, Expert -> Common 31 |> Some
        | 3, Expert -> Individual 13 |> Some
        | 4, Expert -> Individual 11 |> Some
        | _ -> None




