<?php

function get_auth_header(){
  if (isset($_SERVER['Authorization'])) {
    return trim($_SERVER['Authorization']);
  }
  if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    return trim($_SERVER['HTTP_AUTHORIZATION']);
  }
  if (function_exists('apache_request_headers')) {
    $request_headers = apache_request_headers();
    $request_headers = array_combine(array_map('ucwords', array_keys($request_headers)), array_values($request_headers));
    if (isset($request_headers['Authorization'])) {
      return trim($request_headers['Authorization']);
    }
  }
  return NULL;
}

function get_bearer_token() {
  $headers = get_auth_header();
  if (empty($headers)) {
    return NULL;
  }
  if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
    return $matches[1];
  }
}
