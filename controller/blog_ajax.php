<?php


function blog_new()
{
  header('Content-Type: application/json');

  if(!isset($_SESSION['user.id']) && !isset($_POST['blog']))
  {
    http_response_code(404);
    exit(0);
  }

  if(mb_strlen($_POST['blog']) == 0 || mb_strlen($_POST['blog']) > 144)
  {
    http_response_code(404);
    exit(0);
  }

  Util::all_set($_POST, ['blog']);

  $db = DbFactory::create();

  $stmt = $db->prepare('INSERT INTO blog VALUES(NULL, ?, ?, DEFAULT )');
  $stmt->bind_param('is', $_SESSION['user.id'], $_POST['blog']);
  $stmt->execute();
  $blog_id = $stmt->insert_id;
  $stmt->free_result();

  if(preg_match_all('/#[\d\pL_]+/u', $_POST['blog'], $mataches))
  {
    $mataches = array_unique($mataches[0]);

    foreach($mataches as $hash)
    {
      $hash = str_replace('#', '%23', $hash);
      $stmt = $db->prepare('INSERT IGNORE INTO hash VALUES(NULL, ?)');
      $stmt->bind_param('s', $hash);
      $stmt->execute();
      $stmt->free_result();

      $stmt = $db->prepare('SELECT id FROM hash WHERE hash = ?');
      $stmt->bind_param('s', $hash);
      $stmt->execute();
      $res = $stmt->get_result();
      $hash_id = $res->fetch_assoc();
      $stmt->free_result();

      $stmt = $db->prepare('INSERT INTO blog_on_hash VALUES(?, ?)');
      $stmt->bind_param('ii', $hash_id['id'], $blog_id);
      $stmt->execute();
      $stmt->free_result();
    }
  }

  echo json_encode(['error' => 200, 'message' => 'OK', 'blog_id' => $blog_id]);
}