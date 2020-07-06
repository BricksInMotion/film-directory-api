<?php
  require_once '../core/common-utils.php';
  require_once '../core/classes/Film.php';


  // A film id was not provided
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo make_error_response(400, 'A Film ID must be provided!');
  }

  // Return the ratings
  $film = new Film(escape_xss($_GET['id']));
  echo make_response(200, $film->rating());
