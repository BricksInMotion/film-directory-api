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


  function has_links($links) {
    foreach ($links as $pair) {
      $keys = array_keys($pair);
      $diff = array_diff(['url', 'label'], $keys);
      if (!empty($diff)) {
        return false;
      }
    }
    return true;
  }


  function has_genres($genres) {
    return ($genres !== null && count($genres) >= 1);
  }


  function has_cast_crew_info($crew) {
    return (
      $crew !== null &&
      isset($crew['role_id'], $crew['user_id'], $crew['user_name'], $crew['description'])
    );
  }


  function has_rating($rating) {
    return (
      $rating !== null &&
      isset($rating['user_id'], $rating['value'])
    );
  }

  function has_advisories($advisories) {
    return (
      $advisories !== null &&
      isset(
        $advisories['sex'],
        $advisories['language'],
        $advisories['violence']
      ) && (
        $advisories['sex'] >= 0 &&
        $advisories['language'] >= 0 &&
        $advisories['violence'] >= 0
      )
    );
  }
