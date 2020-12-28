<?php
   require_once '../core/common-utils.php';

  function get_years() {
    require_once '../core/database.php';

    $stmt = $pdo->prepare(get_sql('archive-get-years'));
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  function get_films_in_year($year) {
    require_once '../core/database.php';

    $sql = get_sql('archive-get-films-in-year');
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // A list of film years is requested
  if (isset($_GET['year']) && strtolower(escape_xss($_GET['year'])) === 'all') {
    echo make_response(200, get_years());

  // A basic list of films for a given year is requested
  } else if (isset($_GET['year']) && strlen(escape_xss($_GET['year'])) === 4) {
    $year = escape_xss($_GET['year']);
    echo make_response(200, get_films_in_year($year));
  }
