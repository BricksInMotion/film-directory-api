<?php
class Film {
  public $id;

  function __construct($id = 0) {
    $this->id = (int) $id;
  }

  /**
   * Insert a film into the directory.
   *
   * @return {bool}
   */
  function create($film_info) {
    require '../core/database.php';
    try {
      // Record the primary film record
      $pdo->beginTransaction();
      $stmt = $pdo->prepare(get_sql('film-create-primary'));
      $stmt->bindValue(':title', $film_info['title'], PDO::PARAM_STR);
      $stmt->bindValue(':description', $film_info['description'], PDO::PARAM_STR);
      $stmt->bindValue(':length', $film_info['length'], PDO::PARAM_INT);
      $stmt->bindValue(':sex', $film_info['sex'], PDO::PARAM_INT);
      $stmt->bindValue(':language', $film_info['language'], PDO::PARAM_INT);
      $stmt->bindValue(':violence', $film_info['violence'], PDO::PARAM_INT);
      $stmt->bindValue(':creation_date', $film_info['creation_date'], PDO::PARAM_STR);
      $stmt->bindValue(':user_id', $film_info['user_id'], PDO::PARAM_INT);
      $stmt->execute();

      // Get the generated film ID to use for inserting the other data
      $film_id = $pdo->lastInsertId();

      // Insert the film links
      // $stmt = $pdo->prepare(get_sql('film-create-links'));

      // Record the film genres
      // $stmt = $pdo->prepare(get_sql('film-create-genres'));

      // Record who all was involved in the film's creation
      // $stmt = $pdo->prepare(get_sql('film-create-cast-crew'));

      // Finally, save everything
      $pdo->commit();

      // Record the film's ID
      $this->id = $film_id;
      return true;

      // There was an error in recording the film
      // TODO The error should be logged to file
    } catch(Exception $e) {
      $pdo->rollback();
      return false;
    }
  }

  /**
   * Delete a film.
   *
   * @return {bool}
   */
  function delete() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-delete'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    // $stmt->execute();
    return true;
  }

  /**
   * Determine if the film exists.
   *
   * @return {bool}
   */
  function exists() {
    // Short circuit the lookup if the ID is zero
    // Zero is being used to indicate an unknown film
    if ($this->id === 0) { return false; }

    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-exists'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return (bool) $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the film's basic info.
   *
   * @return {stdClass}
   */
  function info() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-info'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get the film's rating.
   *
   * @return {stdClass}
   */
  function rating() {
    // This query calculates the film's rating as well as handles
    // the (likely common) case where a film does not have a rating.
    // It's a big ugly because the lack of a rating is a NULL value
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-rating'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    // Drop the temporary (`raw_`) keys
    $ratings = $stmt->fetch(PDO::FETCH_ASSOC);
    $ratings = array_filter($ratings, function($k) {
      return strpos($k, 'raw_') === false;
    }, ARRAY_FILTER_USE_KEY);
    return $ratings;
  }

  /**
   * Get all links to the film.
   *
   * @return {stdClass}
   */
  function links() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-links'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Get all user reviews of the film.
   *
   * @return {array}
   */
  function reviews() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-reviews'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Get the film's genres.
   *
   * @return {array}
   */
  function genres() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-genres'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_OBJ);

    // This film has no assigned genres
    if (count($genres) === 0) {
      return ['Unassigned'];
    }

    // Flatten the list for a cleaner response
    $result = [];
    foreach ($genres as $record) {
        $result[] = $record->genre;
    }
      return $result;
  }

  /**
   * Get the film's warnings, if any.
   *
   * @return {stdClass}
   */
  function advisories() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-advisories'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $warnings = $stmt->fetch(PDO::FETCH_ASSOC);

    // Define labels based on db values
    $types = [
      'violence' => 'violence',
      'language' => 'language',
      'sex' => 'sexual content'
    ];
    $severity = [
      '0' => 'no',
      '1' => 'mild',
      '2' => 'moderate',
      '3' => 'strong'
    ];

    // Collect the film's warnings
    $results = [];
    foreach ($warnings as $key => $value) {
      $results[] = [
        'type' => $types[$key],
        'severity' => $severity[$value]
      ];
    }
    return $results;
  }

  /**
   * Get the film's cast and crew.
   *
   * @return {array}
   */
  function cast_crew() {
    // Get the predefined roles
    // Both this query and the query for custom-defined roles
    // must take into account the `name` column being NULL
    // because the person being referenced has a record
    // in the `forums_users` table and their /(user|real)_name/ is used for
    // their name instead of it being stored in the `films_castcrew` table,
    // as it is when the person is _not_ a registered user.
    // This adds some complexity to the query but allows us to
    // pull all the data we need in one swoop.
    // Man, I _LOVE_ half-designed, half-organically grown databases!! /s
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-cast-crew-role-predefined'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $standard_roles = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Get the custom-defined roles, again taking into account
    // the /(user|real)_name/ data location difference
    $stmt = $pdo->prepare(get_sql('film-cast-crew-role-custom'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $custom_roles = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Merge the two arrays and drop the temporary (`raw_`) keys
    $all_roles = array_merge($standard_roles, $custom_roles);
    array_map(function($k) {
      unset($k->raw_name);
      unset($k->raw_db_name);
      unset($k->raw_user_name);
      return $k;
    }, $all_roles);
    return $all_roles;
  }

  /**
   * Get the film's staff ratings.
   *
   * @return {array}
   */
  function staff_ratings() {
    // BTW, never generate primary IDs like this table has.
    // Add a new column to the rows each with a unique number
    // indicating what each category represents. _Please._
    // Having to use a regex to select the records is... bad
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-staff-ratings'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $raw_ratings = $stmt->fetchAll(PDO::FETCH_OBJ);

    // There are no ratings for this film
    if (count($raw_ratings) === 0) {
      $indv = new stdClass();
      $indv->class = 'single';
      $indv->category = '';
      $indv->rating = 'No ratings given';

      // We expect an array of `stdClass`es,
      // so we need to replicate that here
      return [$indv];
    }

    // Define the rating categories
    $categories = [
      'Ov' => 'Overall',
      'St' => 'Story',
      'An' => 'Animation',
      'Ci' => 'Cinematography',
      'Ef' => 'Effects',
      'So' => 'Sound',
      'Mu' => 'Music'
    ];

    // Extract the ratings for this film
    $final_ratings = [];
    foreach ($raw_ratings as $rating) {
      $indv = new stdClass();

      // Extract the rating category ID from the record ID and
      // associate each category with the proper rating
      // Get the negative length of the film ID for proper slicing
      $slice_end = -strlen($this->id);
      $review_code = substr($rating->id, 3, $slice_end);
      $indv->class = '';
      $indv->category = $categories[$review_code];
      $indv->rating = $rating->rating;
      $final_ratings[] = $indv;
    }
    return $final_ratings;
  }

  /**
   * Get the film's given honors.
   *
   * @return {string}
   */
  function honors() {
    require '../core/database.php';
    $stmt = $pdo->prepare(get_sql('film-honors'));
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $r = $stmt->fetch(PDO::FETCH_OBJ);

    // Get the proper honor's label
    $honors = [
      '1' => 'No honors given',
      '2' => 'No honors given',
      '3' => "Reviewer's Pick",
      '4' => 'Staff Favorite'
    ];
    return $honors[$r->review_stat];
  }
}
