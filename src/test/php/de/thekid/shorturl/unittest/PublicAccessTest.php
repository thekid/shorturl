<?php namespace de\thekid\shorturl\unittest;

use de\thekid\shorturl\api\PublicAccess;
use peer\URL;
use test\{Assert, Test};

class PublicAccessTest {
  private const URLS= ['test' => 'https://example.com/'];

  #[Test]
  public function can_create() {
    new PublicAccess(new TestingUrls());
  }

  #[Test]
  public function get_existing_sends_redirect() {
    $r= new PublicAccess(new TestingUrls(self::URLS))->get('test');

    Assert::equals(302, $r->export()['status']);
    Assert::equals(self::URLS['test'], $r->export()['headers']['Location']);
  }

  #[Test]
  public function get_non_existant_yields_404() {
    $r= new PublicAccess(new TestingUrls(self::URLS))->get('non-existant');

    Assert::equals(404, $r->export()['status']);
  }

  #[Test]
  public function create() {
    $added= 'https://added.example.com/';
    $r= new PublicAccess(new TestingUrls(self::URLS))->create(new URL($added));

    Assert::equals(201, $r->export()['status']);
    Assert::equals('/da4ad12', $r->export()['headers']['Location']);
  }

  #[Test]
  public function create_named() {
    $added= 'https://named.example.com/';
    $r= new PublicAccess(new TestingUrls(self::URLS))->create(new URL($added), 'named');

    Assert::equals(201, $r->export()['status']);
    Assert::equals('/named', $r->export()['headers']['Location']);
  }

  #[Test]
  public function create_given_existing_name() {
    $added= 'https://another.example.com/';
    $r= new PublicAccess(new TestingUrls(self::URLS))->create(new URL($added), 'test');

    Assert::equals(409, $r->export()['status']);
  }
}