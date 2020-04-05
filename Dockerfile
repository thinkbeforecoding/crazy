FROM mcr.microsoft.com/dotnet/core/aspnet:3.1-alpine

# Install nginx
RUN apk update && apk add nginx && mkdir /var/crazy && mkdir /run/nginx 

# Copy the application files
COPY ./deploy /var/crazy
COPY ./nginx/ /etc/nginx/conf.d/

ENV SERVER_ROOT /var/crazy/public/

# CMD dotnet /var/crazy/Server.exe
EXPOSE 80

CMD nginx && dotnet /var/crazy/bin/Server.dll

