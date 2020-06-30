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

function get_json($path) {
  return json_decode(file_get_contents($path), false);
}

function send_error_response($code, $msg) {
  send_response($code, ['msg-error' => $msg]);
}

function send_response($code, $data=null) {
  http_response_code($code);
  header('Content-Type: application/json');

  // Only send JSON data if needed
  if ($data !== null) {
    echo json_encode($data);
  }
  exit();
}
