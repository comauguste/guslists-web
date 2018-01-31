<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="content user_forms">
    <h1>
      <?php if(function_exists('profile_picture_show')) { profile_picture_show(null, null, 39); } ?>
      <span><?php _e('Recover your password', 'patricia'); ?></span>
    </h1>

    <div class="inner">
      <form action="<?php echo osc_base_url(true) ; ?>" method="post" >
      <input type="hidden" name="page" value="login" />
      <input type="hidden" name="action" value="forgot_post" />
      <input type="hidden" name="userId" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
      <input type="hidden" name="code" value="<?php echo osc_esc_html(Params::getParam('code')); ?>" />
      <fieldset>
        <div>
          <label for="new_email"><?php _e('New pasword', 'patricia') ; ?></label><br />
          <input type="password" name="new_password" value="" />
        </div>
        <div>
          <label for="new_email"><?php _e('Repeat new pasword', 'patricia') ; ?></label><br />
          <input type="password" name="new_password2" value="" />
        </div>

        <button type="submit" id="blue"><?php _e('Change password', 'patricia') ; ?></button>
      </fieldset>
      </form>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>