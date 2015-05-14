<?php Tmpl::render('/header.php', $args) ?>
  <div id="body-outer">
    <div id="body-inner">
      <div class="container">
        <div id="signup-body">
          <h1>إنضم إلى دوّن</h1>
          <form action="<?= Tmpl::url('/p/signup') ?>" method="post" class="form">
            <label>
              <div class="input-label">اسم الحساب</div>
              <input type="text" name="account" value="<?= Tmpl::escape($args['error']['account']['value']) ?>">
<?php if($args['error']['account']['error']) :?>
              <div class="input-help input-error">
              <span class="fa fa-warning"></span>
<?php else: ?>
              <div class="input-help">
              <span class="fa fa-question-circle"></span>
<?php endif ?>
<?php if(is_string($args['error']['account']['error'])): ?>
                <?= Tmpl::escape($args['error']['account']['error']) ?>
<?php else: ?>
                اسم الحساب يجب أن لايزيد عن 20 حرف، ويحتوي فقط على حروف أو أرقام أو شرطة السفلية "_".
<?php endif ?>
              </div>
            </label>

            <label>
              <div class="input-label">الإسم</div>
              <input type="text" name="name" value="<?= Tmpl::escape($args['error']['name']['value']) ?>">
<?php if($args['error']['name']['error']) :?>
              <div class="input-help input-error">
              <span class="fa fa-warning"></span>
<?php else: ?>
              <div class="input-help">
              <span class="fa fa-question-circle"></span>
<?php endif ?>
                أدخل اسمك الكامل الذي تريد أن يعرفك الآخرين به، طول الإسم يجب أن لايزيد عن 20 حرفاً.
              </div>
            </label>

            <label>
              <div class="input-label">البريد الإلكتروني</div>
              <input type="text" name="email" value="<?= Tmpl::escape($args['error']['email']['value']) ?>">
<?php if($args['error']['email']['error']) :?>
              <div class="input-help input-error">
              <span class="fa fa-warning"></span>
<?php else: ?>
              <div class="input-help">
              <span class="fa fa-question-circle"></span>
<?php endif ?>
<?php if(is_string($args['error']['email']['error'])): ?>
                <?= Tmpl::escape($args['error']['email']['error']) ?>
<?php else: ?>
                أدخل بريد إلكتروني صالح، ستستخدمه لتسجيل الدخول.
<?php endif ?>
              </div>
            </label>

            <label>
              <div class="input-label">كلمة المرور</div>
              <input type="password" name="password">
<?php if($args['error']['password']['error']) :?>
              <div class="input-help input-error">
              <span class="fa fa-warning"></span>
<?php else: ?>
              <div class="input-help">
              <span class="fa fa-question-circle"></span>
<?php endif ?>
                يجب أن لا يقل طول كلمة المرور عن خمسة رموز.
              </div>
            </label>

            <button>أنشئ حسابي</button>
            <div class="input-help">بضغطك على "أنشئ حسابي" فأنت توافق على شروط الإستخدام.</div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php Tmpl::render('/footer.php', $args) ?>