<?php namespace de\thekid\shorturl\api;

use de\thekid\shorturl\Urls;
use web\rest\paging\{Paging, PageParameters};
use web\rest\{Response, Resource, Get, Delete, Value};
use web\{Request, Error};

#[Resource]
class Administration {
  private $paging= new Paging(50, [new PageParameters('page', 'per_page')]);

  /** Creates administration API handler */
  public function __construct(private Urls $urls) { }

  /** Returns all URLs */
  #[Get('/')]
  public function all(Request $request, #[Value] $user= null): Response {
    $user ?? throw new Error(403, 'Must be authenticated to list all URLs');

    $pagination= $this->paging->on($request);
    return $pagination->paginate($this->urls->all(
      $pagination->start() ?: 0,
      $pagination->limit() + 1
    ));
  }

  /** Deletes a URL by a given ID */
  #[Delete('/{id}')]
  public function delete(string $id, #[Value] $user= null): Response {
    $user ?? throw new Error(403, 'Must be authenticated to delete URLs');

    if ($this->urls->remove($id)) {
      return Response::noContent();
    } else {
      return Response::notFound('No url by id #'.$id);
    }
  }
}