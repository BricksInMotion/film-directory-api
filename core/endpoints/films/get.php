<?php
  require_once '../core/common-utils.php';


  function get_data() {
    require_once '../core/classes/Film.php';
    $result = ['success' => false];

    // A film id was not provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
      $result['code'] = 400;
      $result['info'] = 'A Film ID must be provided!';
      return $result;
    }

    // Get a film object
    $film = new Film(escape_xss($_GET['id']));

    // That film doesn't exist
    if ($film->exists() === false) {
      $result['code'] = 404;
      $result['info'] = "Could not find Film for ID {$film->id}!";
      return $result;
    }

    // We have a film and not requesting additional info, just fetch the basic info
    if (!isset($_GET['props'])) {
      $result['success'] = true;
      $result['code'] = 200;
      $result['info'] = $film->info();
      return $result;
    }

    // Map the property values to the fetch methods
    $prop_methods = [
      'links'         => 'links',
      'genres'        => 'genres',
      'honors'        => 'honors',
      'reviews'       => 'reviews',
      'cast_crew'     => 'cast_crew',
      'advisories'    => 'advisories',
      'staff_ratings' => 'staff_ratings',
    ];

    // We want to get film properties
    $props = escape_xss($_GET['props']);

    // If the roles key is given, at least one role must be requested
    if (empty($props)) {
      $possible_values = array_keys($prop_methods);
      sort($possible_values, SORT_STRING);
      $possible_values = implode(', ', $possible_values);
      $result['code'] = 400;
      $result['info'] = "At least one Film property must be provided! Possible values: {$possible_values}";
      return $result;
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
      $result['success'] = true;
      $result['code'] = 200;
      $result['info'] = $film_properties;
      return $result;
    }

    // For each given key, get the info
    foreach ($props as $label) {
      // ...But ignoring invalid keys
      if (array_key_exists($label, $prop_methods)) {
        $func = $prop_methods[$label];
        $film_properties[$label] = $film->$func();
      }
    }
    $result['success'] = true;
    $result['code'] = 200;
    $result['info'] = $film_properties;
    return $result;
  }


  function json() {
    $film_data = get_data();
    if ($film_data['success']) {
      return make_response($film_data['code'], $film_data['info']);
    }
    return make_error_response($film_data['code'], $film_data['info']);
  }


  function html() {
    // Get the base film data
    $film_data = get_data()['info'];

    // Create the HTML fragment
    $html = new DOMDocument();
    $root = $html->createElement('div');
    $root->setAttribute('class', 'film-card');
    $root->setAttribute('id', "film-card-{$film_data->id}");

    // Add the film title
    $title = $html->createElement('h2', $film_data->title);
    $title->setAttribute('class', 'title');
    $root->appendChild($title);

    // Add the film release date
    $the_time = new DateTime($film_data->release_date);
    $release_date = $html->createElement('time', $the_time->format('M d, Y'));
    $release_date->setAttribute('class', 'release');
    $release_date->setAttribute('datetime', $the_time->format('Y-m-d'));
    $root->appendChild($release_date);

    // Add the film run time
    // TODO Format the run time properly
    $runtime = $html->createElement('p', $film_data->runtime);
    $runtime->setAttribute('class', 'runtime');
    $root->appendChild($runtime);

    // Add the film description
    $description = $html->createElement('p', $film_data->description);
    $description->setAttribute('class', 'description');
    $root->appendChild($description);

    // Get a string verson of the document
    $html->appendChild($root);
    $data = (string) $html->saveHTML();
    return make_response(200, $data, $headers=null, $format='html');
  }


  // An HTML card has been requested
  if (isset($_GET['format']) && escape_xss($_GET['format']) === 'html') {
    echo html();

  // If a card is not explictly requested
  // or the format is not specified, default to JSON
  } else {
    echo json();
  }
