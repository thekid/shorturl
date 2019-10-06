<?php namespace de\thekid\shorturl;

use rdbms\DriverManager;

class Urls {
  private $conn;

  public function __construct(<<inject('db-dsn')>> string $dsn) {
    $this->conn= DriverManager::getConnection($dsn);
  }

  /** Looks up an URL */
  public function lookup(string $id): ?string {
    return $this->conn->query('select value from url where id = %s', $id)->next('value');
  }

  /** Creates an URL */
  public function create(string $id, string $canonical): void {
    $this->conn->insert('into url (id, value) values (%s, %s)', $id, $canonical);
  }

  /** Removes an URL */
  public function remove(string $id): int {
    return $this->conn->delete('from url where id = %s', $id);
  }

  /** Fetches all URLs */
  public function all(int $start, int $limit): iterable {
    return $this->conn->query('select id, value from url limit %d, %d', $start, $limit);
  }
}
