<?php

function follow()
{
  header('Content-Type: application/json');

  if(!isset($_SESSION['user.id']) || !isset($_POST['user_id']))
  {
    http_response_code(404);
    exit(0);
  }

  if(!preg_match('#^\d+$#', $_POST['user_id']) || $_POST['user_id'] == $_SESSION['user.id'])
  {
    http_response_code(404);
    exit(0);
  }

  $db = DbFactory::create();

  $stmt = $db->prepare('SELECT
    IFNULL(COUNT(*), 0) AS followed
FROM
    follow
WHERE
    src_user_id = ? AND dist_user_id = ?');
  $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['user_id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $field = $res->fetch_assoc();
  $stmt->free_result();

  if($field['followed']) {
    $stmt = $db->prepare('DELETE FROM follow WHERE src_user_id = ? AND dist_user_id = ?');
    $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }
  else {
    $stmt = $db->prepare('INSERT INTO follow VALUES(?, ?)');
    $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }

  echo json_encode(['error' => 200, 'message' => 'OK', 'state' => $field['followed']? false : true]);
}

function reblog()
{
  header('Content-Type: application/json');

  if(!isset($_SESSION['user.id']) || !isset($_POST['blog_id']))
  {
    http_response_code(404);
    exit(0);
  }

  if(!preg_match('#^\d+$#', $_POST['blog_id']))
  {
    http_response_code(404);
    exit(0);
  }

  $db = DbFactory::create();

  $stmt = $db->prepare('SELECT
    blog.user_id AS blog_owner, IFNULL(COUNT(*), 0) AS rebloged_by_me
FROM
    reblog
        INNER JOIN
    blog ON reblog.blog_id = blog.id
WHERE
    reblog.user_id = ? AND blog_id = ?');
  $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['blog_id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $field = $res->fetch_assoc();
  $stmt->free_result();

  if($field['blog_owner'] == $_SESSION['user.id'])
  {
    http_response_code(404);
    echo json_encode(['error' => 404, 'message' => 'Cannot reblog your own blogs.']);
    exit(0);
  }

  if($field['rebloged_by_me']) {
    $stmt = $db->prepare('DELETE FROM reblog WHERE user_id = ? AND blog_id = ?');
    $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['blog_id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }
  else {
    $stmt = $db->prepare('INSERT INTO reblog VALUES(?, ?, DEFAULT);');
    $stmt->bind_param('ii', $_SESSION['user.id'], $_POST['blog_id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }

  echo json_encode(['error' => 200, 'message' => 'OK', 'state' => $field['rebloged_by_me']? false : true]);
}