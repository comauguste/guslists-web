<?php osc_goto_first_locale(); ?>
<!-- container -->
<div id="top-navi">
  <div class="navi-wrap">
    <div id="header">
      <a id="logo" href="<?php echo osc_base_url() ; ?>"><?php echo logo_header(); ?></a>
    </div>

    <!-- Search Bar -->
    <div class="header-right">
      <a id="logo690" href="<?php echo osc_base_url() ; ?>"><?php echo logo_header(); ?></a>

      <div class="top-links">
        <a class="left-link" href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'patricia'); ?></a>
        <?php if(osc_get_preference('phone', 'patricia_theme') <> '') { ?>
          <span class="left-span"><?php echo osc_esc_html( osc_get_preference('phone', 'patricia_theme') ); ?></span>
        <?php } ?>

        <?php if ( osc_count_web_enabled_locales() > 1) { ?>
          <?php $current_locale = mb_get_current_user_locale(); ?>

          <?php osc_goto_first_locale(); ?>
          <span id="lang-open-box">
            <div class="mb-tool-cover">
              <span id="lang_open" <?php if( osc_is_web_user_logged_in() ) { ?>class="logged"<?php } ?>><img src="<?php echo osc_current_web_theme_url();?>images/country_flags/<?php echo strtolower(substr(osc_current_user_locale(), 3)); ?>.png" alt="<?php echo osc_esc_html(__('Country flag', 'patricia'));?> " /><span><?php echo $current_locale['s_short_name']; ?><i class="fa fa-caret-down"></i></span></span>

              <div id="lang-wrap" class="mb-tool-wrap">
                <div class="mb-tool-cover">
                  <ul id="lang-box">
                    <?php $i = 0 ;  ?>
                    <?php while ( osc_has_web_enabled_locales() ) { ?>
                      <li <?php if( $i == 0 ) { echo "class='first'" ; } ?> title="<?php echo osc_esc_html(osc_locale_field("s_description")); ?>"><a id="<?php echo osc_locale_code() ; ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ) ; ?>"><img src="<?php echo osc_current_web_theme_url();?>images/country_flags/<?php echo strtolower(substr(osc_locale_code(), 3)); ?>.png" alt="<?php echo osc_esc_html(__('Country flag', 'patricia')); ?>" /><span><?php echo osc_locale_name(); ?></span></a><?php if (osc_locale_code() == $current_locale['pk_c_code']) { ?><i class="fa fa-check"></i><?php } ?></li>
                      <?php $i++ ; ?>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
          </span>
        <?php } ?>

        <span class="top-info <?php if( osc_is_web_user_logged_in() ) { ?>logged<?php } ?>">
          <div class="mb-tool-cover">
            <span class="open"><?php _e('Information', 'patricia'); ?><i class="fa fa-question-circle"></i></span>

            <div id="info-wrap" class="mb-tool-wrap">
              <div class="mb-tool-cover">
                <div id="info-box">
                  <div class="what"><i class="fa fa-question-circle"></i><?php _e('For more information about our classifieds, please check following links.', 'patricia'); ?></div>

                  <?php osc_reset_static_pages(); ?>
                  <?php while(osc_has_static_pages()) { ?>
                    <span><a href="<?php echo osc_static_page_url(); ?>" title="<?php echo osc_esc_html(osc_static_page_title()); ?>"><?php echo ucfirst(osc_static_page_title());?></a></span>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </span>

        <?php if(osc_users_enabled()) { ?>
          <?php if( osc_is_web_user_logged_in() ) { ?>
            <span class="logout"><a href="<?php echo osc_user_logout_url() ; ?>"><i class="fa fa-sign-out"></i><?php _e('Logout', 'patricia') ; ?></a></span>
            <span class="my-account"><a href="<?php echo osc_user_dashboard_url() ; ?>"><?php _e('My account', 'patricia') ; ?></a></span>
            <span class="welcome"><?php echo __('Hi', 'patricia') . ' ' . osc_logged_user_name() . ' !'; ?></span>
            
          <?php } else { ?>

            <?php if(osc_user_registration_enabled()) { ?>
              <span class="sign-in"><i class="fa fa-user"></i><a href="<?php echo osc_register_account_url() ; ?>"><?php _e('Sign in', 'patricia'); ?></a></span>
            <?php } ?>  
          <?php } ?>
        <?php } ?>
      </div>

      <?php osc_current_web_theme_path('inc.search.php') ; ?>
    </div>
  </div>
</div>

<div class="container">
<!-- header -->
<?php if (strpos($_SERVER['HTTP_HOST'], 'mb-themes') !== false) { ?>
  <div id="piracy" class="noselect" title="Click to hide this box">This theme is ownership of MB Themes and can be bought only on http://www.mb-themes.com or via Osclass Market that is official reseller. When you buy this theme on other site, you will have no support for this theme, you will be supporting piracy and violate ACTA anti-piracy agreement!</div>
  <script>$(document).ready(function(){ $('#piracy').click(function(){ $(this).fadeOut(200); }); });</script>
<?php } ?>
<?php if(function_exists('scrolltop')) { scrolltop(); } ?>

<script>
  var base_url_js = "<?php echo osc_base_url();?>";
</script>

<div class="clear"></div>
<!-- /header -->

<?php osc_show_flash_message(); ?>

<?php
  osc_show_widgets('header') ;
  $breadcrumb = osc_breadcrumb('<span class="bread-arrow"><i class="fa fa-angle-right"></i></span>', false);
  if( $breadcrumb != '') { ?>
    <div class="breadcrumb">
      <div class="bread-home"><i class="fa fa-home"></i></div><?php echo $breadcrumb; ?><?php if (osc_is_ad_page()) { if (osc_item_is_premium()) { ?><span id="top-item" title="<?php echo osc_esc_html(__('Premium listing', 'patricia')); ?>"><i class="fa fa-star"></i></span><?php } } ?>
      <div class="clear"></div>
    </div>
<?php } ?>

<?php View::newInstance()->_erase('countries'); ?>
<?php View::newInstance()->_erase('regions'); ?>
<?php View::newInstance()->_erase('cities'); ?>