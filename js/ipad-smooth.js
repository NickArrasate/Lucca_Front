$(document).ready(function(){
  console.log('hi');
  var deviceAgent = navigator.userAgent.toLowerCase();
  console.log(deviceAgent, 'hello');
  var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
  if(agentID){
    $('body').css('-webkit-overflow-scrolling', 'touch');
  }
});
