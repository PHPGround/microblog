$(document).ready(function() {

  /**
   * Center the login form in the middle of the screen.
   */
  $(window).resize(function () {
    var form = $('.account-form-wrapper');

    form.css({
      'left': Math.round(($(window).width() - form.outerWidth()) / 2) + 'px',
      'top': Math.round(($(window).height() - form.outerHeight()) / 2) + 'px'
    });
  });

  $(window).trigger('resize');

  /**
   * Add event listener to button-signup and button-signin.
   */
  $('.account-form').click(function(event) {
    if(event.target == this)
      $(this).fadeOut(100);
  });

  $('.account-form-close').click(function(event) {
    $(this).parent().parent().fadeOut(100);
  });

  $('#button-signup').click(function(event) {
    $('#signup-form').fadeIn(100);
    $(window).trigger('resize');
  });

  $('.account-form-wrapper form input[name="account"]').keyup(function() {
    this.setCustomValidity('');

    if(!new XRegExp('^[\\p{L}\\d_]+$').test($(this).val()))
      this.setCustomValidity('اسم المستخدم يجب أن يحتوي على حروف فقط أو علامة _ بدون مسافات');
    if($(this).val().length > 5)
      this.setCustomValidity('يجب أن لايزد طول اسم الحساب عن 5 أحرف');
  });

  $('input[type="email"]').on('invalid', function(event) {
    this.setCustomValidity('');
    if(!event.target.validity.valid)
      event.target.setCustomValidity('البريد المدخل غير سليم');
  });
});