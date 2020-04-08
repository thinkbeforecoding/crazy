module EventStore
open Microsoft.Azure.Cosmos
open FSharp.Control.Tasks

[<CLIMutable>]
type BatchData =
    { id: string
      i: int
      p: string
      e: EventData[]}
and [<CLIMutable>] EventData =
    { c: string
      d: obj }


let append serialize (container: Container) stream expectedVersion events =
    task {
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
                .CreateItem( batch )
                .ExecuteAsync()

        if result.StatusCode = System.Net.HttpStatusCode.OK then
            return Ok nextExpected
        else
            return Error result
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


