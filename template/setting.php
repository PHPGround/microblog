<?php Tmpl::render('/header.php', $args) ?>
  <div id="body-outer">
    <div id="body-inner">
      <div class="container">
        <div id="signin-body">
          <h1>الإعدادات</h1>
          <form action="<?= Tmpl::url('/p/setting') ?>" method="post" class="form" enctype="multipart/form-data">
            <input type="file" name="avatar" id="data-file-real" style="display: none; width: 0; height: 0" accept="image/png">
            <label>
              <div class="input-label input-file">الصورة الشخصية</div>
              <img id="data-file-dummy" src="<?=
              Tmpl::url('/static/avatar/avatar_' . ($_SESSION['user.set_avatar'] ? $_SESSION['user.id'] :
                  'default'))?>.png" alt="الصورة الشخصية">
<?php if(is_string($args['error']['avatar']['error'])): ?>
              <div class="input-help input-error">
                <span class="fa fa-warning"></span>
                <?= Tmpl::escape($args['error']['avatar']['error']) ?>
<?php else : ?>
              <div class="input-help">
                <span class="fa fa-question-circle"></span>
                اختر صورتك الشخصية، نوع الصورة يجب أن يكون PNG وحجمها أقل من 1 ميجابايت.
<?php endif ?>
              </div>
            </label>

            <label>
              <div class="input-label">الإسم</div>
              <input type="text" name="name" value="<?= Tmpl::escape($args['setting']['name']) ?>">
<?php if($args['error']['name']['error']) : ?>
                <div class="input-help input-error">
                  <span class="fa fa-warning"></span>
<?php else :?>
                  <div class="input-help">
                <span class="fa fa-question-circle"></span>
<?php endif ?>
                عدّل اسمك الكامل، طول الإسم يجب أن لايتجاوز 20 حرفاً.</div>
            </label>

            <label>
              <div class="input-label">البريد الإلكتروني</div>
              <input style="direction: ltr; text-align: left" type="text" name="email" value="<?= Tmpl::escape($args['setting']['email']) ?>">
<?php if($args['error']['email']['error']) : ?>
              <div class="input-help input-error">
                <span class="fa fa-warning"></span>
 <?php else :?>
                <div class="input-help">
                  <span class="fa fa-question-circle"></span>
<?php endif ?>
                غيّر بريدك الإلكتروني، البريد الإلكتروني الجديد يجب أن يكون صالح.</div>
            </label>

            <label>
              <div class="input-label">كلمة المرور</div>
              <input type="password" name="old-password">
<?php if(is_string($args['error']['old-password']['error'])) : ?>
              <div class="input-help input-error">
              <span class="fa fa-warning"></span>
              <?= Tmpl::escape($args['error']['old-password']['error']) ?>
<?php else :?>
              <div class="input-help">
              <span class="fa fa-question-circle"></span>
                  أدخل كلمة المرور الحالية في في حال أردت تغيير كلمة المرور أو تغيير البريد الإكتروني.
<?php endif ?>
              </div>
            </label>

            <label>
              <div class="input-label">كلمة المرور الجديدة</div>
              <input type="password" name="new-password" id="setting-new-password">
<?php if($args['error']['new-password']['error']) : ?>
              <div class="input-help input-error">
                <span class="fa fa-warning"></span>
<?php else :?>
                <div class="input-help">
                  <span class="fa fa-question-circle"></span>
<?php endif ?>
                أدخل كلمة المرور الجديدة إذا رغبت بتغييرها، طول كلمة المرور الجديدة يجب أن لايقل عن 5 رموز.</div>
            </label>

            <label>
              <div class="input-label">إعادة كلمة المرور الجديدة</div>
              <input type="password" name="re-password" id="setting-re-password">
              <div class="input-help">
                <span class="fa fa-question-circle"></span>
                أعد إدخال كلمة المرور في حال تغيير كلمة المرور.</div>
            </label>

            <button>حدّث البيانات</button>
            <div class="input-help"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php Tmpl::render('/footer.php', $args) ?>