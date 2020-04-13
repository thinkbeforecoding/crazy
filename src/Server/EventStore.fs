module EventStore
open Microsoft.Azure.Cosmos
open FSharp.Control.Tasks
open System.Threading.Tasks
open System

[<CLIMutable>]
type BatchData =
    { id: string
      i: int
      p: string
      e: EventData[]}
and [<CLIMutable>] EventData =
    { c: string
      d: obj }


let append serialize (container: Container) (stream: string) expectedVersion events =
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
                        { c= c; d = d } |] }
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


let handler (streamPrefix: string) deserialize f =
    let prefixLength = streamPrefix.Length
    fun (c: BatchData) ->
        if c.p.StartsWith(streamPrefix) then
            let events = 
                [ for ed in c.e do
                    yield! deserialize (ed.c, ed.d) ]
            let id = c.p.Substring(prefixLength) 

            f id c.i events


let subscription (client: CosmosClient) (container: Container) name (handlers: _ list)  =

    let feed =
       container.GetChangeFeedProcessorBuilder<BatchData>(name,
            fun changes ct ->
                task {
                    for c in changes do
                        for handler in handlers do
                            handler c
                } :> Task
        )
        .WithLeaseContainer(client.GetContainer("crazyfarmers", "subscriptions"))
        .WithInstanceName(System.Environment.MachineName)
        .WithPollInterval(TimeSpan.FromMilliseconds 500.)
        .WithStartTime(DateTime.UtcNow.AddMinutes(-2.))
        .Build()
    feed.StartAsync() |> Async.AwaitTask |> Async.RunSynchronously


