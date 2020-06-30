<?php
  require_once '../core/common-utils.php';
  require_once '../core/classes/Search.php';

  // A search value was not provided
  if (!isset($_GET['title'])) {
    echo make_error_response(400, 'Missing search value!');
  }

  // Get the search value
  $search_query = escape_xss($_GET['title']);
  $search_results = Search::by_title($search_query);

  // No results were found
  if (empty($search_results)) {
    echo make_error_response(404, "No films found for query {$search_query}");
  }

  // We have results
  echo make_response(200, $search_results);
