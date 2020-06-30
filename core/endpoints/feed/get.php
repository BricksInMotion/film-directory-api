<?php
  require_once '../core/common-utils.php';
  require_once '../core/database.php';

  // Get the latest submitted films
  $stmt = $pdo->prepare(get_sql('feed-latest'));
  $stmt->execute();
  echo make_response(200, $stmt->fetchAll(PDO::FETCH_OBJ));
