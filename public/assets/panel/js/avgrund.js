function    closeAvgrundModal() {
    window.location.reload();
    // $('#deleteFarmer').avgrund('toggel'); // Assuming Avgrund provides a 'close' method
}

(function($) {
  'use strict';
  $(function() {
    $('#show').avgrund({
      height: 500,
      holderClass: 'custom',
      showClose: true,
      showCloseText: 'x',
      onBlurContainer: '.container-scroller',
      template: '<p>So implement your design and place content here! If you want to close modal, please hit "Esc", click somewhere on the screen or use special button.</p>' +
        '<div>' +
        '<a href="http://twitter.com/voronianski" target="_blank" class="twitter btn btn-twitter btn-block">Twitter</a>' +
        '<a href="http://dribbble.com/voronianski" target="_blank" class="dribble btn btn-dribbble btn-block">Dribbble</a>' +
        '</div>' +
        '<div class="text-center mt-4">' +
        '<a href="#" target="_blank" class="btn btn-success mr-2">Great!</a>' +
        '<a href="#" target="_blank" class="btn btn-light">Cancel</a>' +
        '</div>'
    });
    $('#deleteFarmer').avgrund({
        height: 300,
        holderClass: 'custom',
        showClose: true,
        showCloseText: 'x',
        onBlurContainer: '.container-scroller',
        template: '<br><p class="text-center">Are your sure? </p><p class="mt-3">You want  to delete this farmer?</p>' +
          '<div class="text-center mt-4">' +
          '<a href="#"  class="btn btn-success mr-2">Confirm!</a>' +
          '<button onclick="closeAvgrundModal();" class="btn btn-light">Cancel</button>' +
          '</div>'
      });
  })
})(jQuery);
