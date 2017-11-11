<?php namespace de\thekid\shorturl;

use inject\ConfiguredBindings;
use inject\Injector;
use scriptlet\Run;
use util\log\ConsoleAppender;
use util\log\LogCategory;
use util\log\LogLevel;
use web\Error;
use web\Filter;
use web\Filters;
use webservices\rest\srv\RestContext;
use webservices\rest\srv\RestScriptlet;

class Api extends \web\Application {

  /** @return [:var] */
  public function routes() {
    $injector= new Injector(new ConfiguredBindings($this->environment->properties('inject')));

    // Setup REST context
    $context= newinstance(RestContext::class, [], [
      'handlerInstanceFor' => function($class) use($injector) {
        return $injector->get($class);
      }
    ]);
    $context->setTrace((new LogCategory('web'))->withAppender(
      new ConsoleAppender(),
      LogLevel::WARN | LogLevel::ERROR
    ));

    // Setup authentication
    $authenticate= newinstance(Filter::class, [], [
      'filter' => function($request, $response, $invocation) {
        $request->pass('user', '(nobody)');
        return $invocation->proceed($request, $response);
      }
    ]);

    return new Filters([$authenticate], [
      '/' => new Run(new RestScriptlet('de.thekid.shorturl.api', '/', $context))
    ]);
  }
}