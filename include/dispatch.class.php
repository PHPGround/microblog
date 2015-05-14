<?php

/**
 * Class Dispatch
 *
 * Dispatch urls to callbacks based on regex.
 */
class Dispatch
{
  private $table;

  public function __construct($table)
  {
    $this->table = $table;
  }

  /**
   * Start dispatching urls to callbacks.
   */
  public function start()
  {
    $url = str_replace(APP_BASE_URL, '', strtok(urldecode($_SERVER['REQUEST_URI']), '?'));

    foreach($this->table as $meta)
    {
      $regex = $meta[0];
      $method = $meta[1];
      $callback = $meta[2];

      if($_SERVER['REQUEST_METHOD'] == $method && preg_match($regex, $url))
      {
        return $callback();
      }
    }

    //http_response_code(404);
    //header('Content-type: plain/text');
    echo 'Not Found';
    exit(0);
  }
}