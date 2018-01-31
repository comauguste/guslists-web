<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="content home">
    <div id="sidebar">
      <div id="loc" class="noselect">
        <div class="title_block">
          <span><?php _e('Location', 'patricia'); ?></span> <?php _e('selector', 'patricia'); ?>
        </div>

        <?php View::newInstance()->_erase('list_countries'); ?>
        <?php View::newInstance()->_erase('list_regions'); ?>
        <?php View::newInstance()->_erase('list_cities'); ?>

        <ul id="countries" <?php if(osc_count_list_countries() <= 1 ) { ?>class="one"<?php } ?>>
          <?php while(osc_has_list_countries() ) { ?>
            <li id="country-<?php echo osc_list_country_code(); ?>" class="country<?php if(Params::getParam('sCountry') == osc_list_country_name() or Params::getParam('sCountry') == osc_list_country_code() and osc_count_list_countries() > 1) { ?> active-wrap<?php } ?>">
              <span>
                <a href="<?php echo osc_list_country_url();?>"><?php echo osc_list_country_name();?></a>
                <div id="grower" class="grower <?php if(Params::getParam('sCountry') == osc_list_country_name() or Params::getParam('sCountry') == osc_list_country_code()) { ?>close<?php } else { ?>open<?php } ?>"></div>
              </span>

              <ul id="regions" <?php if(Params::getParam('sCountry') == osc_list_country_name() or Params::getParam('sCountry') == osc_list_country_code()) { ?>class="active"<?php } ?>>
                <?php View::newInstance()->_exportVariableToView('list_regions', Search::newInstance()->listRegions(osc_list_country_code(), '>', 'region_name ASC') ) ; ?>
                <?php while(osc_has_list_regions() ) { ?>
                  <li id="region-<?php echo osc_list_region_id(); ?>"  class="region<?php if(Params::getParam('sRegion') == osc_list_region_name() or Params::getParam('sRegion') == osc_list_region_id()) { ?> active-wrap<?php } ?>">
                    <span>
                      <a href="<?php echo osc_list_region_url();?>"><?php echo osc_list_region_name();?></a>
                      <div id="grower" class="grower <?php if(Params::getParam('sRegion') == osc_list_region_name() or Params::getParam('sRegion') == osc_list_region_id()) { ?>close<?php } else { ?>open<?php } ?>"></div>
                    </span>

                    <ul id="cities" <?php if(Params::getParam('sRegion') == osc_list_region_name() or Params::getParam('sRegion') == osc_list_region_id()) { ?>class="active"<?php } ?>>
                      <?php while(osc_has_list_cities(osc_list_region_id()) ) { ?>
                        <li id="city-<?php echo osc_list_city_id(); ?>" class="city<?php if(Params::getParam('sCity') == osc_list_city_name() or Params::getParam('sCity') == osc_list_city_id()) { ?> active-wrap<?php } ?>">
                          <a href="<?php echo osc_list_city_url();?>"><?php echo osc_list_city_name();?></a>
                        </li>
                      <?php } ?>
                    </ul>    
                    <?php View::newInstance()->_erase('list_cities') ;  ?>
       
                  </li>
                <?php } ?>
              </ul>
              <?php View::newInstance()->_erase('list_regions') ;  ?>
         
            </li>
          <?php } ?>
        </ul>
        <?php View::newInstance()->_erase('list_countries') ;  ?>
      </div>

      <?php if( patricia_user_location(true)) { ?>
        <div id="side-position">
          <span class="info" title="<?php echo osc_esc_html(__('Based on your position we pre-fill location search parameters for you. If you already filled your location or such location does not exist on our site, no action will be performed.', 'patricia')); ?>"><i class="fa fa-question-circle"></i></span>
          <div class="top">
            <i class="fa fa-location-arrow"></i>
            <span class="text">
              <span class="small"><?php _e('detect your', 'patricia'); ?></span>
              <span class="big"><?php _e('Position', 'patricia'); ?></span>
            </span>
          </div>
          <div class="bottom">
            <span class="text"><?php _e('We found your location', 'patricia'); ?></span>
            <span class="post"><?php echo patricia_user_location(true); ?></span>
          </div> 
        </div>
      <?php } ?>

      <?php if(osc_get_preference('theme_adsense', 'patricia_theme') == 1) { ?>
        <div class="home-google">
          <?php echo osc_get_preference('banner_home', 'patricia_theme'); ?>
        </div>        
      <?php } ?>

      <div class="mobile-friendly noselect">
        <div class="text"><?php _e('Available also on your mobile device', 'patricia'); ?></div>
        <img src="<?php echo osc_current_web_theme_url();?>images/side_mobile.png" />
      </div>

      <div class="share-friendly noselect">
        <div class="text"><?php _e('Share us and become our fan', 'patricia'); ?></div>
        <img src="<?php echo osc_current_web_theme_url();?>images/side_share.png" />
      </div>
    </div>

    <!-- BIG RIGHT COLUMN - MAIN COLUMN -->
    <div id="main-new">
      <?php if(function_exists('osc_slider')) { osc_slider(); } ?>	

      <?php osc_goto_first_category(); ?>
      <?php $search_params = patricia_search_params(); ?>
      <?php $search_params['sPriceMin'] = ''; ?>
      <?php $search_params['sPriceMax'] = ''; ?>

      <div id="home-cat">
        <ul class="top">
          <?php $i = 1; ?>
          <?php $category_icons = array(1 => 'fa-gavel', 2 => 'fa-car', 3 => 'fa-book', 4 => 'fa-home', 5 => 'fa-wrench', 6 => 'fa-music', 7 => 'fa-heart', 8 => 'fa-briefcase', 999 => 'fa-soccer-ball-o'); ?>

          <?php while ( osc_has_categories() ) { ?>
            <li <?php if($i > 10) { ?>style="display:none;"<?php } ?>>
              <a id="cat-<?php echo osc_category_id(); ?>" href="#ct<?php echo osc_category_id(); ?>">
                <div class="name"><?php echo osc_category_name(); ?></div>
                <div class="img">
                  <?php if(osc_get_preference('cat_icons', 'patricia_theme') == 1) { ?>
                    <?php 
                      if(osc_category_field('s_icon') <> '') {
                        $icon = osc_category_field('s_icon');
                      } else {
                        if($category_icons[osc_category_id()] <> '') {
                          $icon = $category_icons[osc_category_id()];
                        } else {
                          $icon = $category_icons[999];
                        }
                      }
                    ?>
                     
                    <i class="fa <?php echo $icon; ?>"></i>
                  <?php } else { ?>
                    <img src="<?php echo osc_current_web_theme_url();?>images/small_cat/<?php echo osc_category_id();?>.png" />
                  <?php } ?>
                </div>
              </a>
            </li>
            <?php $i++; ?>
          <?php } ?>
        </ul>

        <div class="cat-box">
          <?php osc_goto_first_category(); ?>
          <?php while( osc_has_categories() ) { ?>
            <?php $search_params['sCategory'] = osc_category_id(); ?>

            <div id="ct<?php echo osc_category_id(); ?>" class="cat-tab">
              <?php $cat_id = osc_category_id(); ?>
              <div class="head">
                <a href="<?php echo osc_search_url($search_params); ?>"><h2><?php echo osc_category_name(); ?></h2></a>
                <span> - <?php _e('browse in', 'patricia'); ?> <?php echo osc_category_total_items(); ?> <?php _e('listings', 'patricia'); ?></span>
                <div class="add"><a href="<?php echo osc_item_post_url_in_category(); ?>"><i class="fa fa-plus-square"></i><?php _e('Add listing', 'patricia'); ?></a></div>
              </div>
              <div class="left"><a href="<?php echo osc_search_url($search_params); ?>"><img src="<?php echo osc_current_web_theme_url();?>images/large_cat/<?php echo osc_category_id();?>.jpg" /></a></div>
              <div class="middle">
                <?php $c = 0; ?>
                <?php while(osc_has_subcategories()) { ?>
                  <?php $search_params['sCategory'] = osc_category_id(); ?>
                  <?php if($c < 18) { ?>
                    <a href="<?php echo osc_search_url($search_params); ?>"><?php echo osc_category_name(); ?></a>
                  <?php } ?>
                  <?php $c++; ?>
                <?php } ?>
              </div>

              <div class="right">
                <?php osc_query_item(array("category" => $cat_id, "results_per_page" => 10)); ?>
                <?php if(osc_count_custom_items() > 0) { ?>
                  <?php $c_i = 0; ?>
                  <div id="cat-items" class="dark">
                    <?php while( osc_has_custom_items() && $c_i < 2) { ?>
                      <div class="simple-prod o<?php echo $c; ?>">
                        <div class="simple-wrap">
                          <?php if(osc_count_item_resources()) { ?>
                            <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                          <?php } else { ?>
                            <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                          <?php } ?>
                          
                          <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

                          <?php if( osc_price_enabled_at_items() ) { ?>
                            <div class="price"><?php if(osc_item_price() <> 0 and osc_item_price() <> '') { ?><?php _e('now', 'patricia'); ?> <?php } ?><span><?php echo osc_item_formated_price(); ?></span></div>
                          <?php } ?>
                        </div>
                      </div>
                        
                      <?php $c_i++; ?>
                    <?php } ?>
                  </div>
                <?php } else { ?>
                  <div class="no-list">
                    <span class="t-title"><?php _e('No listings in this category', 'patricia'); ?></span>
                    <span class="t-desc"><?php _e('Be first to sell here!', 'patricia'); ?></span>
                  </div>
                <?php } ?>

                <?php $search_params['sCategory'] = $cat_id; ?>
                <a class="go-to-cat" href="<?php echo osc_search_url($search_params); ?>"><i class="fa fa-angle-right"></i></a>
              </div>

              <?php osc_query_item(array("category" => $cat_id, "results_per_page" => 10)); ?>
              <?php if( osc_count_custom_items() > 0) { ?>
                <div class="bottom">
                  <div class="b-head"><?php _e('Hot listings', 'patricia'); ?></div>
                  <?php $c_i = 0; ?>
                  <?php while( osc_has_custom_items() && $c_i < 10) { ?>
                    <a href="<?php echo osc_item_url(); ?>" class="prod round2"><?php echo osc_highlight(osc_item_title(), 20); ?></a>
                    <?php $c_i++; ?>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <!-- END OF MAIN COLUMN -->


    <!-- Latest listings -->
    <div id="latest" class="white">
      <h2 class="extra">
        <span><?php _e('Latest', 'patricia'); ?></span> <?php _e('listings', 'patricia'); ?>
      </h2>

      <?php if( osc_count_latest_items() > 0) { ?>
        <div class="block">
          <div class="wrap">
            <?php $c = 1; ?>
            <?php while( osc_has_latest_items() ) { ?>
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

                  <div class="item-img-wrap">
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

                    <a class="orange-but" title="<?php echo osc_esc_html(__('Quick view', 'patricia')); ?>" href="<?php echo osc_item_url(); ?>" title="<?php echo osc_esc_html(__('Open this listing', 'patricia')); ?>"><i class="fa fa-hand-pointer-o"></i></a>
                  </div>

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

 
    <!-- Popular listings -->
    <?php if(function_exists('popular_ads_start')) { ?>
      <div id="popular" class="dark">
        <h2 class="extra">
          <span><?php _e('Popular', 'patricia'); ?></span> <?php _e('listings', 'patricia'); ?>
          <div class="more"><a href="<?php echo osc_search_url(array('page' => 'search'));?>"><?php _e('more', 'patricia'); ?> <i class="fa fa-angle-right"></i></a></div>
        </h2>
        <?php popular_ads_start(); ?>

        <?php if( osc_count_items() > 0) { ?>
          <div class="block">
            <div class="wrap">
              <?php $c = 1; ?>
              <?php while( osc_has_items() ) { ?>
                <div class="simple-prod o<?php echo $c; ?>">
                  <div class="simple-wrap">
                    <?php if(osc_item_is_premium()) { ?>
                      <div class="flag"><?php _e('top', 'patricia'); ?></div>
                    <?php } ?>

                    <div class="top">
                      <div class="left"><?php echo $c; ?></div>
                      <div class="right"><?php _e('visited', 'patricia'); ?> <span><?php echo osc_item_views(); ?>x</span></div>
                    </div>
                    
                    <?php if(osc_count_item_resources()) { ?>
                      <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                    <?php } else { ?>
                      <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                    <?php } ?>
                    
                    <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

                    <?php if( osc_price_enabled_at_items() ) { ?>
                      <div class="price"><?php if(osc_item_price() <> 0 and osc_item_price() <> '') { ?><?php _e('now', 'patricia'); ?> <?php } ?><span><?php echo osc_item_formated_price(); ?></span></div>
                    <?php } ?>
                  </div>
                </div>
                
                <?php $c++; ?>
              <?php } ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="empty"><?php _e('No popular listings', 'patricia'); ?></div>
        <?php } ?>

        <?php popular_ads_end(); ?>
      </div>
    <?php } ?>
  </div> 

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>