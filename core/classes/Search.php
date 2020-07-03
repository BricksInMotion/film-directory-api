<?php
class Search {
  /**
   * Search for a Film by title.
   */
  static function by_film($title) {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('search-by-title'));
    $stmt->bindValue(':title', $title);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Search for a Director by their name.
   */
  static function by_director($name) {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('search-by-director'));
    $stmt->bindValue(':name', $name);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}
