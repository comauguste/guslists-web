<?php
  //$sQuery = osc_get_preference('keyword_placeholder', 'patricia_theme');
  $sQuery = __('Search in', 'patricia') . ' ' . osc_total_active_items() . ' ' .  __('listings', 'patricia');

  if(osc_count_countries() > 1) { $show_country = true; } else { $show_country = false; }
?>

<div class="scroller">
  <form action="<?php echo osc_base_url(true); ?>" method="get" class="search nocsrf" >
  <?php if($show_country) { ?><input type="hidden" name="sCountry<?php echo radius_installed(); ?>" id="sCountry" value="<?php echo Params::getParam('sCountry' . radius_installed());?>" /><?php } ?>
  <input type="hidden" name="sRegion<?php echo radius_installed(); ?>" id="sRegion" value="<?php echo Params::getParam('sRegion' . radius_installed());?>" />
  <input type="hidden" name="sCity<?php echo radius_installed(); ?>" id="sCity" value="<?php echo Params::getParam('sCity' . radius_installed());?>" />
  <input type="hidden" name="page" value="search" />
  <input type="hidden" name="cookie-action" id="cookie-action" value="" />
  <input type="hidden" name="sCompany" class="sCompany" id="sCompany" value="<?php echo Params::getParam('sCompany');?>" />
  <input type="hidden" name="sShowAs" class="sShowAs" id="sShowAs" value="<?php echo Params::getParam('sShowAs');?>" />

  <fieldset class="main">
    <input type="text" name="sPattern"  id="query" placeholder="<?php echo osc_esc_html($sQuery); ?>" value="<?php if(Params::getParam('sPattern') <> '') { echo Params::getParam('sPattern'); } ?>" />
    <?php  if ( osc_count_categories() ) { ?>
      <?php mb_categories_select('sCategory', Params::getParam('sCategory'), __('Select a category', 'patricia')); ?>
    <?php  } ?> 

    <button style="display:none;visibility:hidden;background:transparent;" type="submit">&nbsp;</button>
    <div class="clear-cookie" title="<?php echo osc_esc_html(__('Clear search form', 'patricia')); ?>"><i class="fa fa-trash-o"></i></div>

  </fieldset>

  <?php patricia_location_selector(); ?>

  <a class="h-pub" href="<?php echo osc_item_post_url(); ?>">
    <div class="cover"></div>
    <span><?php _e('Publish', 'patricia'); ?></span>
  </a>     

  <div class="top-my">
    <div class="my-open">
      <i class="fa fa-caret-down"></i>

      <?php if(!osc_is_web_user_logged_in()) { ?>
        <span class="my-top">
          <a href="<?php echo osc_register_account_url(); ?>"><span class="oran"><?php _e('Sign up', 'patricia'); ?></span> / <span class="join"><?php _e('Join', 'patricia'); ?></span></a>
        </span>
        <span class="my-account"><?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?></span>
      <?php } else { ?>
        <i class="fa fa-wrench user-in-fa"></i>
        <span class="user-in"><span><?php _e('account', 'patricia'); ?></span> <?php _e('Manager', 'patricia'); ?></span>
      <?php } ?>
    </div>

    <div class="my-wrap">
      <div class="top-inside">
        <?php if( osc_is_web_user_logged_in() ) { ?>
          <?php if(osc_logged_user_name() <> '') { ?>
            <span class="welcome"><?php _e('Welcome', 'patricia'); ?> <?php echo osc_logged_user_name(); ?>!</span>
          <?php } else { ?>
            <span class="welcome"><?php _e('Welcome back', 'patricia'); ?></span>
          <?php } ?>

          <span class="space-white"></span>
          <a class="reg-button round2" href="<?php echo osc_user_logout_url(); ?>" title="<?php echo osc_esc_html(__('Log me out', 'patricia')); ?>" class="logout" rel="nofollow"><?php _e('Log out', 'patricia'); ?></a>
        <?php } else { ?>
          <span class="welcome"><?php _e('Welcome to', 'patricia'); ?> <?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?>!</span>
          <a class="log-button round2" href="<?php echo osc_user_login_url(); ?>"><?php _e('Sign In', 'patricia'); ?></a>

          <span class="space"></span>

          <span class="unreg"><?php _e('Are you new to our site?', 'patricia'); ?></span>
          <a class="reg-button round2" href="<?php echo osc_register_account_url(); ?>"><?php _e('Register now', 'patricia'); ?></a>
        <?php } ?>
      </div>

      <div class="bottom-inside">
        <span class="top"><i class="fa fa-briefcase"></i> <?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?></span>
        <a href="<?php echo osc_user_dashboard_url(); ?>" class="elem"><?php _e('My account', 'patricia'); ?></a>
        <a href="<?php echo osc_user_alerts_url(); ?>" class="elem"><?php _e('My alerts', 'patricia'); ?></a>
        <a href="<?php echo osc_user_profile_url(); ?>" class="elem"><?php _e('My personal info', 'patricia'); ?></a>
        <a href="<?php echo osc_user_list_items_url(); ?>" class="elem"><?php _e('My listings', 'patricia'); ?></a>
        <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_public_profile_url(osc_logged_user_id()); ?>" class="elem"><?php _e('My public profile', 'patricia'); ?></a><?php } ?>
        <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_logout_url(); ?>" class="elem"><?php _e('Log me out', 'patricia'); ?></a><?php } ?>
      </div>
    </div>
  </div>              

  <div id="search-example"></div>
  </form>  
