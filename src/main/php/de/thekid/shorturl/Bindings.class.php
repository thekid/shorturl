<?php namespace de\thekid\shorturl;

use rdbms\DriverManager;
use rdbms\DBConnection;
use xp\scriptlet\WebApplication;
use scriptlet\Filter;
use webservices\rest\srv\RestContext;
use webservices\rest\srv\RestScriptlet;
use util\Secret;

class Bindings extends \inject\Bindings {

  public function configure($injector) {
    $pass= $injector->get(Secret::class, 'huddle_pass')->reveal();

    $conn= DriverManager::getConnection('mysql://huddle:'.$pass.'@127.0.0.1/HUDDLE');
    $injector->bind(DBConnection::class, $conn, 'huddle');

    $injector->bind(RestContext::class, newinstance(RestContext::class, [], [
      'handlerInstanceFor' => function($class) use($injector) {
        return $injector->get($class);
      }
    ]));

    $injector->bind(WebApplication::class, (new WebApplication('default'))
      ->withScriptlet(RestScriptlet::class)
      ->withArguments(['de.thekid.shorturl.api', '/', $injector->get(RestContext::class)])
      ->withFilter(newinstance(Filter::class, [], [
        'filter' => function($request, $response, $invocation) use($pass) {
          if ('admin:'.$pass === $request->getEnvValue('PHP_AUTH_USER').':'.$request->getEnvValue('PHP_AUTH_PW')) {
            $request->addHeader('user', 'admin');
          } else {
            $request->addHeader('user', null);
          }
          return $invocation->proceed($request, $response);
        }
      ]))
    );
  }
}