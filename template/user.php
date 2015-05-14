<?php Tmpl::render('/header.php', $args) ?>
  <div id="body-outer">

    <div id="current-user">
      <div id="current-meta">
        <a href="<?= APP_BASE_URL . '/' . $args['page']['account'] ?>">
          <img id="user-avatar" src="<?= APP_BASE_URL ?>/static/avatar/avatar_<?= $args['page']['set_avatar']? $args['page']['id'] : 'default' ?>.png" alt="???-user-???">
          <div id="user-name"><?= Tmpl::escape($args['page']['name']) ?></div>
          <div id="user-account">@<?= Tmpl::escape($args['page']['account']) ?></div>
        </a>
      </div>
<?php if($_SESSION['user.id'] != $args['page']['id']): ?>

    <button id="followed-user"<?=$args['page']['followed_by_me']? '': ' style="display:none"' ?> data-user-id="<?= $args['page']['id'] ?>">
      <span id="button-layer-1">مُتابع</span>
      <span id="button-layer-2">إلغاء المتابعة</span>
    </button>

  <button id="follow-user"<?=$args['page']['followed_by_me']? ' style="display:none"' : '' ?> data-user-id="<?= $args['page']['id'] ?>">تابع</button>

<?php endif ?>
    </div>

    <div id="body-inner">

      <div id="dashboard">
        <div class="widget">
          <div id="user-profile">
            <ul>
              <li>
                <a href="#/user">
                  <span>تدوينات</span>
                  <span data-state-blogs="<?=$args['state']['blogs']?>"><?= Tmpl::ar($args['page']['blogs']) ?></span>
                </a>
              </li>
              <li>
                <a href="#/user/followers">
                  <span>متابعين</span>
                  <span><?= Tmpl::ar($args['page']['followers']) ?></span>
                </a>
              </li>
              <li>
                <a href="#/user/following">
                  <span>يتابع</span>
                  <span><?= Tmpl::ar($args['page']['following']) ?></span>
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
    <img src="static/avatar/avatar_<?= $user['set_avatar'] ? $user['id'] : 'default' ?>.png" alt="">
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

