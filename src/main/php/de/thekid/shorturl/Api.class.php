<?php namespace de\thekid\shorturl;

use xp\scriptlet\WebApplication;
use inject\Injector;
use scriptlet\Filter;
use webservices\rest\srv\RestContext;
use webservices\rest\srv\RestScriptlet;
use util\log\LogCategory;
use util\log\ConsoleAppender;
use util\log\LogLevel;

class Api implements \xp\scriptlet\WebLayout {

  /** @return [:xp.scriptlet.WebApplication] */
  public function mappedApplications($profile= null) {
    $injector= new Injector(new Bindings());

    $context= newinstance(RestContext::class, [], [
      'handlerInstanceFor' => function($class) use($injector) {
        return $injector->get($class);
      }
    ]);

    if ('dev' === $profile) {
      $context->setTrace((new LogCategory('web'))->withAppender(new ConsoleAppender(), LogLevel::WARN | LogLevel::ERROR));
    }

    return ['/' => (new WebApplication('default'))
      ->withScriptlet(RestScriptlet::class)
      ->withArguments(['de.thekid.shorturl.api', '/', $context])
      ->withFilter(newinstance(Filter::class, [], [
        'filter' => function($request, $response, $invocation) use($injector) {
          if ($injector->get('string', 'credentials') === $request->getEnvValue('PHP_AUTH_USER').':'.$request->getEnvValue('PHP_AUTH_PW')) {
            $request->addHeader('user', $request->getEnvValue('PHP_AUTH_USER'));
          } else {
            $request->addHeader('user', null);
          }
          return $invocation->proceed($request, $response);
        }
      ]))
    ]; 
  }

  /** @return [:var] */
  public function staticResources($profile= null) { return []; }
}