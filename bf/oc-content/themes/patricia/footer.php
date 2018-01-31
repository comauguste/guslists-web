<?php
  osc_show_widgets('footer');
  //$sQuery = osc_get_preference('keyword_placeholder', 'patricia_theme');
  $sQuery = __('Search in', 'patricia') . ' ' . osc_total_active_items() . ' ' .  __('listings', 'patricia');
?>
</div>
<!-- /container -->

<?php osc_run_hook('footer') ; ?>

<div id="footer-new">
  <div class="inside">
    <div class="bottom-place">
      <?php if(osc_is_search_page()) { ?>
        <?php osc_alert_form(); ?>
      <?php } else { ?>
        <div id="n-block" class="block quick">
          <div class="head"> 
            <span class="left"><i class="fa fa-plug"></i></span> 
            <span class="next"> 
              <span class="small"><?php _e('easy and fast', 'patricia'); ?></span> 
              <span class="big"><?php _e('Publish', 'patricia'); ?></span> 
            </span>
          </div>
          
          <div class="text"><?php _e('Share your listing quickly on our website', 'patricia'); ?></div>
          <div class="n-wrap">
            <form action="<?php echo osc_item_post_url_in_category(); ?>" method="post" name="add_listing" id="add_listing">
              <input type="text" value="<?php _e('Enter name of item', 'patricia'); ?>" name="add_title" id="add_title" />
              <input type="submit" value="<?php _e('Publish', 'patricia'); ?>" class="button orange-button round2" name="submitNewsletter"> 
            </form>
          </div>
            
          <div class="under">
            <div class="row"><?php _e('Buy & sell items with us', 'patricia'); ?>:</div>
            <div class="row"><i class="fa fa-dollar"></i> <?php _e('Lower price', 'patricia'); ?></div>
            <div class="row"><i class="fa fa-truck"></i> <?php _e('Fast delivery', 'patricia'); ?></div>
          </div>
        </div>
      <?php } ?>

      <?php /* Commented and replaced by social share links ?>
      <div class="some-block">
        <h4><?php _e('Hot items', 'patricia'); ?></h4>
        <div class="text">
          <?php osc_query_item(); $c = 1; ?>
          <?php while(osc_has_custom_items() and $c <= 8) { ?>
            <span><a href="<?php echo osc_item_url();?>" title="<?php echo osc_esc_html(strip_tags(osc_item_description()));?>"><?php echo ucfirst(osc_item_title());?></a></span>
          <?php $c++; } ?>
        </div>
      </div>
      <?php */ ?>

      <div class="some-block">
        <h4><?php _e('Categories', 'patricia'); ?></h4>
        <div class="text">
          <?php osc_goto_first_category(); $c = 1; ?>
          <?php while(osc_has_categories() and $c <= 8) { ?>
            <span><a href="<?php echo osc_search_category_url() ; ?>" title="<?php echo osc_esc_html(osc_category_name()); ?>"><?php echo ucfirst(osc_category_name());?></a></span>
          <?php $c++; } ?>
        </div>
      </div>

      <div class="some-block">
        <h4><?php _e('Information', 'patricia'); ?></h4>
        <div class="text">
          <?php osc_reset_static_pages(); ?>
          <?php $c = 1; ?>
          <?php while(osc_has_static_pages() and $c <= 8) { ?>
            <span><a href="<?php echo osc_static_page_url(); ?>" title="<?php echo osc_esc_html(osc_static_page_title()); ?>"><?php echo ucfirst(osc_static_page_title());?></a></span>
          <?php $c++; } ?>
        </div>
      </div>

      <div id="footer-share" class="some-block right">
        <h4><?php _e('Share us', 'patricia'); ?></h4>
        <div class="text">
          <span class="facebook"><i class="fa fa-facebook-square"></i><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo osc_base_url(); ?>" title="<?php echo osc_esc_html(__('Share us on Facebook', 'patricia')); ?>" target="_blank"><?php _e('Facebook', 'patricia'); ?></a></span>
          <span class="pinterest"><i class="fa fa-pinterest"></i><a href="https://pinterest.com/pin/create/button/?url=<?php echo osc_base_url(); ?>/oc-content/themes/patricia/images/logo.jpg&media=<?php echo osc_base_url(); ?>&description=" title="<?php echo osc_esc_html(__('Share us on Pinterest', 'patricia')); ?>" target="_blank"><?php _e('Pinterest', 'patricia'); ?></a></span>
          <span class="twitter"><i class="fa fa-twitter-square"></i><a href="https://twitter.com/home?status=<?php echo osc_base_url(); ?>%20-%20<?php _e('your', 'patricia'); ?>%20<?php _e('classifieds', 'patricia'); ?>" title="<?php echo osc_esc_html(__('Tweet us', 'patricia')); ?>" target="_blank"><?php _e('Twitter', 'patricia'); ?></a></span>
          <span class="google-plus"><i class="fa fa-google-plus-square"></i><a href="https://plus.google.com/share?url=<?php echo osc_base_url(); ?>" title="<?php echo osc_esc_html(__('Share us on Google+', 'patricia')); ?>" target="_blank"><?php _e('Google+', 'patricia'); ?></a></span>
          <span class="linkedin"><i class="fa fa-linkedin-square"></i><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo osc_base_url(); ?>&title=<?php echo osc_esc_html(__('My', 'patricia')); ?>%20<?php _e('classifieds', 'patricia'); ?>&summary=&source=" title="<?php echo osc_esc_html(__('Share us on LinkedIn', 'patricia')); ?>" target="_blank"><?php _e('LinkedIn', 'patricia'); ?></a></span>
        </div>
      </div>

    </div>

    <div class="del is_full"></div>

    <?php if(osc_get_preference('enable_partner', 'patricia_theme') == 1) { ?>
      <div id="sponsor">
        <div class="lead"><?php _e('Our partners', 'patricia'); ?></div>

        <?php 
          $sponsor_path = osc_base_path() . 'oc-content/themes/patricia/images/sponsor-logos'; 
          $sponsor_url = osc_base_url() . 'oc-content/themes/patricia/images/sponsor-logos'; 
          $sponsor_images = scandir($sponsor_path);

          foreach($sponsor_images as $img) {
            $ext = strtolower(pathinfo($sponsor_path . '/' . $img, PATHINFO_EXTENSION));
            $allowed_ext = array('png', 'jpg', 'jpeg', 'gif');

            if(in_array($ext, $allowed_ext)) {
              echo '<img class="sponsor-image" src="' . $sponsor_url . '/' . $img . '" alt="' . osc_esc_html(__('Our sponsor logo', 'patricia')) . '" />';
            }
          }
        ?>
      </div>
    <?php } ?>

    <div class="top-place">
      <a class="orang" href="<?php echo osc_base_url(); ?>"><?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?></a> | 
      <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'patricia'); ?></a> | 
      <a href="">Mail us to <?php _e('info@info.com', 'patricia'); ?></a> | 
      <?php if(osc_get_preference('footer_link', 'patricia_theme')) { ?><?php _e('This website is powered by', 'patricia'); ?> <a href="http://www.osclass.org">OSclass</a> | <?php } ?> 
      <a href="http://www.mb-themes.com/">Created by MB themes</a>
      <div class="cop"><?php _e('Copyright', 'patricia'); ?> &copy; <?php echo date("Y"); ?> <strong><?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?></strong></div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  var addQuery = '<?php echo osc_esc_js(__('Enter name of item', 'patricia')); ?>' ;

  if($('input[name=add_title]').val() == addQuery) {
    $('input[name=add_title]').css('color', 'gray');
  }
  $('input[name=add_title]').click(function(){
    if($('input[name=add_title]').val() == addQuery) {
      $('input[name=add_title]').val('');
      $('input[name=add_title]').css('color', '');
    }
  });
  $('input[name=add_title]').blur(function(){
    if($('input[name=add_title]').val() == '') {
      $('input[name=add_title]').val(addQuery);
      $('input[name=add_title]').css('color', 'gray');
    }
  });
  $('input[name=add_title]').keypress(function(){
    $('input[name=add_title]').css('background','');
  });
});
</script>


<?php if (1==2) { 
  $cat = osc_search_category_id();
  $cat = $cat[0];

  echo 'Page: ' . Params::getParam('page') . '<br />';
  echo 'Param Country: ' . Params::getParam('sCountry') . '<br />';
  echo 'Param Region: ' . Params::getParam('sRegion') . '<br />';
  echo 'Param City: ' . Params::getParam('sCity') . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo 'Param Category: ' . Params::getParam('sCategory') . '<br />';
  echo 'Search Region: ' . osc_search_region() . '<br />';
  echo 'Search City: ' . osc_search_city() . '<br />';
  echo 'Search Category: ' . $cat . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';
  echo 'Cookie Category: ' . mb_get_cookie('patricia-sCategory') . '<br />';
  echo 'Cookie Country: ' . mb_get_cookie('patricia-sCountry') . '<br />';
  echo 'Cookie Region: ' . mb_get_cookie('patricia-sRegion') . '<br />';
  echo 'Cookie City: ' . mb_get_cookie('patricia-sCity') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';

  echo '<br/>';
  echo '<br/>';
  echo 'end<br/>';

}
?>