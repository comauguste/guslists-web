<?php
  require_once 'functions.php';
  patricia_backoffice_menu(__('Settings', 'patricia'));
?>

<?php
// MANAGE IMAGES
if(Params::getParam('patricia_images') == 'done') { 
  $upload_dir_small = osc_themes_path() . 'patricia/images/small_cat/';
  $upload_dir_large = osc_themes_path() . 'patricia/images/large_cat/';

  if (!file_exists($upload_dir_small)) { mkdir($upload_dir_small, 0777, true); }
  if (!file_exists($upload_dir_large)) { mkdir($upload_dir_large, 0777, true); }

  $count_real = 0;
  for ($i=1; $i<=1000; $i++) {
    if(isset($_POST['fa-icon' .$i])) {
      $fields['fields'] = array('s_icon' => Params::getParam('fa-icon' .$i));
      $fields['aFieldsDescription'] = array();
      Category::newInstance()->updateByPrimaryKey($fields, $i);
      message_ok(__('Font Awesome icon successfully saved for category' . ' <strong>#' . $i . '</strong>' ,'patricia'));
    }

    if(isset($_FILES['small' .$i]) and $_FILES['small' .$i]['name'] <> ''){

      $file_ext   = strtolower(end(explode('.', $_FILES['small' .$i]['name'])));
      $file_name  = $i . '.' . $file_ext;
      $file_tmp   = $_FILES['small' .$i]['tmp_name'];
      $file_type  = $_FILES['small' .$i]['type'];   
      $extensions = array("png");

      if(in_array($file_ext,$extensions )=== false) {
        $errors = __('extension not allowed, only allowed extension is .png!','patricia');
      } 
				
      if(empty($errors)==true){
        move_uploaded_file($file_tmp, $upload_dir_small.$file_name);
        message_ok(__('Small image #','patricia') . $i . __(' uploaded successfully.','patricia'));
        $count_real++;
      } else {
        message_error(__('There was error when uploading small image #','patricia') . $i . ': ' .$errors);
      }
    }
  }

  $count_real = 0;
  for ($i=1; $i<=1000; $i++) {
    if(isset($_FILES['large' .$i]) and $_FILES['large' .$i]['name'] <> ''){
      $file_ext   = strtolower(end(explode('.', $_FILES['large' .$i]['name'])));
      $file_name  = $i . '.' . $file_ext;
      $file_tmp   = $_FILES['large' .$i]['tmp_name'];
      $file_type  = $_FILES['large' .$i]['type'];   
      $extensions = array("jpg");

      if(in_array($file_ext,$extensions )=== false) {
        $errors = __('extension not allowed, only allowed extension for large images is .jpg!','patricia');
      }
				
      if(empty($errors)==true){
        move_uploaded_file($file_tmp, $upload_dir_large.$file_name);
        message_ok(__('Large image #','patricia') . $i . __(' uploaded successfully.','patricia'));
        $count_real++;
      } else {
        message_error(__('There was error when uploading large image #','patricia') . $i . ': ' .$errors);
      }
    }
  }
}
?>



