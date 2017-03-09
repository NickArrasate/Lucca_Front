$(document).ready(function(){
  //Handles Collapse state of dropdown menus if screen size changes
  $(window).resize(function() {
    var x = document.getElementsByClassName('collapse')
      if ($(window).width() > 768) {
          $(x).removeClass('in');
      } else {
        return
      }
  }).resize(); // trigger resize event initially

})
