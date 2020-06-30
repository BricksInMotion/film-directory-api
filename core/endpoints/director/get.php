<?php
require_once '../core/common-utils.php';
require_once '../core/classes/Director.php';

// A director id was not provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo make_error_response(400, 'Missing Director user ID!');
}

// Get the director ID
$director = new Director(escape_xss($_GET['id']));
// $search_results = Search::by_title($search_query);

// No results were found
if ($director->exists() === false) {
  echo make_error_response(404, "Could not find Director for ID {$director->id}!");
}

// We have a Director, get their info
echo make_response(200, $director->info());
