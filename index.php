<?php

define('APP_PATH', getcwd());
define('APP_BASE_URL', dirname($_SERVER['PHP_SELF']) === DIRECTORY_SEPARATOR &&
                       DIRECTORY_SEPARATOR === '\\'? ''
                       : dirname($_SERVER['PHP_SELF']));
define('CSRF_SALT', "\xc1v\xca\xc2'j\xfe\xb1\xa6\x90\x9a\xca;\xe5vb\x01\xd51!");

require_once APP_PATH . '/include/dispatch.class.php';
require_once APP_PATH . '/include/tmpl.class.php';

require_once APP_PATH . '/controller/signup.php';
require_once APP_PATH . '/controller/signin.php';
require_once APP_PATH . '/controller/blog_ajax.php';
require_once APP_PATH . '/controller/ajax.php';
require_once APP_PATH . '/controller/signout.php';
require_once APP_PATH . '/controller/user.php';
require_once APP_PATH . '/controller/index.php';
require_once APP_PATH . '/controller/setting.php';
require_once APP_PATH . '/controller/search.php';

session_start();

/**
 * Dispatch requested URLs to their controllers based on the regex and the HTTP
 * method.
 */
(new Dispatch([
  ['#^/$#u', 'GET', 'index'],
  ['#^/[\pL_]+$#u', 'GET', 'user'],
  ['#^/p/search$#u', 'GET', 'search'],
  ['#^/p/setting$#', 'GET', 'setting'],
  ['#^/p/setting$#', 'POST', 'setting_post'],
  ['#^/p/signup$#', 'GET', 'signup'],
  ['#^/p/signup$#', 'POST', 'signup_post'],
  ['#^/p/signin$#', 'GET', 'signin'],
  ['#^/p/signin$#', 'POST', 'signin_post'],
  ['#^/p/signout#', 'GET', 'signout'],
  // AJAX
  ['#^/ajax/blog/new$#', 'POST', 'blog_new'],
  ['#^/ajax/follow$#', 'POST', 'follow'],
  ['#^/ajax/reblog#', 'POST', 'reblog']

]))->start();

