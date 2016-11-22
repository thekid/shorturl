<?php namespace de\thekid\shorturl;

use inject\Named;
use inject\InstanceBinding;
use security\vault\Vault;
use util\Secret;

class SecretsIn extends \inject\Bindings {
  private $vault;

  public function __construct(Vault $vault) { $this->vault= $vault; }

  public function configure($injector) {
    $vault= $this->vault;
    $injector->bind(Secret::class, newinstance(Named::class, [], [
      'provides' => function($name) { return true; },
      'binding'  => function($name) use($vault) { return new InstanceBinding($vault->credential($name)); }
    ]));
  }
}