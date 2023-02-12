<?php namespace de\thekid\shorturl\unittest;

use de\thekid\shorturl\api\PublicAccess;
use peer\URL;
use test\{Assert, Test, Values};

class PublicAccessTest extends ApiTest<PublicAccess> {

  #[Test, Values(['test', 'e8762e2'])]
  public function get_existing_sends_redirect($id) {
    Assert::equals(
      [302 => self::URLS[$id]],
      $this->test(fn($fixture) => $fixture->get($id)),
    );
  }

  #[Test]
  public function get_non_existant_yields_404() {
    Assert::equals(
      [404 => 'No url by id #non-existant'],
      $this->test(fn($fixture) => $fixture->get('non-existant')),
    );
  }

  #[Test]
  public function create() {
    Assert::equals(
      [201 => '/da4ad12'],
      $this->test(fn($fixture) => $fixture->create(new URL('https://added.example.com/'))),
    );
  }

  #[Test, Values(['https://test.example.com', 'https://test.example.com/', 'https://TEST.example.com/'])]
  public function create_given_existing_url_redirects($url) {
    Assert::equals(
      [302 => '/e8762e2'],
      $this->test(fn($fixture) => $fixture->create(new URL($url))),
    );
  }

  #[Test, Values(['named', 'Named', 'NAMED'])]
  public function create_named($name) {
    Assert::equals(
      [201 => '/named'],
      $this->test(fn($fixture) => $fixture->create(new URL('https://named.example.com/'), $name)),
    );
  }

  #[Test, Values(['test', 'Test', 'TEST'])]
  public function create_given_existing_name_yield_conflict($name) {
    Assert::equals(
      [409 => 'Name "test" already taken'],
      $this->test(fn($fixture) => $fixture->create(new URL('https://another.example.com/'), $name)),
    );
  }
}