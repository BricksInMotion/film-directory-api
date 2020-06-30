<?php
class Director {
  public $id = 0;

  function __construct($id) {
    $this->id = (int) $id;
  }

  /**
   * @private
   *
   * Helper method to pull info for roles
   * with a common structure.
   *
   * @return {stdClass}
   */
  private function get_role($role) {
    // The defined roles and their numeric code
    // 'director': '1'
    // 'writer'  : '2'
    // 'composer': '3'
    // 'animator': '4'
    // 'editor'  : '5'
    // 'vfx'     : '6'
    // 'sound'   : '7'
    // 'other'   : '8'
    // 'thanks'  : '9'
    // 'va'      : '10'

    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('director-get-role'));
    $stmt->bindValue(':id', $this->id);
    $stmt->bindValue(':role', $role);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $results;
  }

  /**
   * Determine if the director exists.
   *
   * @return {bool}
   */
  function exists() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('director-exists'));
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
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
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the director's animator role.
   *
   * @return {stdClass}
   */
  function as_animator() {
    return $this->get_role('4');
  }

  /**
   * Get the director's composer role.
   *
   * @return {stdClass}
   */
  function as_composer() {
    return $this->get_role('3');
  }

  /**
   * Get the director's crew role.
   *
   * @return {stdClass}
   */
  function as_crew() {
    return $this->get_role('8');
  }

  /**
   * Get the director's director role.
   *
   * @return {stdClass}
   */
  function as_director() {
    return $this->get_role('1');
  }

  /**
   * Get the director's editor role.
   *
   * @return {stdClass}
   */
  function as_editor() {
    return $this->get_role('5');
  }

  /**
   * Get the director's sound editing role.
   *
   * @return {stdClass}
   */
  function as_sound() {
    return $this->get_role('7');
  }

  /**
   * Get the director's special thanks role.
   *
   * @return {stdClass}
   */
  function as_thanks() {
    return $this->get_role('9');
  }

  /**
   * Get the director's visual effects role.
   *
   * @return {stdClass}
   */
  function as_vfx() {
    return $this->get_role('6');
  }

  /**
   * Get the director's voice actor role.
   *
   * @return {stdClass}
   */
  function as_voice() {
    return $this->get_role('10');
  }

  /**
   * Get the director's writer role.
   *
   * @return {stdClass}
   */
  function as_writer() {
    return $this->get_role('2');
  }
}
