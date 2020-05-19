module Storage
open System
open System.Threading.Tasks
open FSharp.Control.Tasks.V2

open Microsoft.Azure.Cosmos

let tryGetUserByEmail cnxstring email =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "login")
    let iter = container.GetItemQueryIterator<Model.User>(QueryDefinition("SELECT * FROM login l where l.email = @email").WithParameter("@email", email))
    if iter.HasMoreResults then
        let! response = iter.ReadNextAsync()
        return response.Resource |> Seq.tryHead
    else
        return None
    }

let saveUser cnxstring (user: Model.User) =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "login")
    try
        let! _ = container.CreateItemAsync(user)
        return true
    with
    | :? CosmosException  ->
        return false

    }


let saveChallenge cnxstring (challenge: Model.Challenge) = 
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "login")
    try
        let! response = container.CreateItemAsync(challenge)

        return ()
    with
    | :? CosmosException as ex ->
        printfn "%O" ex
        return failwith "Cannot save challenge"

    }

let tryGetChallenge cnxstring userid code =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "login")
    let iter = container.GetItemQueryIterator<Model.Challenge>(QueryDefinition("SELECT * FROM login l where l.userid = @userid and l.challenge = @code" ).WithParameter("@userid", userid).WithParameter("@code", code))
    if iter.HasMoreResults then
        let! response = iter.ReadNextAsync()
        return response.Resource |> Seq.tryHead
    else
        return None
    }

let getUserById cnxstring (userid: string) =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "login")
    let! response = container.ReadItemAsync<Model.User>("user", PartitionKey userid)
    return response.Resource
    }



let createGame cnxString (gameId: string)  =
     task {
    let client = new CosmosClient(cnxString)
    let container = client.GetContainer("crazyfarmers", "publicgames")
    try
        let! response = container.CreateItemAsync({ Model.Game.id = gameId 
                                                    Model.Game.players = [||]
                                                    Model.Game.p = "games"
                                                    Model.Game.isPublic = false
                                                    Model.Game.goal = "Regular"
                                                    Model.Game.created = DateTime.UtcNow
                                                    Model.Game.ttl = 3600<FSharp.Data.UnitSystems.SI.UnitSymbols.s> })
        return ()
    with
    | :? CosmosException as ex ->
        printfn "%O" ex
        return failwith "Cannot save challenge"
    }

let loadGame cnxstring (gameId: string) =
    task {
     let client = new CosmosClient(cnxstring)
     let container = client.GetContainer("crazyfarmers", "publicgames")
     let! response = container.ReadItemAsync<Model.Game>(gameId, PartitionKey "games")
     return response.Resource
     }

let updateGame cnxstring (game: Model.Game) =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "publicgames")
    try
        let! response = container.ReplaceItemAsync(game, game.id)

        return ()
    with
    | :? CosmosException as ex ->
        printfn "%O" ex
        return failwith "Cannot save game"

    }

let deleteGame cnxstring gameid =
    task {
    let client = new CosmosClient(cnxstring)
    let container = client.GetContainer("crazyfarmers", "publicgames")
    try
        let! response = container.DeleteItemAsync(gameid, PartitionKey "games")

        return ()
    with
    | :? CosmosException as ex ->
        printfn "%O" ex
        return failwith "Cannot save game"

    }

let loadPublicGames cnxstring =
   task {
   let client = new CosmosClient(cnxstring)
   let container = client.GetContainer("crazyfarmers", "publicgames")
   let iter = container.GetItemQueryIterator<Model.Game>(QueryDefinition("SELECT * FROM g where g.isPublic = true" ))
   let mutable games = []
   while iter.HasMoreResults do
       let! response = iter.ReadNextAsync()
       games <- List.ofSeq response.Resource @ games

   return  games
   }
 


let initialize cnxstring =

    let client = new CosmosClient(cnxstring)
    let db =
        client.CreateDatabaseIfNotExistsAsync("crazyfarmers", Nullable 400)
        |> Async.AwaitTask |> Async.RunSynchronously
    db.Database.CreateContainerIfNotExistsAsync("crazyfarmers", "/p")
    |> Async.AwaitTask |> Async.RunSynchronously |> ignore
    db.Database.CreateContainerIfNotExistsAsync(
        ContainerProperties( "login", "/userid", DefaultTimeToLive = Nullable -1) )
    |> Async.AwaitTask |> Async.RunSynchronously |> ignore



