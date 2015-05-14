<?php Tmpl::render('/header.php', $args) ?>
<div id="body-outer">
  <div id="body-inner">
    <div class="container">
      <div id="signin-body">
        <h1>تسجيل الدخول</h1>
        <form action="<?= Tmpl::url('/p/signin') ?>" method="post" class="form">
<?php if($args['bad_auth']): ?>
          <div class="input-help input-error">
          <span class="fa fa-warning"></span>
          البريد الإلكتروني أو كلمة المرور غير صحيحة.</div>
<?php endif ?>
          <label>
            <div class="input-label">البريد الإلكتروني</div>
            <input type="text" name="email">
            <div class="input-help">
            <span class="fa fa-question-circle"></span>
            أدخل بريدك الإلكتروني الذي سجلت به.</div>
          </label>

          <label>
            <div class="input-label">كلمة المرور</div>
            <input type="password" name="password">
            <div class="input-help">
            <span class="fa fa-question-circle"></span>
            أدخل كلمة المرور.</div>
          </label>

          <button>دخول</button>
          <div class="input-help"></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php Tmpl::render('/footer.php', $args) ?>