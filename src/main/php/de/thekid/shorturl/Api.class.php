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

    if ('dev' === $profile) {
      $injector->get(RestContext::class)->setTrace((new LogCategory('web'))->withAppender(
        new ConsoleAppender(),
        LogLevel::WARN | LogLevel::ERROR)
      );
    }

    return ['/' => $injector->get(WebApplication::class)];
  }

  /** @return [:var] */
  public function staticResources($profile= null) { return []; }
}