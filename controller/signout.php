<?php

function signout()
{
  if (isset($_GET['token']) && $_GET['token'] == md5(session_id() . CSRF_SALT)) {
    session_destroy();
    header('Location: ' . APP_BASE_URL . '/p/signin');
    exit(0);
  }

  header('Location: ' . APP_BASE_URL);
  exit(0);
}