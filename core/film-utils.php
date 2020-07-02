<?php
  function has_base_film_data($film_info) {
    $result = [
      'success' => false,
      'missing' => []
    ];

    // Ensure all required info was given
    $required_info = [
      'title',
      'description',
      'length',
      'sex',
      'language',
      'violence',
      'release_date',
      'user_id',
      'links',
      'genres'
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


  function has_links_data($film_links) {
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
    return count($film_links) >= 1;
  }
