<?php

class Tmpl
{
  public static function static_url($url)
  {
    return Tmpl::escape(APP_BASE_URL . $url);
  }

  public static function escape($html)
  {
    return htmlspecialchars($html);
  }

  public static function render($tmlp, $args=[])
  {
    return include_once APP_PATH . '/template/' . $tmlp;
  }
}