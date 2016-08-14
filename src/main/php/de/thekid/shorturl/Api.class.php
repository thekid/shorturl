<?php namespace de\thekid\shorturl;

use xp\scriptlet\WebApplication;
use inject\Injector;
use webservices\rest\srv\RestContext;
use util\log\LogCategory;
use util\log\ConsoleAppender;
use util\log\LogLevel;
use security\vault\Vault;
use security\vault\FromEnvironment;

class Api implements \xp\scriptlet\WebLayout {

  /** @return [:xp.scriptlet.WebApplication] */
  public function mappedApplications($profile= null) {
    $vault= new Vault(new FromEnvironment(FromEnvironment::REMOVE));
    $injector= new Injector(new SecretsIn($vault), new Bindings());

    if ('dev' === $profile) {
      $injector->get(RestContext::class)->setTrace((new LogCategory('web'))->withAppender(
        new ConsoleAppender(),
        LogLevel::WARN | LogLevel::ERROR)
      );
    }

    $vault->close();
    return ['/' => $injector->get(WebApplication::class)];
  }

  /** @return [:var] */
  public function staticResources($profile= null) { return []; }
}