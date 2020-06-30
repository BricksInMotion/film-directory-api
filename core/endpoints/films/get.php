<?php
  require_once '../core/common-utils.php';
  require_once '../core/classes/Film.php';

  // A film id was not provided
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo make_error_response(400, 'A Film ID must be provided!');
  }

  // Get the film
  $film = new Film(escape_xss($_GET['id']));

  // That film doesn't exist
  if ($film->exists() === false) {
    echo make_error_response(404, "Could not find Film for ID {$film->id}!");
  }

  echo make_response(200, $film->info());
