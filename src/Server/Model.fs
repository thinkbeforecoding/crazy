module Model
open System
open FSharp.Data.UnitSystems.SI.UnitSymbols


[<CLIMutable>]
type User = 
    { id: string
      userid: string
      email: string
      name: string
    }

[<CLIMutable>]
type Challenge = 
    { id: string
      userid: string
      challenge: string 
      ttl: int<s> }


