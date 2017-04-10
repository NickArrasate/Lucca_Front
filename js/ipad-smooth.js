$(document).ready(function(){
  var deviceAgent = navigator.userAgent.toLowerCase();
  var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
  if(agentID){
    $('body').css('-webkit-overflow-scrolling', 'touch');
  }
});