<div class="mb-body">
  <div class="mb-info-box" style="margin:5px 0 30px 0;">
    <div class="mb-line"><strong><?php _e('Plugins for this theme', 'patricia'); ?></strong></div>
    <div class="mb-line"><?php _e('We have modified for you many plugins to fit theme design that will work without need of any modifications', 'patricia'); ?>.</div>
    <div class="mb-line"><?php _e('Plugins are not delivered in theme package, must be downloaded separately', 'patricia'); ?>.</div>
    <div class="mb-line" style="margin:10px 0;"><a href="http://mb-themes.com/theme-plugins/patricia-plugins.zip" target="_blank" class="mb-button-white"><i class="fa fa-download"></i> <?php _e('Download plugins', 'patricia'); ?></a></div>
    <div class="mb-line" style="margin-top:15px;">- <?php _e('upload and extract downloaded file <strong>patricia-plugins.zip</strong> into folder <strong>oc-content/plugins/</strong> on your hosting', 'patricia'); ?>.</div>
    <div class="mb-line">- <?php _e('go to <strong>oc-admin > Plugins</strong> and install plugins you like', 'patricia'); ?>.</div>
  </div>


  <!-- GENERAL -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-cog"></i> <?php _e('General settings', 'patricia'); ?></div>

    <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/settings.php'); ?>" method="post">
      <input type="hidden" name="patricia_general" value="done" />

      <div class="mb-inside">
        <div class="mb-row">
          <label for="phone" class="h1"><span><?php _e('Contact Number', 'patricia'); ?></span></label> 
          <input size="40" name="phone" id="promote_service_id" type="text" value="<?php echo osc_esc_html( osc_get_preference('phone', 'patricia_theme') ); ?>" placeholder="<?php _e('Contact number', 'patricia'); ?>" />

          <div class="mb-explain"><?php _e('Leave blank to disable.', 'patricia'); ?></div>
        </div>

        <div class="mb-row">
          <label for="website_name" class="h2"><span><?php _e('Website Name', 'patricia'); ?></span></label> 
          <input size="40" name="website_name" id="website_name" type="text" value="<?php echo osc_esc_html( osc_get_preference('website_name', 'patricia_theme') ); ?>" placeholder="<?php _e('Website Name', 'patricia'); ?>" />
        </div>
        
        <div class="mb-row">
          <label for="date_format" class="h4"><span><?php _e('Date Format on Listings', 'patricia'); ?></span></label> 
          <select name="date_format" id="date_format">
            <option value="m/d" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'm/d' ? 'selected="selected"' : ''); ?>>m/d (12/01)</option>
            <option value="d/m" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'd/m' ? 'selected="selected"' : ''); ?>>d/m (01/12)</option>
            <option value="m-d" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'm-d' ? 'selected="selected"' : ''); ?>>m-d (12-01)</option>
            <option value="d-m" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'd-m' ? 'selected="selected"' : ''); ?>>d-m (01-12)</option>
            <option value="j. M" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'j. M' ? 'selected="selected"' : ''); ?>>j. M (1. Dec)</option>
            <option value="M" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'M' ? 'selected="selected"' : ''); ?>>M (Dec)</option>
            <option value="F" <?php echo (osc_get_preference('date_format', 'patricia_theme') == 'F' ? 'selected="selected"' : ''); ?>>F (December)</option>
          </select>

          <div class="mb-explain"><?php _e('Selected date format will be applied in all section on listings.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="def_view" class="h5"><span><?php _e('Default View on Search Page', 'patricia'); ?></span></label> 
          <select name="def_view" id="def_view">
            <option value="0" <?php echo (osc_get_preference('def_view', 'patricia_theme') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Gallery view', 'patricia'); ?></option>
            <option value="1" <?php echo (osc_get_preference('def_view', 'patricia_theme') == 1 ? 'selected="selected"' : ''); ?>><?php _e('List view', 'patricia'); ?></option>
          </select>
        </div>        


        <div class="mb-row">
          <label for="footer_link" class="h6"><span><?php _e('Footer Link', 'patricia'); ?></span></label> 
          <input name="footer_link" id="footer_link" class="element-slide" type="checkbox" <?php echo (osc_get_preference('footer_link', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Link to MB-themes and Osclass will be shown in footer.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="default_logo" class="h7"><span><?php _e('Use Default Logo', 'patricia'); ?></span></label> 
          <input name="default_logo" id="default_logo" class="element-slide" type="checkbox" <?php echo (osc_get_preference('default_logo', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('If you did not upload any logo yet, osclass default logo will be used.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="image_upload" class="h8"><span><?php _e('Use Drag & Drop Photo Uploader', 'patricia'); ?></span></label> 
          <input name="image_upload" id="image_upload" class="element-slide" type="checkbox" <?php echo (osc_get_preference('image_upload', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Drag & Drop uploader is recommended specially for mobile devices.', 'patricia'); ?></div>
        </div>

        <div class="mb-row">
          <label for="locations_empty" class="h24"><span><?php _e('Show Empty Locations', 'patricia'); ?></span></label> 
          <input name="locations_empty" id="locations_empty" class="element-slide" type="checkbox" <?php echo (osc_get_preference('locations_empty', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Show also Countries, Regions & Cities that does not contain any listing.', 'patricia'); ?></div>
        </div>

        <div class="mb-row">
          <label for="search_sub" class="h25"><span><?php _e('Show Subcategories on Search', 'patricia'); ?></span></label> 
          <input name="search_sub" id="search_sub" class="element-slide" type="checkbox" <?php echo (osc_get_preference('search_sub', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Show subcategories block on Search/Category page.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="cat_icons" class="h9"><span><?php _e('Category Icons Type', 'patricia'); ?></span></label> 
          <input name="cat_icons" id="cat_icons" class="element-slide" type="checkbox" <?php echo (osc_get_preference('cat_icons', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Check to ON if you want to use Font-Awesome icons instead of Small images for categories.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="enable_partner" class="h10"><span><?php _e('Partner Section in Footer', 'patricia'); ?></span></label> 
          <input name="enable_partner" id="enable_partner" class="element-slide" type="checkbox" <?php echo (osc_get_preference('enable_partner', 'patricia_theme') == 1 ? 'checked' : ''); ?> />
        </div>
       
        <div class="mb-row">
          <label for="drop_cat" class="h11"><span><?php _e('Dropdown Subcategories on Publish', 'patricia'); ?></span></label> 
          <input name="drop_cat" id="drop_cat" class="element-slide" type="checkbox" <?php echo (osc_get_preference('drop_cat', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable to allow cascading to select category on publish page.', 'patricia'); ?></div>
        </div>
      </div>

      <div class="mb-foot">
        <button type="submit" class="mb-button"><?php _e('Save', 'patricia');?></button>
      </div>
    </form>
  </div>


  <!-- BANNERS -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-clone"></i> <?php _e('Banner settings', 'patricia'); ?></div>

    <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/settings.php'); ?>" method="post">
      <input type="hidden" name="patricia_banner" value="done" />

      <div class="mb-inside">
        <div class="mb-row">
          <label for="theme_adsense" class="h20"><span><?php _e('Enable Google Adsense Banners', 'patricia'); ?></span></label> 
          <input name="theme_adsense" id="theme_adsense" class="element-slide" type="checkbox" <?php echo (osc_get_preference('theme_adsense', 'patricia_theme') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, bellow banners will be shown in front page.', 'patricia'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="banner_home" class="h21"><span><?php _e('Home Page Banner Code', 'patricia'); ?></span></label> 
          <textarea class="mb-textarea mb-textarea-large" name="banner_home" placeholder="<?php _e('Will be shown at bottom of home page, recommended is responsive banner with width 1200px', 'patricia'); ?>"><?php echo stripslashes( osc_get_preference('banner_home', 'patricia_theme') ); ?></textarea>
        </div>
        
        <div class="mb-row">
          <label for="banner_search" class="h22"><span><?php _e('Search Page Banner Code', 'patricia'); ?></span></label> 
          <textarea class="mb-textarea mb-textarea-large" name="banner_search" placeholder="<?php _e('Will be shown in left sidebar on search page, recommended is responsive banner with width 270px', 'patricia'); ?>"><?php echo stripslashes( osc_get_preference('banner_search', 'patricia_theme') ); ?></textarea>
        </div>   

        <div class="mb-row">
          <label for="banner_item" class="h23"><span><?php _e('Home Page Banner Code', 'patricia'); ?></span></label> 
          <textarea class="mb-textarea mb-textarea-large" name="banner_item" placeholder="<?php _e('Will be shown in right sidebar on item page, recommended is responsive banner with width 360px', 'patricia'); ?>"><?php echo stripslashes( osc_get_preference('banner_item', 'patricia_theme') ); ?></textarea>
        </div>
      </div>

      <div class="mb-foot">
        <button type="submit" class="mb-button"><?php _e('Save', 'patricia');?></button>
      </div>
    </form>
  </div>


  <!-- CATEGORY ICONS -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-photo"></i> <?php _e('Category icons settings', 'patricia'); ?></div>

    <form name="promo_form" id="load_image" action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/settings.php'); ?>" method="POST" enctype="multipart/form-data" >
      <input type="hidden" name="patricia_images" value="done" />

      <div class="mb-inside">
        <div class="mb-table">
          <div class="mb-table-head">
            <div class="mb-col-1_2 id"><?php _e('ID', 'patricia'); ?></div>
            <div class="mb-col-3 mb-align-left name"><?php _e('Name', 'patricia'); ?></div>
            <div class="mb-col-1 mb-no-pad icon"><?php _e('Has small image', 'patricia'); ?></div>
            <div class="mb-col-2_1_2"><?php _e('Small image (50x30px - png)', 'patricia'); ?></div>
            <div class="mb-col-1 mb-no-pad icon"><?php _e('Has large image', 'patricia'); ?></div>
            <div class="mb-col-2_1_2"><?php _e('Large image (150x250px - jpg)', 'patricia'); ?></div>
            <div class="mb-col-1_1_2 fa-icon"><a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank"><?php _e('Font-Awesome icon', 'patricia'); ?></a></div>
          </div>

          <?php patricia_has_subcategories_special(Category::newInstance()->toTree(), 0); ?> 
        </div>
      </div>

      <div class="mb-foot">
        <button type="submit" class="mb-button"><?php _e('Save', 'patricia');?></button>
      </div>
    </form>
  </div>



  <!-- HELP TOPICS -->
  <div class="mb-box" id="mb-help">
    <div class="mb-head"><i class="fa fa-question-circle"></i> <?php _e('Help', 'patricia'); ?></div>

    <div class="mb-inside">
      <div class="mb-row mb-help"><span class="sup">(1)</span> <div class="h1"><?php _e('Leave blank to disable contact number. This number will be shown in theme header.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(2)</span> <div class="h2"><?php _e('Website name can be used in user menu and footer of website.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(3)</span> <div class="h3"><?php _e('Choose which currency you want to show in search menu on category/search page.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(4)</span> <div class="h4"><?php _e('Select date format that will be used on listings. This setting is valid for latest listings, search page and listing page.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(5)</span> <div class="h5"><?php _e('Select default view type for users. Listings can be shown in grid or as list. User can change view to prefered one. Note that this setting is valid for search page only.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(6)</span> <div class="h6"><?php _e('I want to help OSClass & MB themes by linking to <a href="http://osclass.org/" target="_blank">osclass.org</a> and <a href="http://www.mb-themes.com" target="_blank">MB-Themes.com</a> from my site', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(7)</span> <div class="h7"><?php _e('Show default logo in case you didn\'t upload one previously.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(8)</span> <div class="h8"><?php _e('Use new Drag & Drop image uploader instead old one. Note that it is required to have osclass version 3.3 or higher.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(9)</span> <div class="h9"><?php _e('Use FontAwesome icons instead of small image icons for categories on homepage', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(10)</span> <div class="h10"><?php _e('Enable partner section in footer. In this section are shown images uploaded to folder oc-content/themes/patricia/images/sponsor-logos.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(11)</span> <div class="h11"><?php _e('Use categories/subcategories dropdown when publishing or editing listings. If unchecked, one select for categories & subcategories is used.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(20)</span> <div class="h20"><?php _e('Check if you want to enable Google Adsense banners on your site. You can define code for banner in bellow boxes.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(21)</span> <div class="h21"><?php _e('Will be shown at bottom of home page, recommended is responsive banner with width 1200px.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(22)</span> <div class="h22"><?php _e('Will be shown in left sidebar on search/category page, recommended is responsive banner with width 270px.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(23)</span> <div class="h23"><?php _e('Will be shown in right sidebar on listings page, recommended is responsive banner with width 360px.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(24)</span> <div class="h24"><?php _e('Show also countries, regions & cities that has no listing. Otherwise these will be hidden.', 'patricia'); ?></div></div>
      <div class="mb-row mb-help"><span class="sup">(25)</span> <div class="h25"><?php _e('Show subcategories of current category on Search/Category page under premium listings view', 'patricia'); ?></div></div>
    </div>
  </div>
</div>

<?php echo patricia_footer(); ?>