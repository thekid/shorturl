ShortURL
========

URL Shortener service

Setup
-----
Create a database:

```sql
create database HUDDLE
use HUDDLE
create table url (id varchar(40) not null PRIMARY KEY, value varchar(1024))
grant all on HUDDLE.* to 'huddle'@'%' identified by '...'
```

Run composer:

```sh
$ composer install
# ...
```

Running
-------
Start the server:

```sh
$ export HUDDLE_PASS=...
$ xp web com.example.shorturl.Api
```

Working with the service
------------------------

Create a URL:

```sh
$ curl -i localhost:8080 -d "url=http://thekid.de/"
HTTP/1.1 201
Date: Sun, 14 Aug 2016 10:25:39 GMT
Server: XP/PHP
Connection: close
Location: http://localhost:8080/ce70d4a
```

Access the URL:

```sh
$ curl -i localhost:8080/ce70d4a
HTTP/1.1 302
Date: Sun, 14 Aug 2016 10:26:17 GMT
Server: XP/PHP
Connection: close
Location: http://thekid.de/
```