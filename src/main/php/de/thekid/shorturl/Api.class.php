<?php namespace de\thekid\shorturl;

use inject\ConfiguredBindings;
use inject\Injector;
use scriptlet\Run;
use util\log\Logging;
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
    $context->setTrace(Logging::named('web')->of(LogLevel::WARN | LogLevel::ERROR)->toConsole());

    // Setup authentication
    $admin= base64_encode('admin:'.$injector->get('string', 'admin-pass'));
    $authenticate= newinstance(Filter::class, [], [
      'filter' => function($request, $response, $invocation) use($admin) {
        $request->pass('user', null);
        if (sscanf($request->header('Authorization'), 'Basic %s', $authorization)) {
          if ($authorization !== $admin) {
            $response->header('WWW-Authenticate', 'Basic realm="Administration"');
            $response->answer(401, 'Unauthorized');
            return;
          }

          $request->pass('user', 'admin');
        }
        return $invocation->proceed($request, $response);
      }
    ]);

    return new Filters([$authenticate], [
      '/' => new Run(new RestScriptlet('de.thekid.shorturl.api', '/', $context))
    ]);
  }
}