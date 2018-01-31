<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="content user_account">
    <h1>
      <?php if(function_exists('profile_picture_show')) { profile_picture_show(40); } ?>
      <span><?php _e('User account manager', 'patricia') ; ?></span>
    </h1>
    <div id="sidebar">
      <?php echo osc_private_user_menu() ; ?>
      <?php if(function_exists('profile_picture_upload')) { profile_picture_upload();} ?>
    </div>

    <div id="main" class="ad_list">
      <?php osc_render_file(); ?>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>