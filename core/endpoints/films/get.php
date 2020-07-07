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

  // We have a film and not requesting additional info, just fetch the basic info
  if (!isset($_GET['props'])) {
    echo make_response(200, $film->info());
  }

  // Map the property values to the fetch methods
  $prop_methods = [
    'links'      => 'links',
    'genres'     => 'genres',
    'honors'     => 'honors',
    'reviews'    => 'reviews',
    'cast_crew'  => 'cast_crew',
    'advisories' => 'advisories',
    'staff_ratings' => 'staff_ratings',
  ];

  // We want to get film properties
  $props = escape_xss($_GET['props']);

  // If the roles key is given, at least one role must be requested
  if (empty($props)) {
    $possible_values = array_keys($prop_methods);
    sort($possible_values, SORT_STRING);
    $possible_values = implode(', ', $possible_values);
    echo make_error_response(400, "At least one Film property must be provided! Possible values: {$possible_values}");
  }

  // Convert the requested roles into a list
  $props = explode(',', $props);
  $film_properties = [];

  // If a special "all" key is given, collect all the info
  if (count($props) === 1 && $props[0] === "all") {
    foreach ($prop_methods as $label => $func) {
      $film_properties['info'] = $film->info();
      $film_properties[$label] = $film->$func();
    }
    echo make_response(200, $film_properties);
  }

  // For each given key, get the info
  foreach ($props as $label) {
    // ...But ignoring invalid keys
    if (array_key_exists($label, $prop_methods)) {
      $func = $prop_methods[$label];
      $film_properties[$label] = $film->$func();
    }
  }
  echo make_response(200, $film_properties);

