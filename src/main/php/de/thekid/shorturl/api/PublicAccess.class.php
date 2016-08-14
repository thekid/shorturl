<?php namespace de\thekid\shorturl\api;

use webservices\rest\srv\Response;
use peer\URL;

#[@webservice]
class PublicAccess extends Handler {
  const SHA1_MIN = 7;
  const SHA1_MAX = 40;

  /**
   * Creates an entry with a given name
   *
   * @param  string $name
   * @param  string $canonical
   * @return webservices.rest.srv.Response
   */
  private function createNamed($name, $canonical) {
    if ($this->urls->lookup($name)) {
      return Response::error(409, 'Name "'.$name.'" already taken');
    }

    $this->urls->create($name, $canonical);
    return Response::created('/'.$name);
  }

  /**
   * Creates an entry with an ID
   *
   * @param  string $canonical
   * @return webservices.rest.srv.Response
   */
  private function createWithId($canonical) {
    $sha1= sha1($canonical);
    $offset= self::SHA1_MIN;
    do {
      $id= substr($sha1, 0, $offset);
      $stored= $this->urls->lookup($id);
      if (null === $stored) {
        $this->urls->create($id, $canonical);
        return Response::created('/'.$id);
      } else if ($canonical === $stored) {
        return Response::see('/'.$id);
      }

      // Another URL stored under this ID, disambiguate by using more of SHA
    } while ($offset++ < self::SHA1_MAX);

    return Response::error(503, 'Cannot store '.$canonical);
  }

  /**
   * Creates a new URL
   *
   * @param  string $url
   * @param  string $name
   * @return webservices.rest.srv.Response
   */
  #[@webmethod(verb= 'POST'), @$url: param, @$name: param]
  public function create($url, $name= null) {
    $canonical= (new URL($url))->getCanonicalURL();
    if (null === $name) {
      return $this->createWithId($canonical);
    } else {
      return $this->createNamed(strtolower($name), $canonical);
    }
  }

  /**
   * Redirects to an existing URL
   *
   * @param  string $id
   * @return webservices.rest.srv.Response
   */
  #[@webmethod(verb= 'GET', path= '/{id}')]
  public function get($id) {
    if ($url= $this->urls->lookup($id)) {
      return Response::see($url);
    } else {
      return Response::notFound(['message' => 'No url by id #'.$id]);
    }
  }
}