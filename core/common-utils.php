<?php
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
 * Load any JSON data into a PHP array.
 */
function get_json($path) {
  return json_decode(file_get_contents($path), true);
}


/**
 * Load the contents of a SQL script.
 * Throws an Exception if the script cannot be found.
 * @param {string} $script_name - The filename of the SQL script.
 * @return {string} The SQL script.
 */
function get_sql($script_name) {
  // Throw an exception if we can't find the script
  $sql = $_SERVER['DOCUMENT_ROOT'] . "/core/sql/queries/{$script_name}.sql";
  if (file_exists($sql) === false) {
    throw new Exception("SQL script {$script_name} does not exist!");
  }
  return file_get_contents($sql);
}


/**
 * Construct an error endpoint response.
 */
function make_error_response($code, $msg, $headers=null, $is_xml=false) {
  make_response($code, ['message' => $msg], $headers, $is_xml);
}


/**
 * Construct a non-error endpoint response.
 */
function make_response($code, $data=null, $headers=null, $is_xml=false) {
  http_response_code($code);

  // Return the proper content type
  if ($is_xml) {
    header('Content-Type: application/xml; charset=UTF-8');
  } else {
    header('Content-Type: application/json');
  }

  // If we have any extra headers send them too
  if ($headers !== null) {
    foreach ($headers as $http_header) {
      header($http_header);
    }
  }

  // Only send data if needed
  if ($data !== null) {
    $data = $is_xml ? $data : json_encode($data);
    echo $data;
  }
  exit();
}


/**
 * Write an error message to log.
 *
 * @param {str} $msg - The error message to write.
 */
function write_to_log($msg) {
  $log_file = $_SERVER['DOCUMENT_ROOT'] . '/log/error.log';
  $current_time = date('c');
  $log_msg = "[{$current_time}] {$msg}\n";
  file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
}
