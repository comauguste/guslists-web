function scrollFixListings(event, targeted, scrolled, items, position) {
  var doc_width = $(document).width();
  var items = 4;

  if(doc_width > 900) {
    items = 4;
  } else if (doc_width > 320 && doc_width <= 900) {
    items = 3;
  } else {
    items = 2;
  }

  var total = $('#pictures .img-bar .small-img').length;

  var leftArrow = position == 0 ? true : false;
  var rightArrow = position + items >= total ? true : false;
	
  $('#scroll.prev').find(leftArrow ? 'active' : 'inactive').show();
  $('#scroll.prev').find(leftArrow ? 'inactive' : 'active').hide();

  if(leftArrow) { 
    $('#scroll.prev .active').hide();
    $('#scroll.prev .inactive').show();
  } else {
    $('#scroll.prev .active').show();
    $('#scroll.prev .inactive').hide();
  }

  if(rightArrow) { 
    $('#scroll.next .active').hide();
    $('#scroll.next .inactive').show();
  } else {
    $('#scroll.next .active').show();
    $('#scroll.next .inactive').hide();
  }

  if(total <= items) {
    $('#scroll.prev .active, #scroll.next .active').hide();
    $('#scroll.prev .inactive, #scroll.next .inactive').show();
  }

  return true;
}

$(document).ready(function(){
  var doc_width = $(document).width();

  var v_step = 2;
  if(doc_width > 900) {
    v_step = 2;
  } else {
    v_step = 1;
  }

  $('#pictures .img-bar').serialScroll({
    items:'.small-img',
    prev:'#scroll.prev .active',
    next:'#scroll.next .active',
    onBefore: scrollFixListings,
    axis:'x',
    stop:true,
    offset:0,
    duration:300,
    step: v_step,
    lazy: false,
    lock: true,
    force:false,
    cycle:false
  });

  $('#pictures .img-bar').trigger( 'goto', 0 );

  var b_width = $('#pictures .img-bar').width();
  var total = $('#pictures .img-bar .small-img').length;
  var items = 4;

  if(doc_width > 900) {
    items = 4;
  } else if (doc_width > 320 && doc_width <= 900) {
    items = 3;
  } else {
    items = 2;
  }

  $('#pictures .img-bar .small-img').css('width', ((100 - 1.6*items)/items)*b_width/100 + 'px');
  $('#pictures .img-bar .small-img').css('margin-left', 0.8*b_width/100 + 'px');
  $('#pictures .img-bar .small-img').css('margin-right', 0.8*b_width/100 + 'px');
  $('#pictures .img-bar .wrap').css('width', (b_width/items)*total + 'px');
});