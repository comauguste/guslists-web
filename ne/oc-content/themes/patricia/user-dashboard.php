<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php'); ?>
  <div class="content user_account">
    <h1>
      <?php if(function_exists('profile_picture_show')) { profile_picture_show(null, null, 39); } ?>
      <span><?php echo __('Hello', 'patricia') . ' <span class="bold">' . osc_logged_user_name() . '</span>, ' .__('welcome to your account', 'patricia'); ?>!</span>
    </h1>

    <div id="sidebar">
      <?php echo osc_private_user_menu() ; ?>
    </div>

    <div id="main" class="dashboard">
      <h3 class="title_block"><?php echo _e('Your', 'patricia'); ?> <span><?php echo _e('latest', 'patricia'); ?></span> <?php echo _e('listings', 'patricia'); ?></h3>
      <?php if(osc_count_items() == 0) { ?>
        <div class="empty"><?php _e('No listings have been added yet', 'patricia'); ?></div>
      <?php } else { ?>
        <?php $c = 1; ?>
        <?php while(osc_has_items()) { ?>
          <div class="dash-item<?php if($c%2 == 0) { ?> odd<?php } ?>">
            <span class="id">#<?php echo osc_item_id(); ?></span>
            <span class="titl"><a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a><?php if(osc_item_is_premium()) { ?><span id="top-item" title="<?php echo osc_esc_html(__('Premium listing', 'patricia')); ?>"><i class="fa fa-star"></i></span><?php } ?></span>
            <span class="date"><?php echo date("Y-m-d", strtotime(osc_item_pub_date()));; ?></span>
            <span class="price"><?php if( osc_price_enabled_at_items() ) { echo osc_format_price(osc_item_price()); } ?></span>
            <span class="views"><i class="fa fa-male"></i>&nbsp;<?php echo osc_item_views(); ?>x</span>
            <span class="edit"><a href="<?php echo osc_item_edit_url(); ?>"><i class="fa fa-wrench"></i>&nbsp;<?php _e('Edit', 'patricia'); ?></a></span>
            <?php if(osc_item_is_inactive()) {?>
              <span class="activate"><a href="<?php echo osc_item_activate_url();?>" ><i class="fa fa-rocket"></i>&nbsp;<?php _e('Activate', 'patricia'); ?></a></span>
            <?php } ?>
          </div>
          <?php $c++; ?>
        <?php } ?>
      <?php } ?>

      <div class="count-alerts round2">
        <?php $alerts = Alerts::newInstance()->findByUser( osc_logged_user_id()); ?>
        <h3><i class="fa fa-bell-o"></i>&nbsp;<?php echo __('You have', 'patricia') . ' <strong>' . count($alerts) . '</strong> ' . __('alerts, you can check them in section', 'patricia'); ?> <a href="<?php echo osc_user_alerts_url(); ?>"><?php echo _e('Manage your alerts', 'patricia'); ?></a></h2>
      </div>

      <?php $u = User::newInstance()->findByPrimaryKey(osc_logged_user_id()); ?>

      <?php if($u['s_website'] == '' or ($u['s_phone_land'] == '' and $u['s_phone_mobile'] == '') or $u['s_country'] == '' or $u['s_region'] == '' or $u['s_city'] == '' or $u['s_address'] == '') { ?>
        <div class="inform-profile">
          <h3><i class="fa fa-warning"></i>&nbsp;<?php echo __('You profile is not complete!', 'patricia'); ?></h3>

          <div class="i-block">
            <span class="descr"><?php echo _e('We found that your profile is still not complete! Take a minute and enter as much information as possible, this will help you sell your stuffs faster.', 'patricia'); ?></span>

            <?php if($u['s_phone_land'] == '' and $u['s_phone_mobile'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('No phone number was entered. You should enter at least 1 phone number to your mobile or land phone', 'patricia'); ?></span><?php } ?>
            <?php if($u['s_website'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('You did not entered your website', 'patricia'); ?></span><?php } ?>
            <?php if($u['s_country'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('Country was not entered', 'patricia'); ?></span><?php } ?>
            <?php if($u['s_region'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('Region was not entered', 'patricia'); ?></span><?php } ?>
            <?php if($u['s_city'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('City was not entered', 'patricia'); ?></span><?php } ?>
            <?php if($u['s_address'] == '') { ?><span class="entry"><i class="fa fa-exclamation"></i>&nbsp;<?php echo _e('Address was not entered', 'patricia'); ?></span><?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="inform-profile-ok"><i class="fa fa-thumbs-o-up"></i>&nbsp;<?php echo _e('Your profile is complete!', 'patricia'); ?></div>
      <?php } ?>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>