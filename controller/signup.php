<?php

require_once APP_PATH . '/include/dbfactory.class.php';
require_once APP_PATH . '/include/util.class.php';

/**
 * Signup page controller.
 */
function signup()
{
  if (isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL);
    exit(0);
  }

  $error_list = Util::make_list(['account', 'name', 'email', 'password']);

  Tmpl::render('/signup.php', ['title' => 'التسجيل', 'error' => $error_list]);
}

/**
 * Signup post request.
 */
function signup_post()
{
  if (isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL);
    exit(0);
  }

  $error_list = Util::all_set($_POST, ['account', 'name', 'email', 'password']);
  $error = false;

  if (!preg_match('/^[\pL_]{1,20}$/u', $_POST['account'])) {
    $error = $error_list['account']['error'] = true;
  }

  if (mb_strlen($_POST['name']) > 20 || mb_strlen($_POST['name']) == 0) {
    $error = $error_list['name']['error'] = true;
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email']) > 254) {
    $error = $error_list['email']['error'] = true;
  }

  if (mb_strlen($_POST['password']) < 5) {
    $error = $error_list['password']['error'] = true;
  }

  if ($error) {
    Tmpl::render('/signup.php', ['title' => 'التسجيل', 'error' => $error_list]);
    exit(0);
  }

  $db = DbFactory::create();

  $db->begin_transaction();

  $query = $db->prepare('SELECT * FROM user WHERE account = ?');
  $query->bind_param('s', $_POST['account']);
  $query->execute();
  $res = $query->get_result();
  if ($res->num_rows) {
    $error_list['account']['error'] = 'اسم الحساب محجوز.';
    Tmpl::render('/signup.php', ['title' => 'التسجيل', 'error' => $error_list]);
    $db->rollback();
    exit(0);
  }
  $query->free_result();

  $query = $db->prepare('SELECT * FROM user WHERE email = ?');
  $query->bind_param('s', $_POST['email']);
  $query->execute();
  $res = $query->get_result();
  if ($res->num_rows > 0) {
    $error_list['email']['error'] = 'البريد الإلكتروني مسجل مسبقاً.';
    Tmpl::render('/signup.php', ['title' => 'التسجيل', 'error' => $error_list]);
    $db->rollback();
    exit(0);
  }
  $query->free_result();

  $query = $db->prepare('INSERT INTO user VALUES(NULL, ?, ?, ?)');
  $query->bind_param('sss',
    $_POST['account'], $_POST['email'], password_hash($_POST['password'], CRYPT_BLOWFISH));
  $query->execute();
  $query->free_result();

  $query = $db->prepare('INSERT INTO user_extra VALUES(?, DEFAULT, DEFAULT, ?, DEFAULT)');
  $query->bind_param('is', $db->insert_id, $_POST['name']);
  $query->execute();
  $query->free_result();

  $db->commit();

  header('Location: ' . APP_BASE_URL . '/p/signin');
}