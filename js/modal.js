//controls for 'media' page modal
var selector = '.media-item';
$(selector).on('click', function() {
  $('#modal-fullscreen').modal('toggle');
  $('#mediaCarousel').carousel($(this).index());
});
$(".modal-fullscreen").on('show.bs.modal', function () {
  setTimeout( function() {
    $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
  }, 0);
});
$(".modal-fullscreen").on('hidden.bs.modal', function () {
  $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
});
