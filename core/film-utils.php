<?php

  class Validate {
    static function has_film_info($film_info) {
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

    static function has_links($links) {
      foreach ($links as $pair) {
        $keys = array_keys($pair);
        $diff = array_diff(['url', 'label'], $keys);
        if (!empty($diff)) {
          return false;
        }
      }
      return true;
    }

    static function has_genres($genres) {
      return ($genres !== null && count($genres) >= 1);
    }

    static function has_cast_crew($crew) {
      if ($crew === null) {
        return false;
      }

      foreach ($crew as $person) {
        if (!isset($person['role_id'], $person['user_id'], $person['user_name'], $person['description'])) {
          return false;
        }
      }
      return true;
    }

    static function has_rating($rating) {
      return (
        $rating !== null &&
        isset($rating['user_id'], $rating['value'])
      );
    }

    static function has_advisories($advisories) {
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
  }
