<?php namespace de\thekid\shorturl;

use inject\{Injector, ConfiguredBindings};
use web\Application;
use web\auth\Basic;
use web\rest\{RestApi, ResourcesIn};

class Api extends Application {

  /** Routing */
  public function routes() {
    $injector= new Injector(new ConfiguredBindings($this->environment->properties('inject')));
    $auth= new Basic('Administration', fn($user, $secret) => {
      return 'admin' === $user && $secret->equals($injector->get('string', 'admin-pass')) ? 'admin' : null;
    });

    // Use optional authentication - not all routes require authentication.
    // The handlers must implement verifying a user is present themselves!
    return $auth->optional(new RestApi(new ResourcesIn('de.thekid.shorturl.api', [$injector, 'get'])));
  }
}