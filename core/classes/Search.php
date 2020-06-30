<?php
class Search {
  /**
   * Search for a film by title.
   */
  static function by_title($title) {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('search-by-title'));
    $stmt->bindValue(':title', $title);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}
