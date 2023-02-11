<?php namespace de\thekid\shorturl\unittest;

use de\thekid\shorturl\Urls;

class TestingUrls extends Urls {

  public function __construct(private array<string, string> $urls= []) { }

  /** Looks up an URL */
  public function lookup(string $id): ?string {
    return $this->urls[$id] ?? null;
  }

  /** Creates an URL */
  public function create(string $id, string $canonical): void {
    $this->urls[$id]= $canonical;
  }

  /** Removes an URL */
  public function remove(string $id): int {
    if (!isset($this->urls[$id])) return 0;

    unset($this->urls[$id]);
    return 1;
  }

  /** Fetches all URLs */
  public function all(int $start, int $limit): iterable {
    return array_slice($this->urls, $start, $limit);
  }
}