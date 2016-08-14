<?php namespace de\thekid\shorturl\api;

use de\thekid\shorturl\Urls;
use webservices\rest\srv\paging\Paging;
use webservices\rest\srv\paging\PageParameters;

abstract class Handler {
  protected $urls;

  #[@inject]
  public function __construct(Urls $urls) {
    $this->urls= $urls;
  }

  /** @return webservices.rest.srv.paging.Paging */
  protected function paging() {
    return new Paging(50, [new PageParameters('page', 'per_page')]);
  }
}