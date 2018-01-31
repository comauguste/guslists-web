function scrollFix(event, targeted, scrolled, items, position) {
  var items = 0;
  var total = $('#prem-slider .simple-prod').length;
  var doc_width = $(document).width();
  
  if($('#prem-box').hasClass('has_img')) {
    if(doc_width > 900) {
      items = 3;
    } else if (doc_width > 690 && doc_width <= 900) {
      items = 3;
    } else if (doc_width > 320 && doc_width <= 690) {
      items = 2;
    } else {
      items = 1;
    }  } else {
    if(doc_width > 900) {
      items = 4;
    } else if (doc_width > 690 && doc_width <= 900) {
      items = 4;
    } else if (doc_width > 480 && doc_width <= 690) {
      items = 3;
    } else if (doc_width > 320 && doc_width <= 480) {
      items = 2;
    } else {
      items = 1;
    }
  }

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
    $('#scroll.prev, #scroll.next').css('display', 'none');
    $('#prem-box').addClass('not_full');
  }

  return true;
}

$(document).ready(function(){
  var v_step = 2;
  var doc_width = $(document).width();

  if(doc_width > 900) {
    v_step = 2;
  } else {
    v_step = 1;
  }

  $('#prem-slider.block').serialScroll({
    items:'div.simple-prod',
    prev:'#scroll.prev .active',
    next:'#scroll.next .active',
    onBefore: scrollFix,
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

  $('#prem-slider.block').trigger( 'goto', 0 );

  var b_width = $('#prem-slider.block').width();
  var items = 0;
  var total = $('#prem-slider .simple-prod').length;

  if($('#prem-box').hasClass('has_img')) {
    if(doc_width > 900) {
      items = 3;
    } else if (doc_width > 690 && doc_width <= 900) {
      items = 3;
    } else if (doc_width > 320 && doc_width <= 690) {
      items = 2;
    } else {
      items = 1;
    }  } else {
    if(doc_width > 900) {
      items = 4;
    } else if (doc_width > 690 && doc_width <= 900) {
      items = 4;
    } else if (doc_width > 480 && doc_width <= 690) {
      items = 3;
    } else if (doc_width > 320 && doc_width <= 480) {
      items = 2;
    } else {
      items = 1;
    }
  }

  $('#prem-box .simple-prod').css('width', ((100 - 2*items)/items)*b_width/100 + 'px');
  $('#prem-box .simple-prod').css('margin-left', 1*b_width/100 + 'px');
  $('#prem-box .simple-prod').css('margin-right', 1*b_width/100 + 'px');
  $('#prem-box .wrap').css('width', (b_width/items)*total + 'px');
});