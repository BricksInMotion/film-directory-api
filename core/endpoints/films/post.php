<?php
  require_once '../core/common-utils.php';
  require_once '../core/film-utils.php';
  require_once '../core/classes/Film.php';

  // Get the film data from the submitted JSON
  $film_info = get_json('php://input');

  // We didn't get any film information, periodt.
  if ($film_info === null) {
    echo make_error_response(400, "Film info must be provided!");
  }

  // There's missing film base information
  $has_info = has_film_info($film_info);
  if (!$has_info['success']) {
    sort($has_info['missing'], SORT_STRING);
    $missing_info = implode(', ', $has_info['missing']);
    echo make_error_response(422, "The following Film info is missing: {$missing_info}");
  }

  // Film links were not provideds in the correct format
  $has_links = has_links($film_info['links']);
  if (!$has_links) {
    echo make_error_response(422, "Film links are incorrectly submitted!");
  }

  // There must be at least one genre
  $has_genre = has_genres($film_info['genres']);
  if (!$has_genre) {
    echo make_error_response(422, "Films must have at least one assigned genre!");
  }

  $has_cast_crew = has_cast_crew_info($film_info['cast_crew']);

  // Create a film object to create the film
  $film = new Film();
  $create_result = $film->create($film_info);

  // It worked!!
  if ($create_result) {
    echo make_response(201, $film->info());

  // There was a problem recording the film, probably from bad data
  } else {
    echo make_error_response(422, "There was an error recording the Film due to invalid information!");
  }
