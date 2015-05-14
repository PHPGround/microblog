<?php

require_once APP_PATH . '/include/dbfactory.class.php';
require_once APP_PATH . '/include/util.class.php';
require_once APP_PATH . '/include/tmpl.class.php';

/**
 * Signup page controller.
 */
function signin()
{
  if (isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL);
    exit(0);
  }

  Tmpl::render('/signin.php', ['title' => 'الدخول', 'bad_auth' => false]);
}

function signin_post()
{
  if (isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL);
    exit(0);
  }

  Util::all_set($_POST, ['email', 'password']);

  $db = DbFactory::create();

  $stmt = $db->prepare('SELECT
    *
FROM
    user
        INNER JOIN
    user_extra ON user.id = user_extra.id
WHERE
    email = ?');
  $stmt->bind_param('s', $_POST['email']);
  $stmt->execute();
  $res = $stmt->get_result();

  if (!($user = $res->fetch_assoc())) {
    Tmpl::render('/signin.php', ['title' => 'الدخول', 'bad_auth' => true]);
    exit(0);
  }

  if (!password_verify($_POST['password'], $user['password'])) {
    Tmpl::render('/signin.php', ['title' => 'الدخول', 'bad_auth' => true]);
    exit(0);
  }


  $_SESSION['user.id'] = $user['id'];
  $_SESSION['user.account'] = $user['account'];
  $_SESSION['user.name'] = $user['name'];
  $_SESSION['user.set_avatar'] = $user['set_avatar'];

  setcookie('t', time());

  header('Location: ' . APP_BASE_URL);
  exit(0);
}