<?php
  /**
   * https://www.php.net/manual/en/function.array-key-first#refsect1-function.array-key-first-notes
   */
  if (!function_exists('array_key_first')) {
    function array_key_first($arr) {
      foreach($arr as $key => $_) {
        return $key;
      }
      return null;
    }
  }
