<?php
class Director {
  public $id = 0;

  function __construct($id) {
    $this->id = (int) $id;
  }

  /**
   * Determine if the director exists.
   *
   * @return {bool}
   */
  function exists() {
    // Short circuit the lookup if the ID is zero
    // Zero is being used to indicate an unknown director
    if ($this->id === 0) return false;

    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('director-exists'));
    $stmt->execute([$this->id]);
    return (bool) $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the director information.
   *
   * @return {stdClass}
   */
  function info() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('director-info'));
    $stmt->execute([$this->id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  }
