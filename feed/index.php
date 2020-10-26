<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  require_once '../core/register.php';
  register_endpoint($_SERVER['REQUEST_METHOD'], 'feed');
