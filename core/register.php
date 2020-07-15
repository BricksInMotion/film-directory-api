<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/common-utils.php';

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