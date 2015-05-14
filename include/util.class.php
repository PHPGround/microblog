<?php

class Util
{
  public static function make_list($fields)
  {
    $error_list = [];
    foreach($fields as $field) {
      $error_list[$field] = ['value' => '', 'error' => false];
    }
    return $error_list;
  }

  public static function all_set($method, $fields)
  {
    $error_list = [];

    foreach($fields as $field)
    {
      if(!isset($method[$field])) {
        echo 'Method Not Allowed';
        exit(0);
      }

      $error_list[$field] = ['value' => $method[$field], 'error' => false];
    }

    return $error_list;
  }
}