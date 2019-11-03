<?php namespace de\thekid\shorturl;

use inject\{Injector, ConfiguredBindings};
use web\rest\{RestApi, ResourcesIn};
use web\{Application, Filters};

class Api extends Application {

  public function routes() {
    $injector= new Injector(new ConfiguredBindings($this->environment->properties('inject')));

    // Setup authentication
    $admin= base64_encode('admin:'.$injector->get('string', 'admin-pass'));
    $authenticate= fn($request, $response, $invocation) => {
      if (sscanf($request->header('Authorization'), 'Basic %s', $authorization) > 0) {
        if ($authorization !== $admin) {
          $response->header('WWW-Authenticate', 'Basic realm="Administration"');
          $response->answer(401, 'Unauthorized');
          return;
        }

        $request->pass('user', 'admin');
      } else {
        $request->pass('user', null);
      }
      return $invocation->proceed($request, $response);
    };

    return new Filters([$authenticate], new RestApi(new ResourcesIn('de.thekid.shorturl.api', [$injector, 'get'])));
  }
}