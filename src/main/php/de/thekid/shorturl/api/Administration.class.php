<?php namespace de\thekid\shorturl\api;

use web\Request;
use web\rest\Response;

class Administration extends Handler {

  /** Returns all URLs */
  #[@get('/'), @$user: value, @$request: request]
  public function all(string $user, Request $request): Response {
    $pagination= $this->paging()->on($request);
    return $pagination->paginate($this->urls->all(
      $pagination->start(),
      $pagination->limit() + 1
    ));
  }

  /** Deletes a URL by a given ID */
  #[@delete('/{id}'), @$user: value]
  public function delete(string $user, string $id): Response {
    $this->urls->remove($id);
    return Response::noContent();
  }
}