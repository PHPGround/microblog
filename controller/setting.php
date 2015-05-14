<?php

function setting()
{
  if (!isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL . '/p/signin');
    exit(0);
  }

  $db = DbFactory::create();
  $stmt = $db->prepare('SELECT
    name, email
FROM
    user
        INNER JOIN
    user_extra ON user.id = user_extra.id
WHERE
    user.id = ?');

  $stmt->bind_param('i', $_SESSION['user.id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $user_settings = $res->fetch_assoc();
  $stmt->free_result();
  $_SESSION['user.email'] = $user_settings['email'];

  $error_list = Util::make_list(['name', 'email', 'old-password',
    'new-password']);
  $error_list['avatar'] = ['error' => false];

  Tmpl::render('/setting.php', ['title' => 'الإعدادات', 'setting' => $user_settings
    , 'error' => $error_list]);
}

function setting_post()
{
  if (!isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL . '/p/signin');
    exit(0);
  }

  $error_list = Util::all_set($_POST, ['name', 'email', 'old-password',
    'new-password']);
  $error_list['avatar'] = ['error' => false];
  $error = false;
  $avatar_uploaded = false;

  if(!isset($_FILES['avatar']))
  {
    echo 'Method Not Allowed';
    exit(0);
  }

  $db = DbFactory::create();
  $stmt = $db->prepare('SELECT
    name, email
FROM
    user
        INNER JOIN
    user_extra ON user.id = user_extra.id
WHERE
    user.id = ?');

  $stmt->bind_param('i', $_SESSION['user.id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $user_settings = $res->fetch_assoc();
  $stmt->free_result();

  if (mb_strlen($_POST['name']) > 20 || mb_strlen($_POST['name']) == 0) {
    $error = $error_list['name']['error'] = true;
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email']) > 254) {
    $error = $error_list['email']['error'] = true;
  }

  if (!empty($_POST['new-password']) && mb_strlen($_POST['new-password']) < 5) {
    $error = $error_list['new-password']['error'] = true;
    Tmpl::render('/setting.php', ['title' => 'الإعدادات', 'setting' => $user_settings, 'error' => $error_list]);
    exit(0);
  }

  if ($_FILES['avatar']['error'] != UPLOAD_ERR_NO_FILE && $_FILES['avatar']['error'] == UPLOAD_ERR_OK &&
    $_FILES['avatar']['type'] == 'image/png' && $_FILES['avatar']['size'] > 0 &&
    $_FILES['avatar']['size'] < 1000000
  ) {
    $avatar_uploaded = move_uploaded_file($_FILES['avatar']['tmp_name'], APP_PATH . '/static/avatar/avatar_' . $_SESSION['user.id'] . '.png');
  } else if ($_FILES['avatar']['error'] != UPLOAD_ERR_NO_FILE) {
    $error = $error_list['avatar']['error'] = 'فشل رفع الصورة، تأكد من أن الصورة صورة PNG صالحة حجمها أقل من 1 ميجابايت.';
  }

  if ($_POST['email'] != $_SESSION['user.email'] || !empty($_POST['new-password'])) {
    if (empty($_POST['old-password'])) {
      $error = $error_list['old-password']['error'] = 'يجب إدخال كلمة المرور عند رغبتك بتغيير البريد الإلكتروني أو كلمة المرور.';
    } else {
      $stmt = $db->prepare('SELECT password FROM user WHERE id = ?');
      $stmt->bind_param('i', $_SESSION['user.id']);
      $stmt->execute();
      $res = $stmt->get_result();
      $password = $res->fetch_assoc();
      $stmt->free_result();

      if (!password_verify($_POST['old-password'], $password['password'])) {
        $error = $error_list['old-password']['error'] = 'كلمة المرور غير صحيحة.';
        exit(0);
      }
    }
  }

  if($error) {
    Tmpl::render('/setting.php', ['title' => 'الإعدادات', 'setting' => $user_settings
      , 'error' => $error_list]);
    exit(0);
  }

  $stmt = null;

  if (!empty($_POST['new-password'])) {
    $stmt = $db->prepare('UPDATE user SET email = ?, password = ? WHERE id = ?');
    $stmt->bind_param('ssi', $_POST['email'], password_hash($_POST['new-password'], CRYPT_BLOWFISH),
      $_SESSION['user.id']);
  } else if ($_POST['email'] != $_SESSION['user.email']) {
    $stmt = $db->prepare('UPDATE user SET email = ? WHERE id = ?');
    $stmt->bind_param('si', $_POST['email'], $_SESSION['user.id']);

  }

  if ($stmt) {
    $stmt->execute();
    $stmt->free_result();
  }

  $stmt = $db->prepare('UPDATE user_extra SET name = ?, set_avatar = ? WHERE id = ?');
  $stmt->bind_param('sii', $_POST['name'], $avatar_uploaded, $_SESSION['user.id']);
  $stmt->execute();
  $stmt->free_result();
  $_SESSION['user.name'] = $_POST['name'];
  $_SESSION['user.set_avatar'] = $avatar_uploaded;


  header('Location: ' . APP_BASE_URL . '/p/setting');
}