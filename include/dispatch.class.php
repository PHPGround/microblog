<?php

//
// Dispatch urls to callbacks based on regex.
//
class Dispatch
{
  private $table;

  public function __construct($table)
  {
    $this->table = $table;
  }

  //
  // Start dispatching urls to callbacks.
  //
  public function start()
  {
    $url = str_replace(APP_BASE_URL, '', strtok($_SERVER['REQUEST_URI'], '?'));

    foreach($this->table as $regex => $info)
    {
      if($_SERVER['REQUEST_METHOD'] == $info[0] && preg_match($regex, $url))
      {
        return $info[1]();
      }
    }

    echo 'Page not found';
    exit(0);
  }
}