<?php namespace de\thekid\shorturl;

use rdbms\DriverManager;
use rdbms\DBConnection;

class Bindings extends \inject\Bindings {

  public function configure($injector) {
    $conn= DriverManager::getConnection('mysql://huddle:'.getenv('HUDDLE_PASS').'@127.0.0.1/HUDDLE');
    $injector->bind(DBConnection::class, $conn, 'huddle');
    $injector->bind('string', 'test:test', 'credentials');
  }
}