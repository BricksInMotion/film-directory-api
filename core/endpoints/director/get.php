<?php
  require_once '../core/common-utils.php';
  require_once '../core/classes/Director.php';

  // A director id was not provided
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo make_error_response(400, 'A Director ID must be provided!');
  }

  // Get the director
  $director = new Director(escape_xss($_GET['id']));

  // That director doesn't exist
  if ($director->exists() === false) {
    echo make_error_response(404, "Could not find Director for ID {$director->id}!");
  }

  // We have a Director and not requesting filmography, send back just their info
  if (!isset($_GET['roles'])) {
    echo make_response(200, $director->info());
  }

  // Map the role labels to the fetch methods
  $role_methods = [
    'director' => 'as_director',
    'writer'   => 'as_writer',
    'composer' => 'as_composer',
    'animator' => 'as_animator',
    'editor'   => 'as_editor',
    'vfx'      => 'as_vfx',
    'sound'    => 'as_sound',
    'other'    => 'as_crew',
    'thanks'   => 'as_thanks',
    'va'       => 'as_voice',
  ];

  // We want to get the filmography
  if (isset($_GET['roles'])) {
    $roles = escape_xss($_GET['roles']);

    // If the roles key is given, at least one role must be requested
    if (empty($roles)) {
      $possible_vaues = implode(', ', array_keys($role_methods));
      echo make_error_response(400, "At least one Director role must be provided! Possible values: {$possible_vaues}");
    }

    // Convert the requested roles into a list
    $roles = explode(',', $roles);
    $filmography = [];

    // If a special "all" key is given, collect all the info
    if (count($roles) === 1 && $roles[0] === "all") {
      foreach ($role_methods as $label => $func) {
        $filmography[$label] = $director->$func();
      }
      echo make_response(200, $filmography);
    }

    // For each given key, get the info
    foreach ($roles as $label) {
      // ...But ignoring invalid keys
      if (array_key_exists($label, $role_methods)) {
        $func = $role_methods[$label];
        $filmography[$label] = $director->$func();
      }
    }
    echo make_response(200, $filmography);
  }
