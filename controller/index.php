<?php

function index()
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
    user_id,
    account,
    name,
    set_avatar,
    blog_id,
    text,
    blog_date,
    rebloged,
    rebloged_by,
    rebloged_by_name,
    rebloged_by_account,
    comment_times,
    reblog_times,
    favorite_times,
    comment_by_me,
    reblog_by_me,
    favorite_by_me
FROM
    (SELECT
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
            NULL AS rebloged_by,
            NULL AS rebloged_by_name,
            NULL AS rebloged_by_account,
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
    INNER JOIN user_extra ON user.id = user_extra.id UNION ALL SELECT
        user.id AS user_id,
            user.account,
            user_extra.name,
            user_extra.set_avatar,
            blog.id AS blog_id,
            blog.text,
            blog.date AS blog_date,
            reblog.date AS rebloged,
            reblog.user_id AS rebloged_by,
            (SELECT
                    name
                FROM
                    user_extra
                WHERE
                    id = reblog.user_id) AS rebloged_by_name,
            (SELECT
                    account
                FROM
                    user
                WHERE
                    id = reblog.user_id) AS rebloged_by_account,
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
    INNER JOIN user_extra ON user.id = user_extra.id) AS T) AS T2
WHERE
    user_id IN (SELECT
            follow.dist_user_id
        FROM
            follow
        WHERE
            follow.src_user_id = ?)
        OR rebloged_by IN (SELECT
            follow.dist_user_id
        FROM
            follow
        WHERE
            follow.src_user_id = ?)
    OR user_id = ?
GROUP BY blog_id
ORDER BY (CASE
    WHEN rebloged IS NULL THEN blog_date
    ELSE rebloged
END) DESC');
  $stmt->bind_param('iiiiiiiii',
    $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'],
    $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'], $_SESSION['user.id'],
    $_SESSION['user.id']);
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

  Tmpl::render('/index.php', ['title' => 'الصفحة الرئيسية',
    'state' => $user_state, 'blogs' => $blog_list, 'hashes' => $top_hashes, 'follow' => $follow]);

}