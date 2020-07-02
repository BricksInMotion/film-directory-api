<?php
  function has_all_film_data($film_info) {
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
      'creation_date',
      'user_id',
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
