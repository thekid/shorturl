<?php namespace de\thekid\shorturl\api;

use webservices\rest\srv\Response;
use scriptlet\Request;

#[@webservice]
class Administration extends Handler {

  /**
   * Returns all URLs
   *
   * @param  string $user
   * @param  scriptlet.Request $request
   * @return webservices.rest.srv.Response
   */
  #[@webmethod(verb= 'GET', path= '/'), @$user: header, @$request: request]
  public function all($user, Request $request) {
    $pagination= $this->paging()->on($request);
    return Response::paginated($pagination, $this->urls->all(
      $pagination->start(),
      $pagination->limit() + 1
    ));
  }

  /**
   * Deletes a URL by a given ID
   *
   * @param  string $id
   * @param  string $user
   * @return webservices.rest.srv.Response
   */
  #[@webmethod(verb= 'DELETE', path= '/{id}'), @$user: header]
  public function delete($id, $user) {
    $this->urls->remove($id);
    return Response::noContent();
  }
}