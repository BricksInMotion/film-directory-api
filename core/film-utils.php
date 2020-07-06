<?php
  function has_film_info($film_info) {
    $result = [
      'success' => false,
      'missing' => []
    ];

    // Ensure all required info was given
    $required_info = [
      'advisories', 'cast_crew', 'description',
      'genres', 'runtime', 'links',
      'release_date', 'title', 'user_id',
    ];
    $provided_info = array_keys($film_info);
    $diff = array_diff($required_info, $provided_info);

    // If the difference returns a non-empty array,
    // that is what information is missing from the submission
    if (!empty($diff)) {
      $result['missing'] = $diff;
      return $result;
    }

    // All data is present
    $result['success'] = true;
    return $result;
  }


  function has_links($film_links) {
    foreach ($film_links as $pair) {
      $keys = array_keys($pair);
      $diff = array_diff(['url', 'label'], $keys);
      if (!empty($diff)) {
        return false;
      }
    }
    return true;
  }


  function has_genres($film_genres) {
    return count($film_genres) >= 1;
  }


  function has_cast_crew_info($film_crew) {
    return true;
  }


  function has_rating($film_rating) {
    return (
      $film_rating !== null &&
      isset($film_rating['user_id'], $film_rating['value'])
    );
  }

  function has_advisories($film_advisories) {
    return isset(
      $film_advisories['sex'],
      $film_advisories['language'],
      $film_advisories['violence']
    );
  }
