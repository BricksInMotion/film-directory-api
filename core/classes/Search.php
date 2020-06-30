<?php
class Search {
  /**
   * Search for a film by title.
   */
  static function by_title($value) {
    require_once '../core/database.php';
    $stmt = $pdo->prepare(get_sql('search-by-title'));
    $stmt->execute([$value]);
    $films = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $films;
  }
}
