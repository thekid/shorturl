<?php namespace de\thekid\shorturl\api;

use de\thekid\shorturl\Urls;
use web\rest\paging\{Paging, PageParameters};

abstract class Handler {

  public function __construct(protected Urls $urls) { }

  /** Creates default paging */
  protected function paging() ==> new Paging(50, [new PageParameters('page', 'per_page')]);
}