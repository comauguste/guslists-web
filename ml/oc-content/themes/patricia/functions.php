<?php
define('PATRICIA_THEME_VERSION', '1.3.5');

function patricia_theme_info() {
  return array(
    'name'    => 'OSClass Patricia Premium Theme',
    'version'   => '1.3.5',
    'description' => 'Premium OSClass Patricia Theme, most powerful theme for osclass',
    'author_name' => 'MB Themes',
    'author_url'  => 'http://mb-themes.com',
    'support_uri'  => 'http://forums.mb-themes.com/patricia-osclass-responsive-theme/',
    'locations'   => array('header', 'footer')
  );
}


// Cookies work
if(!function_exists('mb_set_cookie')) {
  function mb_set_cookie($name, $val) {
    Cookie::newInstance()->set_expires( 86400 * 30 );
    Cookie::newInstance()->push($name, $val);
    Cookie::newInstance()->set();
  }
}

if(!function_exists('mb_get_cookie')) {
  function mb_get_cookie($name) {
    return Cookie::newInstance()->get_value($name);
  }
}

if(!function_exists('mb_drop_cookie')) {
  function mb_drop_cookie($name) {
    Cookie::newInstance()->pop($name);
  }
}

// Ajax clear cookies
if(Params::getParam('clearCookieSearch') == 'done') {
  mb_drop_cookie('patricia-sCategory');
  //mb_drop_cookie('patricia-sPattern');
  mb_drop_cookie('patricia-sPriceMin');
  mb_drop_cookie('patricia-sPriceMax');
}

if(Params::getParam('clearCookieLocation') == 'done') {
  mb_drop_cookie('patricia-sCountry');
  mb_drop_cookie('patricia-sRegion');
  mb_drop_cookie('patricia-sCity');
  mb_drop_cookie('patricia-sLocator');
}

