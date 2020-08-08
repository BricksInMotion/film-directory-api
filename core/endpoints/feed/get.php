<?php
  require_once '../core/common-utils.php';
  require_once '../core/database.php';

  // Default number of films to load
  $total = 4;

  // A different number of films was requested
  if (isset($_GET['total']) && is_numeric($_GET['total'])) {
    $total = intval(escape_xss($_GET['total']));
  }

  // Get the latest submitted films
  $stmt = $pdo->prepare(get_sql('feed-latest'));
  $stmt->bindValue(':total', $total, PDO::PARAM_INT);
  $stmt->execute();
  echo make_response(200, $stmt->fetchAll(PDO::FETCH_OBJ));
