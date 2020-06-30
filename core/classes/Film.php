<?php
class Film {
  public $id = 0;

  function __construct($id) {
    $this->id = (int) $id;
  }

  /**
   * Determine if the film exists.
   *
   * @return {bool}
   */
  function exists() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-exists'));
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    return (bool) $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the film's basic info.
   *
   * @return {stdClass}
   */
  function info() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-info'));
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the film's rating.
   *
   * @return {stdClass}
   */
  function rating() {
    // This query calculates the film's rating as well as handles
    // the (likely common) case where a film does not have a rating.
    // It's a big ugly because the lack of a rating is a NULL value
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-rating'));
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

}
