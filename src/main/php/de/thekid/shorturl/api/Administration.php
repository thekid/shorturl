<?php namespace de\thekid\shorturl\api;

use web\rest\{Response, Resource, Get, Delete, Value};
use web\{Request, Error};

#[Resource]
class Administration extends Handler {

  /** Returns all URLs */
  #[Get('/')]
  public function all(Request $request, #[Value] $user= null): Response {
    $user ?? throw new Error(403, 'Must be authenticated to list all URLs');

    $pagination= $this->paging()->on($request);
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
      return Response::notFound(['message' => 'No url by id #'.$id]);
    }
  }
}