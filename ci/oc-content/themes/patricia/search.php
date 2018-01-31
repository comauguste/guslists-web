<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <?php if( osc_count_items() == 0 || Params::getParam('iPage') > 0 || stripos($_SERVER['REQUEST_URI'], 'search') )  { ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
  <?php } else { ?>
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
  <?php } ?>
</head>

<body>
<?php osc_current_web_theme_path('header.php') ; ?>

<div class="content list">
  <div id="main" class="search">

    <!-- TOP SEARCH TITLE -->
    <?php
      $search_cat_parent_id = '';
      $search_cat_id = osc_search_category_id();
      $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';
      if($search_cat_id <> 0 and $search_cat_id <> '') {
        $search_cat = Category::newInstance()->findByPrimaryKey($search_cat_id);
        $search_cat_text = $search_cat['s_description'];

        if($search_cat_text == '') {
          $search_cat_text = __('Browse in', 'patricia') . ' ' . osc_search_total_items() . ' ' .  __('listings', 'patricia');
        }

        $search_cat_parent_id = isset($search_cat['fk_i_parent_id']) ? $search_cat['fk_i_parent_id'] : '';

        if($search_cat_parent_id <> 0 and $search_cat_parent_id <> '') {
          $search_cat_parent = Category::newInstance()->findByPrimaryKey($search_cat_parent_id);
        }
      } else {
        $search_cat_text = __('Browse in', 'patricia') . ' ' . osc_search_total_items() . ' ' .  __('listings', 'patricia');
      }
    ?>

    <h2>
      <span class="lead"><?php echo search_title() <> '' ? search_title() : __('Search', 'patricia'); ?></span>
      <span class="follow"><?php echo osc_highlight($search_cat_text, 100); ?></span>
      
      <?php if($search_cat_parent_id <> 0 && $search_cat_parent_id <> '') { ?>
        <?php $search_params['sCategory'] = $search_cat_parent_id; ?>
        <a class="up" href="<?php echo osc_search_url($search_params); ?>"><?php echo $search_cat_parent['s_name']; ?> <i class="fa fa-level-up"></i></a>
      <?php } ?>
    </h2>


    <!-- PREMIUM LISTINGS BLOCK -->
    <?php osc_get_premiums(15); ?>

    <div class="prem-wrap">
      <?php if(osc_count_premiums() > 0) { ?>
        <?php if(file_exists(osc_base_path() . 'oc-content/themes/patricia/images/large_cat/' . $search_cat_id . ".jpg")) { ?>
          <div class="cat-img"><img src="<?php echo osc_current_web_theme_url();?>images/large_cat/<?php echo $search_cat_id;?>.jpg" /></div>
        <?php } else if (file_exists(osc_base_path() . 'oc-content/themes/patricia/images/large_cat/' . $search_cat_parent_id . ".jpg")) { ?>
          <div class="cat-img"><img src="<?php echo osc_current_web_theme_url();?>images/large_cat/<?php echo $search_cat_parent_id;?>.jpg" /></div>
        <?php } ?>

        <div id="prem-box" class="dark <?php if(file_exists(osc_base_path() . 'oc-content/themes/patricia/images/large_cat/' . $search_cat_id . ".jpg") or file_exists(osc_base_path() . 'oc-content/themes/patricia/images/large_cat/' . $search_cat_parent_id . ".jpg")) { ?>has_img<?php } ?>">
          <div id="scroll" class="prev">
            <div class="active"><i class="fa fa-chevron-left"></i></div>
            <div class="inactive"><i class="fa fa-chevron-left"></i></div>
          </div>

          <div id="prem-slider" class="block">
            <div class="wrap">
              <?php $c = 1; ?>
              <?php while( osc_has_premiums() ) { ?>
                <div class="simple-prod o<?php echo $c; ?>">
                  <div class="simple-wrap">
                    <div class="flag">#<?php echo $c; ?></div>

                    <?php if(osc_count_premium_resources()) { ?>
                      <a class="img-link" href="<?php echo osc_premium_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_premium_title()); ?>" alt="<?php echo osc_esc_html(osc_premium_title()); ?>" /></a>
                    <?php } else { ?>
                      <a class="img-link" href="<?php echo osc_premium_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_premium_title()); ?>" alt="<?php echo osc_esc_html(osc_premium_title()); ?>" /></a>
                    <?php } ?>
                    
                    <a class="title" href="<?php echo osc_premium_url(); ?>"><?php echo osc_highlight(osc_premium_title(), 100); ?></a>

                    <?php if( osc_price_enabled_at_items() ) { ?>
                      <div class="price"><span><?php echo patricia_premium_formated_price(osc_premium_price()); ?></span></div>
                    <?php } ?>
                  </div>
                </div>
                
                <?php $c++; ?>
              <?php } ?>
            </div>
          </div>

          <div id="scroll" class="next">
            <div class="active"><i class="fa fa-chevron-right"></i></div>
            <div class="inactive"><i class="fa fa-chevron-right"></i></div>
          </div>
        </div>
      <?php } ?>
    </div>


    <!-- SEARCH SUBCATEGORIES LIST -->
    <?php if(osc_get_preference('search_sub', 'patricia_theme') == 1) { ?>
      <?php
        if($search_cat_id <> 0 and $search_cat_id <> '') {
          $subcats = Category::newInstance()->findSubcategories($search_cat_id);
        } else {
          $subcats = Category::newInstance()->findRootCategories();
        }

        $search_params = patricia_search_params();
        $search_params['sPriceMin'] = '';
        $search_params['sPriceMax'] = '';
      ?>

      <?php if(count($subcats) > 0) { ?>
        <div id="subcats">
          <h3><span><?php _e('Subcategories', 'patricia'); ?></span></h3>
          <ul class="list big">
            <li class="null"></li>
            <?php foreach ( $subcats as $sub ) { ?> 
              <?php $search_params['sCategory'] = $sub['pk_i_id']; ?>
              <li><a href="<?php echo osc_search_url($search_params); ?>"><i class="fa fa-arrow-right"></i><?php echo $sub['s_name'] ; ?></a></li>
            <?php } ?>
            <li class="null"></li>
          </ul>

          <ul class="list small">
            <li class="null"></li>
            <?php $i = 1; ?>
            <?php foreach ( $subcats as $sub ) { ?>
              <?php if($i <= 4) { ?> 
                <?php $search_params['sCategory'] = $sub['pk_i_id']; ?>
                <li><a href="<?php echo osc_search_url($search_params); ?>"><i class="fa fa-arrow-right"></i><?php echo $sub['s_name'] ; ?></a></li>
              <?php $i++; ?>
              <?php } ?>
            <?php } ?>
            <li class="null"></li>
          </ul>

          <a href="#" class="more open" rel="<?php _e('Less', 'patricia'); ?>"><?php _e('More', 'patricia'); ?></a>
        </div>
      <?php } ?>
    <?php } ?>


    <!-- SEARCH FILTERS - SORT / COMPANY / VIEW -->
    <div id="search-sort">
      <div class="user-company-change">
        <div class="all <?php if(Params::getParam('sCompany') == '' or Params::getParam('sCompany') == null) { ?>active<?php } ?>"><span><?php _e('All results', 'patricia'); ?></span></div>
        <div class="individual <?php if(Params::getParam('sCompany') == '0') { ?>active<?php } ?>"><span><?php _e('Personal', 'patricia'); ?></span></div>
        <div class="company <?php if(Params::getParam('sCompany') == '1') { ?>active<?php } ?>"><span><?php _e('Company', 'patricia'); ?></span></div>
      </div>

      <div class="list-grid">
        <?php $def_view = osc_get_preference('def_view', 'patricia_theme') == 0 ? 'gallery' : 'list'; ?>
        <?php $old_show = Params::getParam('sShowAs') == '' ? $def_view : Params::getParam('sShowAs'); ?>
        <?php $params['sShowAs'] = 'list'; ?>
        <a href="<?php echo osc_update_search_url($params); ?>" title="<?php echo osc_esc_html(__('Switch to list view', 'patricia')); ?>" <?php echo ($old_show == $params['sShowAs'] ? 'class="active"' : ''); ?>><i class="fa fa-th-list"></i></a>
        <?php $params['sShowAs'] = 'gallery'; ?>
        <a href="<?php echo osc_update_search_url($params); ?>" title="<?php echo osc_esc_html(__('Switch to grid view', 'patricia')); ?>" <?php echo ($old_show == $params['sShowAs'] ? 'class="active"' : ''); ?>><i class="fa fa-th"></i></a>
      </div>

      <div class="counter">
        <?php echo osc_default_results_per_page_at_search()*(osc_search_page())+1;?> - <?php echo osc_default_results_per_page_at_search()*(osc_search_page()+1)+osc_count_items()-osc_default_results_per_page_at_search();?> <?php echo ' ' . __('of', 'patricia') . ' '; ?> <?php echo osc_search_total_items() ?> <?php echo (osc_search_total_items() == 1 ? __('listing', 'patricia') : __('listings', 'patricia')); ?>                                                           
      </div>

      <div class="sort-it">
        <div class="sort-title">
          <div class="title-keep">
            <?php $orders = osc_list_orders(); ?>
            <?php $current_order = osc_search_order(); ?>
            <?php foreach($orders as $label => $params) { ?>
              <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
              <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                <?php if($current_order == 'dt_pub_date') { ?>
                  <i class="fa fa-sort-amount-asc"></i>
                <?php } else { ?>
                  <?php if($orderType == 0) { ?>
                    <i class="fa fa-sort-numeric-asc"></i>
                  <?php } else { ?>
                    <i class="fa fa-sort-numeric-desc"></i>
                  <?php } ?>
                <?php } ?>

                <span><?php echo $label; ?></span>
              <?php } ?>
            <?php } ?>
          </div>

          <div id="sort-wrap">
            <div class="sort-content">
              <?php $i = 0; ?>
              <?php foreach($orders as $label => $params) { ?>
                <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
                <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                  <a class="current" href="<?php echo osc_update_search_url($params) ; ?>"><span><?php echo $label; ?></span></a>
                <?php } else { ?>
                  <a href="<?php echo osc_update_search_url($params) ; ?>"><span><?php echo $label; ?></span></a>
                <?php } ?>
                <?php $i++; ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="search-items">                    
      <?php if(osc_count_items() == 0) { ?>
        <div class="empty" ><?php printf(__('There are no results matching "%s"', 'patricia'), osc_search_pattern()) ; ?></div>
      <?php } else { ?>
        <?php require($old_show == 'list' ? 'search_list.php' : 'search_gallery.php') ; ?>
      <?php } ?>

      <div class="paginate">
        <?php echo osc_search_pagination(); ?>
        <div class="lead"><?php _e('Select page', 'patricia'); ?>:</div>
      </div>
      <div class="clear"></div>
    </div>
  </div>

  <div id="sidebar" class="noselect">
    <div id="sidebar-search">
      <form action="<?php echo osc_base_url(true); ?>" method="get" onsubmit="" class="nocsrf">
        <input type="hidden" name="page" value="search" />
        <input type="hidden" name="cookie-action-side" id="cookie-action-side" value="" />
        <input type="hidden" name="sCategory" value="<?php echo Params::getParam('sCategory'); ?>" />
        <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
        <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo $allowedTypesForSorting[osc_search_order_type()]; ?>" />
        <?php foreach(osc_search_user() as $userId) { ?>
          <input type="hidden" name="sUser[]" value="<?php echo $userId; ?>" />
        <?php } ?>
        <input type="hidden" name="sCompany" class="sCompany" id="sCompany" value="<?php echo Params::getParam('sCompany');?>" />
        <input type="hidden" id="priceMin" name="sPriceMin" value="<?php echo Params::getParam('sPriceMin'); ?>" size="6" maxlength="6" />
        <input type="hidden" id="priceMax" name="sPriceMax" value="<?php echo Params::getParam('sPriceMax'); ?>" size="6" maxlength="6" />

        <h3 class="head">
          <span class="left"><i class="fa fa-search"></i></span>
          <span class="right">
            <span class="top"><?php _e('advanced', 'patricia'); ?></span>
            <span class="bottom"><?php _e('Search', 'patricia'); ?></span>
          </span>

          <div id="show-hide" class="closed"></div>
        </h3>

        <div class="search-wrap">
          <fieldset class="box location">
            <div class="row">
              <h4><?php _e('Search text', 'patricia') ; ?></h4>                            
              <input type="text" name="sPattern" id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" />
            </div>

            <?php $aCountries = Country::newInstance()->listAll(); ?>
            
            <div class="row" <?php if(count($aCountries) <= 1 ) {?>style="display:none;"<?php } ?>>
              <h4><?php _e('Country', 'patricia') ; ?></h4>

              <?php
                // IF THERE IS JUST 1 COUNTRY, PRE-SELECT IT TO ENABLE REGION DROPDOWN
                $s_country = Country::newInstance()->listAll();
                if(count($s_country) <= 1) {
                  $s_country = $s_country[0];
                }
              ?>

              <select id="countryId" name="sCountry">
                <option value=""><?php _e('Select a country', 'patricia'); ?></option>

                <?php foreach ($aCountries as $country) {?>
                  <?php $country['pk_c_code'] = isset($country['pk_c_code']) ? $country['pk_c_code'] : ''; ?>
                  <?php $s_country['pk_c_code'] = isset($s_country['pk_c_code']) ? $s_country['pk_c_code'] : ''; ?>
                  <option value="<?php echo $country['pk_c_code']; ?>" <?php if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] = $country['pk_c_code']) { ?>selected="selected"<?php } ?>><?php echo $country['s_name'] ; ?></option>

                  <?php 
                    if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] = $country['pk_c_code']) {
                      $current_country_code = $country['pk_c_code'];
                    } 
                  ?>
                <?php } ?>
              </select>
            </div>

          
            <?php
              $current_country = Params::getParam('country') <> '' ? Params::getParam('country') : Params::getParam('sCountry');

              if($current_country <> '') {
                $aRegions = Region::newInstance()->findByCountry($current_country_code);
              } else {
                if(osc_count_countries() <= 1) {
                  $aRegions = Region::newInstance()->findByCountry($s_country['pk_c_code']);
                } else {
                  $aRegions = '';
                }
              }
            ?>

            <div class="row">
              <h4><?php _e('Region', 'patricia') ; ?></h4>

              <?php if(isset($aRegions) && !empty($aRegions) && $aRegions <> '' && count($aRegions) >= 1) { ?>
                <select id="regionId" name="sRegion" <?php if(Params::getParam('sRegion') == '' && Params::getParam('region')) {?>disabled<?php } ?>>
                  <option value=""><?php _e('Select a region', 'patricia'); ?></option>
                  
                  <?php foreach ($aRegions as $region) {?>
                    <option value="<?php echo $region['pk_i_id']; ?>" <?php if(Params::getParam('sRegion') == $region['pk_i_id'] or Params::getParam('sRegion') == $region['s_name']) { ?>selected="selected"<?php } ?>><?php echo $region['s_name']; ?></option>
                  <?php } ?>
                </select>
              <?php } else { ?>
                <input type="text" name="sRegion" id="sRegion-side" value="<?php echo Params::getParam('sRegion'); ?>" placeholder="<?php echo osc_esc_html(__('Enter a region', 'patricia')); ?>" />
              <?php } ?>
            </div>
            
            <?php 
              $current_region = Params::getParam('region') <> '' ? Params::getParam('region') : Params::getParam('sRegion');

              if(!is_numeric($current_region) && $current_region <> '') {
                $reg = Region::newInstance()->findByName($current_region);
                $current_region = $reg['pk_i_id'];
              }

              if($current_region <> '') {
                $aCities = City::newInstance()->findByRegion($current_region);
              } else {
                $aCities = '';
              }
            ?>

            <div class="row">
              <h4><?php _e('City', 'patricia') ; ?></h4>

              <?php if(isset($aCities) && !empty($aCities) && $aCities <> '' && count($aCities) >= 1) { ?>
                <select name="sCity" id="cityId" <?php if(Params::getParam('sCity') == '' && Params::getParam('city') == '') {?>disabled<?php } ?>> 
                  <option value=""><?php _e('Select a city', 'patricia'); ?></option>
            
                  <?php if(isset($aCities) && !empty($aCities)) { ?>
                    <?php foreach ($aCities as $city) {?>
                      <option value="<?php echo $city['pk_i_id']; ?>" <?php if(Params::getParam('sCity') == $city['pk_i_id'] or Params::getParam('sCity') == $city['s_name']) { ?>selected="selected"<?php } ?>><?php echo $city['s_name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              <?php } else { ?>
                <input type="text" name="sCity" id="sCity-side" value="<?php echo Params::getParam('sCity'); ?>" placeholder="<?php echo osc_esc_html(__('Enter a city', 'patricia')); ?>" />
              <?php } ?>
            </div>
          </fieldset>

          <?php if( osc_price_enabled_at_items() ) { ?>
            <fieldset class="price-box">
              <div class="row price">
                <h4><?php _e('Price', 'patricia'); ?>:</h4>
                <div id="amount-min"></div><div id="amount-del">-</div><div id="amount-max"></div>
              </div>

              <div id="slider-range"></div>
            </fieldset>
          <?php } ?>


          <fieldset>
            <?php if( osc_images_enabled_at_items() ) { ?>
              <div class="row checkboxes">
                <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked="checked"' : ''); ?> />
                <label for="withPicture" class="with-pic-label"><?php _e('Show only listings with photo', 'patricia') ; ?></label>
              </div>
            <?php } ?>
          </fieldset>

          <div class="sidebar-hooks">
            <?php 
              if(osc_search_category_id()) { 
                osc_run_hook('search_form', osc_search_category_id());
              } else { 
                osc_run_hook('search_form');
              } 
            ?>
          </div>

          <button type="submit" id="blue"><?php _e('Search', 'patricia') ; ?></button>
        </div>

        <div class="clear"></div>
      </form>
    </div>

    <div class="clear"></div>


    <!-- Refine categories -->
    <?php 
      $current_cat_id = osc_search_category_id(); 
      $current_cat_id = isset($current_cat_id[0]) ? $current_cat_id[0] : '';
      $current_cat = Category::newInstance()->findByPrimaryKey($current_cat_id);
      $parent_cat = Category::newInstance()->findByPrimaryKey($current_cat['fk_i_parent_id']);
      $parent_cat['pk_i_id'] = isset($parent_cat['pk_i_id']) ? $parent_cat['pk_i_id'] : '';
      $parent_cat['fk_i_parent_id'] = isset($parent_cat['fk_i_parent_id']) ? $parent_cat['fk_i_parent_id'] : '';
      $superparent_cat = Category::newInstance()->findByPrimaryKey($parent_cat['fk_i_parent_id']);

      $parent_categories = Category::newInstance()->hierarchy($parent_cat['pk_i_id']);
      $parent_categories = isset($parent_categories[0]) ? $parent_categories[0] : '';

      if($parent_cat['pk_i_id'] <> '') {
        $sibling_categories = Category::newInstance()->findSubcategories($parent_cat['pk_i_id']);
      } else {
        $sibling_categories = Category::newInstance()->findRootCategories();
      }
      
      $child_categories = Category::newInstance()->findSubcategories($current_cat_id);
    ?>
    
    <div id="menu">
      <h3 class="cats title_block">
        <span><?php _e('Categories', 'patricia'); ?></span> <?php _e('list', 'patricia'); ?>
        <?php if($superparent_cat['pk_i_id'] <> '') { ?>
          <?php $search_params['sCategory'] = $superparent_cat['pk_i_id']; ?>
          <a class="level-up" href="<?php echo osc_search_url($search_params); ?>"><?php _e('up', 'patricia'); ?> <i class="fa fa-level-up"></i></a>
        <?php } ?>

        <div id="show-hide" class="closed"></div>
      </h3>

      <?php $search_params = patricia_search_params(); ?>
      <?php $search_params['sPriceMin'] = ''; ?>
      <?php $search_params['sPriceMax'] = ''; ?>

      <div class="menu-wrap">
        <div class="category">
          <?php $parent_categories['pk_i_id'] = isset($parent_categories['pk_i_id']) ? $parent_categories['pk_i_id'] : ''; ?>
          <?php if($parent_categories['pk_i_id'] <> '') { ?>
            <h4>
              <?php $search_params['sCategory'] = $parent_categories['pk_i_id']; ?>
              <a href="<?php echo osc_search_url($search_params); ?>"><?php echo $parent_categories['s_name'] ; ?> </a> <span>(<?php echo $parent_categories['i_num_items'] ; ?>)</span>
            </h4>
          <?php } ?>

          <ul class="subcategory">
            <?php if($parent_cat['pk_i_id'] == '') { ?>
              <?php $search_params['sCategory'] = 0; ?>
              <li><a class="bold" href="<?php echo osc_search_url($search_params); ?>"><?php _e('All categories', 'patricia'); ?></a></li>
            <?php } ?>

            <?php foreach( $sibling_categories as $scat ) { ?> 
              <?php $search_params['sCategory'] = $scat['pk_i_id']; ?>
              <li <?php if ($current_cat_id == $scat['pk_i_id']) { echo ' class="is_child" '; }  ?>><a href="<?php echo osc_search_url($search_params); ?>"><?php echo $scat['s_name'] ; ?></a>

                <?php if ($current_cat_id == $scat['pk_i_id'] and !empty($child_categories)) { ?>
                  <ul class="sub-subcategory">
                    <?php foreach ( $child_categories as $ccat ) { ?>
                      <?php $search_params['sCategory'] = $ccat['pk_i_id']; ?>
                      <li><a href="<?php echo osc_search_url($search_params); ?>"><?php echo $ccat['s_name'] ; ?></a></li>
                    <?php } ?>
                  </ul>
                <?php }  ?>
              </li>
            <?php } ?>
          </ul>
        </div> 
      </div>
    </div>

    <?php if(osc_get_preference('theme_adsense', 'patricia_theme') == 1) { ?>
      <div class="search-google">
        <?php echo osc_get_preference('banner_search', 'patricia_theme'); ?>
      </div>        
    <?php } ?>
  </div>
</div>


<!-- JAVASCRIPT FOR PRICE SLIDER IN SEARCH BOX -->
<script>
  <?php
    $max = patricia_max_price($search_cat_id, Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity'));
    $max_price = ceil($max['max_price']/25)*25;
    $max_currency = $max['max_currency'];
    $format_sep = osc_get_preference('format_sep', 'patricia_theme');
    $format_cur = osc_get_preference('format_cur', 'patricia_theme');

    if($format_cur == 0) {
      $format_prefix = $max_currency;
      $format_suffix = '';
    } else if ($format_cur == 1) {
      $format_prefix = '';
      $format_suffix = $max_currency;
    } else {
      $format_prefix = '';
      $format_suffix = '';
    }
  ?>

  $(function() {
    $( "#slider-range" ).slider({
      range: true,
      step: <?php echo $max_price/25; ?>,
      min: 0,
      max: <?php echo $max_price; ?>,
      values: [<?php echo (Params::getParam('sPriceMin') <> '' ? Params::getParam('sPriceMin') : '0'); ?>, <?php echo (Params::getParam('sPriceMax') <> '' ? Params::getParam('sPriceMax') : $max_price); ?> ],
      slide: function( event, ui ) {
        if(ui.values[ 0 ] <= 0) {
          $( "#amount-min" ).text( "<?php _e('Free', 'patricia'); ?>" );
          $( "#amount-max" ).text( ui.values[ 1 ] );
          $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        } else {
          $( "#amount-min" ).text( ui.values[ 0 ] );
          $( "#amount-max" ).text( ui.values[ 1 ] );
          $( "#amount-min" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
          $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        }

        if(ui.values[ 0 ] <= 0) { 
          $( "#priceMin" ).val('');
        } else {
          $( "#priceMin" ).val(ui.values[ 0 ]);
        }

        if(ui.values[ 1 ] >= <?php echo $max_price; ?>) {
          $( "#priceMax" ).val('');
        } else {
          $( "#priceMax" ).val(ui.values[ 1 ]);
        }

        $("#cookie-action-side").val('done');
      }
    });
    

    if( $( "#slider-range" ).slider( "values", 0 ) <= 0 ) {
      if( $( "#slider-range" ).slider( "values", 1 ) <= 0 ) {
        $( "#amount-min" ).text( "<?php _e('Free', 'patricia'); ?>" );
        $( "#amount-max" ).text( "" );
        $( "#amount-del" ).hide(0);
      } else {
        $( "#amount-min" ).text( "<?php _e('Free', 'patricia'); ?>" );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 1 ) );
        $( "#amount-del" ).show(0);
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      }
    } else {
      if( $( "#slider-range" ).slider( "values", 0 ) == $( "#slider-range" ).slider( "values", 1 ) ) {
        $( "#amount-min" ).text( "" );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 0 ) );
        $( "#amount-del" ).hide(0);
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      } else {
        $( "#amount-min" ).text( $( "#slider-range" ).slider( "values", 0 ) );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 1 ) );
        $( "#amount-del" ).show(0);
        $( "#amount-min" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      }
    }
  });
