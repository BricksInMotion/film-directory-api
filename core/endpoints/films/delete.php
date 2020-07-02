<?php
  require_once '../core/common-utils.php';
  require_once '../core/classes/Film.php';

  // A film id was not provided
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo make_error_response(400, 'A Film ID must be provided!');
  }

  // Get a film object
  $film = new Film(escape_xss($_GET['id']));

  // That film doesn't exist
  if ($film->exists() === false) {
    echo make_error_response(404, "Could not find Film for ID {$film->id}!");
  }

  // Delete the film. We're going to mimic SQL's behavior of
  // always reporting a successful deletion just because it's easy
  // $film->delete();
  echo make_response(204);