function patricia_manage_cookies() { 
  if(Params::getParam('page') == 'search') { $reset = true; } else { $reset = false; }
  if($reset) {
    if(Params::getParam('sCountry') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') {
      mb_set_cookie('patricia-sCountry', Params::getParam('sCountry')); 
      mb_set_cookie('patricia-sRegion', ''); 
      mb_set_cookie('patricia-sCity', ''); 
    }

    if(Params::getParam('sRegion') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') {
      if(is_numeric(Params::getParam('sRegion'))) {
        $reg = Region::newInstance()->findByPrimaryKey(Params::getParam('sRegion'));
      
        mb_set_cookie('patricia-sCountry', strtoupper($reg['fk_c_country_code'])); 
        mb_set_cookie('patricia-sRegion', $reg['s_name']); 
        mb_set_cookie('patricia-sCity', ''); 
      } else {
        mb_set_cookie('patricia-sRegion', Params::getParam('sRegion')); 
        mb_set_cookie('patricia-sCity', ''); 
      }
    }

    if(Params::getParam('sCity') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') {
      if(is_numeric(Params::getParam('sCity'))) {
        $city = City::newInstance()->findByPrimaryKey(Params::getParam('sCity'));
        $reg = Region::newInstance()->findByPrimaryKey($city['fk_i_region_id']);
        
        mb_set_cookie('patricia-sCountry', strtoupper($city['fk_c_country_code'])); 
        mb_set_cookie('patricia-sRegion', $reg['s_name']); 
        mb_set_cookie('patricia-sCity', $city['s_name']); 
      } else {
        mb_set_cookie('patricia-sCity', Params::getParam('sCity')); 
      }
    }


    if(Params::getParam('sCategory') <> '' and Params::getParam('sCategory') <> 0 or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sCategory', Params::getParam('sCategory')); }
    if(Params::getParam('sCategory') == 0 and osc_is_search_page()) { mb_set_cookie('patricia-sCategory', ''); }
    //if(Params::getParam('sPattern') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sPattern', Params::getParam('sPattern')); }
    if(Params::getParam('sPriceMin') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sPriceMin', Params::getParam('sPriceMin')); }
    if(Params::getParam('sPriceMax') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sPriceMax', Params::getParam('sPriceMax')); }
    if(Params::getParam('sLocator') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sLocator', Params::getParam('sLocator')); }
    if(Params::getParam('sCompany') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done' or Params::existParam('sCompany')) { mb_set_cookie('patricia-sCompany', Params::getParam('sCompany')); }
    if(Params::getParam('sShowAs') <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sShowAs', Params::getParam('sShowAs')); }
  }

  $cat = osc_search_category_id();
  $cat = isset($cat[0]) ? $cat[0] : '';

  $reg = osc_search_region();
  $cit = osc_search_city();

  if($cat <> '' and $cat <> 0 or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sCategory', $cat); }
  if($reg <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sRegion', $reg); }
  if($cit <> '' or Params::getParam('cookie-action') == 'done' or Params::getParam('cookie-action-side') == 'done') { mb_set_cookie('patricia-sCity', $cit); }

  Params::setParam('sCountry', mb_get_cookie('patricia-sCountry'));
  Params::setParam('sRegion', mb_get_cookie('patricia-sRegion'));
  Params::setParam('sCity', mb_get_cookie('patricia-sCity'));
  Params::setParam('sCategory', mb_get_cookie('patricia-sCategory'));
  //Params::setParam('sPattern', mb_get_cookie('patricia-sPattern'));
  Params::setParam('sPriceMin', mb_get_cookie('patricia-sPriceMin'));
  Params::setParam('sPriceMax', mb_get_cookie('patricia-sPriceMax'));
  Params::setParam('sLocator', mb_get_cookie('patricia-sLocator'));
  Params::setParam('sCompany', mb_get_cookie('patricia-sCompany'));
  Params::setParam('sShowAs', mb_get_cookie('patricia-sShowAs'));
}


// LOCATION FORMATER
function patricia_location_format($country = null, $region = null, $city = null) { 
  if($country <> '') {
    if(strlen($country) == 2) {
      $country_full = Country::newInstance()->findByCode($country);
    } else {
      $country_full = Country::newInstance()->findByName($country);
    }

    if($region <> '') {
      if($city <> '') {
        return $city . ' ' . __('in', 'patricia') . ' ' . $region . ($country_full['s_name'] <> '' ? ' (' . $country_full['s_name'] . ')' : '');
      } else {
        return $region . ' (' . $country_full['s_name'] . ')';
      }
    } else { 
      if($city <> '') {
        return $city . ' ' . __('in', 'patricia') . ' ' . $country_full['s_name'];
      } else {
        return $country_full['s_name'];
      }
    }
  } else {
    if($region <> '') {
      if($city <> '') {
        return $city . ' ' . __('in', 'patricia') . ' ' . $region;
      } else {
        return $region;
      }
    } else { 
      if($city <> '') {
        return $city;
      } else {
        return __('Location not entered', 'patricia');
      }
    }
  }
}

// Add All / Private /Company type to search page
function mb_filter_user_type() {
  if(Params::getParam('sCompany') <> '' and Params::getParam('sCompany') <> null) {
    Search::newInstance()->addJoinTable( 'pk_i_id', DB_TABLE_PREFIX.'t_user', DB_TABLE_PREFIX.'t_item.fk_i_user_id = '.DB_TABLE_PREFIX.'t_user.pk_i_id', 'LEFT OUTER' ) ; // Mod

    if(Params::getParam('sCompany') == 1) {
      Search::newInstance()->addConditions(sprintf("%st_user.b_company = 1", DB_TABLE_PREFIX));
    } else {
      Search::newInstance()->addConditions(sprintf("coalesce(%st_user.b_company, 0) <> 1", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
    }
  }
}

osc_add_hook('search_conditions', 'mb_filter_user_type');


// Radius search compatibility
if(!function_exists('radius_installed')) {function radius_installed() {return '';}}


function patricia_search_params() {
 return array(
   'sCategory' => Params::getParam('sCategory'),
   'sCountry' => Params::getParam('sCountry'),
   'sRegion' => Params::getParam('sRegion'),
   'sCity' => Params::getParam('sCity'),
   'sPriceMin' => Params::getParam('sPriceMin'),
   'sPriceMin' => Params::getParam('sPriceMax'),
   'sCompany' => Params::getParam('sCompany'),
   'sShowAs' => Params::getParam('sShowAs')
  );
}

function patricia_max_price($cat_id = null, $country_code = null, $region_id = null, $city_id = null) {
  $maxSearch = new Search();
  $maxSearch->addCategory($cat_id);
  $maxSearch->addCountry($country_code);
  $maxSearch->addRegion($region_id);
  $maxSearch->addCity($city_id);
  $maxSearch->addCity($city_id);
  $maxSearch->order('i_price', 'DESC');
  $maxSearch->limit(0, 2);

  $result = $maxSearch->doSearch();

  $max_price = 0;
  $max_currency = '';
  $ids = '';
  foreach($result as $item) {
    $ids .= ' - ' . $item['pk_i_id'];
    if($max_price < $item['i_price']) {
      $max_price = $item['i_price'];
      $max_currency = $item['fk_c_currency_code'];
    }
  }

  if($max_currency <> '') {
    $cur = Currency::newInstance()->findByPrimaryKey($max_currency);
    $cur_symbol = $cur['s_description'];
  }

  return array(
    'max_price' => $max_price/1000000,
    'max_currency' => osc_get_preference('def_cur', 'patricia_theme')
    //'max_currency' => $cur_symbol
  );
}

// Locate user position
// Credits to GeoPlugin.net for this great locator script!
function patricia_user_location($echo = null, $ip = null) {
  $geo_loc = osc_get_preference('geo_loc', 'patricia_theme') <> '' ? osc_get_preference('geo_loc', 'patricia_theme') : 1;

  if($geo_loc) {
    if($ip <> '') {
      $user_ip = $ip;
    } else {
      if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        $user_ip = $_SERVER['REMOTE_ADDR'];
      }
    }

    if(@file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip") === false) {
      return false;
    }

    $geo = unserialize(@file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
    $city = $geo["geoplugin_city"];
    $region = $geo["geoplugin_regionName"];
    $country = $geo["geoplugin_countryName"];
    $country_code = $geo["geoplugin_countryCode"];

    if(!$echo) {
      return array(
        'city' => $city,
        'region' => $region,
        'country' => $country,
        'country_code' => $country_code
      );
    } else {
      if($city == '') {
        if($region == '') {
          return $country;
        } else {
          return $region . ' (' . $country_code . ')';
        }
      } else {
        if($region == '') {
          return $city . ' (' . $country_code . ')';
        } else {
          return $city . ', ' . $region . ' (' . $country_code . ')';
        }
      }
    }
  } else {
    return false;
  }
}

// Drag & drop image uploader
if(modern_is_fineuploader() and osc_get_osclass_section() == 'item_add' or osc_get_osclass_section() == 'item_edit') {
  if(!OC_ADMIN) {
    osc_enqueue_style('fine-uploader-css', osc_assets_url('js/fineuploader/fineuploader.css'));
  }
  osc_enqueue_script('jquery-fineuploader');
}

function modern_is_fineuploader() {
  if(class_exists('Scripts')) {
    return Scripts::newInstance()->registered['jquery-fineuploader'] && method_exists('ItemForm', 'ajax_photos');
  }
}

if( !OC_ADMIN ) {
  if( !function_exists('add_close_button_action') ) {
    function add_close_button_action(){
      echo '<script type="text/javascript">';
      echo '$(".flashmessage .ico-close").click(function(){';
      echo '$(this).parent().hide();';
      echo '});';
      echo '</script>';
    }
    osc_add_hook('footer', 'add_close_button_action') ;
  }
}

if(!function_exists('message_ok')) {
  function message_ok( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-ok flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}

if(!function_exists('message_error')) {
  function message_error( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-error flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}

if(!function_exists('osc_count_countries')) {
  function osc_count_countries() {
    if ( !View::newInstance()->_exists('contries') ) {
      View::newInstance()->_exportVariableToView('countries', Search::newInstance()->listCountries( ">=", "country_name ASC" ) );
    }
    return View::newInstance()->_count('countries');
  }
}

/* ------------- Own functions to retrieve locations also with no listings --------------*/
function mb_has_list_countries() {
  $loc_empty = osc_get_preference('locations_empty', 'patricia_theme') <> '' ? osc_get_preference('locations_empty', 'patricia_theme') : 1;
  $loc_empty == 1 ? $comp = ">=" : $comp = ">";

  if ( !View::newInstance()->_exists('list_countries') ) {
    View::newInstance()->_exportVariableToView('list_countries', CountryStats::newInstance()->listCountries($comp) );
  }
  $result = View::newInstance()->_next('list_countries');
  if (!$result) {
    View::newInstance()->_reset('list_countries');
  }
  return $result;
}

function mb_has_list_regions($country = '%%%%') {
  $loc_empty = osc_get_preference('locations_empty', 'patricia_theme') <> '' ? osc_get_preference('locations_empty', 'patricia_theme') : 1;
  $loc_empty == 1 ? $comp = ">=" : $comp = ">";

  if ( !View::newInstance()->_exists('list_regions') ) {
    View::newInstance()->_exportVariableToView('list_regions', RegionStats::newInstance()->listRegions($country, $comp) );
  }
  $result = View::newInstance()->_next('list_regions');
  if (!$result) {
    View::newInstance()->_reset('list_regions');
  }
  return $result;
}

function mb_has_list_cities($region = '%%%%') {
  $loc_empty = osc_get_preference('locations_empty', 'patricia_theme') <> '' ? osc_get_preference('locations_empty', 'patricia_theme') : 1;
  $loc_empty == 1 ? $comp = ">=" : $comp = ">";
  
  if ( !View::newInstance()->_exists('list_cities') ) {
    View::newInstance()->_exportVariableToView('list_cities', CityStats::newInstance()->listCities($region, $comp) );
  }
  $result = View::newInstance()->_next('list_cities');
  if (!$result) {
    View::newInstance()->_reset('list_cities');
  }
  return $result;
}
/* --------------------------------------------------------------------------------------*/

function mb_subcat_list($categories) {
  foreach($categories as $c) {
    echo '<h3>';
      echo '<a href="#" class="single-subcat" id="' . $c['pk_i_id'] . '">' . $c['s_name'] . '</a>';

      if(isset($c['categories']) && is_array($c['categories']) && !empty($c['categories'])) {
        echo '<div class="icon-add-next"></div>';
        echo '<div class="sub" style="display:none">';
          mb_subcat_list($c['categories']);
        echo '</div>';
      }
    echo '</h3>';
  }     
}

/* ------------ New Category Select ----------------------*/
function mb_category_select($categories, $c_cat, $default_item = null, $name = "sCategory") {
  echo '<input type="hidden" id="sCategory" name="sCategory" value="' . $c_cat . '" />';
  echo '<div id="uniform-sCategory">';

  if($c_cat <> 0 and $c_cat <> '') {
    $def = Category::newInstance()->findByPrimaryKey($c_cat);
    $def = $def['s_name'];
  } else {
    $def = $default_item;
  }

  echo '<span>' . $def . '</span>';

  echo '<div id="inc-cat-box">';
  echo '<div class="current-cat"><i class="fa fa-hand-o-right"></i>&nbsp;' . __('Select category you would like to browse', 'patricia') . '</div>';

  echo '<ul id="inc-cat-list">';
  echo '<li class="bold" rel="">' . __('All categories', 'patricia') . '</li>';
  //if(isset($default_item)) {
  //  echo '<option value="">' . $default_item . '</option>';
  //}

  $found_parent = false;

  foreach($categories as $c) {
    if($c['b_enabled'] == 1) {
      echo '<li rel="' . $c['pk_i_id'] . '"' . ( ($c_cat == $c['pk_i_id']) ? 'class="active"' : '' ) . '>' . $c['s_name'] . '</li>';
      if(isset($c['categories']) && is_array($c['categories']) && !$found_parent) {
        $is_parent = isset($is_parent) ? $is_parent : '';
        $a = mb_subcategory_select($c['categories'], $c_cat, $default_item, 1, $is_parent, $c['pk_i_id']);

        // If found selected category, whole subcategory tree is added to select
        if($a[1] or $c_cat == $c['pk_i_id']) { echo $a[0]; }
      }
    }
  }

  echo '</ul>';
  echo '</div>';
  echo '</div>';
}

function mb_subcategory_select($categories, $c_cat = 0, $default_item = null, $deep = 0, $is_parent = 0, $parent = 0) {
  $help_text = '';
  $deep_string = "";
  for($var = 0;$var<$deep;$var++) {
    $deep_string .= '&nbsp;&nbsp;';
  }
  $deep_string = $deep_string . '-&nbsp;';
  $deep++;



  if($is_parent < 2) { // only show subcategories in next level, not more
    if($is_parent == 1) {$is_parent = 2;}
    $found_parent = false;
    foreach($categories as $c) {
      if($c_cat == $c['pk_i_id']) { 
        $is_parent = 1;
        $found_parent = true; 
      }

      $c['b_enabled'] = isset($c['b_enabled']) ? $c['b_enabled'] : '';
      if($c['b_enabled'] == 1) {
        $help_text .= '<li rel="' . $c['pk_i_id'] . '"' . ( ($c_cat == $c['pk_i_id']) ? 'class="active"' : '' ) . '>' . $deep_string . $c['s_name'] . '</li>';
      }

      if(isset($c['categories']) && is_array($c['categories'])) {
        $a = mb_subcategory_select($c['categories'], $c_cat, $default_item, $deep, $is_parent, $c['pk_i_id']);
        $help_text .= $a[0];
        if($a[1]) {$found_parent = true; }
      }
    }

    if($found_parent or $parent == $c_cat) {} else {$help_text = '';}
  }
     
  return array($help_text, $found_parent);
}


function mb_categories_select($name = 'sCategory', $category = null, $default_str = null) {
  if($default_str == null) { $default_str = __('Select a category', 'patricia'); }
  mb_category_select(Category::newInstance()->toTree(), $category, $default_str, $name);
}

/* ----------------- End New Category Select --------------------- */


function mb_get_current_user_locale() {
  return OSCLocale::newInstance()->findByPrimaryKey(osc_current_user_locale());
}

function theme_patricia_actions_admin() {
  if( Params::getParam('file') == 'oc-content/themes/patricia/admin/settings.php' ) {
    if( Params::getParam('donation') == 'successful' ) {
      osc_set_preference('donation', '1', 'patricia_theme');
      osc_reset_preferences();
    }
  }


  if( Params::getParam('patricia_general') == 'done' ) {
    $search_sub = Params::getParam('search_sub');
    $cat_icons = Params::getParam('cat_icons');
    $footerLink  = Params::getParam('footer_link');
    $defaultLogo = Params::getParam('default_logo');
    $image_upload = Params::getParam('image_upload');
    $enable_partner = Params::getParam('enable_partner');
    $drop_cat = Params::getParam('drop_cat');
    $def_cur = Params::getParam('def_cur');
    $def_view = Params::getParam('def_view');
    $format_sep = Params::getParam('format_sep');
    $format_cur = Params::getParam('format_cur');
    $geo_loc = Params::getParam('geo_loc');
    $locations_empty = Params::getParam('locations_empty');


    osc_set_preference('phone', Params::getParam('phone'), 'patricia_theme');
    osc_set_preference('date_format', Params::getParam('date_format'), 'patricia_theme');
    osc_set_preference('search_sub', ($search_sub ? '1' : '0'), 'patricia_theme');
    osc_set_preference('cat_icons', ($cat_icons ? '1' : '0'), 'patricia_theme');
    osc_set_preference('footer_link', ($footerLink ? '1' : '0'), 'patricia_theme');
    osc_set_preference('default_logo', ($defaultLogo ? '1' : '0'), 'patricia_theme');
    osc_set_preference('image_upload', ($image_upload ? '1' : '0'), 'patricia_theme');
    osc_set_preference('enable_partner', ($enable_partner ? '1' : '0'), 'patricia_theme');
    osc_set_preference('drop_cat', ($drop_cat ? '1' : '0'), 'patricia_theme');
    osc_set_preference('def_cur', Params::getParam('def_cur'), 'patricia_theme');
    osc_set_preference('def_view', Params::getParam('def_view'), 'patricia_theme');
    osc_set_preference('format_sep', Params::getParam('format_sep'), 'patricia_theme');
    osc_set_preference('format_cur', Params::getParam('format_cur'), 'patricia_theme');
    osc_set_preference('geo_loc', ($geo_loc ? '1' : '0'), 'patricia_theme');
    osc_set_preference('locations_empty', ($locations_empty ? '1' : '0'), 'patricia_theme');
    osc_set_preference('website_name', Params::getParam('website_name'), 'patricia_theme');

    osc_add_flash_ok_message(__('Theme settings updated correctly', 'patricia'), 'admin');
    header('Location: ' . osc_admin_render_theme_url('oc-content/themes/patricia/admin/settings.php')); exit;
  }


  if( Params::getParam('patricia_banner') == 'done' ) {
    $theme_adsense = Params::getParam('theme_adsense');

    osc_set_preference('theme_adsense', ($theme_adsense ? '1' : '0'), 'patricia_theme');
    osc_set_preference('banner_home', stripslashes(Params::getParam('banner_home', false, false)), 'patricia_theme');
    osc_set_preference('banner_search', stripslashes(Params::getParam('banner_search', false, false)), 'patricia_theme');
    osc_set_preference('banner_item', stripslashes(Params::getParam('banner_item', false, false)), 'patricia_theme');

    osc_add_flash_ok_message(__('Banner settings updated correctly', 'patricia'), 'admin');
    header('Location: ' . osc_admin_render_theme_url('oc-content/themes/patricia/admin/settings.php')); exit;
  }


  switch( Params::getParam('action_specific') ) {
    case('upload_logo'):
      $package = Params::getFiles('logo');
      if( $package['error'] == UPLOAD_ERR_OK ) {
        if( move_uploaded_file($package['tmp_name'], WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
          osc_add_flash_ok_message(__('The logo image has been uploaded correctly', 'patricia'), 'admin');
        } else {
          osc_add_flash_error_message(__("An error has occurred, please try again", 'patricia'), 'admin');
        }
      } else {
        osc_add_flash_error_message(__("An error has occurred, please try again", 'patricia'), 'admin');
      }
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/patricia/admin/header.php')); exit;
      break;

    case('remove'):
      if(file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
        @unlink( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" );
        osc_add_flash_ok_message(__('The logo image has been removed', 'patricia'), 'admin');
      } else {
        osc_add_flash_error_message(__("Image not found", 'patricia'), 'admin');
      }
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/patricia/admin/header.php')); exit;
      break;
    }
  }

  osc_add_hook('init_admin', 'theme_patricia_actions_admin');
  osc_admin_menu_appearance(__('Header logo', 'patricia'), osc_admin_render_theme_url('oc-content/themes/patricia/admin/header.php'), 'header_patricia');
  osc_admin_menu_appearance(__('Theme settings', 'patricia'), osc_admin_render_theme_url('oc-content/themes/patricia/admin/settings.php'), 'settings_patricia');

  if( !function_exists('logo_header') ) {
    function logo_header() {
      $html = '<img border="0" alt="' . osc_esc_html(osc_page_title()) . '" src="' . osc_current_web_theme_url('images/logo.jpg') . '" />';
      if( file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
        return $html;
      } else if( osc_get_preference('default_logo', 'patricia_theme') && (file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/default-logo.jpg")) ) {
        return '<img border="0" alt="' . osc_esc_html(osc_page_title()) . '" src="' . osc_current_web_theme_url('images/default-logo.jpg') . '" />';
      } else {
        return osc_page_title();
      }
    }
  }


function patricia_location_selector() {
  //View::newInstance()->_exportVariableToView('list_regions', Search::newInstance()->listRegions('%%%%', '>=', 'region_name ASC') ) ;
  //View::newInstance()->_exportVariableToView('list_countries', Search::newInstance()->listCountries('%%%%', '>=', 'country_name ASC') ) ;

  $curr_country = '';
  $curr_reg = osc_search_region();
  $curr_city = osc_search_city();
  
  if(function_exists('osc_search_country')) { $curr_country = osc_search_country(); } 
  if($curr_country == '') { $curr_country = Params::getParam('country'); }
  if($curr_country == '') { $curr_country = Params::getParam('sCountry'); }
  if($curr_reg == '') { $curr_reg = Params::getParam('sRegion'); }
  if($curr_city == '') { $curr_city = Params::getParam('sCity'); }

  // Detect user location, if was not set already or does not exist in installation, nothing happen
    mb_set_cookie('patricia-userLocation', '0');

  if(mb_get_cookie('patricia-userLocation') <> 1) {
    mb_set_cookie('patricia-userLocation', '1');
    $user_loc = patricia_user_location();

    if($curr_country == '' and $curr_reg == '' and $curr_city == '') {
      $country = Country::newInstance()->findByCode($user_loc['country_code']);
      $country['pk_c_code'] = isset($country['pk_c_code']) ? $country['pk_c_code'] : '';
      $region = Region::newInstance()->findByName($user_loc['region']);
      $region['pk_i_id'] = isset($region['pk_i_id']) ? $region['pk_i_id'] : '';
      $city = City::newInstance()->findByName($user_loc['city']);
      $city['pk_i_id'] = isset($city['pk_i_id']) ? $city['pk_i_id'] : '';
      
      if($country['pk_c_code'] <> '') {
        $curr_country = $user_loc['country_code'];
        mb_set_cookie('patricia-sCountry', $curr_country);
      }

      if($region['pk_i_id'] <> '') {
        $curr_reg = $user_loc['region'];
        mb_set_cookie('patricia-sRegion', $curr_reg);
      }

      if($city['pk_i_id'] <> '') {
        $curr_city = $user_loc['city'];
        mb_set_cookie('patricia-sCity', $curr_city);
      }
    }
  }

  if(osc_count_countries() > 1) {
    $del = '&nbsp;&nbsp;&nbsp;';
    $show_country = true;
  } else {
    $del = '';
    $show_country = false;
  }

  if(strlen($curr_country) > 2) { 
    $cc = Country::newInstance()->findByName($curr_country);
    $curr_country = $cc['pk_c_code'];
  }

  echo '<div id="uniform-Locator">';
  echo '<div class="cover"></div>';
  echo '<span>' . __('Location', 'patricia') . '</span>';

  echo '<div id="loc-box">';

  // My current location
  echo '<div class="current-loc">' . __('Your current location is:', 'patricia') . '</div>';
  echo '<div class="h-my-loc">';
  echo '<div class="font">';

  if(Params::getParam('sCountry') == '' and Params::getParam('sRegion') == '' and Params::getParam('sCity') == '') {
    _e('Location not saved', 'patricia');
  } else {
    $loc = array_filter(array(Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity')));
    $loc = trim(implode(', ', $loc));
    echo $loc;
    echo '<i class="fa fa-close clear-cookie-location" title="' . osc_esc_html(__('Clear location', 'patricia')) . '"></i>';
  }

  echo '</div>';
  echo '</div>';
  // End my location block

  echo '<div class="choose"><i class="fa fa-hand-o-right"></i>' . __('Select location', 'patricia') . '</div>';


  echo '<ul id="loc-list" name="Locator" data-placeholder="' . osc_esc_js(__('Location', 'patricia')) . '"  id="Locator">';

  if($show_country) {
    while(mb_has_list_countries()) {
      if($show_country) {
        echo '<li rel="' . osc_list_country_name() . '" class="country-level' . ( (osc_list_country_code() == $curr_country or osc_list_country_name() == $curr_country) ? ' active' : '' ) . '">' . osc_list_country_name() . '</li>';
      }

      if(osc_list_country_code() == $curr_country) { 
        while(mb_has_list_regions($curr_country) ) { 
          echo '<li rel="//' . osc_list_region_name() . '" class="region-level' . ( (osc_list_region_name() == $curr_reg ) ? ' active' : '' ) . '">' . $del . osc_list_region_name() . '</li>';

          if(osc_list_region_name() == $curr_reg) { 
            $myreg_id = '';
            if( $curr_reg != '' ) {
              $v_reg_id  = Region::newInstance()->findByName($curr_reg);
              if(isset($v_reg_id['pk_i_id'])) {
                $myreg_id = $v_reg_id['pk_i_id'];
              }
            }

            while(mb_has_list_cities($myreg_id)) { 
              echo '<li rel="--' . osc_list_city_name() . '" class="city-level' . ( (osc_list_city_name() == $curr_city ) ? ' active' : '' ) . '">&nbsp;&nbsp;&nbsp;' . $del . '- ' . osc_list_city_name() . '</li>';
            }
          } 
        } // End region loop
      }
    } // End country loop 

  } else {

    while(mb_has_list_regions() ) { 
      echo '<li rel="//' . osc_list_region_name() . '" class="region-level' . ( (osc_list_region_name() == $curr_reg ) ? ' active' : '' ) . '">' . $del . osc_list_region_name() . '</li>';

      if(osc_list_region_name() == $curr_reg) { 
        $myreg_id = '';
        if( $curr_reg != '' ) {
          $v_reg_id  = Region::newInstance()->findByName($curr_reg);
          if(isset($v_reg_id['pk_i_id'])) {
            $myreg_id = $v_reg_id['pk_i_id'];
          }
        }
 
        while(mb_has_list_cities($myreg_id)) { 
          echo '<li rel="--' . osc_list_city_name() . '" class="city-level' . ( (osc_list_city_name() == $curr_city ) ? ' active' : '' ) . '">&nbsp;&nbsp;&nbsp;' . $del . '- ' . osc_list_city_name() . '</li>';
        }
      }
    }
  }

  echo '</ul>';
  echo '</div>';
  echo '</div>';

  View::newInstance()->_erase('cities');
  View::newInstance()->_erase('regions');
  View::newInstance()->_erase('countries');
}

// install update options
if( !function_exists('patricia_theme_install') ) {
  $themeInfo = patricia_theme_info();

  function patricia_theme_install() {
    osc_set_preference('version', PATRICIA_THEME_VERSION, 'patricia_theme');
    osc_set_preference('phone', __('+1 (800) 228-5651', 'patricia'), 'patricia_theme');
    osc_set_preference('date_format', 'mm/dd', 'patricia_theme');
    osc_set_preference('search_sub', '1', 'patricia_theme');
    osc_set_preference('cat_icons', '1', 'patricia_theme');
    osc_set_preference('footer_link', '1', 'patricia_theme');
    osc_set_preference('donation', '0', 'patricia_theme');
    osc_set_preference('default_logo', '1', 'patricia_theme');
    osc_set_preference('image_upload', '1', 'patricia_theme');
    osc_set_preference('theme_adsense', '1', 'patricia_theme');
    osc_set_preference('def_cur', '', 'patricia_theme');
    osc_set_preference('def_view', '0', 'patricia_theme');
    osc_set_preference('format_sep', '', 'patricia_theme');
    osc_set_preference('format_cur', '0', 'patricia_theme');
    osc_set_preference('geo_loc', '1', 'patricia_theme');
    osc_set_preference('locations_empty', '1', 'patricia_theme');
    osc_set_preference('enable_partner', '1', 'patricia_theme');
    osc_set_preference('drop_cat', '1', 'patricia_theme');
    osc_set_preference('banner_home', '', 'patricia_theme');
    osc_set_preference('banner_search', '', 'patricia_theme');
    osc_set_preference('banner_item', '', 'patricia_theme');
    osc_set_preference('website_name', 'myWebsite.com', 'patricia_theme');

    osc_reset_preferences();
  }
}

if(!function_exists('check_install_patricia_theme')) {
  function check_install_patricia_theme() {
    $current_version = osc_get_preference('version', 'patricia_theme');
    //check if current version is installed or need an update<
    if( !$current_version ) {
      patricia_theme_install();
    }
  }
}

check_install_patricia_theme();

// New function to fix premium price format
function patricia_premium_formated_price($price = null) {
  if($price == '') {
    $price = osc_premium_price();
  }

  return (string) patricia_premium_format_price($price);
}

function patricia_premium_format_price($price, $symbol = null) {
  if ($price === null) return osc_apply_filter ('item_price_null', __('Check with seller') );
  if ($price == 0) return osc_apply_filter ('item_price_zero', __('Free') );

  if($symbol==null) { $symbol = osc_premium_currency_symbol(); }

  $price = $price/1000000;

  $currencyFormat = osc_locale_currency_format();
  $currencyFormat = str_replace('{NUMBER}', number_format($price, osc_locale_num_dec(), osc_locale_dec_point(), osc_locale_thousands_sep()), $currencyFormat);
  $currencyFormat = str_replace('{CURRENCY}', $symbol, $currencyFormat);
  return osc_apply_filter('premium_price', $currencyFormat );
}


// USER MENU FIX
function patricia_user_menu_fix() {
  $user = User::newInstance()->findByPrimaryKey( osc_logged_user_id() );
  View::newInstance()->_exportVariableToView('user', $user);
}

osc_add_hook('header', 'patricia_user_menu_fix');
?>