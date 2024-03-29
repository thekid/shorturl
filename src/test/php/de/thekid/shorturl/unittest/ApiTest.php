<?php namespace de\thekid\shorturl\unittest;

use test\Test;
use web\Error;
use web\rest\Response;

/** Base class */
abstract class ApiTest<T> {
  protected const URLS= ['test' => 'https://example.com/', 'e8762e2' => 'https://test.example.com/'];

  /** Test helper */
  protected function test(function(T): Response $call): array<int, mixed> {
    try {
      $e= $call(new T(new TestingUrls(self::URLS)))->export();
    } catch (Error $e) {
      $e= ['status' => $e->status(), 'body' => ['error' => ['message' => $e->getMessage()]]];
    }

    return [$e['status'] => match ($e['status']) {
      201, 302 => $e['headers']['Location'],
      default  => $e['body']['error']['message'] ?? $e['body'],
    }];
  }

  #[Test]
  public function can_create() {
    $T->newInstance(new TestingUrls());
  }
}