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
  public static function url($url)
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

  /**
   * Convert digits to eastern Arabic digits.
   */
  public static function ar($num)
  {
    return str_replace(array("0","1","2","3","4","5","6","7","8","9"),
                       array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"), "$num");
  }

  /**
   * Normalize blog.
   */
  public static function norm($text)
  {
    $smiley = ["angry", "biggrin", "blink", "blush", "cool", "cry", "drool", "getlost", "grin", "happy", "kiss", "kissed", "laughing", "music", "poo", "pouty", "rolleyes", "sad", "shock", "shocked", "sick", "sideways", "sleep", "smile", "stfu", "teeth", "tongue", "wacko", "wink", "wrong", "yawn"];

    $escaped = Tmpl::escape($text);
    foreach($smiley as $i) {
      $escaped = str_replace("[$i]", "<img src=\"" . APP_BASE_URL . "/assets/image/smiley/$i.png\" alt=\"$i\">",$escaped);
    }
    return preg_replace('/#([\d\pL_]+)/u', '<a href="' . APP_BASE_URL . '/p/search?q=%23${1}">#${1}</a>', $escaped);
  }
}