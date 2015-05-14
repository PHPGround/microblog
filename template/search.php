<?php Tmpl::render('/header.php', $args) ?>
  <div id="body-outer">
    <div id="body-inner">

      <div id="dashboard">
        <div class="widget">
          <div id="user-profile">
            <div id="user-background"></div>
            <a href="<?= APP_BASE_URL . '/' . $_SESSION['user.account'] ?>">
              <img id="user-avatar" src="<?= APP_BASE_URL ?>/static/avatar/avatar_<?= $args['state']['set_avatar']? $_SESSION['user.id'] : 'default' ?>.png" alt="???-user-???">
              <h3><?= Tmpl::escape($_SESSION['user.name']) ?></h3>
            </a>
            <ul>
              <li>
                <a href="#/user">
                  <span>تدوينات</span>
                  <span data-state-blogs="<?=$args['state']['blogs']?>"><?= Tmpl::ar($args['state']['blogs']) ?></span>
                </a>
              </li>
              <li>
                <a href="#/user/followers">
                  <span>متابعين</span>
                  <span><?= Tmpl::ar($args['state']['followers']) ?></span>
                </a>
              </li>
              <li>
                <a href="#/user/following">
                  <span>يتابع</span>
                  <span><?= Tmpl::ar($args['state']['following']) ?></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <?php if($args['hashes']): ?>
          <div class="widget">
            <div class="widget-label">حديث الساعة</div>
            <div id="hot-topics">
              <ul>
                <?php foreach($args['hashes'] as $hash): ?>
                  <li><a href="<?= APP_BASE_URL . '/p/search?q=' . Tmpl::escape($hash['hash']) ?>"><?= urldecode(Tmpl::escape($hash['hash'])) ?></a></li>
                <?php endforeach ?>
              </ul>
            </div>
          </div>
        <?php endif ?>
        <?php if($args['follow']): ?>
          <div class="widget">
            <div class="widget-label">أشخاص مقترحين</div>
            <div id="suggested-people">
              <?php foreach($args['follow'] as $user): ?>
                <a href="<?= APP_BASE_URL . '/' . $user['account'] ?>">
                  <img src="<?= APP_BASE_URL ?>/static/avatar/avatar_<?= $user['set_avatar'] ? $user['id'] : 'default' ?>.png" alt="">
                  <div>
                    <p><?= $user['name'] ?></p>
                    <span>@<?= $user['account'] ?></span>
                  </div>
                </a>
              <?php endforeach ?>
            </div>
          </div>
        <?php endif ?>
        <div class="widget">
          <div class="widget-label">مواضيع شائعة</div>
          <div id="suggested-topics">
            <ul>
              <li><a href="<?= Tmpl::url('/p/search?q=%23اسلام') ?>"><span class="fa fa-moon-o"></span>اسلام</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23تعليم') ?>"><span class="fa fa-graduation-cap"></span>تعليم</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23أخبار') ?>"><span class="fa fa-newspaper-o"></span>أخبار</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23اقتصاد') ?>"><span class="fa fa-area-chart"></span>اقتصاد</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23تقنية') ?>"><span class="fa fa-flask"></span>تقنية</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23رياضة') ?>"><span class="fa fa-soccer-ball-o"></span>رياضة</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23ترفيه') ?>"><span class="fa fa-gamepad"></span>ترفيه</a></li>
            </ul>
            <ul>
              <li><a href="<?= Tmpl::url('/p/search?q=%23صحة') ?>"><span class="fa fa-heart"></span>صحة</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23لغات') ?>"><span class="fa fa-language"></span>لغات</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23سياحة') ?>"><span class="fa fa-compass"></span>سياحة</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23ثقافة') ?>"><span class="fa fa-quote-right"></span>ثقافة</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23تسوق') ?>"><span class="fa fa-shopping-cart"></span>تسوق</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23نقل') ?>"><span class="fa fa-car"></span>نقل</a></li>
              <li><a href="<?= Tmpl::url('/p/search?q=%23فنون') ?>"><span class="fa fa-film"></span>فنون</a></li>
            </ul>
          </div>
        </div>
        <div class="widget">
          <a class="ads-widget" href="#/ads/a339cdf1373ef73621768f201eb7239f">
            <span>إعلان</span>
          </a>
        </div>
      </div>
      <div id="content-main">


        <div id="main-list-wrapper">

          <?php if($args['blogs']): ?>
            <div class="blog blog-empty">
              <div>نتيجة البحث عن "<?= Tmpl::escape($args['search']) ?>"</div>
            </div>
            <?php foreach($args['blogs'] as $blog) : ?>

              <div class="blog" data-blog-id="<?= $blog['blog_id'] ?>">
                <a href="<?= APP_BASE_URL . '/' . $blog['account'] ?>">
                  <span class="blog-time" data-time="<?= $blog['blog_date'] ?>"></span>
                  <img src="<?= APP_BASE_URL ?>/static/avatar/avatar_<?= $blog['set_avatar']? $blog['user_id'] : 'default' ?>.png" alt="">
                  <div>
                    <p><?= Tmpl::escape($blog['name']) ?></p>
                    <span>@<?= Tmpl::escape($blog['account']) ?></span>
                  </div>
                </a>
                <p><?= Tmpl::norm($blog['text']) ?></p>

                <div class="blog-options">
                  <ul>
                    <li<?= $blog['comment_by_me']? ' class="comment-icon"' : ''?>><a href="javascript:;" class="fa fa-reply"></a><span><?= Tmpl::ar($blog['comment_times']) ?></span></li>
                    <li <?php if($blog['user_id'] != $_SESSION['user.id']): ?> data-reblog-id="<?= $blog['blog_id'] ?>" <?php else : ?> class="no-reblog-button" <?php endif?> <?= $blog['reblog_by_me']? ' class="reblog-icon"' : ''?>><a href="javascript:;" class="fa fa-share-alt"></a><span><?= Tmpl::ar($blog['reblog_times']) ?></span></li>
                    <li<?= $blog['favorite_by_me']? ' class="favorite-icon"' : ''?>><a href="javascript:;" class="fa fa-star"></a><span><?= Tmpl::ar($blog['favorite_times']) ?></span></li>
                    <li style="float: left; margin-left: 10px; width: auto" ><a href="javascript:;" class="fa fa-expand"></a></li>
                  </ul>
                </div>
              </div>
            <?php endforeach ?>
          <?php else : ?>
            <div class="blog blog-empty">
              <div>لم نجد شيء عمّا تبحث عنه.</div>
            </div>
          <?php endif ?>
        </div>

      </div>

    </div>
  </div>
<?php Tmpl::render('/footer.php', $args) ?>