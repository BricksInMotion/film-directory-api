<?php
  require_once '../core/common-utils.php';
  require_once '../core/film-utils.php';
  require_once '../core/classes/Film.php';


  // A film id was not provided
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo make_error_response(400, 'A Film ID must be provided!');
  }

  // Get the rating data from the submitted JSON
  $rating_info = get_json('php://input');

  // We didn't get any rating information
  if (!Validate::has_rating($rating_info)) {
    echo make_error_response(400, "Film rating info must be provided!");
  }

  // Get a film object
  $film = new Film(escape_xss($_GET['id']));

  // Record the film rating
  $r = $film->rate($rating_info);

  // Respond to the recording as needed
  if ($r) {
    echo make_response(201);
  } else {
    echo make_error_response(400, "Could not successfully rate Film {$film->id}!");
  }
