module EventStore
open Microsoft.Azure.Cosmos
open FSharp.Control.Tasks
open System.Threading.Tasks
open System
open System.Text.RegularExpressions
open FSharp.Data.UnitSystems.SI.UnitSymbols

[<CLIMutable>]
type BatchData =
    { id: string
      i: int
      p: string
      e: EventData[]
      o: string }
and [<CLIMutable>] EventData =
    { c: string
      d: obj }



let append serialize (container: Container) (stream: string) correlationId expectedVersion events =
    task {
        if List.isEmpty events then
            try
                let! result =
                    container.ReadItemAsync<BatchData>(string expectedVersion, PartitionKey stream)
                if result.StatusCode = System.Net.HttpStatusCode.NotFound then
                    return Ok expectedVersion
                else
                    return Error result.StatusCode
            with
            | :? CosmosException as ex ->
                return (Error ex.StatusCode)
            | ex ->
                return Error System.Net.HttpStatusCode.NotFound
        else
            let batch =
                { id = string expectedVersion
                  i = expectedVersion
                  p = stream
                  e = 
                    [| for e in events do
                        let c,d = serialize e
                        { c= c; d = d } |]
                  o = correlationId }
            let nextExpected = expectedVersion+1
            let! result =
                container
                    .CreateTransactionalBatch(PartitionKey stream)
                    .CreateItem(batch)
                    .ExecuteAsync()

            if result.StatusCode = System.Net.HttpStatusCode.OK then
                return Ok nextExpected
            else
                return Error result.StatusCode
         }

[<CLIMutable>]
type CommandData =
    { id: string
      p: string
      c: EventData
      o: string }

let appendCmd serialize (container: Container) (stream: string) correlationId command =
    task {
            let data =
                { id = correlationId 
                  p = stream
                  c = 
                    let c,d = serialize command
                    { c= c; d = d } 
                  o = correlationId }
            let! result =
                container
                    .CreateTransactionalBatch(PartitionKey stream)
                    .CreateItem(data)
                    .ExecuteAsync()

            if result.StatusCode = System.Net.HttpStatusCode.OK then
                return Ok()
            else
                return Error result.StatusCode
         }


let fold deserialize (container: Container) f stream state start =
    let iter = container.GetItemQueryIterator<BatchData>(QueryDefinition("SELECT * FROM c WHERE c.p = @stream AND c.i >= @version").WithParameter("@stream",stream).WithParameter("@version", start))
    let rec fold (state,last) =
        task {
            if iter.HasMoreResults then
                let! batchResult = iter.ReadNextAsync()
                let newState =
                    batchResult.Resource
                    |> Seq.fold (fun (s,_) b ->  
                        let ns =  
                            b.e 
                            |> Seq.collect(fun e -> deserialize (e.c,e.d))
                            |> Seq.fold f s
                        ns, b.i )
                        (state,last)

                return! fold newState 
            else
                return state,last+1
        }
    fold (state,start-1)


let handler (streamRegex: string) deserialize (f: _ -> _ -> _ -> _ Task) =
    let regex = Regex(streamRegex, RegexOptions.Compiled)

    fun (c: BatchData) ->
        task {
            try
                let m = regex.Match c.p
                if m.Success then
                    let id = m.Groups.["id"].Value
                    let events = 
                        [ for ed in c.e do
                            yield! deserialize (ed.c, ed.d) ]

                    do! f id c.i events
            with
            | ex ->
                printfn "%O" ex

        }


let subscription (client: CosmosClient) (container: Container) name (handlers: (_ -> _ Task) list)  =

    let feed =
       container.GetChangeFeedProcessorBuilder<BatchData>(name,
            fun changes ct ->
                task {
                    for c in changes do
                        for handler in handlers do
                            do! handler c
                } :> Task
        )
        .WithLeaseContainer(client.GetContainer("crazyfarmers", "subscriptions"))
        .WithInstanceName(System.Environment.MachineName)
        .WithPollInterval(TimeSpan.FromMilliseconds 500.)
        .WithStartTime(DateTime.UtcNow.AddMinutes(-2.))
        .Build()
    feed.StartAsync() |> Async.AwaitTask |> Async.RunSynchronously




[<CLIMutable>]
type ChatEntry =
    { id: string
      p: string
      m: string
      pl: string
      d: System.DateTime
      ttl: int<s>}


let appendChat (container: Container) (stream: string) (message, player, date) =
    task {
            let data =
                { id = Guid.NewGuid().ToString("n")
                  p = stream
                  m = message
                  pl = player
                  d = date
                  ttl = 3 * 3600<s> }
            let! result =
                container
                    .CreateTransactionalBatch(PartitionKey stream)
                    .CreateItem(data)
                    .ExecuteAsync()

            return ()
         }

let chatSubscription (client: CosmosClient) (container: Container) name (handler: _ -> _ Task)  =

    let feed =
       container.GetChangeFeedProcessorBuilder<ChatEntry>(name,
            fun changes ct ->
                task {
                    for c in changes do
                        do! handler c
                } :> Task
        )
        .WithLeaseContainer(client.GetContainer("crazyfarmers", "subscriptions"))
        .WithInstanceName(System.Environment.MachineName)
        .WithPollInterval(TimeSpan.FromMilliseconds 500.)
        .WithStartTime(DateTime.UtcNow.AddMinutes(-2.))
        .Build()
    feed.StartAsync() |> Async.AwaitTask |> Async.RunSynchronously


let loadChat (container: Container) stream =
    let iter = container.GetItemQueryIterator<ChatEntry>(QueryDefinition("SELECT * FROM c WHERE c.p = @stream").WithParameter("@stream",stream))
    let rec fold entries =
        task {
            if iter.HasMoreResults then
                let! batchResult = iter.ReadNextAsync()
                let newEntries =
                    
                    (batchResult.Resource |> Seq.map(fun r ->
                        { Shared.Text = r.m; Shared.ChatEntry.Player = r.pl; Shared.Date = r.d }
                    ) |> Seq.toList) @ entries

                return! fold newEntries
            else
                return entries
        }
    fold []

