module Email
open MailKit
open MimeKit
open MailKit.Net.Smtp
open Fable.React
open Fable.React.Props

open Fable.ReactServer
open Microsoft.Extensions.Configuration
open System.IO
open FSharp.Control.Tasks

let mailContent baseUri userid code =
    html [] 
        [ body []
            [ p []
                [ str "Cliquez sur le lien ci-dessous pour vous connecter:"
                  a [ Href (sprintf "%s/auth/check/%s/%s" baseUri userid code) ] 
                    [ str "Connexion" ]
                ]
              p []
                [ str "Ou saisissez le code suivant dans la page:" ]
              p []
                [ str code ]
            ]
        
        
        ]

type SmtpConfig = 
    { Host: string
      Port: int
      User: string
      Password: string }

let smtpConfig =
    let config =
        ConfigurationBuilder()
            .SetBasePath(Directory.GetCurrentDirectory())
            .AddJsonFile("local.settings.json",true)
            .AddEnvironmentVariables()
            .Build();
    let smtp = config.GetSection("smtp")
    { Host = smtp.["host"]
      Port = int smtp.["port"]
      User = smtp.["user"]
      Password = smtp.["password"]
    }


let sendCode baseUri email userid code =
    task {
        try
            let msg = MimeMessage()
            msg.From.Add(MailboxAddress("Crazy Farmers","no-reply@thefreaky42.com"))
            msg.To.Add(MailboxAddress(email))
            msg.Subject <- "Crazy Farmers"
            msg.Body <- TextPart("html", Text = renderToString (mailContent baseUri userid code))

            let client = new SmtpClient()
            do! client.ConnectAsync(smtpConfig.Host,smtpConfig.Port  ,true)
            do!  client.AuthenticateAsync(smtpConfig.User,smtpConfig.Password)
            do! client.SendAsync(msg)
        with 
        | ex -> printfn "Error while sending email:\n%O" ex
    }

