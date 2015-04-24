<?php

define('APP_PATH', getcwd());
define('APP_BASE_URL', dirname($_SERVER['PHP_SELF']) === DIRECTORY_SEPARATOR &&
                       DIRECTORY_SEPARATOR === '\\'? ''
                       : dirname($_SERVER['PHP_SELF']));

require_once APP_PATH . '/include/dispatch.class.php';
require_once APP_PATH . '/include/tmpl.class.php';

require_once APP_PATH . '/controller/index.php';

/**
 * Dispatch requested URLs to their controllers based on the regex and the HTTP
 * method.
 */
(new Dispatch([
  '#^/$#' => ['GET', 'index']
]))->start();

