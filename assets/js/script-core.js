$(document).ready(function() {

  /**
   * Center the login form in the middle of the screen.
   */
  var loginFormResizeCallback = function () {
    var loginFormWrapper = $('#login-form-wrapper');

    loginFormWrapper.css({
      'left': Math.floor(($(window).width() - loginFormWrapper.outerWidth()) / 2) + 'px',
      'top': Math.floor(($(window).height() - loginFormWrapper.outerHeight()) / 2) + 'px'
    });
  };

  loginFormResizeCallback(); $(window).resize(loginFormResizeCallback);

  /**
   * Add event listener to button-signup and button-signin.
   */
  $('#login-form').click(function (event) {
    if(event.target == this)
      $(this).hide();
  });
  
  $('#button-signup').click(function (event) {
    event.stopPropagation();
    $("#login-form").css({'display' : 'block'});
  });

  $('#button-signin').click(function (event) {
    event.stopPropagation();
    $("#login-form").css({'display' : 'block'});
  });

  $('#close-icon').click(function (event) {
    $('#login-form').hide();
  })
});