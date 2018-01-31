$(document).ready(function(){

  // APPLY UNIFORM STYLES
  $("input:file, textarea, select, .search select, .filters select, .add_item form select, .modify_profile select").uniform({fileDefaultText: fileDefaultText,fileBtnText: fileBtnText});
  $("button, .filters button, #comments form button, #contact form button, .search button, .user_forms form button, .add_item form button, .modify_profile button").not("[id^=prm_]").not("[id^=pub_]").not("[id^=wlt_]").not("[id^=hlt_]").not("[id^=top_]").uniform(); 


  // QUICK VIEW FUNCTIONALITY
  $(document).on('click', '.orange-but', function(e){
    e.preventDefault();
    var url = this.href;
    var anchor = '';

    if (url.indexOf('#') != -1) {
      anchor = url.substring(url.indexOf('#'), url.length);
      url = url.substring(0, url.indexOf('#'));
    }

    if (url.indexOf('?') != -1) {
      url += '&';
    } else {
      url += '?';
    }

    if (!!$.prototype.fancybox) {
      $.fancybox({
        'padding':  0,
        'width':    1087,
        'height':   610,
        'wrapCSS':  'quick-view',
        'type':     'iframe',
        'href':     url + 'content_only=1' + anchor
     });
    }
  });

  // MAKE SURE LINKS IN FANCYBOX WILL WORK
  $('#content_only a:not(#visit)').click(function(e) {
    return false;
  });

  $('#content_only a#visit').click(function(e) {
    parent.$.fancybox.close();
  });



  // WHEN ANY LOCATION IS CHOOSEN, REFRESH COOKIES
  $('#countryId, #regionId, #cityId, #sRegion-side, #sCity-side').change(function(){
    $('.search #sCountry, .search #sRegion, .search #sCity').val('');
    $('#cookie-action-side').val('done');
  });

  // RECAPTCHA WIDTH FIX
  var wi = $('#recaptcha_image').width();
  $('#recaptcha_image, #recaptcha_image img').css('max-width', wi + 'px');


  // MORE INFO HANDLER - LISTINGS
  $('.desc-more').click(function(event){
    event.preventDefault();
    $("html, body").animate({ scrollTop: $('#more-info').offset().top - 20 }, 1000);

    $('#more-info .elem').removeClass('selected');
    $('#more-info #more-info-desc').addClass('selected');
    $('#more-info .elem-more').hide();
    $('#more-info #tab1').fadeIn();
  });

  $('.seller-contact').click(function(event){
    event.preventDefault();
    $("html, body").animate({ scrollTop: $('#more-info').offset().top - 20 }, 1000);

    $('#more-info .elem').removeClass('selected');
    $('#more-info #more-info-contact').addClass('selected');
    $('#more-info .elem-more').hide();
    $('#more-info #tab3').fadeIn();
  });


  // LISTING IMG CLICK - CHANGE SOURCE
  $('#listing .small-img').click(function(){
    // With fade effect
    var small_img_wrapper = $(this).parent().parent().parent();
    var small_img = $(this).find('img');
    var small_img_src = $(this).find('img').prop('src');
    var big_img = $(this).parent().parent().parent().siblings('#big-img').find('img');

    big_img.fadeOut(200, function() { 
      big_img.attr('src', small_img_src);
    }).fadeIn(200);

    $(this).parent().parent().parent().siblings('#big-img').attr('href', small_img_src);

    small_img_wrapper.find('.selected').removeClass('selected');
    small_img.parent().addClass('selected');
  });


  // SEARCH LIST IMG CLICK - CHANGE SOURCE
  $('.small-img').click(function(){
    // Without fade effect
    //$(this).parent().siblings('.big-img').find('img').attr('src', $(this).find('img').prop('src'));

    // With fade effect
    var small_img_wrapper = $(this).parent();
    var small_img = $(this).find('img');
    var small_img_src = $(this).find('img').prop('src');
    var big_img = $(this).parent().siblings('.big-img').find('img');

    big_img.fadeOut(200, function() { 
      big_img.attr('src', small_img_src);
    }).fadeIn(200);

    small_img_wrapper.find('img').removeClass('selected');
    small_img.addClass('selected');
  });


  // OPEN/CLOSE FUNCTIONALITIES
  if($(document).width() > 480) {
    $('#lang-open-box, .top-info').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.mb-tool-wrap').stop(true,true).fadeIn(200);
      $(this).find('.mb-tool-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
    }, function(){
      $(this).find('.mb-tool-wrap').stop( true, true ).delay(700).fadeOut(200);
      $(this).find('.mb-tool-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(700).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('.top-my').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.my-wrap').stop(true,true).fadeIn(200);
      $(this).find('.my-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
      $(this).addClass('hovered');

    }, function(){
      $(this).find('.my-wrap').stop( true, true ).delay(700).fadeOut(200);
      $(this).find('.my-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(700).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('#search-sort .sort-title').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('#sort-wrap').stop(true,true).fadeIn(200);
      $(this).find('#sort-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
    }, function(){
      $(this).find('#sort-wrap').stop( true, true ).delay(700).fadeOut(200);
      $(this).find('#sort-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(700).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('#report').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.cont-wrap').stop(true,true).fadeIn(200);
      $(this).find('.cont-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
      $(this).addClass('hovered');

    }, function(){
      $(this).find('.cont-wrap').stop( true, true ).delay(700).fadeOut(200);
      $(this).find('.cont-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(700).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });
  } else {
    $('#lang-open-box, .top-info').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.mb-tool-wrap').stop(true,true).fadeIn(0);
      $(this).find('.mb-tool-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
    }, function(){
      $(this).find('.mb-tool-wrap').stop( true, true ).delay(0).fadeOut(0);
      $(this).find('.mb-tool-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(0).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('.top-my').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.my-wrap').stop(true,true).fadeIn(0);
      $(this).find('.my-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
      $(this).addClass('hovered');

    }, function(){
      $(this).find('.my-wrap').stop( true, true ).delay(0).fadeOut(0);
      $(this).find('.my-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(0).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('#search-sort .sort-title').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('#sort-wrap').stop(true,true).fadeIn(0);
      $(this).find('#sort-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
    }, function(){
      $(this).find('#sort-wrap').stop( true, true ).delay(0).fadeOut(0);
      $(this).find('#sort-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(0).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    $('#report').hover(function(){
      $(this).stop( true, true ).addClass('hovered');
      $(this).find('.cont-wrap').stop(true,true).fadeIn(0);
      $(this).find('.cont-wrap').css('overflow', 'visible');
      $(this).css('z-index', '99999');
      $(this).addClass('hovered');

    }, function(){
      $(this).find('.cont-wrap').stop( true, true ).delay(0).fadeOut(0);
      $(this).find('.cont-wrap').css('overflow', 'visible');
      $(this).css('z-index', '9999');

      $(this).delay(0).queue(function() { $(this).removeClass('hovered'); $(this).dequeue(); });
    });

    // ON MOBILE DEVICES WHEN CLICK ON HOMEPAGE CATEGORY, SCROLL TO SUBCATEGORY LIST
    $("#home-cat .top li a").click(function(event) {
      if(event.originalEvent !== undefined) {
        $('html, body').animate({
          scrollTop: $('.cat-box').offset().top
        }, 1000);
      }
    });
  }



  // FANCY BOX FUNCTIONALITY
  $("a[rel=image_group]").fancybox({
    openEffect : 'none',
    closeEffect : 'none',
    nextEffect : 'fade',
    prevEffect : 'fade',
    loop : false,
    helpers : { title : {type : 'inside'} }
  });


  // SEARCH PAGE - FILTER BY ALL / COMPANY / PERSONAL
  $('.user-company-change div').click(function() {
    if($(this).hasClass('all')) {
      $('input#sCompany').val('');
    }

    if($(this).hasClass('individual')) {
      $('input#sCompany').val(0);
    }

    if($(this).hasClass('company')) {
      $('input#sCompany').val(1);
    }

    $('input#cookie-action').val('done');
    $('form.search').submit();
  });


  // RICHEDIT PLUGIN FIX FOR RESPONSIVE DESIGN AND ONE MORE FIX
  if($('.mceLayout').length) {
    $('.mceLayout').css('width', '100%');
  }

  $('a.MCtooltip').click(function(){
    return false;
  });

  $('a.MCtooltip').contents().filter(function(){ return this.nodeType === 3; }).remove();

  // IDTABS FUNCTIONALITY IN HOMEPAGE CATEGORY SHOWCASE
  $("#home-cat").idTabs(function(id,list,set){ 
    $("a",set).removeClass("selected").filter("[href='"+id+"']",set).addClass("selected"); 
    for(i in list) $(list[i]).hide();
    $(id).fadeIn(); 
    return false; 
  });


  // IDTABS FUNCTIONALITY ON LISTINGS PAGE - MORE INFORMATION
  $("#more-info").idTabs(function(id,list,set){ 
    $("a",set).removeClass("selected").filter("[href='"+id+"']",set).addClass("selected"); 
    for(i in list) $(list[i]).hide();
    $(id).fadeIn(); 
    return false; 
  });


  // OPEN/CLOSE FUNCTIONALITY IN HOMEPAGE CATEGORY SIDEBAR
  $('.grower').click(function(){
    $(this).toggleClass('open close');
    $(this).parent().parent('li').toggleClass('active-wrap');
    $(this).parent().find('a').toggleClass('hide-after');
    $(this).parent('span').parent('li').children('ul').slideToggle(200);
  });


  // REMOVE PLUS/MINUS IN EMPTY BOXES - HOMEPAGE CATEGORY SIDEBAR
  $('.region').each(function(){
    if( $(this).find('ul').children().length == 0 ) {
     $(this).find('ul').remove();
     $(this).find('span>.grower').remove();
    }
  });

  $("#tip_close2").click(function(){
    $("#flashmessage").slideUp(200);
  });


  // INFO ICON IN HOMEPAGE SIDEBAR - LOCATION DETECTOR 
  $("#side-position .info").click(function(){
    if(!$(this).hasClass('open')) {
      $(this).addClass('open');
      $(this).parent().append('<span class="after"><i class="fa fa-arrow-right"></i>' + $(this).attr('title') + '</span>');
    }

    $("#side-position .after").slideToggle(300);
  });


  // ADD DELIMITER TO H3 TAG IN SEARCH SIDEBAR, BECAUSE H3 IS ABSOLUTE
  $('#sidebar-search fieldset h3').before('<div class="h3-side-absolute"></div>');


  // JAVASCRIPT FOR CATEGORIES MENU IN SEARCH SIDEBAR
  $('#subcats .more').click(function(){
    var rel_old = $(this).attr('rel');
    $(this).attr('rel', $(this).text());
    $(this).text(rel_old);

    if($(this).hasClass('open')) {
      $(this).siblings('ul.small').slideUp(300);
      $(this).siblings('ul.big').delay(350).slideDown(300);
    } else {
      $(this).siblings('ul.big').slideUp(300)
      $(this).siblings('ul.small').delay(350).slideDown(300);
    }

    $(this).toggleClass('open', 'close');
    return false;
  });


  // PAGINATION FONTAWESOME FIX FOR NEXT, LAST, PREV AND FIRST
  $('.searchPaginationNext').html('<i class="fa fa-angle-right"></i>');
  $('.searchPaginationLast').html('<i class="fa fa-angle-double-right"></i>');
  $('.searchPaginationPrev').html('<i class="fa fa-angle-left"></i>');
  $('.searchPaginationFirst').html('<i class="fa fa-angle-double-left"></i>');


  // USER MENU HIGHLIGHT ACTIVE
  var url = window.location.toString();

  $('.user_account #sidebar li a').each(function(){
    var myHref= $(this).attr('href');
    if( url == myHref) {
      $(this).parent('li').addClass('active');
    }
  });

  $('h3.head #show-hide').click(function(){
    if($(this).hasClass('opened')) {
      $(this).parent().parent().find('.search-wrap').slideUp(300);
    } else {
      $(this).parent().parent().find('.search-wrap').slideDown(300);
    }

    $(this).toggleClass('opened', 'closed');
  });

  $('#menu h3 #show-hide').click(function(){
    if($(this).hasClass('opened')) {
      $(this).parent().parent().find('.menu-wrap').slideUp(300);
    } else {
      $(this).parent().parent().find('.menu-wrap').slideDown(300);
    }

    $(this).toggleClass('opened', 'closed');
  });

});	