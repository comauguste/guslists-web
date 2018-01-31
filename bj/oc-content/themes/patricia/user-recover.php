<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="clear"></div>
  <div id="i-forms" class="content recover">
    <h2><span><?php _e('Recover', 'patricia'); ?></span> <?php _e('your password', 'patricia'); ?></h2>

    <div class="user_forms">
      <div class="inner">
        <form action="<?php echo osc_base_url(true) ; ?>" method="post" >
          <input type="hidden" name="page" value="login" />
          <input type="hidden" name="action" value="recover_post" />
          <fieldset>
            <label for="email"><?php _e('E-mail', 'patricia') ; ?></label> <?php UserForm::email_text() ; ?><br />
            <?php osc_show_recaptcha('recover_password'); ?>
            <button type="submit" id="orange"><?php _e('Send me a new password', 'patricia') ; ?></button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <div class="clear"></div><br /><br />

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>