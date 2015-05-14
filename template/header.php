<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="utf-8">
  <meta name="description" content="دوّن، موقع للتدوين المصغر، يمكنك متابعة التطورات والآحداث، ومشاركة الآخرين آرائك وإبداعاتك عن طريق تدوينات مصغرة.">
  <meta name="keywords" content="دون, تدوين, صور, فديو, مقالات, تدوين مصغر">
  <title>دوّن | <?= Tmpl::escape($args['title']) ?></title>
  <link rel="stylesheet" href="<?= Tmpl::url('/assets/css/core-style.css') ?>">
  <link rel="stylesheet" href="<?= Tmpl::url('/assets/css/core-icons.css') ?>">
  <script>
    var Microblog = Microblog || {};

    Microblog.base = '<?= addslashes(APP_BASE_URL) ?>';
<?php if(isset($_SESSION['user.id'])): ?>
    Microblog.user_id = <?= $_SESSION['user.id'] ?>;
    Microblog.user_account = '<?= addslashes($_SESSION['user.account']) ?>';
    Microblog.user_name = '<?= addslashes($_SESSION['user.name']) ?>';
    Microblog.user_set_avatar = <?= $_SESSION['user.set_avatar'] ? 'true' : 'false' ?>;
<?php endif ?>
  </script>
  <script src="<?= Tmpl::url('/assets/js/jquery-1.11.2_min.js') ?>"></script>
  <script src="<?= Tmpl::url('/assets/js/xregexp-min.js') ?>"></script>
  <script src="<?= Tmpl::url('/assets/js/script-core.js') ?>"></script>
</head>
<body>
  <header id="header-outer">
    <div id="header-inner">
      <nav id="navigation-bar">
        <ul>
<?php if(isset($_SESSION['user.id'])): ?>
          <li><a href="<?= Tmpl::url('/') ?>"><span class="fa fa-home"></span>الرئيسية</a></li>
          <li><a id="new-blog" href="<?= APP_BASE_URL ?>"><span class="fa fa-edit"></span>تدوينة جديدة</a></li>
          <!--<li><a href="<?= Tmpl::url('/p/discover') ?>"><span class="fa fa-compass"></span>اكتشف</a></li> -->
          <li><a href="<?= Tmpl::url('/p/setting') ?>"><span class="fa fa-cog"></span>الإعدادات</a></li>
          <li><a href="<?= Tmpl::url('/p/signout?token=') . md5(session_id() . CSRF_SALT) ?>"><span class="fa fa-sign-out"></span>خروج</a></li>
<?php else: ?>
          <li><a href="<?= Tmpl::url('/p/signup') ?>"><span class="fa fa-user-plus"></span>التسجيل</a></li>
          <li><a href="<?= Tmpl::url('/p/signin') ?>"><span class="fa fa-user"></span>دخول</a></li>
<?php endif ?>
        </ul>
      </nav>
      <div id="tool-bar">
        <a href="<?= Tmpl::url('/') ?>"></a>
        <form action="<?= Tmpl::url('/p/search') ?>" method="get">
          <div>
            <input type="text" autocomplete="off" placeholder="بحث" name="q">
            <button class="fa fa-search"></button>
          </div>
        </form>
      </div>
    </div>
  </header>
