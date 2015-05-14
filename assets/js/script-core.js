var smiley = ["angry", "biggrin", "blink", "blush", "cool", "cry",
              "drool", "getlost", "grin", "happy", "kiss", "kissed",
              "laughing", "music", "poo", "pouty", "rolleyes", "sad",
              "shock", "shocked", "sick", "sideways", "sleep", "smile",
              "stfu", "teeth", "tongue", "wacko", "wink", "wrong", "yawn"];

$.fn.getCursorPosition = function () {
  var el = $(this).get(0);
  var pos = 0;
  if ('selectionStart' in el) {
    pos = el.selectionStart;
  } else if ('selection' in document) {
    el.focus();
    var Sel = document.selection.createRange();
    var SelLength = document.selection.createRange().text.length;
    Sel.moveStart('character', -el.value.length);
    pos = Sel.text.length - SelLength;
  }
  return pos;
}

$(document).ready(function () {

  $('#data-file-real').change(function(e) {
    var path = $(this).files;
    
    if(e.target.files[0].type === 'image/png')
      $('#data-file-dummy').attr('src', URL.createObjectURL(e.target.files[0]));
  });

  $('#data-file-dummy').click(function() {
    $('#data-file-real').click();
  });

  var new_password_verify = function() {
    $('.form button').first().attr('disabled', true);

    if($('#setting-new-password').val() == $('#setting-re-password').val() &&
      ($('#setting-new-password').val().length >= 5 || $('#setting-new-password').val().length == 0)) {
      $('.form button').first().attr('disabled', false);
    }
  }

  $('#setting-new-password').keyup(new_password_verify);
  $('#setting-re-password').keyup(new_password_verify);

  $('#new-blog').click(function(e) {
    if($('#post-blog')) {
      $('html, body').animate({ scrollTop: $('#post-blog').offset().top - $('#header-outer').outerHeight() - 10 }, 'fast');
      e.preventDefault();
    }
  });

  String.prototype.arDigits = function () {
    return this.replace(/[0-9]/g, function (d) {
      return ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'][+d];
    });
  };

  String.prototype.format = function () {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function (match, number) {
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
        ;
    });
  };

  Number.prototype.pad = function (size) {
    var s = String(this);
    while (s.length < (size || 2)) {
      s = '0' + s;
    }
    return s;
  }

  var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "/": '&#x2F;'
  };

  function escapeHtml(string) {
    return String(string).replace(/[&<>"\/]/g, function (s) {
      return entityMap[s];
    });
  }

  // [0] "لا عناصر"
  // [1] "عنصر واحد"
  // [2] "عنصران"
  // [3] "%d عناصر"
  // [4] "%d عنصرا"
  // [5] "%d عنصر"
  var plural = function (n, list, args) {
    var p = n == 0 ? 0 : n == 1 ? 1 : n == 2 ? 2
      : n % 100 >= 3 && n % 100 <= 10 ? 3 : n % 100 >= 11 ? 4 : 5;
    return list[p].format(args);
  }

  var MAX_BLOG_LENGTH = 144;

  var handle_blog_input = function (e) {
    if(e.currentTarget === undefined)
      return;
    var rem = MAX_BLOG_LENGTH - e.currentTarget.value.length;
    var msg = '';
    var obj = $('#remaining-characters');

    if (rem == MAX_BLOG_LENGTH || rem < 0) {
      $('#post-blog form button').attr('disabled', true);
    }
    else {
      $('#post-blog form button').attr('disabled', false);
    }

    if (rem >= 0) {
      var lst = [
        "لم يبقى شيء",
        "بقي حرف واحد",
        "بقي حرفين",
        "بقي {0} أحرف",
        "بقي {0} حرفاً",
        "بقي {0} حرف",
      ];
      msg = plural(rem, lst, rem.toString().arDigits());
      obj.css('color', 'inherit');
    } else {
      var lst = [
        "###",
        "تجاوزت الحد الأقصى بحرف واحد",
        "تجاوزت الحد الأقصى بحرفين",
        "تجاوزت الحد الأقصى بـ{0} أحرف",
        "تجاوزت الحد الأقصى بـ{0} حرفاً",
        "تجاوزت الحد الأقصى بـ{0} حرف",
      ];
      msg = plural(-rem, lst, (-rem).toString().arDigits());
      obj.css('color', 'red');
    }

    obj.text(msg);
  };

  handle_blog_input({'currentTarget': $('#blogarea-wrapper > textarea')[0]});

  // Add blog text area even listener.
  // remaining-characters
  $('#blogarea-wrapper > textarea').keyup(handle_blog_input);

  $('#smile-button').click(function () {
    var target = $('#smile-list');
    if(!target.is(':animated'))
      target.slideToggle('fast');
  });

  $('[data-icon]').click(function(e) {

    var entry = $('#blogarea-entry');

    var content = entry.val();
    var position = entry.getCursorPosition();
    var newContent = content.substr(0, position) + '[' + $(this).attr('data-icon') + ']' + content.substr(position);
    entry.val(newContent);
    entry.keyup();
  });

  $('#follow-user, #followed-user').click(function() {
    var user_id = $(this).attr('data-user-id');

    $.post(Microblog.base + '/ajax/follow', {'user_id' : user_id}, function(data) {
      if(data['state']) {
        $('#follow-user').hide();
        $('#followed-user').show();
      }
      else {
        $('#follow-user').show();
        $('#followed-user').hide();
      }
    }).fail(function (xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
      console.log(errorThrown);
    });
  });


  $('[data-reblog-id]').click(function() {
    var blog_id = $(this).attr('data-reblog-id');
    var obj = $(this);

    $.post(Microblog.base + '/ajax/reblog', {'blog_id' : blog_id}, function(data) {
      console.log(data);
      if(obj.hasClass('reblog-icon')) {
        obj.removeClass('reblog-icon');
      }
      else {
        obj.addClass('reblog-icon');
      }
    }).fail(function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    });;
  });

  $('#send-post').click(function () {
    $('#post-blog form button').attr('disabled', true);
    $('#post-blog form button').attr('disabled', true);

    var blog = $('#post-blog textarea').val();

    $.post(Microblog.base + '/ajax/blog/new', {'blog': blog}, function (data) {

      var blog = escapeHtml($('#post-blog textarea').val());

      for(var i = 0; i < smiley.length ; i++) {
        blog = blog.replace('['+smiley[i]+']', '<img src="' + Microblog.base + '/assets/image/smiley/'+smiley[i]+'.png" alt="'+smiley[i]+'">');
      }

      if ($('#main-list-wrapper > div').first().hasClass('blog-empty')) {
        $('#main-list-wrapper > div').first().remove();
      }

      console.log(data);

      blog = XRegExp.replace(blog, XRegExp('#(?<hash>[\\d\\p{L}_]{1,144})+'), '<a href="' + Microblog.base + '/p/search?q=%23${hash}">#${hash}</a>', 'all');
      var t = new Date();
      var time = '{0}-{1}-{2} {3}:{4}:{5}'.format(t.getFullYear(), (t.getMonth() + 1).pad(), t.getDate().pad(), t.getHours().pad(), t.getMinutes().pad(), t.getSeconds().pad());
      $('#main-list-wrapper').prepend("<div class=\"blog\"><a href=\"#\"><span class=\"blog-time\" data-time=\"" + time + "\">الآن</span><img src=\"static/avatar/avatar_" + (Microblog.user_set_avatar ? Microblog.user_id : 'default') + ".png\" alt=\"\"><div><p>" + Microblog.user_name + "</p><span>@" + Microblog.user_account + "</span></div></a><p>" + blog + "</p><div class=\"blog-options\"><ul><li><a href=\"javascript:;\" class=\"fa fa-reply\"></a><span>٠</span></li><li><a href=\"javascript:;\" class=\"fa fa-share-alt\"></a><span>٠</span></li><li><a href=\"javascript:;\" class=\"fa fa-star\"></a><span>٠</span></li><li style=\"float: left; margin-left: 10px; width: auto\" ><a href=\"javascript:;\" class=\"fa fa-expand\"></a></li></ul></div></div>");

      $('#post-blog form textarea').attr('disabled', false);
      $('#post-blog form button').attr('disabled', false);
      var blogs_counter = $('[data-state-blogs]').first();
      var update = parseInt(blogs_counter.attr('data-state-blogs')) + 1;

      blogs_counter.attr('data-state-blogs', update);
      blogs_counter.text(update.toString().arDigits());
      $('#blogarea-entry').val('');
    }).fail(function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    });
    ;
  });

  function timeDiffReadable(time) {

    var t = time.split(/[- :]/);

    var milliseconds = Math.abs(new Date() - new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));

    var temp = Math.floor(milliseconds / 1000);
    var years = Math.floor(temp / 31536000);
    if (years) {
      return plural(years, ["###", "سنة", "سنتين", "{0} سنوات", "{0} سنةً", "{0} سنة"], years.toString().arDigits());
    }

    var days = Math.floor((temp %= 31536000) / 86400);
    if (days) {
      return plural(days, ["###", "يوم", "يومين", "{0} أيام", "{0} يوماً", "{0} يوم"], days.toString().arDigits());
    }
    var hours = Math.floor((temp %= 86400) / 3600);
    if (hours) {
      return plural(hours, ["###", "ساعة", "ساعتين", "{0} ساعات", "{0} ساعة", "{0} ساعة"], hours.toString().arDigits());
    }
    var minutes = Math.floor((temp %= 3600) / 60);
    if (minutes) {
      return plural(minutes, ["###", "دقيقة", "دقيقتين", "{0} دقائق", "{0} دقيقة", "{0} دقيقة"], minutes.toString().arDigits());
    }
    var seconds = temp % 60;
    if (seconds) {
      return plural(seconds, ["###", "ثانية", "ثانيتين", "{0} ثواني", "{0} ثانية", "{0} ثانية"], seconds.toString().arDigits());
    }
    return 'الآن';
  };

  $('[data-time]').each(function () {
    $(this).text(timeDiffReadable($(this).attr('data-time')));
  });

  window.setInterval(function () {
    $('[data-time]').each(function () {
      $(this).text(timeDiffReadable($(this).attr('data-time')));
    });
  }, 15000);

});