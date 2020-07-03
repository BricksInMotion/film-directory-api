<?php
  require_once '../core/common-utils.php';

  function is_valid_api_key($api_key) {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('api-key-validate'));
    $stmt->bindValue(':token', $api_key, PDO::PARAM_STR);
    $stmt->execute();
    $r = $stmt->fetchColumn();
    return $r !== false;
  }


  function is_authorized_request() {
    // We were not given an API key at all
    if (!isset($_SERVER['AUTHORIZATION'])) {
      return false;
    }

    // Attempt to extract thee API from the header
    $bearer = $_SERVER['AUTHORIZATION'];
    $api_key = explode('Bearer', $bearer);
    if (count($api_key) !== 2) {
      return false;
    }

    // Determine if the API key is valid
    return is_valid_api_key(trim($api_key[1]));
  }
