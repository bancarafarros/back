
$(document).ready(function () {
    $('.show-password').click(function(e) {
        e.preventDefault();
        $('[name=password]').attr('type', 'text');
        $('.show-password').addClass('d-none');
        $('.hide-password').removeClass('d-none');
      })

      $('.hide-password').click(function(e) {
        e.preventDefault();
        $('[name=password]').attr('type', 'password');
        $('.show-password').removeClass('d-none');
        $('.hide-password').addClass('d-none');
      })
});