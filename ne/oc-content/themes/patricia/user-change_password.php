<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php'); ?>
  <div class="content user_account">
    <h1>
      <?php if(function_exists('profile_picture_show')) { profile_picture_show(null, null, 39); } ?>
      <span><?php _e('Change your password', 'patricia'); ?></span>
    </h1>
    <div id="sidebar">
      <?php echo osc_private_user_menu(); ?>
    </div>
    <div id="main" class="modify_profile">
      <h2 class="title_block"><?php _e('Change your', 'patricia'); ?> <span><?php _e('password', 'patricia'); ?></span></h2>
      <form action="<?php echo osc_base_url(true); ?>" method="post">
      <input type="hidden" name="page" value="user" />
      <input type="hidden" name="action" value="change_password_post" />
      <fieldset>
        <div class="limit">
          <label for="password"><?php _e('Current password', 'patricia'); ?> *</label>
          <input type="password" name="password" id="password" value="" />
        </div>
        <div class="limit">
          <label for="new_password"><?php _e('New password', 'patricia'); ?> *</label>
          <input type="password" name="new_password" id="new_password" value="" />
        </div>
        <div class="limit">
          <label for="new_password2"><?php _e('Repeat new password', 'patricia'); ?> *</label>
          <input type="password" name="new_password2" id="new_password2" value="" />
        </div>

        <div class="clear"></div>
        <button type="submit" id="blue"><?php _e('Update', 'patricia'); ?></button>
      </fieldset>
      </form>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>