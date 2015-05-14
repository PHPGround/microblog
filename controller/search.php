<?php

function search()
{
  if (!isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL . '/p/signin');
    exit(0);
  }

  $db = DbFactory::create();

  $stmt = $db->prepare('SELECT
    (SELECT
            set_avatar
        FROM
            user_extra
        WHERE
            id = ?) AS set_avatar,
    (SELECT
            COUNT(*)
        FROM
            blog
        WHERE
            user_id = ?) AS blogs,
    (SELECT
            COUNT(*)
        FROM
            follow
        WHERE
            dist_user_id = ?) AS followers,
    (SELECT
            COUNT(*)
        FROM
            follow
        WHERE
            src_user_id = ?) AS following');
  $stmt->bind_param('iiii', $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $user_state = $res->fetch_assoc();
  $stmt->free_result();

  $stmt = $db->prepare('SELECT
    user.id,
    user.account,
    user_extra.name,
    user_extra.set_avatar
FROM
    user
        INNER JOIN
    user_extra ON user.id = user_extra.id
WHERE
    user.id <> ?
        AND NOT (SELECT
            IFNULL(COUNT(*), 0)
        FROM
            follow
        WHERE
            follow.dist_user_id = user.id
                AND follow.src_user_id = ?)
ORDER BY RAND()
LIMIT 4');
  $stmt->bind_param('ii', $_SESSION['user.id'], $_SESSION['user.id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $follow = [];
  while (($data = $res->fetch_assoc())) {
    $follow[] = $data;
  }
  $stmt->free_result();

  $res = $db->query('SELECT
    hash.id, hash.hash, COUNT(*)
FROM
    hash
        INNER JOIN
    blog_on_hash ON hash.id = blog_on_hash.hash_id
GROUP BY hash
ORDER BY COUNT(*) DESC
LIMIT 6');

  $top_hashes = [];
  while (($data = $res->fetch_assoc())) {
    $top_hashes[] = $data;
  }

  $keywords = (isset($_GET['q']) and !empty($_GET['q'])) ? explode(' ', $_GET['q']) : null;
  $blog_list = [];

  if($keywords) {
    $query = 'SELECT
    user.id AS user_id,
    user.account,
    user_extra.name,
    user_extra.set_avatar,
    blog.id AS blog_id,
    blog.text,
    blog.date AS blog_date,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                GROUP BY comment.dist_blog_id),
            0) AS comment_times,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                GROUP BY reblog.blog_id),
            0) AS reblog_times,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                GROUP BY favorite.blog_id),
            0) AS favorite_times,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                        AND comment.src_blog_id = ?
                GROUP BY comment.dist_blog_id),
            0) AS comment_by_me,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                        AND reblog.user_id = ?
                GROUP BY reblog.blog_id),
            0) AS reblog_by_me,
    IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                        AND favorite.user_id = ?
                GROUP BY favorite.blog_id),
            0) AS favorite_by_me
FROM
    blog
        INNER JOIN
    user ON blog.user_id = user.id
        INNER JOIN
    user_extra ON user.id = user_extra.id
        LEFT JOIN
    blog_on_hash ON blog_on_hash.blog_id = blog.id
        LEFT JOIN
    hash ON hash.id = blog_on_hash.hash_id
WHERE
  1 = 0 ';

    $func_args = [$_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id']];

    $types = 'iii';

    foreach($keywords as $keyword) {
      if($keyword[0] == '#') {
        $keyword = str_replace('#', '%23', $keyword);
        $query .= ' OR hash.hash = ?';
      }
      else {
        $keyword = '%' . $keyword . '%';
        $query .= ' OR blog.text LIKE ?';
      }
      $func_args[] = $keyword;
      $types .= 's';
    }

    $query .= ' ORDER BY date DESC';

    $stmt = $db->prepare($query);

    array_unshift($func_args, $types);

    for($i = 0; $i < count($func_args); $i++) {
      $func_args_ref[] = &$func_args[$i];
    }

    call_user_func_array(array($stmt, 'bind_param'), $func_args_ref);

    $stmt->execute();
    $res = $stmt->get_result();
    $blog_list = [];
    while (($data = $res->fetch_assoc())) {
      $blog_list[] = $data;
    }

  }

  Tmpl::render('/search.php', ['title' => 'بحث',
    'state' => $user_state, 'blogs' => $blog_list, 'hashes' => $top_hashes, 'follow' => $follow,
  'search' => isset($_GET['q']) ? $_GET['q'] : null]);
}