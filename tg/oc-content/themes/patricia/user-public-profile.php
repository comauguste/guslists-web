<?php
  $address = '';
  if(osc_user_address()!='') {
    $address = osc_user_address();
  }

  $location_array = array();
  if(trim(osc_user_city()." ".osc_user_zip())!='') {
    $location_array[] = trim(osc_user_city()." ".osc_user_zip());
  }

  if(osc_user_region()!='') {
    $location_array[] = osc_user_region();
  }

  if(osc_user_country()!='') {
    $location_array[] = osc_user_country();
  }

  $location = implode(", ", $location_array);
  unset($location_array);

  $user_keep = osc_user();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
</head>
<body>
  <?php View::newInstance()->_exportVariableToView('user', $user_keep); ?>
  <?php osc_current_web_theme_path('header.php') ; ?>

  <div class="content user_public_profile">
    <h1><?php echo __('Welcome in store of', 'patricia') . ' <span class="bold">' . osc_user_name(); ?></span></h1>

    <!-- RIGHT BLOCK -->
    <div id="right-block">
      <h3 class="desc"><?php _e('Seller\'s information', 'patricia'); ?></h3>

      <!-- SELLER INFORMATION -->
      <div id="description">
        <?php if(function_exists('profile_picture_show')) { profile_picture_show(200); } ?>

        <ul id="user_data">
          <li class="name"><?php echo osc_user_name(); ?></li>
          <?php if ( osc_user_phone_mobile() != "" ) { ?><li><?php echo osc_user_phone_mobile() ; ?></li><?php } ?>
          <?php if ( osc_user_phone() != "" && osc_user_phone() != osc_user_phone_mobile() ) { ?><li><?php echo osc_user_phone() ; ?></li><?php } ?>                    
          <?php if ($address != '') { ?><li><?php echo $address; ?></li><?php } ?>
          <?php if ($location != '') { ?><li><?php echo $location; ?></li><?php } ?>
          <?php if (osc_user_website() != '') { ?><li><?php echo osc_user_website(); ?></li><?php } ?>
        </ul>

        <?php if(osc_user_info() <> '') { ?><div class="user-desc"><?php echo osc_user_info(); ?></div><?php } ?>
      </div>


      <!-- CONTACT SELLER BLOCK -->
      <?php if(osc_user_id() == osc_logged_user_id()) { ?>
        <div class="empty"><?php _e('This is your public profile and therefore contact form is disabled for you', 'patricia'); ?></div>
      <?php } ?>

      <?php if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
        <div id="pub-con" <?php if(osc_user_id() == osc_logged_user_id()) { ?>class="same-user"<?php } ?>>
          <div id="contact_form_wrap">
            <ul id="error_list"></ul>
            <?php ContactForm::js_validation(); ?>
            <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="con_form" <?php if( osc_recaptcha_public_key() ) { ?>style="height:auto;"<?php } ?>>
            <input type="hidden" name="action" value="contact_post" class="nocsrf" />
            <input type="hidden" name="page" value="user" />
            <input type="hidden" name="id" value="<?php echo osc_user_id(); ?>" />

            <fieldset>
              <h3 class="title_block"><span><?php _e('Contact', 'patricia'); ?></span> <?php _e('seller', 'patricia'); ?></h3>

              <?php if(osc_is_web_user_logged_in()) { ?>
                <input type="hidden" name="authorName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
                <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
              <?php } else { ?>
                <div class="row">
                  <label for="yourName"><span><?php _e('Name', 'patricia') ; ?></span></label> 
                  <?php ContactForm::your_name(); ?>
                </div>
                <div class="row">
                  <label for="yourEmail"><span><?php _e('E-mail', 'patricia') ; ?></span><span class="req">*</span></label> 
                  <?php ContactForm::your_email(); ?>
                </div>                  
              <?php } ?>
              <div class="row">
                <label for="phoneNumber"><span><?php _e('Phone number', 'patricia') ; ?></span></label>
                <?php ContactForm::your_phone_number(); ?>
              </div>
               
              <div class="row mes">
                <label for="message"><span><?php _e('Message', 'patricia') ; ?></span></label>
                <?php ContactForm::your_message(); ?>
              </div>
              <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>

              <!-- ReCaptcha -->
              <?php if( osc_recaptcha_public_key() ) { ?>
                <script type="text/javascript">
                  var RecaptchaOptions = {
                    theme : 'custom',
                    custom_theme_widget: 'recaptcha_widget'
                  };
                </script>


                <div id="recaptcha_widget">
                  <div id="recaptcha_image"><img /></div>
                  <span class="recaptcha_only_if_image"><?php _e('Enter the words above','patricia'); ?>:</span>
                  <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                  <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'patricia'); ?></a> <a href="javascript:Recaptcha.reload()"><?php _e('Reload', 'patricia'); ?></a></div>
                </div>
              <?php } ?>
              <?php osc_show_recaptcha(); ?>

              <button type="submit" id="blue"><?php _e('Send', 'patricia') ; ?></button>
            </fieldset>
            </form>
          </div>
        </div>
      <?php } ?>
    </div>


    <!-- LISTINGS OF SELLER -->
    <div id="public-items" class="white">
      <?php if( osc_count_items() > 0) { ?>
        <div class="block">
          <div class="wrap">
            <?php $c = 1; ?>
            <?php while( osc_has_items() ) { ?>
              <div class="simple-prod o<?php echo $c; ?>">
                <div class="simple-wrap">
                  <?php if(osc_item_region() <> '') { ?>
                    <div class="loc">
                      <i class="fa fa-map-marker"></i>&nbsp;<?php echo osc_item_region(); ?>
                      <span class="loc-hide">
                        <?php if(osc_item_city() <> '') { ?>
                          <i class="fa fa-angle-right"></i>&nbsp;<?php echo osc_item_city(); ?>
                          <?php if(osc_item_country_code() <> '') { ?>
                            (<?php echo osc_item_country_code(); ?>)
                          <?php } ?>
                        <?php } else if (osc_item_country_code() <> '') { ?>
                          <i class="fa fa-angle-right"></i>&nbsp;<?php echo osc_item_country_code(); ?>
                        <?php } ?>
                      </span>
                    </div>
                  <?php } else if(osc_item_city() <> '') { ?>
                    <div class="loc">
                      <i class="fa fa-map-marker"></i>&nbsp;<?php echo osc_item_city(); ?>
                      <?php if (osc_item_country_code() <> '') { ?>
                        <span class="loc-hide">
                          <i class="fa fa-angle-right"></i>&nbsp;<?php echo osc_item_country_code(); ?>
                        </span>
                      <?php } ?>
                    </div>
                  <?php } else if(osc_item_country() <> '') { ?>
                    <div class="loc"><i class="fa fa-map-marker"></i>&nbsp;<?php echo osc_item_country(); ?></div>
                  <?php } ?>

                  <div class="item-img-wrap">
                    <?php if(osc_count_item_resources()) { ?>
                      <?php if(osc_count_item_resources() == 1) { ?>
                        <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                      <?php } else { ?>
                        <a class="img-link" href="<?php echo osc_item_url(); ?>">
                          <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                            <?php if($i <= 1) { ?>
                              <img class="link<?php echo $i; ?>" src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                            <?php } ?>
                          <?php } ?>
                        </a>
                      <?php } ?>
                    <?php } else { ?>
                      <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                    <?php } ?>

                    <a class="orange-but" title="<?php echo osc_esc_html(__('Quick view', 'patricia')); ?>" href="<?php echo osc_item_url(); ?>" title="<?php echo osc_esc_html(__('Open this listing', 'patricia')); ?>"><i class="fa fa-hand-pointer-o"></i></a>
                  </div>

                  <?php
                    $now = time();
                    $your_date = strtotime(osc_item_pub_date());
                    $datediff = $now - $your_date;
                    $item_d = floor($datediff/(60*60*24));

                    if($item_d == 0) {
                      $item_date = __('today', 'patricia');
                    } else if($item_d == 1) {
                      $item_date = __('yesterday', 'patricia');
                    } else {
                      $item_date = date(osc_get_preference('date_format', 'patricia_theme'), $your_date);
                    }
                  ?>

                  <?php if(osc_item_is_premium()) { ?>
                    <div class="new">
                      <span class="top"><?php _e('top', 'patricia'); ?></span>
                      <span class="bottom"><?php _e('item', 'patricia'); ?></span>
                    </div>
                  <?php } ?>

                  <div class="status">
                    <div class="green"><?php echo osc_item_category(); ?></div>
                    <div class="normal"><?php echo $item_date; ?></div>
                  </div>
                  
                  <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

                  <?php if( osc_price_enabled_at_items() ) { ?>
                    <div class="price"><span><?php echo osc_item_formated_price(); ?></span></div>
                  <?php } ?>
                </div>
              </div>
              
              <?php $c++; ?>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="empty"><?php _e('No listings posted by this seller', 'patricia'); ?></div>
      <?php } ?>
    </div>
  </div>

  <?php if(osc_user_id() == osc_logged_user_id() && osc_user_id() <> 0 && osc_user_id() <> '') { ?>
    <script>
      $(document).ready(function(){
        $('#pub-con.same-user .button#uniform-blue').addClass('disabled');
        $('#pub-con.same-user').find('input, textarea').prop('disabled', true);
        $('.button.disabled').click(function(event){
          event.preventDefault();
          return false;
        });
      });
    </script>
  <?php } ?>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>