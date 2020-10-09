<?php namespace de\thekid\shorturl\api;

use web\Request;
use web\rest\{Response, Resource, Get, Delete, Value};

#[Resource]
class Administration extends Handler {

  /** Returns all URLs */
  #[Get('/')]
  public function all(#[Value] string $user, Request $request): Response {
    $pagination= $this->paging()->on($request);
    return $pagination->paginate($this->urls->all(
      $pagination->start() ?: 0,
      $pagination->limit() + 1
    ));
  }

  /** Deletes a URL by a given ID */
  #[Delete('/{id}')]
  public function delete(#[Value] string $user, string $id): Response {
    if ($this->urls->remove($id)) {
      return Response::noContent();
    } else {
      return Response::notFound(['message' => 'No url by id #'.$id]);
    }
  }
}