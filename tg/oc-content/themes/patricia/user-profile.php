<?php
  $locales = __get('locales');
  $user = osc_user();
?>

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
      <?php if(function_exists('profile_picture_show')) { profile_picture_show(null, null, 39); } ?>
      <span><?php _e('User account manager', 'patricia') ; ?></span>
    </h1>
    <div id="sidebar">
      <?php echo osc_private_user_menu() ; ?>
      <?php if(function_exists('profile_picture_upload')) { profile_picture_upload();} ?>
    </div>
    <div id="main" class="modify_profile">
      <?php UserForm::location_javascript(); ?>
      <form action="<?php echo osc_base_url(true) ; ?>" method="post">
      <input type="hidden" name="page" value="user" />
      <input type="hidden" name="action" value="profile_post" />

      <div id="left-user">
        <h3 class="title_block"><span><?php _e('Personal', 'patricia'); ?></span> <?php _e('information', 'patricia'); ?></h3>
        <div class="row">
          <label for="name"><span><?php _e('Name', 'patricia') ; ?></span><span class="req">*</span></label>
          <?php UserForm::name_text(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="email"><span><?php _e('E-mail', 'patricia') ; ?></span><span class="req">*</span></label>
          <span class="update">
            <?php echo osc_user_email() ; ?><br />
            <a href="<?php echo osc_change_user_email_url() ; ?>"><?php _e('Modify e-mail', 'patricia') ; ?></a> <a href="<?php echo osc_change_user_password_url() ; ?>" ><?php _e('Modify password', 'patricia') ; ?></a>
          </span>
        </div>

        <div class="row">
          <label for="phoneMobile"><span><?php _e('Mobile phone', 'patricia'); ?></span><span class="req">*</span></label>
          <?php UserForm::mobile_text(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="phoneLand"><?php _e('Land Phone', 'patricia') ; ?></label>
          <?php UserForm::phone_land_text(osc_user()) ; ?>
        </div>                        

        <div class="row">
          <label for="info"><?php _e('Some info about you', 'patricia') ; ?></label>
          <?php UserForm::multilanguage_info($locales, osc_user()); ?>
        </div>
        <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>

        <div class="row user-buttons">
          <button type="submit" id="blue" class="round3 button"><?php _e('Update profile', 'patricia') ; ?></button>

          <?php //if (strpos($_SERVER[HTTP_HOST],'mb-themes') === false) { ?>
            <a id="uniform-gray" class="round3" href="<?php echo osc_base_url(true).'?page=user&action=delete&id='.osc_user_id().'&secret='.$user['s_secret']; ?>" onclick="return confirm('<?php _e('Are you sure you want to delete your account? This action cannot be undone', 'patricia'); ?>?')"><span><?php _e('Delete account', 'patricia'); ?></span></a>
          <?php //} ?>
        </div>
      </div>

      <div id="right-user">
        <h3 class="title_block"><span><?php _e('Business', 'patricia'); ?></span> <?php _e('information & location', 'patricia'); ?></h3>
        <div class="row">
          <label for="user_type"><?php _e('User type', 'patricia') ; ?></label>
          <?php UserForm::is_company_select(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="webSite"><?php _e('Website', 'patricia') ; ?></label>
          <?php UserForm::website_text(osc_user()) ; ?>
        </div>

        <?php $user = osc_user(); ?>
        <?php $country = Country::newInstance()->listAll(); ?>

        <?php 
          if(count($country) <= 1) {
            $u_country = Country::newInstance()->listAll();
            $u_country = $u_country[0];
            $user['fk_c_country_code'] = $u_country['pk_c_code'];
          }
        ?>

        <div class="row" <?php if(count($country) == 1) { ?>style="display:none;"<?php } ?>>
          <label for="country"><span><?php _e('Country', 'patricia') ; ?></span><span class="req">*</span></label>
          <?php UserForm::country_select(Country::newInstance()->listAll(), osc_user()); ?>
        </div>
        

        <div class="row">
          <label for="region"><span><?php _e('Region', 'patricia') ; ?></span><span class="req">*</span></label>
          <?php UserForm::region_select(osc_get_regions($user['fk_c_country_code']), osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="city"><span><?php _e('City', 'patricia') ; ?></span><span class="req">*</span></label>
          <?php UserForm::city_select(osc_get_cities($user['fk_i_region_id']), osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="address"><?php _e('Address', 'patricia') ; ?></label>
          <?php UserForm::address_text(osc_user()) ; ?>
        </div>
      </div>
           
      <?php osc_run_hook('user_form') ; ?>
      </form>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>

</body>
</html>