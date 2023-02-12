<?php namespace de\thekid\shorturl\unittest;

use de\thekid\shorturl\api\Administration;
use test\{Assert, Test, Values};
use web\Request;
use web\io\TestInput;

class AdministrationTest extends ApiTest<Administration> {
  private const USER= ['id' => 'admin'];

  #[Test]
  public function all() {
    Assert::equals(
      [200 => ['value' => self::URLS]],
      $this->test(fn($fixture) => $fixture->all(new Request(new TestInput('GET', '/')), self::USER)),
    );
  }

  #[Test, Values([1, 2])]
  public function paged($page) {
    Assert::equals(
      [200 => ['value' => array_slice(self::URLS, $page - 1, 1)]],
      $this->test(fn($fixture) => $fixture->all(new Request(new TestInput('GET', '/?per_page=1&page='.$page)), self::USER)),
    );
  }

  #[Test]
  public function delete_existing() {
    Assert::equals(
      [204 => null],
      $this->test(fn($fixture) => $fixture->delete('test', self::USER)),
    );
  }

  #[Test]
  public function delete_non_existant_yields_404() {
    Assert::equals(
      [404 => 'No url by id #non-existant'],
      $this->test(fn($fixture) => $fixture->delete('non-existant', self::USER)),
    );
  }

  #[Test]
  public function listing_requires_authentication() {
    Assert::equals(
      [403 => 'Must be authenticated to list all URLs'],
      $this->test(fn($fixture) => $fixture->all(new Request(new TestInput('GET', '/')))),
    );
  }

  #[Test]
  public function deleting_requires_authentication() {
    Assert::equals(
      [403 => 'Must be authenticated to delete URLs'],
      $this->test(fn($fixture) => $fixture->delete('test'))
    );
  }
}