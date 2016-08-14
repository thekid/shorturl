ShortURL
========

[![Uses XP Framework](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
![Less than 1000 lines](https://raw.githubusercontent.com/xp-framework/web/master/static/less-than-1000LOC.png)

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
$ xp web de.thekid.shorturl.Api
```

Working with the service
------------------------

Create a URL:

```sh
$ curl -i localhost:8080 -d "url=https://github.com/"
HTTP/1.1 201
Date: Sun, 14 Aug 2016 10:25:39 GMT
Server: XP/PHP
Connection: close
Location: http://localhost:8080/d7b3438
```

Access the URL:

```sh
$ curl -i localhost:8080/d7b3438
HTTP/1.1 302
Date: Sun, 14 Aug 2016 10:26:17 GMT
Server: XP/PHP
Connection: close
Location: https://github.com/
```

Create a named URL:

```sh
$ curl -i localhost:8080 -d "url=http://thekid.de/&name=home"
HTTP/1.1 201
Date: Sun, 14 Aug 2016 10:25:39 GMT
Server: XP/PHP
Connection: close
Location: http://localhost:8080/home
```

Administering the service
-------------------------

List URLs:

```sh
$ curl -i admin:$HUDDLE_PASS@localhost:8080
HTTP/1.1 200
Date: Sun, 14 Aug 2016 10:39:42 GMT
Server: XP/PHP
Connection: close
Content-Type: application/json

[{"id":"d7b3438","value":"https://github.com/"},{"id":"home","value":"http://thekid.de/"}]
```

Delete URLs:

```sh
$ curl -i -X DELETE admin:$HUDDLE_PASS@localhost:8080/d7b3438
HTTP/1.1 204
Date: Sun, 14 Aug 2016 10:38:26 GMT
Server: XP/PHP
Connection: close
```