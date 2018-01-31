<?php 
  // GET IF PAGE IS LOADED VIA QUICK VIEW
  $content_only = (Params::getParam('content_only') == 1 ? true : false);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
</head>

<body<?php if($content_only) { ?> id="content_only"<?php } ?>>
  <?php if(!$content_only) { ?>
    <?php osc_current_web_theme_path('header.php') ; ?>
    <?php if( osc_item_is_expired () ) { ?><div id="exp_box"></div><div id="exp_mes"><?php _e('This listing has expired.', 'patricia'); ?></div><?php } ?>
  <?php } ?>

  <div id="listing" class="content list">
    <?php if(!$content_only) { ?>

      <!-- LEFT SIDEBAR -->
      <div id="sidebar" class="noselect">
        <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
          <div id="s-tools">
            <div class="lead"><i class="fa fa-wrench"></i> <?php _e('Seller\'s tools', 'patricia'); ?></div>
            <div class="text"><?php _e('You are seller of this item and therefore you can edit or delete it.', 'patricia'); ?></div>
            <a href="<?php echo osc_item_edit_url(); ?>"><i class="fa fa-arrow-right"></i><?php _e('Edit listing', 'patricia'); ?></a>
            <a href="<?php echo osc_item_delete_url(); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this listing? This action cannot be undone.', 'patricia'); ?>?')"><i class="fa fa-arrow-right"></i><?php _e('Delete listing', 'patricia'); ?></a>
          </div>
        <?php } ?>

        <div id="sidebar-search">
          <form action="<?php echo osc_base_url(true); ?>" method="get" onsubmit="return doSearch()" class="nocsrf">
            <input type="hidden" name="page" value="search" />
            <input type="hidden" name="sCategory" value="<?php echo Params::getParam('sCategory'); ?>" />
            <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
            <?php $allowedTypesForSorting = Search::getAllowedTypesForSorting(); ?>
            <?php $allowedTypesForSorting[osc_search_order_type()] = isset($allowedTypesForSorting[osc_search_order_type()]) ? $allowedTypesForSorting[osc_search_order_type()] : ''; ?>
            <input type="hidden" name="iOrderType" value="<?php echo $allowedTypesForSorting[osc_search_order_type()]; ?>" />
            <?php foreach(osc_search_user() as $userId) { ?>
              <input type="hidden" name="sUser[]" value="<?php echo $userId; ?>" />
            <?php } ?>
            <input type="hidden" name="sCompany" class="sCompany" id="sCompany" value="<?php echo Params::getParam('sCompany');?>" />
            <input type="hidden" id="priceMin" name="sPriceMin" value="<?php echo osc_search_price_min(); ?>" size="6" maxlength="6" />
            <input type="hidden" id="priceMax" name="sPriceMax" value="<?php echo osc_search_price_max(); ?>" size="6" maxlength="6" />

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
                    if(osc_count_countries() <= 1) {
                      $s_country = Country::newInstance()->listAll();
                      $s_country = $s_country[0];
                    }
                  ?>

                  <select id="countryId" name="sCountry">
                    <option value=""><?php _e('Select a country', 'patricia'); ?></option>

                    <?php foreach ($aCountries as $country) {?>
                      <?php $country['pk_c_code'] = isset($country['pk_c_code']) ? $country['pk_c_code'] : ''; ?>
                      <?php $s_country['pk_c_code'] = isset($s_country['pk_c_code']) ? $s_country['pk_c_code'] : ''; ?>
                      <option value="<?php echo $country['pk_c_code']; ?>" <?php if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] = $country['pk_c_code']) { ?>selected="selected"<?php } ?>><?php echo $country['s_name'] ; ?></option>
                    <?php } ?>
                  </select>
                </div>

              
                <?php
                  $current_country = Params::getParam('country') <> '' ? Params::getParam('country') : Params::getParam('sCountry');
                  if($current_country <> '') {
                    $aRegions = Region::newInstance()->findByCountry($current_country);
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
                  if(!is_int($current_region) && $current_region <> '') {
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
                
                      <?php foreach ($aCities as $city) {?>
                        <option value="<?php echo $city['pk_i_id']; ?>" <?php if(Params::getParam('sCity') == $city['pk_i_id'] or Params::getParam('sCity') == $city['s_name']) { ?>selected="selected"<?php } ?>><?php echo $city['s_name']; ?></option>
                      <?php } ?>
                    </select>
                  <?php } else { ?>
                    <input type="text" name="sCity" id="sCity-side" value="<?php echo Params::getParam('sCity'); ?>" placeholder="<?php echo osc_esc_html(__('Enter a city', 'patricia')); ?>" />
                  <?php } ?>
                </div>
              </fieldset>

              <?php if( osc_price_enabled_at_items() ) { ?>
                <fieldset>
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

              <button type="submit" id="blue"><?php _e('Search', 'patricia') ; ?></button>
            </div>

            <div class="clear"></div>
          </form>
        </div>

        <div class="clear"></div>


        <!-- Regine categories -->
        <?php 
          $current_cat_id = osc_item_category_id(); 
          $current_cat = Category::newInstance()->findByPrimaryKey($current_cat_id);
          $parent_cat = Category::newInstance()->findByPrimaryKey($current_cat['fk_i_parent_id']);
          $superparent_cat = Category::newInstance()->findByPrimaryKey($parent_cat['fk_i_parent_id']);

          $parent_categories = Category::newInstance()->hierarchy($parent_cat['pk_i_id']);
          $parent_categories = $parent_categories[0];

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
              <a class="level-up" href="<?php echo osc_search_url(array('sCategory' => $superparent_cat['pk_i_id'])); ?>"><?php _e('up', 'patricia'); ?> <i class="fa fa-level-up"></i></a>
            <?php } ?>

            <div id="show-hide" class="closed"></div>
          </h3>

          <div class="menu-wrap">
            <div class="category">
              <?php if($parent_categories['pk_i_id'] <> '') { ?>
                <h4>
                  <a href="<?php echo osc_search_url(array('sCategory' => $parent_categories['pk_i_id'])); ?>"><?php echo $parent_categories['s_name'] ; ?> </a> <span>(<?php echo $parent_categories['i_num_items'] ; ?>)</span>
                </h4>
              <?php } ?>

              <ul class="subcategory">
                <?php if($parent_cat['pk_i_id'] == '') { ?>
                  <li><a class="bold" href="<?php echo osc_search_url(array('sCategory' => 0)); ?>"><?php _e('All categories', 'patricia'); ?></a></li>
                <?php } ?>

                <?php foreach( $sibling_categories as $scat ) { ?> 
                  <li <?php if ($current_cat_id == $scat['pk_i_id']) { echo ' class="is_child" '; }  ?>><a href="<?php echo osc_search_url(array('sCategory' => $scat['pk_i_id'])); ?>"><?php echo $scat['s_name'] ; ?></a>

                    <?php if ($current_cat_id == $scat['pk_i_id'] and !empty($child_categories)) { ?>
                      <ul class="sub-subcategory">
                        <?php foreach ( $child_categories as $ccat ) { ?> 
                          <li><a href="<?php echo osc_search_url(array('sCategory' => $ccat['pk_i_id'])); ?>"><?php echo $ccat['s_name'] ; ?></a></li>
                        <?php } ?>
                      </ul>
                    <?php }  ?>
                  </li>
                <?php } ?>
              </ul>
            </div> 
          </div>
        </div>

        <?php if (function_exists('show_qrcode')) { ?>
          <div class="qr-friendly noselect">
            <div class="text"><?php _e('Open your listing with mobile', 'patricia'); ?></div>
            <?php show_qrcode(); ?>
          </div>
        <?php } ?>

        <div class="mobile-friendly mobile-item noselect">
          <div class="text"><?php _e('Available also on your mobile device', 'patricia'); ?></div>
          <img src="<?php echo osc_current_web_theme_url();?>images/side_mobile.png" />
        </div>

        <div class="share-friendly noselect">
          <div class="text"><?php _e('Share us and become our fan', 'patricia'); ?></div>
          <img src="<?php echo osc_current_web_theme_url();?>images/side_share.png" />
        </div>

        <?php if(osc_get_preference('theme_adsense', 'patricia_theme') == 1) { ?>
          <?php if(osc_get_preference('banner_item', 'patricia_theme') <> '') { ?>
            <div class="item-google">
              <?php echo osc_get_preference('banner_item', 'patricia_theme'); ?>
            </div>        
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>


    <!-- RIGHT LISTING BODY -->
    <div id="main">
      <div id="left">

        <!-- IMAGE BOX -->
        <?php if( osc_images_enabled_at_items() and osc_count_item_resources() > 0 ) { ?>  
          <div id="pictures">
            <a id="big-img" href="<?php echo osc_resource_url(); ?>" <?php if(!$content_only) { ?>rel="image_group"<?php } ?> title="<?php echo osc_esc_html(osc_item_title()); ?>" <?php if($content_only) {?>onclick="return false;"<?php } ?>>
              <img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - 1/<?php echo osc_count_item_resources();?>" />
              <?php if(!$content_only) { ?>
                <div class="max"><i class="fa fa-search"></i> <?php _e('Maximize', 'patricia'); ?></div>
              <?php } ?>
            </a>

            <div class="img-bottom <?php echo (osc_count_item_resources() <= 4 ? 'not_full' : ''); ?>">
              <div id="scroll" class="prev">
                <div class="active"><i class="fa fa-angle-left"></i></div>
                <div class="inactive"><i class="fa fa-angle-left"></i></div>
              </div>

              <div class="img-bar">
                <?php osc_reset_resources(); ?>

                <div class="wrap">
                  <?php for( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                    <span class="small-img <?php echo ($i == 0 ? 'selected' : ''); ?>"><img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?> / <?php echo osc_count_item_resources();?>" /></span>
                  <?php } ?>
                </div>
              </div>

              <div id="scroll" class="next">
                <div class="active"><i class="fa fa-angle-right"></i></div>
                <div class="inactive"><i class="fa fa-angle-right"></i></div>
              </div>
            </div>

            <?php if(!$content_only) { ?>
              <div class="image-rel-hidden">
                <?php osc_reset_resources(); ?>
                <?php for( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                  <?php if($i <> 0) { ?>
                    <a rel="image_group" href="<?php echo osc_resource_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'patricia'); ?> <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>"><img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>" /></a>
                  <?php } ?>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        <?php } else { ?>
          <div id="image-empty">
            <i class="fa fa-image"></i>
            <span><?php _e('No pictures added', 'patricia'); ?></span>
          </div>
        <?php } ?>


        <?php if(!$content_only) { ?>
          <!-- SOCIAL SHARING -->
          <div class="listing-share">
            <?php osc_reset_resources(); ?>
            <span><?php _e('Share', 'patricia'); ?></span> 
            <a class="single single-facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'patricia')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo osc_item_url(); ?>"><i class="fa fa-facebook-square"></i></a> 
            <a class="single single-google-plus" title="<?php echo osc_esc_html(__('Share on Google Plus', 'patricia')); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo osc_item_url(); ?>"><i class="fa fa-google-plus-square"></i></a> 
            <a class="single single-twitter" title="<?php echo osc_esc_html(__('Share on Twitter', 'patricia')); ?>" target="_blank" href="https://twitter.com/home?status=<?php echo osc_item_title(); ?>"><i class="fa fa-twitter-square"></i></a> 
            <a class="single single-pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'patricia')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo osc_item_url(); ?>&media=<?php echo osc_resource_url(); ?>&description=<?php echo htmlspecialchars(osc_item_title()); ?>"><i class="fa fa-pinterest-square"></i></a> 
          </div>


          <!-- ITEM TOOLS -->
          <div id="tools">
            <a href="<?php echo osc_item_send_friend_url(); ?>" rel="nofollow"><i class="fa fa-paper-plane"></i> <?php _e('Send to friend', 'patricia'); ?></a>

            <?php if (function_exists('show_printpdf')) { ?>
              <a id="print_pdf" href="<?php echo osc_base_url(); ?>oc-content/plugins/printpdf/download.php?item=<?php echo osc_item_id(); ?>"><i class="fa fa-file-pdf-o"></i> <?php _e('Show PDF sheet', 'patricia'); ?></a>
            <?php } ?>
           
            <?php if (function_exists('print_ad')) { print_ad(); } ?>

            <a href="javascript:void(0)" rel="nofollow"><i class="fa fa-barcode"></i> <?php _e('Listing', 'patricia'); ?> #<?php echo osc_item_id(); ?></a>
          </div>
        <?php } ?>


        <!-- BUYERS PROTECTION -->
        <div id="protect">
          <div class="warn"><i class="fa fa-umbrella"></i> <span><?php _e('Buyer\'s protection', 'patricia'); ?></span></div>
          <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Act locally', 'patricia'); ?></span> <?php _e('to avoid scam', 'patricia'); ?></div>
          <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Anonymous payment gateways', 'patricia'); ?></span> <?php _e('are very unsafe', 'patricia'); ?></div>
          <div class="elem"><i class="fa fa-check-circle-o"></i> <span class="bold"><?php _e('Cheques payments', 'patricia'); ?></span> <?php _e('are not recommended', 'patricia'); ?></div>
        </div>
      </div>

      <div id="right">
        <h2><?php echo ucfirst(osc_item_title()); ?></h2>
        <div class="short-desc"><?php echo osc_highlight(osc_item_description(), 200); ?></div>
        <a href="#" class="desc-more"><?php _e('Show more', 'patricia'); ?><i class="fa fa-chevron-circle-down"></i></a>

        <div class="status">
          <a class="green" href="<?php echo osc_search_category_url();?>"><?php echo osc_item_category(); ?></a>
          <span class="normal">#<?php echo osc_item_id(); ?></span>
          <span class="normal"><?php echo '<strong>' . osc_item_views() . '</strong>'; ?> <?php echo (osc_item_views() == 1 ? __('view', 'patricia') : __('views', 'patricia')); ?></span>

          <?php if(!$content_only) { ?>
            <div id="report">
              <i class="fa fa-bullhorn"></i>
              <div class="cont-wrap">
                <div class="cont">
                  <a id="item_spam" href="<?php echo osc_item_link_spam() ; ?>" rel="nofollow"><?php _e('spam', 'patricia') ; ?></a>
                  <a id="item_bad_category" href="<?php echo osc_item_link_bad_category() ; ?>" rel="nofollow"><?php _e('misclassified', 'patricia') ; ?></a>
                  <a id="item_repeated" href="<?php echo osc_item_link_repeated() ; ?>" rel="nofollow"><?php _e('duplicated', 'patricia') ; ?></a>
                  <a id="item_expired" href="<?php echo osc_item_link_expired() ; ?>" rel="nofollow"><?php _e('expired', 'patricia') ; ?></a>
                  <a id="item_offensive" href="<?php echo osc_item_link_offensive() ; ?>" rel="nofollow"><?php _e('offensive', 'patricia') ; ?></a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>

        <?php if( osc_price_enabled_at_items() ) { ?>
          <div class="price">
            <span class="lead"><?php _e('Price', 'patricia'); ?></span>
            <span class="value"><?php echo osc_item_formated_price(); ?></span>
          </div>
        <?php } ?>

        <div class="gray-box">
          <?php if(function_exists('profile_picture_show')) { ?>
            <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
              <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'patricia')); ?>">
                <?php profile_picture_show(null, 'item', 33); ?>
              </a>
            <?php } else { ?>
              <?php profile_picture_show(null, 'item', 33); ?>
            <?php } ?>
          <?php } ?>

          <?php if (osc_item_pub_date() != '') { ?><div class="pub-date"><span><i class="fa fa-check-square-o"></i> <?php _e('Published', 'patricia'); ?></span> <?php echo osc_format_date(osc_item_pub_date()); ?></div><?php } ?>
          <?php if (osc_item_mod_date() != '') { ?><div class="mod-date"><span><i class="fa fa-edit"></i> <?php _e('Modified', 'patricia'); ?></span> <?php echo osc_format_date(osc_item_mod_date()); ?></div><?php } ?>

          <?php 
            if(osc_item_user_id() <> 0) {
              $item_user = User::newInstance()->findByPrimaryKey(osc_item_user_id());
            }
          ?>

          <div class="phone">
            <?php 
              $mobile = '';
              if($mobile == '') { $mobile = osc_item_city_area(); }      
              if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_mobile']; }      
              if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_land']; }      
              if($mobile == '') { $mobile = __('No phone number', 'patricia'); }      
            ?> 

            <span><i class="fa fa-phone"></i> <?php _e('Phone', 'patricia'); ?></span> 
            <a id="phone-show" href="#" rel="<?php echo $mobile; ?>" title="<?php echo osc_esc_html(__('Click to show phone number', 'patricia')); ?>">
              <?php 
                if(strlen($mobile) > 3 and $mobile <> __('No phone number', 'patricia')) {
                  echo substr($mobile, 0, strlen($mobile) - 3) . 'XXX'; 
                } else {
                  echo $mobile;
                }
              ?>
             </a>
             <a class="p-desc" href="#">(<?php _e('Click to show', 'patricia'); ?>)</a>
          </div>

          <?php if( osc_item_show_email() ) { ?>
            <div class="show-email"><span><i class="fa fa-envelope-o"></i> <?php _e('E-mail', 'patricia'); ?></span> <?php echo osc_item_contact_email(); ?></div>
          <?php } ?>

          <div class="seller">
            <?php
              $c_name = '';
              if(osc_item_contact_name() <> '' and osc_item_contact_name() <> __('Anonymous', 'patricia')) {
                $c_name = osc_item_contact_name();
              }

              if($c_name == '' and $item_user['s_name'] <> '') { 
                $c_name = $item_user['s_name'];
              }

              if($c_name == '') {
                $c_name = __('Anonymous', 'patricia');
              }
            ?>
            <span><i class="fa fa-user"></i> <?php _e('Seller', 'patricia'); ?></span>
            <?php if(osc_item_user_id() <> 0) { ?>
              <div class="name-wrap">
                <a class="name" href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php echo $c_name; ?></a>
                <div class="reg-date">
                  <?php if(function_exists('show_feedback_overall')) { ?>
                    <span class="feedback"><?php echo show_feedback_overall(); ?></span>
                  <?php } else { ?>
                    (<?php echo __('reg. on', 'patricia') . ' ' . osc_format_date(osc_user_regdate()); ?>)
                  <?php } ?>
                </div>        
              </div>

              <?php if(function_exists('seller_post') && !$content_only) { ?>
                <div class="other">
                  <?php seller_post(); ?><span class="num">(<?php $user = User::newInstance()->findByPrimaryKey(osc_item_user_id());$num_items_user = $user['i_items'];echo $num_items_user;?>)</span>
                </div>
              <?php } ?>
            <?php } else { ?>
              <div class="name-wrap">
                <div class="name"><?php echo $c_name; ?></div>
              </div>
            <?php } ?>
          </div>

          <?php if(!$content_only) { ?>
            <div class="seller-contact round3"><?php _e('Contact seller', 'patricia'); ?></div>
            <?php if (function_exists('watchlist')) { watchlist(); } ?>
          <?php } ?>
        </div>

        <div class="locations">
          <div class="lead"><i class="fa fa-truck"></i><?php _e('Location of item', 'patricia'); ?></div>

          <?php if(osc_count_countries() <= 1 && osc_item_region() == '' && osc_item_city() == '' or  osc_item_country() == '' && osc_item_region() == '' && osc_item_city()) {?>
            <div class="empty"><?php _e('Location of item was not specified', 'patricia'); ?></div>
          <?php } ?>

          <?php if(osc_item_country() <> '' and osc_count_countries() > 1) { ?>
            <div class="elem">
              <span class="left"><?php _e('Country', 'patricia'); ?></span>
              <span class="right"><?php echo osc_item_country(); ?></span>
            </div>
          <?php } ?>

          <?php if(osc_item_region() <> '') { ?>
            <div class="elem">
              <span class="left"><?php _e('Region', 'patricia'); ?></span>
              <span class="right"><?php echo osc_item_region(); ?></span>
            </div>
          <?php } ?>

          <?php if(osc_item_city() <> '') { ?>
            <div class="elem">
              <span class="left"><?php _e('City', 'patricia'); ?></span>
              <span class="right"><?php echo osc_item_city(); ?></span>
            </div>
          <?php } ?>

          <?php if(osc_item_address() <> '') { ?>
            <div class="elem">
              <span class="left"><?php _e('Address', 'patricia'); ?></span>
              <span class="right"><?php echo osc_item_address(); ?></span>
            </div>
          <?php } ?>

          <div class="map">
            <?php osc_run_hook('location') ; ?>
          </div>
        </div>
      </div>

      <?php $has_custom = false; ?>
      <?php if( osc_count_item_meta() >= 1 ) { ?>
        <div id="custom_fields">
          <div class="meta_list">
            <h3><?php _e('Additional information','patricia'); ?></h3>

            <?php $class = 'odd'; ?>
            <?php while( osc_has_item_meta() ) { ?>
              <?php if(osc_item_meta_value()!='') { ?>
                <?php $has_custom = true; ?>
                <div class="meta <?php echo $class; ?>">
                  <span><?php echo osc_item_meta_name(); ?>:</span> <?php echo osc_item_meta_value(); ?>
                </div>
              <?php } ?>

              <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
            <?php } ?>
          </div>

        </div>
      <?php } ?>

      <div id="plugin-details">
        <?php osc_run_hook('item_detail', osc_item() ) ; ?>  
      </div>

      <?php if(!$content_only) { ?>
        <?php if(function_exists('related_ads_start')) { related_ads_start(); } ?>
      <?php } ?>

      <div id="more-info">
        <div class="heads">
          <a class="elem selected" id="more-info-desc" href="#tab1"><?php _e('Description', 'patricia'); ?></a>
          <?php if( osc_comments_enabled() && !$content_only) { ?>
            <a class="elem" id="more-info-comments" href="#tab2"><?php _e('Comments', 'patricia'); ?></a>
          <?php } ?>

          <?php if(!$content_only) { ?>
            <a class="elem" id="more-info-contact" href="#tab3"><?php _e('Contact seller', 'patricia'); ?></a>
          <?php } ?>
        </div>
      
        <div class="more-information">
          <div class="elem-more" id="tab1">
            <?php echo osc_item_description(); ?>
          </div>


          <!-- MORE INFO - COMMENTS SECTION -->
          <?php if( osc_comments_enabled() && !$content_only) { ?>
            <div class="elem-more" id="tab2">
              <!-- LIST OF COMMENTS -->
              <div id="comments">
                <?php if( osc_count_item_comments() >= 1 ) { ?>
                  <?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?>
                    <div class="empty"><?php _e('Comments can be published by registered users only.', 'patricia'); ?> <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Sign in', 'patricia'); ?></a> <?php _e('and leave comment', 'patricia'); ?>.</div>
                  <?php } ?>

                  <h3 class="title_block">
                    <span>
                      <i class="fa fa-comments-o"></i> <?php _e('Comments', 'patricia'); ?>
                    </span>

                    <?php echo __('on', 'patricia') . ' ' . ucfirst(osc_highlight(osc_item_title(), 50)); ?>
                    <div class="smalli">(<?php echo osc_item_total_comments();?>)</div>
                  <?php } else { ?>
                    <?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?>
                      <div class="empty"><?php _e('No comments added yet.', 'patricia'); ?> <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Log in', 'patricia'); ?></a> <?php _e('and be first to leave comment!', 'patricia'); ?></div>
                    <?php } else { ?>
                      <div class="empty"><?php _e('No comments added yet. Be first to leave comment!', 'patricia'); ?></div>
                    <?php } ?>
                  <?php } ?>
                </h3>

                <ul id="comment_error_list"></ul>
                <?php CommentForm::js_validation(); ?>
                <?php if( osc_count_item_comments() >= 1 ) { ?>
                  <div class="comments_list">
                    <?php $class = 'even'; ?>
                    <?php while ( osc_has_item_comments() ) { ?>
                      <div class="comment-wrap <?php echo $class; ?>">
                        <div class="hide"><?php _e('Click to hide', 'patricia'); ?></div>
                        <div class="comment-image">
                          <?php if(function_exists('profile_picture_show')) { profile_picture_show(40, 'comment'); } ?>
                        </div>
                        <div class="comment">
                          <h4><span class="bold"><?php if(osc_comment_title() == '') { _e('Review', 'patricia'); } else { echo osc_comment_title(); } ?></span> <?php _e('by', 'patricia') ; ?> <?php if(osc_comment_title() == '') { _e('Anonymous', 'patricia'); } else { echo osc_comment_author_name(); } ?>:</h4>
                          <div class="body"><?php echo osc_comment_body() ; ?></div>
                          <?php if ( osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id()) ) { ?>
                            <a rel="nofollow" class="remove" href="<?php echo osc_delete_comment_url(); ?>" title="<?php echo osc_esc_html(__('Delete your comment', 'patricia')); ?>"><?php _e('Delete', 'patricia'); ?></a>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="clear"></div>
                      <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
                    <?php } ?>
                    <div class="pagination"><?php echo osc_comments_pagination(); ?></div>
                  </div>
                <?php } ?>

                <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                  <div id="comment_form_wrap">
                    <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="comment_form" id="comment_form">
                      <fieldset>
                        <h4 class="add-new title_block"><span><?php _e('Add', 'patricia'); ?></span> <?php _e('your comment', 'patricia'); ?></h4>
                        <div class="clear"></div>
                        <input type="hidden" name="action" value="add_comment" />
                        <input type="hidden" name="page" value="item" />
                        <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />

                        <div class="add-form">
                          <?php if(osc_is_web_user_logged_in()) { ?>
                            <input type="hidden" name="authorName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
                            <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
                          <?php } else { ?>
                            <div class="third">
                              <label for="authorName"><?php _e('Name', 'patricia') ; ?></label> 
                              <?php CommentForm::author_input_text(); ?>
                              <div class="small-info"><?php _e('Real name or Username', 'patricia'); ?></div>
                            </div>
                            <div class="third">
                              <label for="authorEmail"><span><?php _e('E-mail', 'patricia') ; ?></span><span class="req">*</span></label> 
                              <?php CommentForm::email_input_text(); ?>
                              <div class="small-info"><?php _e('Will not be published', 'patricia'); ?></div>
                            </div>                  
                          <?php }; ?>
                          <div class="third" id="tit">
                            <label for="title"><?php _e('Title', 'patricia') ; ?></label>
                            <?php CommentForm::title_input_text(); ?>
                            <div class="small-info"><?php _e('Review, feedback or question', 'patricia'); ?></div>
                          </div>
                      
                          <?php CommentForm::body_input_textarea(); ?>

                          <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>
                          <button type="submit" id="blue"><?php _e('Send comment', 'patricia') ; ?></button>
                          <div onclick="document.getElementById('body').value = '';document.getElementById('title').value = '';document.getElementById('authorName').value = '';document.getElementById('authorEmail').value = '';" class="clear-button-comment button gray-button round3"><?php _e('Clear', 'patricia'); ?></div>
                        </div>
                      </fieldset>
                    </form>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php } ?>


          <?php if(!$content_only) { ?>
            <div class="elem-more" id="tab3">

              <!-- SELLER CONTACT FORM -->
              <?php if( osc_item_is_expired () ) { ?>
                <div class="empty">
                  <?php _e('This listing expired, you cannot contact seller.', 'patricia') ; ?>
                </div>
              <?php } else if( (osc_logged_user_id() == osc_item_user_id()) && osc_logged_user_id() != 0 ) { ?>
                <div class="empty">
                  <?php _e('It is your own listing, you cannot contact yourself.', 'patricia') ; ?>
                </div>
              <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
                <div class="empty">
                  <?php _e('You must log in or register a new account in order to contact the advertiser.', 'patricia') ; ?>
                </div>
              <?php } else { ?> 
                <ul id="error_list"></ul>
                <?php ContactForm::js_validation(); ?>

                <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact_form">
                  <input type="hidden" name="action" value="contact_post" />
                  <input type="hidden" name="page" value="item" />
                  <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />

                  <?php osc_prepare_user_info() ; ?>

                  <h3>
                    <i class="fa fa-envelope-o"></i>
                    <span><?php _e('Send message to seller', 'patricia') ; ?></span>
                  </h3>

                  <fieldset>
                    <div class="row first">
                      <label><?php _e('Name', 'patricia') ; ?></label>
                      <?php ContactForm::your_name(); ?>
                    </div>

                    <div class="row second">
                      <label><span><?php _e('E-mail', 'patricia'); ?></span><span class="req">*</span></label>
                      <?php ContactForm::your_email(); ?>
                    </div>

                    <div class="row third">
                      <label><span><?php _e('Phone number', 'patricia'); ?></span></label>
                      <?php ContactForm::your_phone_number(); ?>
                    </div>

                    <div class="row full">
                      <label><span><?php _e('Message', 'patricia') ; ?></span><span class="req">*</span></label>
                      <?php ContactForm::your_message(); ?>
                    </div>

                    <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>

                    <?php osc_run_hook('item_contact_form', osc_item_id()); ?>

                    <!-- ReCaptcha -->
                    <?php if( osc_recaptcha_public_key() ) { ?>
                      <script type="text/javascript">
                        var RecaptchaOptions = {
                          theme : 'custom',
                          custom_theme_widget: 'recaptcha_widget'
                        };
                      </script>

                      <div id="recaptcha_widget">
                        <div id="recaptcha_image"><img /></div>
                        <span class="recaptcha_only_if_image"><?php _e('Enter the words above','patricia'); ?>:</span>
                        <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                        <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'patricia'); ?></a></div>
                      </div>
                    <?php } ?>
                    <?php osc_show_recaptcha(); ?>

                    <button type="submit" id="green"><?php _e('Send message', 'patricia') ; ?></button>
                    <div onclick="document.getElementById('message').value = '';document.getElementById('yourName').value = '';document.getElementById('yourEmail').value = '';" class="clear-button button gray-button round3"><?php _e('Clear', 'patricia'); ?></div>
                  </fieldset>
                </form>
              <?php } ?>

            </div>
          <?php } ?>

        </div>
      </div>
    </div>  
  </div>

  <?php if($content_only) { ?>
    <div class="visit-wrap">
      <a id="visit" target="_parent" href="<?php echo osc_item_url(); ?>"><?php _e('View listing', 'patricia'); ?> <i class="fa fa-angle-double-right"></i></a>
    </div>
  <?php } ?>
     

  <script type="text/javascript">
    <!-- SHOW PHONE NUMBER ON CLICK -->
    $(document).ready(function(){
      $('#phone-show, .p-desc').click(function(){
        if($(this).attr('href') == '#') {
          $('#phone-show').text($('#phone-show').attr('rel')).css('font-weight', 'bold');
          $('#phone-show').siblings('.p-desc').text('<?php echo osc_esc_js(__('(Click to call)', 'patricia')); ?>');
          $('#phone-show, .p-desc').attr('href', 'tel:' + $('#phone-show').attr('rel'));
          return false;
        }
      });
    });
  </script>
     
  <!-- Scripts -->
  <script type="text/javascript">
  $(document).ready(function(){
    $('.comment-wrap').hover(function(){
      $(this).find('.hide').fadeIn(200);}, 
      function(){
      $(this).find('.hide').fadeOut(200);
    });

    $('.comment-wrap .hide').click(function(){
      $(this).parent().fadeOut(200);
    });

    $('#but-con').click(function(){
      $(".inner-block").slideToggle();
      $("#rel_ads").slideToggle();
    }); 

    
    <?php if(!$has_custom) { echo '$("#custom_fields").hide();';} ?>
  });

  $(document).mouseup(function (e) {
    var container = $('.watchlist a');
    if (!container.is(e.target) && container.has(e.target).length === 0) { container.hide('slow'); }
  });
  </script>


  <?php if(!$content_only) { ?>
    <!-- JAVASCRIPT FOR PRICE SLIDER IN SEARCH BOX -->
    <script>
      <?php
        $max = patricia_max_price($current_cat_id, Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity'));
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
              $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'patricia')); ?>" );
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
            $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'patricia')); ?>" );
            $( "#amount-max" ).text( "" );
            $( "#amount-del" ).hide(0);
          } else {
            $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'patricia')); ?>" );
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
                  $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'patricia')); ?>');
                } else {

                  $("#regionId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a region', 'patricia')); ?>" type="text" name="sRegion" id="sRegion-side" />');
                  $("#regionId").parent().remove();
                  
                  $("#cityId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a city', 'patricia')); ?>" type="text" name="sCity" id="sCity-side" />');
                  $("#cityId").parent().remove();

                  $("#sCity-side").val('');
                }

                $("#regionId").html(result);
                $("#cityId").html('<option selected value=""><?php _e('Select a city', 'patricia'); ?></option>');
                $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'patricia')); ?>');
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
             $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'patricia')); ?>');
             $("#cityId").attr('disabled',true);
             $("#uniform-cityId").addClass('disabled');
             $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'patricia')); ?>');

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
                  $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'patricia')); ?>');
                } else {
                  result += '<option value=""><?php _e('No cities found', 'patricia'); ?></option>';
                  $("#cityId").parent().before('<input type="text" placeholder="<?php echo osc_esc_html(__('Enter a city', 'patricia')); ?>" name="sCity" id="sCity-side" />');
                  $("#cityId").parent().remove();
                }
                $("#cityId").html(result);
              }
            });
          } else {
            $("#cityId").attr('disabled',true);
            $("#uniform-cityId").addClass('disabled');
            $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'patricia')); ?>');
          }
        });

        if( $("#regionId").attr('value') == "")  {
          $("#cityId").attr('disabled',true);
          $("#uniform-cityId").addClass('disabled');
        }

        if($("#countryId").length != 0) {
          if( $("#countryId").attr('value') == "")  {
            $("#regionId").attr('disabled',true);
            $("#uniform-regionId").addClass('disabled');
          }
        }

        //Make sure when select loads after input, span wrap is correctly filled
        $(".row").on('change', '#cityId, #regionId', function() {
          $(this).parent().find('span').text($(this).find("option:selected" ).text());
        });

      });
    </script>
  <?php } ?>

  <?php if(!$content_only) { ?>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  <?php } ?>
</body>
</html>			