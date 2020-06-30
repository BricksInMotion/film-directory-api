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


}
