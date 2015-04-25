<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="utf-8">
  <meta name="description" content="دوّن، موقع للتدوين المصغر، يمكنك متابعة التطورات والآحداث، ومشاركة الآخرين آرائك وإبداعاتك عن طريق تدوينات مصغرة.">
  <meta name="keywords" content="دون, تدوين, صور, فديو, مقالات, تدوين مصغر">
  <title>دوّن</title>
  <link rel="stylesheet" href="<?= Tmpl::static_url('/assets/css/core-style.css') ?>">
  <link rel="stylesheet" href="<?= Tmpl::static_url('/assets/css/core-icons.css') ?>">
  <script src="<?= Tmpl::static_url('/assets/js/jquery-1.11.2_min.js') ?>"></script>
  <script src="<?= Tmpl::static_url('/assets/js/xregexp-min.js') ?>"></script>
  <script src="<?= Tmpl::static_url('/assets/js/script-core.js') ?>"></script>
</head>
<body>
  <header id="header-outer">
    <div id="header-inner">
      <nav id="navigation-bar">
        <ul>
          <li><a href="<?= Tmpl::static_url('/') ?>"><span class="fa fa-home"></span>الرئيسية</a></li>
          <li><a href="<?= Tmpl::static_url('/p/discover') ?>"><span class="fa fa-compass"></span>اكتشف</a></li>
          <li><a id="button-signup" href="javascript:;"><span class="fa fa-user-plus"></span>التسجيل</a></li>
          <li><a id="button-signin" href="javascript:;"><span class="fa fa-user"></span>دخول</a></li>
        </ul>
      </nav>
      <div id="tool-bar">
        <a href="<?= Tmpl::static_url('/') ?>"></a>
        <form action="<?= Tmpl::static_url('/search') ?>" method="get">
          <div>
            <input type="text" autocomplete="off" placeholder="بحث" name="q">
            <button class="fa fa-search"></button>
          </div>
        </form>
      </div>
    </div>
  </header>

  <div id="signup-form" class="account-form">
    <div class="account-form-wrapper">
      <span class="fa fa-close account-form-close"></span>
      <h3>انضم إلى دوّن</h3>
      <form id="signup-form" action="javascript:;" method="post">
        <input name="account" type="text" class="form-input" placeholder="اسم الحساب">
        <input name="email" type="email" class="form-input" placeholder="البريد الإلكتروني">
        <input name="password" type="password" class="form-input" placeholder="كلمة المرور">
        <button class="form-button">سجّل</button>
      </form>
    </div>
  </div>