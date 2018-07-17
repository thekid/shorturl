<?php namespace de\thekid\shorturl\api;

use web\Request;
use web\rest\Response;

class Administration extends Handler {

  /** Returns all URLs */
  <<get('/')>>
  public function all(<<value>> string $user, <<request>> Request $request): Response {
    $pagination= $this->paging()->on($request);
    return $pagination->paginate($this->urls->all(
      $pagination->start() ?: 0,
      $pagination->limit() + 1
    ));
  }

  /** Deletes a URL by a given ID */
  <<delete('/{id}')>>
  public function delete(<<value>> string $user, string $id): Response {
    $this->urls->remove($id);
    return Response::noContent();
  }
}