<?php if($_SESSION['user.id'] == $args['page']['id']) : ?>
        <div id="post-blog">
          <form action="javascript:;" method="post">
            <p>انشر تدوينة جديدة!</p>
            <span id="remaining-characters" style="color: inherit;">بقي ١٤٤ حرفاً</span>
            <div id="blogarea-wrapper">
              <textarea name="blog" id="blogarea-entry"></textarea>
            </div>
            <ul>
              <li id="smile-button" style="color: #fab501;"><span class="fa fa-smile-o"></span>ابتسامة</li>
              <li style="color: #71ae23;"><span class="fa fa-image"></span>صورة</li>
            </ul>
            <button id="send-post" disabled="disabled">دوّن!</button>
          </form>
          <div id="smile-list">
            <ul>
              <li data-icon="angry"><img src="<?= Tmpl::url('/assets/image/smiley/angry.png') ?>" alt="angry"></li>
              <li data-icon="biggrin"><img src="<?= Tmpl::url('/assets/image/smiley/biggrin.png') ?>" alt="biggrin"></li>
              <li data-icon="blink"><img src="<?= Tmpl::url('/assets/image/smiley/blink.png') ?>" alt="blink"></li>
              <li data-icon="blush"><img src="<?= Tmpl::url('/assets/image/smiley/blush.png') ?>" alt="blush"></li>
              <li data-icon="cool"><img src="<?= Tmpl::url('/assets/image/smiley/cool.png') ?>" alt="cool"></li>
              <li data-icon="cry"><img src="<?= Tmpl::url('/assets/image/smiley/cry.png') ?>" alt="cry"></li>
              <li data-icon="drool"><img src="<?= Tmpl::url('/assets/image/smiley/drool.png') ?>" alt="drool"></li>
              <li data-icon="getlost"><img src="<?= Tmpl::url('/assets/image/smiley/getlost.png') ?>" alt="getlost"></li>
              <li data-icon="grin"><img src="<?= Tmpl::url('/assets/image/smiley/grin.png') ?>" alt="grin"></li>
              <li data-icon="happy"><img src="<?= Tmpl::url('/assets/image/smiley/happy.png') ?>" alt="happy"></li>
              <li data-icon="kiss"><img src="<?= Tmpl::url('/assets/image/smiley/kiss.png') ?>" alt="kiss"></li>
              <li data-icon="kissed"><img src="<?= Tmpl::url('/assets/image/smiley/kissed.png') ?>" alt="kissed"></li>
              <li data-icon="laughing"><img src="<?= Tmpl::url('/assets/image/smiley/laughing.png') ?>" alt="laughing"></li>
              <li data-icon="music"><img src="<?= Tmpl::url('/assets/image/smiley/music.png') ?>" alt="music"></li>
              <li data-icon="poo"><img src="<?= Tmpl::url('/assets/image/smiley/poo.png') ?>" alt="poo"></li>
              <li data-icon="pouty"><img src="<?= Tmpl::url('/assets/image/smiley/pouty.png') ?>" alt="pouty"></li>
              <li data-icon="rolleyes"><img src="<?= Tmpl::url('/assets/image/smiley/rolleyes.png') ?>" alt="rolleyes"></li>
              <li data-icon="sad"><img src="<?= Tmpl::url('/assets/image/smiley/sad.png') ?>" alt="sad"></li>
              <li data-icon="shock"><img src="<?= Tmpl::url('/assets/image/smiley/shock.png') ?>" alt="shock"></li>
              <li data-icon="shocked"><img src="<?= Tmpl::url('/assets/image/smiley/shocked.png') ?>" alt="shocked"></li>
              <li data-icon="sick"><img src="<?= Tmpl::url('/assets/image/smiley/sick.png') ?>" alt="sick"></li>
              <li data-icon="sideways"><img src="<?= Tmpl::url('/assets/image/smiley/sideways.png') ?>" alt="sideways"></li>
              <li data-icon="sleep"><img src="<?= Tmpl::url('/assets/image/smiley/sleep.png') ?>" alt="sleep"></li>
              <li data-icon="smile"><img src="<?= Tmpl::url('/assets/image/smiley/smile.png') ?>" alt="smile"></li>
              <li data-icon="stfu"><img src="<?= Tmpl::url('/assets/image/smiley/stfu.png') ?>" alt="stfu"></li>
              <li data-icon="teeth"><img src="<?= Tmpl::url('/assets/image/smiley/teeth.png') ?>" alt="teeth"></li>
              <li data-icon="tongue"><img src="<?= Tmpl::url('/assets/image/smiley/tongue.png') ?>" alt="tongue"></li>
              <li data-icon="wacko"><img src="<?= Tmpl::url('/assets/image/smiley/wacko.png') ?>" alt="wacko"></li>
              <li data-icon="wink"><img src="<?= Tmpl::url('/assets/image/smiley/wink.png') ?>" alt="wink"></li>
              <li data-icon="wrong"><img src="<?= Tmpl::url('/assets/image/smiley/wrong.png') ?>" alt="wrong"></li>
              <li data-icon="yawn"><img src="<?= Tmpl::url('/assets/image/smiley/yawn.png') ?>" alt="yawn"></li>
            </ul>
          </div>
        </div>
<?php endif ?>
        <div id="main-list-wrapper">

<?php if($args['blogs']): ?>
<?php foreach($args['blogs'] as $blog) : ?>

          <div class="blog" data-blog-id="<?= $blog['blog_id'] ?>">
            <a href="<?= APP_BASE_URL . '/' . $blog['account'] ?>">
              <span class="blog-time" data-time="<?= $blog['blog_date'] ?>"></span>
              <img src="static/avatar/avatar_<?= $blog['set_avatar']? $blog['user_id'] : 'default' ?>.png" alt="">
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
            <div>لم تُنشر أي تدوينة بعد!</div>
          </div>
<?php endif ?>
        </div>

      </div>

    </div>
  </div>
<?php Tmpl::render('/footer.php', $args) ?>