module Model
open System


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
      expiry: DateTime }



