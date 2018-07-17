<?php namespace de\thekid\shorturl\api;

use de\thekid\shorturl\Urls;
use web\rest\paging\{Paging, PageParameters};

abstract class Handler {
  protected $urls;

  public function __construct(Urls $urls) {
    $this->urls= $urls;
  }

  /** Creates default paging */
  protected function paging(): Paging {
    return new Paging(50, [new PageParameters('page', 'per_page')]);
  }
}