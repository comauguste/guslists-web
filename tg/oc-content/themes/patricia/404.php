<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
  <head>
    <?php osc_current_web_theme_path('head.php') ; ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
    <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
  </head>
  <body>
    <?php osc_current_web_theme_path('header.php') ; ?>
    <div class="content error err400">
      <h1><?php _e('Whoops, something is wrong', 'patricia'); ?></h1>
      <div class="reason">
        <?php _e('We are sorry, but the Web address you have entered is no longer available.', 'patricia'); ?><br />
        <?php _e('To find a correct listing, please use search box above.', 'patricia'); ?><br /><br /><br />

        <a class="button gray-button round3" href="<?php echo osc_base_url();?>"><?php _e('Go home', 'patricia'); ?></a>
        <br /><br />
      </div>

      <div class="clear"></div>

      <!-- Latest listings -->
      <div id="latest" class="white">
        <h2 class="extra"><?php _e('Check', 'patricia'); ?> <span><?php _e('latest offer', 'patricia'); ?></span> <?php echo __('on', 'patricia') . ' ' . osc_page_title(); ?></h2>

        <?php osc_query_item(array( "results_per_page" => 6)); ?>
        <?php if( osc_count_custom_items() > 0) { ?>
          <div class="block">
            <div class="wrap">
              <?php $c = 1; ?>
              <?php while( osc_has_custom_items() ) { ?>
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
                    <a class="orange-but" href="<?php echo osc_item_url(); ?>" title="<?php echo osc_esc_html(__('Open this listing', 'patricia')); ?>"><i class="fa fa-arrow-right"></i></a>
                    
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
          <div class="empty"><?php _e('No latest listings', 'patricia'); ?></div>
        <?php } ?>
        
        <?php View::newInstance()->_erase('items') ; ?>
      </div>
    </div>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  </body>
</html>