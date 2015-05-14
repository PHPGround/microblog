<?php

require_once APP_PATH . '/include/dbfactory.class.php';
require_once APP_PATH . '/include/util.class.php';

/**
 * Index page controller.
 */
function user()
{
  if (!isset($_SESSION['user.id'])) {
    header('Location: ' . APP_BASE_URL . '/p/signin');
    exit(0);
  }


  $url = str_replace(APP_BASE_URL, '', strtok(urldecode($_SERVER['REQUEST_URI']), '?'));
  preg_match('#/([\pL_]+)$#u', $url, $matches);


  assert(isset($matches[1]));

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
    *,
    (SELECT
            COUNT(*)
        FROM
            blog
        WHERE
            user_id = user.id) AS blogs,
    (SELECT
            COUNT(*)
        FROM
            follow
        WHERE
            dist_user_id = user.id) AS followers,
    (SELECT
            COUNT(*)
        FROM
            follow
        WHERE
            src_user_id = user.id) AS following,
    (SELECT
    IFNULL(COUNT(*), 0) AS followed
FROM
    follow
WHERE
    src_user_id = ? AND dist_user_id = user.id) AS followed_by_me
FROM
    user
        INNER JOIN
    user_extra ON user.id = user_extra.id
WHERE
    account = ?');
  $stmt->bind_param('is', $_SESSION['user.id'], $matches[1]);
  $stmt->execute();
  $res = $stmt->get_result();
  $user_page = $res->fetch_assoc();
  $stmt->free_result();

  if ($user_page == null) {
    echo 'Not Found';
    exit(0);
  }

  $stmt = $db->prepare('SELECT
    *
FROM
    (SELECT
        user.id AS user_id,
            user.account,
            user_extra.name,
            user_extra.set_avatar,
            blog.id AS blog_id,
            blog.text,
            blog.date AS blog_date,
            NULL AS rebloged,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                GROUP BY comment.dist_blog_id), 0) AS comment_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                GROUP BY reblog.blog_id), 0) AS reblog_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                GROUP BY favorite.blog_id), 0) AS favorite_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                        AND comment.src_blog_id = ?
                GROUP BY comment.dist_blog_id), 0) AS comment_by_me,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                        AND reblog.user_id = ?
                GROUP BY reblog.blog_id), 0) AS reblog_by_me,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                        AND favorite.user_id = ?
                GROUP BY favorite.blog_id), 0) AS favorite_by_me
    FROM
        blog
    INNER JOIN user ON blog.user_id = user.id
    INNER JOIN user_extra ON user.id = user_extra.id
    WHERE
        blog.user_id = ? UNION ALL SELECT
        user.id AS user_id,
            user.account,
            user_extra.name,
            user_extra.set_avatar,
            blog.id AS blog_id,
            blog.text,
            blog.date AS blog_date,
            reblog.date AS rebloged,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                GROUP BY comment.dist_blog_id), 0) AS comment_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                GROUP BY reblog.blog_id), 0) AS reblog_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                GROUP BY favorite.blog_id), 0) AS favorite_times,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    comment
                WHERE
                    blog.id = comment.dist_blog_id
                        AND comment.src_blog_id = ?
                GROUP BY comment.dist_blog_id), 0) AS comment_by_me,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    reblog
                WHERE
                    blog.id = reblog.blog_id
                        AND reblog.user_id = ?
                GROUP BY reblog.blog_id), 0) AS reblog_by_me,
            IFNULL((SELECT
                    COUNT(*)
                FROM
                    favorite
                WHERE
                    blog.id = favorite.blog_id
                        AND favorite.user_id = ?
                GROUP BY favorite.blog_id), 0) AS favorite_by_me
    FROM
        blog
    INNER JOIN reblog ON blog.id = reblog.blog_id
    INNER JOIN user ON blog.user_id = user.id
    INNER JOIN user_extra ON user.id = user_extra.id
    WHERE
        reblog.user_id = ?) AS T
ORDER BY (CASE
    WHEN rebloged IS NULL THEN blog_date
    ELSE rebloged
END) DESC');
  $stmt->bind_param('iiiiiiii',
    $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'], $user_page['id'],
    $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'], $user_page['id']);
  $stmt->execute();
  $res = $stmt->get_result();
  $blog_list = [];
  while (($data = $res->fetch_assoc())) {
    $blog_list[] = $data;
  }
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

  Tmpl::render('/user.php', ['title' => $user_page['name'],
    'state' => $user_state, 'blogs' => $blog_list, 'hashes' => $top_hashes, 'follow' => $follow, 'page' => $user_page]);
}