</div>

<script>
$('.clear-cookie').click(function(){
  // Clear all search parameters
  $.ajax({
    url: "<?php echo osc_base_url(); ?>oc-content/themes/patricia/ajax.php?clearCookieAll=done",
    type: "GET",
    success: function(response){
      //alert(response);
    }
  });

  $('#sCategory').val('');
  $('#uniform-sCategory span').text('<?php _e('Select a category', 'patricia'); ?>');
  $('#query').val('');
  $('#priceMin').val('');
  $('#priceMax').val('');
  $('#cookie-action').val('done');

  $('#Locator').attr('rel', '');
  $('input[name=sCountry<?php echo radius_installed(); ?>]').val('');
  $('input[name=sRegion<?php echo radius_installed(); ?>]').val('');
  $('input[name=sCity<?php echo radius_installed(); ?>]').val('');
  $('#uniform-Locator span').text('<?php _e('Location', 'patricia'); ?>');

  $('.h-my-loc .font').hide(150);
  $('.h-my-loc .font').text("<?php _e('Location not saved', 'patricia'); ?>");
  $('.h-my-loc .font').delay(150).show(150);
});

$('.clear-cookie-location').click(function(){
  $.ajax({
    url: "<?php echo osc_base_url(); ?>oc-content/themes/patricia/ajax.php?clearCookieLocation=done",
    type: "GET",
    success: function(response){
      //alert(response);
    }
  });

  $('#Locator').attr('rel', '');
  $('input[name=sCountry<?php echo radius_installed(); ?>]').val('');
  $('input[name=sRegion<?php echo radius_installed(); ?>]').val('');
  $('input[name=sCity<?php echo radius_installed(); ?>]').val('');
  $('#uniform-Locator span').text('<?php _e('Location', 'tatiana'); ?>');

  $('.h-my-loc .font').hide(150);
  $('.h-my-loc .font').text("<?php _e('Location not saved', 'tatiana'); ?>");
  $('.h-my-loc .font').delay(150).show(150);
});

$('#sCategory').change(function(){
  $('#cookie-action').val('done');
});

// DO NOT FADE WHEN RESPONSIVE
if($(document).width() > 480) {
  var time = 200;
  var delay = 500;
} else {
  var time = 0;
  var delay = 0;
}

$('#uniform-Locator').hover(function() {
  $(this).find('#loc-box').stop(true, true).fadeIn(time);
  $(this).find('#loc-list').css('overflow-y', 'scroll');
}, function() {
  $(this).find('#loc-box').stop(true, true).delay(delay).fadeOut(time);
});

$('#uniform-sCategory').hover(function() {
  $(this).find('#inc-cat-box').stop(true, true).fadeIn(time);
  $(this).find('#inc-cat-list').css('overflow-y', 'scroll');
}, function() {
  $(this).find('#inc-cat-box').stop(true, true).delay(delay).fadeOut(time);
});

$('#uniform-Locator, .top-my, #uniform-sCategory').hover(function(){
  $(this).css('z-index', 99999);
}, function(){
  $(this).css('z-index', 9);
});

$('#loc-list li').click(function(){
  var sQuery = '<?php echo osc_esc_js( $sQuery ); ?>';
  var isreg = $(this).attr('rel');
  if(!isreg.indexOf("--")) { 
    $('#sCity').val(isreg.substring(2, isreg.length));
  } else if(!isreg.indexOf("//")) { 
    $('#sRegion').val(isreg.substring(2, isreg.length));
    $('#sCity').val('');
  } else {
    <?php if($show_country) { ?>$('#sCountry').val(isreg);<?php } ?>
    $('#sRegion').val('');
    $('#sCity').val('');
  }

  if($('input[name=sPattern]').val() == sQuery) {
    $('input[name=sPattern]').val('');
  }

  $('#loc-box').stop(true, true).fadeOut(time);

  $(this).attr('rel', '');
  $('#cookie-action').val('done');
  $('.search').submit();
});


// Category click list action
$('#inc-cat-list li').click(function(){
  var sQuery = '<?php echo osc_esc_js( $sQuery ); ?>';
  $('#sCategory').val($(this).attr('rel'));

  if($('input[name=sPattern]').val() == sQuery) {
    $('input[name=sPattern]').val('');
  }

  $('#inc-cat-box').stop(true, true).fadeOut(time);

  $(this).attr('rel', '');
  $('#cookie-action').val('done');
  $('.search').submit();
});

// Remove &nbsp; and - from location name in span
$(document).ready(function(){
  var loc_text = $('#uniform-Locator span').text().trim();
  loc_text = loc_text.replace('- ','');
  $('#uniform-Locator span').text(loc_text);
});

//document.getElementById("sCategory").onchange = function(){this.form.submit();};
$("#sCategory").change(function(){
  $('.search').submit();
});

$(".search").submit(function(){
  $('#Locator').attr('rel', '');
});  
</script>