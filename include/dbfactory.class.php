<?php

class DbFactory
{
  public static function create()
  {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli('localhost', 'root', '', 'microblog');
    $db->set_charset('utf8');
    return $db;
  }
}