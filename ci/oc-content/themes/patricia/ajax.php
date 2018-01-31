<?php
define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
require_once ABS_PATH . 'oc-load.php';
require_once ABS_PATH . 'oc-content/themes/patricia/functions.php';

// Ajax clear cookies
if($_GET['clearCookieSearch'] == 'done') {
  mb_set_cookie('patricia-sCategory', '');
  mb_set_cookie('patricia-sPattern', '');
  mb_set_cookie('patricia-sPriceMin', '');
  mb_set_cookie('patricia-sPriceMax', '');
}

if($_GET['clearCookieLocation'] == 'done') {
  mb_set_cookie('patricia-sCountry', '');
  mb_set_cookie('patricia-sRegion', '');
  mb_set_cookie('patricia-sCity', '');
  mb_set_cookie('patricia-sLocator', '');
}

if($_GET['clearCookieAll'] == 'done') {
  mb_set_cookie('patricia-sCategory', '');
  mb_set_cookie('patricia-sPattern', '');
  mb_set_cookie('patricia-sPriceMin', '');
  mb_set_cookie('patricia-sPriceMax', '');
  mb_set_cookie('patricia-sCountry', '');
  mb_set_cookie('patricia-sRegion', '');
  mb_set_cookie('patricia-sCity', '');
  mb_set_cookie('patricia-sLocator', '');
}

//echo 'test string';
?>