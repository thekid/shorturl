ShortURL
========

[![Build status on GitHub](https://github.com/thekid/shorturl/workflows/Tests/badge.svg)](https://github.com/thekid/shorturl/actions)
[![Uses XP Framework](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
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
$ xp -supervise web -c src/main/etc/prod de.thekid.shorturl.Api
```

Working with the service
------------------------

Create a URL:

```sh
$ curl -i localhost:8080 -d "url=https://github.com/"
HTTP/1.1 201
Date: Sun, 14 Aug 2016 10:25:39 GMT
Location: /d7b3438
```

Access the URL:

```sh
$ curl -i localhost:8080/d7b3438
HTTP/1.1 302
Date: Sun, 14 Aug 2016 10:26:17 GMT
Location: https://github.com/
```

Create a named URL:

```sh
$ curl -i localhost:8080 -d "url=http://thekid.de/&name=home"
HTTP/1.1 201
Date: Sun, 14 Aug 2016 10:25:39 GMT
Location: /home
```

Administering the service
-------------------------

List URLs:

```sh
$ curl -i admin:$HUDDLE_PASS@localhost:8080
HTTP/1.1 200
Date: Sun, 14 Aug 2016 10:39:42 GMT
Content-Type: application/json

[{"id":"d7b3438","value":"https://github.com/"},{"id":"home","value":"http://thekid.de/"}]
```

Delete URLs:

```sh
$ curl -i -X DELETE admin:$HUDDLE_PASS@localhost:8080/d7b3438
HTTP/1.1 204
Date: Sun, 14 Aug 2016 10:38:26 GMT
```