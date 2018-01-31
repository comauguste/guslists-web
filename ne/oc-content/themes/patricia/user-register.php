<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
</head>

<body>
  <?php UserForm::js_validation() ; ?>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div id="i-forms" class="content">
    <div id="left">
      <div class="user_forms login">
        <div class="inner">                
          <form action="<?php echo osc_base_url(true); ?>" method="post" >
          <input type="hidden" name="page" value="login" />
          <input type="hidden" name="action" value="login_post" />
          <fieldset>
            <label for="email"><span><?php _e('E-mail', 'patricia'); ?></span></label> <?php UserForm::email_login_text() ; ?>
            <label for="password"><span><?php _e('Password', 'patricia'); ?></span></label> <?php UserForm::password_login_text() ; ?>
            <div class="checkbox"><?php UserForm::rememberme_login_checkbox();?> <label for="remember"><?php _e('Remember me', 'patricia') ; ?></label></div>
            <div class="clear"></div>
            <button type="submit" id="blue"><?php _e("Log in", 'patricia');?></button>

            <div class="more-login">
              <a href="<?php echo osc_recover_user_password_url() ; ?>"><?php _e("Forgot password", 'patricia') ; ?><i class="fa fa-question-circle"></i></a>
            </div>
          </fieldset>
          </form>
        </div>
      </div>

      <!-- USER PROTECTION -->
      <div id="reg-protect">
        <div class="warn"><i class="fa fa-umbrella"></i> <span><?php _e('User protection', 'patricia'); ?></span></div>
        <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Act locally', 'patricia'); ?></span> <?php _e('to avoid scam', 'patricia'); ?></div>
        <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Anonymous payment gateways', 'patricia'); ?></span> <?php _e('are very unsafe', 'patricia'); ?></div>
        <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Cheques payments', 'patricia'); ?></span> <?php _e('are not recommended', 'patricia'); ?></div>
      </div>
    </div>

    <div id="right">
      <h2><span><?php _e('Register', 'patricia'); ?></span> <?php _e('an account for free', 'patricia'); ?></h2>

      <div class="user_forms register">
        <div class="inner">          
          <form name="register" id="register" action="<?php echo osc_base_url(true) ; ?>" method="post" >
          <input type="hidden" name="page" value="register" />
          <input type="hidden" name="action" value="register_post" />
          <fieldset>
            <?php if(class_exists('OSCFacebook')) { ?>
              <?php 
                $user = OSCFacebook::newInstance()->getUser() ;
                if( !$user or !osc_is_web_user_logged_in() ) {
              ?>

              <div class="fb-box">
                <a class="fb-login" href="<?php echo OSCFacebook::newInstance()->loginUrl(); ?>"></a>
                <span class="fb-load"></span>
              </div>

              <?php } ?>
            <?php } ?>

            <h1></h1>
            <ul id="error_list"></ul>

            <label for="name"><span><?php _e('Name', 'patricia') ; ?></span><span class="req">*</span></label> <?php UserForm::name_text(); ?>
            <label for="password"><span><?php _e('Password', 'patricia') ; ?></span><span class="req">*</span></label> <?php UserForm::password_text(); ?>
            <label for="password"><span><?php _e('Re-type password', 'patricia') ; ?></span><span class="req">*</span></label> <?php UserForm::check_password_text(); ?>
            <p id="password-error" style="display:none;">
              <?php _e('Passwords don\'t match', 'patricia') ; ?>.
            </p>
            <label for="email"><span><?php _e('E-mail', 'patricia') ; ?></span><span class="req">*</span></label> <?php UserForm::email_text() ; ?>
            <label for="phone"><?php _e('Mobile Phone', 'patricia'); ?></label> <?php UserForm::mobile_text(osc_user()) ; ?>
            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>

            <?php osc_run_hook('user_register_form') ; ?>

            <script type="text/javascript">var RecaptchaOptions = {theme : 'white'};</script>
            <?php osc_show_recaptcha('register'); ?>
            <button type="submit" id="green"><?php _e('Create account', 'patricia') ; ?></button>
          </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>