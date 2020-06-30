<?php
// TODO Remove these!
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Escape any characters that could facilitate an XSS attack vector.
 *
 * @param {string} $text - The raw string to be escaped.
 * @return {string} The escaped string.
 */
function escape_xss($text) {
  // Correctly handle non-breaking spaces
  $text = trim($text, chr(0xC2).chr(0xA0));
  $text = str_replace("&nbsp;", "", $text);
  return htmlentities(strip_tags(trim($text)));
}


/**
 * Register all responses for this this endpoint.
 * @param {string} $verb - The HTTP verb used in the request.
 * @param {string} $endpoint - The endpoint name being accessed.
 */
function register_endpoint($verb, $endpoint) {
  // Build out a path to the endpoint's responses
  $verb_lower = strtolower($verb);
  $require_path = $_SERVER['DOCUMENT_ROOT'] . "/core/endpoints/{$endpoint}";
  $response_file = "{$require_path}/{$verb_lower}.php";

  // A response for that verb is not available
  if (file_exists($response_file) === false){
    make_error_response(405, "Unsupported verb {$verb}.", [generate_allow_header($require_path)]);
  }

  // We have a response, execute it
  require_once $response_file;
}


/**
 * Generate an Allow header for an endpoint.
 * @param {string} $endpoint_path - The folder path to the endpoint's responses.
 * @return {string} A fully-formed HTTP Allow header.
 */
function generate_allow_header($endpoint_path) {
  // Dynamically determine what actions are available
  // by checking what files are present in the folder
  $dir_contents = array_filter(scandir($endpoint_path), function($item) {
    return strpos($item, '.php') !== false;
  });

  // Now that we know what actions are present,
  // convert the filenames into an acceptable list for an Accept header
  $dir_contents = array_map(function($item) {
    return strtoupper(str_replace('.php', '', $item));
  }, $dir_contents);

  // Build out the header
  return 'Allow: ' . implode(', ', $dir_contents);
}


/**
 * Load any JSON data into a PHP array.
 */
function get_json($path) {
  return json_decode(file_get_contents($path), false);
}


/**
 * Load the contents of a SQL script.
 * Throws an Exception if the script cannot be found.
 * @param {string} $script_name - The filename of the SQL script.
 * @return {string} The SQL script.
 */
function get_sql($script_name) {
  // Throw an exception if we can't find the script
  $sql = $_SERVER['DOCUMENT_ROOT'] . "/core/sql/{$script_name}.sql";
  if (file_exists($sql) === false) {
    throw new Exception("SQL script {$script_name} does not exist!");
  }
  return file_get_contents($sql);
}


/**
 * Construct an error endpoint response.
 */
function make_error_response($code, $msg, $headers=null) {
  make_response($code, ['message' => $msg], $headers);
}


/**
 * Construct a non-error endpoint response.
 */
function make_response($code, $data=null, $headers=null) {
  http_response_code($code);
  header('Content-Type: application/json');

  // If we have any extra headers send them too
  if ($headers !== null) {
    foreach ($headers as $http_header) {
      header($http_header);
    }
  }

  // Only send JSON data if needed
  if ($data !== null) {
    echo json_encode($data);
  }
  exit();
}
