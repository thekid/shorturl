<?php namespace de\thekid\shorturl\api;

use de\thekid\shorturl\Urls;
use peer\URL;
use web\rest\{Response, Resource, Get, Post, Param};

#[Resource]
class PublicAccess {
  private const SHA1_MIN = 7;
  private const SHA1_MAX = 40;

  /** Creates public API handler */
  public function __construct(private Urls $urls) { }

  /** Creates an entry with a given name */
  private function createNamed(string $name, string $canonical): Response {
    if ($this->urls->lookup($name)) {
      return Response::error(409, 'Name "'.$name.'" already taken');
    }

    $this->urls->create($name, $canonical);
    return Response::created('/{name}', $name);
  }

  /** Creates an entry with an ID */
  private function createWithId(string $canonical): Response {
    $sha1= sha1($canonical);
    $offset= self::SHA1_MIN;
    do {
      $id= substr($sha1, 0, $offset);
      $stored= $this->urls->lookup($id);
      if (null === $stored) {
        $this->urls->create($id, $canonical);
        return Response::created('/{id}', $id);
      } else if ($canonical === $stored) {
        return Response::see('/{id}', $id);
      }

      // Another URL stored under this ID, disambiguate by using more of SHA
    } while ($offset++ < self::SHA1_MAX);

    return Response::error(503, 'Cannot store '.$canonical);
  }

  /** Creates a new URL */
  #[Post('/')]
  public function create(#[Param] URL $url, #[Param] string $name= null): Response {
    $canonical= $url->getCanonicalURL();
    if (null === $name) {
      return $this->createWithId($canonical);
    } else {
      return $this->createNamed(strtolower($name), $canonical);
    }
  }

  /** Redirects to an existing URL */
  #[Get('/{id}')]
  public function get(string $id): Response {
    if ($url= $this->urls->lookup($id)) {
      return Response::see($url);
    } else {
      return Response::notFound(['message' => 'No url by id #'.$id]);
    }
  }
}