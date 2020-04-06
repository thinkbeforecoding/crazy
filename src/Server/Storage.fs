module Storage
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
