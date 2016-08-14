<?php namespace de\thekid\shorturl;

use rdbms\DBConnection;

class Urls {
  private $conn;

  #[@inject(name= 'huddle')]
  public function __construct(DBConnection $conn) {
    $this->conn= $conn;
  }

  /**
   * Looks up an URL
   *
   * @param  string $id
   * @return string
   */
  public function lookup($id) {
    return $this->conn->query('select value from url where id = %s', $id)->next('value');
  }

  /**
   * Creates an URL
   *
   * @param  string $id
   * @param  string $canonical
   * @return void
   */
  public function create($id, $canonical) {
    $this->conn->insert('into url (id, value) values (%s, %s)', $id, $canonical);
  }

  /**
   * Removes an URL
   *
   * @param  string $id
   * @return void
   */
  public function remove($id) {
    $this->conn->delete('from url where id = %s', $id);
  }

  /**
   * Fetches all URLS
   *
   * @param  int $start
   * @param  int $limit
   * @return php.Traversable
   */
  public function all($start, $limit) {
    return $this->conn->query('select id, value from url limit %d, %d', max($start, 1), $limit);
  }
}