<?php
  require_once '../core/common-utils.php';
  require_once '../core/polyfills.php';
  require_once '../core/classes/Search.php';

  // No query param was provided
  if (empty($_GET)) {
    echo make_error_response(400, 'Missing search value!');
  }

  // More than more search param was sent. That's not allowed
  if (count($_GET) > 1) {
    echo make_error_response(400, 'Only one search type can be performed at once!');
  }

  // We're wanting to search by film title
  if (isset($_GET['title'])) {
    $search_param = 'title';
    $search_query = escape_xss($_GET['title']);
    $search_results = Search::by_title($search_query);

  // We're wanting to search by director name
  } else if (isset($_GET['director'])) {
    $search_param = 'director';
    $search_query = escape_xss($_GET['director']);
    $search_results = Search::by_director($search_query);

  // We were provided an invalid query param
  } else {
    $search_param = array_key_first($_GET);
    $search_query = $_GET[$search_param];
    $search_results = [];
  }

  // No results were found
  if (empty($search_results)) {
    echo make_error_response(404, "No results found for search {$search_param}={$search_query}");
  }

  // We have results
  echo make_response(200, $search_results);
