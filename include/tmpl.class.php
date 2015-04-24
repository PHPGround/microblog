<?php

/**
 * Class Tmpl
 *
 * Template helper class.
 */
class Tmpl
{
  /**
   * Construct a URL for static files.
   */
  public static function static_url($url)
  {
    return Tmpl::escape(APP_BASE_URL . $url);
  }

  /**
   * Escape HTML special characters.
   */
  public static function escape($html)
  {
    return htmlspecialchars($html);
  }

  /**
   * Render template.
   */
  public static function render($tmlp, $args=[])
  {
    return include_once APP_PATH . '/template/' . $tmlp;
  }
}