</script>


<!-- JAVASCRIPT AJAX LOADER FOR COUNTRY/REGION/CITY SELECT BOX -->
<script>
  $(document).ready(function(){
    $("#countryId").live("change",function(){
      var pk_c_code = $(this).val();
      var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
      var result = '';

      if(pk_c_code != '') {
        $("#regionId").attr('disabled',false);
        $("#uniform-regionId").removeClass('disabled');
        $("#cityId").attr('disabled',true);
        $("#uniform-cityId").addClass('disabled');

        $.ajax({
          type: "POST",
          url: url,
          dataType: 'json',
          success: function(data){
            var length = data.length;
            
            if(length > 0) {

              result += '<option value=""><?php _e('Select a region', 'patricia'); ?></option>';
              for(key in data) {
                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
              }

              $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="sRegion" id="regionId" ></select></div>');
              $("#sRegion-side").remove();

              $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="sCity" id="cityId" ></select></div>');
              $("#sCity-side").remove();
              
              $("#regionId").val("");
              $("#uniform-regionId").find('span').text('<?php _e('Select a region', 'patricia'); ?>');
            } else {

              $("#regionId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a region', 'patricia')); ?>" type="text" name="sRegion" id="sRegion-side" />');
              $("#regionId").parent().remove();
              
              $("#cityId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a city', 'patricia')); ?>" type="text" name="sCity" id="sCity-side" />');
              $("#cityId").parent().remove();

              $("#sCity-side").val('');
            }

            $("#regionId").html(result);
            $("#cityId").html('<option selected value=""><?php _e('Select a city', 'patricia'); ?></option>');
            $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');
          }
         });

       } else {

         // add empty select
         $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="sRegion" id="regionId" ><option value=""><?php _e('Select a region', 'patricia'); ?></option></select></div>');
         $("#sRegion-side").remove();
         
         $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="sCity" id="cityId" ><option value=""><?php _e('Select a city', 'patricia'); ?></option></select></div>');
         $("#sCity-side").remove();

         if( $("#regionId").length > 0 ){
           $("#regionId").html('<option value=""><?php _e('Select a region', 'patricia'); ?></option>');
         } else {
           $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="sRegion" id="regionId" ><option value=""><?php _e('Select a region', 'patricia'); ?></option></select></div>');
           $("#sRegion-side").remove();
         }

         if( $("#cityId").length > 0 ){
           $("#cityId").html('<option value=""><?php _e('Select a city', 'patricia'); ?></option>');
         } else {
           $("#sCity-side").parent().before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="sCity" id="cityId" ><option value=""><?php _e('Select a city', 'patricia'); ?></option></select></div>');
           $("#sCity-side").parent().remove();
         }

         $("#regionId").attr('disabled',true);
         $("#uniform-regionId").addClass('disabled');
         $("#uniform-regionId").find('span').text('<?php _e('Select a region', 'patricia'); ?>');
         $("#cityId").attr('disabled',true);
         $("#uniform-cityId").addClass('disabled');
         $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');

      }
    });

    $("#regionId").live("change",function(){
      var pk_c_code = $(this).val();
      var url = '<?php echo osc_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
      var result = '';

      if(pk_c_code != '') {
        
        $("#cityId").attr('disabled',false);
        $("#uniform-cityId").removeClass('disabled');

        $.ajax({
          type: "POST",
          url: url,
          dataType: 'json',
          success: function(data){
            var length = data.length;
            if(length > 0) {
              result += '<option selected value=""><?php _e('Select a city', 'patricia'); ?></option>';
              for(key in data) {
                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
              }

              $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="sCity" id="cityId" ></select></div>');
              $("#sCity-side").remove();

              $("#cityId").val("");
              $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');
            } else {
              result += '<option value=""><?php _e('No cities found', 'patricia'); ?></option>';
              $("#cityId").parent().before('<input type="text" placeholder="<?php echo osc_esc_js(__('Enter a city', 'patricia')); ?>" name="sCity" id="sCity-side" />');
              $("#cityId").parent().remove();
            }
            $("#cityId").html(result);
          }
        });
      } else {
        $("#cityId").attr('disabled',true);
        $("#uniform-cityId").addClass('disabled');
        $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');
      }
    });

    if( $("#regionId").attr('value') == "")  {
      $("#cityId").attr('disabled',true);
      $("#uniform-cityId").addClass('disabled');
    } else {
      $("#cityId").attr('disabled',false);
      $("#uniform-cityId").removeClass('disabled');
    }

    if($("#countryId").length != 0) {
      if( $("#countryId").attr('value') == "")  {
        $("#regionId").attr('disabled',true);
        $("#uniform-regionId").addClass('disabled');
      }
    }


    //Make sure when select loads after input, span wrap is correctly filled
    $("#countryId").live('change', function() {
      $(this).parent().find('span').text($(this).find("option:selected" ).text());
    });

    $("#regionId").live('change', function() {
      $(this).parent().find('span').text($(this).find("option:selected" ).text());
    });

    $("#cityId").live('change', function() {
      $(this).parent().find('span').text($(this).find("option:selected" ).text());
    });

  });
</script>


<?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>