<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />

  <!-- only item-edit.php -->
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>

  <?php if(osc_images_enabled_at_items()) ItemForm::photos_javascript(); ?>

  <script type="text/javascript">

    <?php if(osc_get_preference('image_upload', 'patricia_theme') <> 1) { ?>
    function uniform_input_file(){
      photos_div = $('div.photos');
      $('div',photos_div).each(
        function(){
          if( $(this).find('div.uploader').length == 0  ){
            divid = $(this).attr('id');
            if(divid != 'photos'){
              divclass = $(this).hasClass('box');
              if( !$(this).hasClass('box') & !$(this).hasClass('uploader') & !$(this).hasClass('row')){
                $("div#"+$(this).attr('id')+" input:file").uniform({fileDefaultText: fileDefaultText,fileBtnText: fileBtnText});
              }
            }
          }
        }
      );
    }
    <?php } ?>

    setInterval("uniform_plugins()", 250);
    function uniform_plugins() {
      
      var content_plugin_hook = $('#plugin-hook').text();
      content_plugin_hook = content_plugin_hook.replace(/(\r\n|\n|\r)/gm,"");
      if( content_plugin_hook != '' ){
        
        var div_plugin_hook = $('#plugin-hook');
        var num_uniform = $("div[id*='uniform-']", div_plugin_hook ).size();
        if( num_uniform == 0 ){
          if( $('#plugin-hook input:text').size() > 0 ){
            $('#plugin-hook input:text').uniform();
          }
          if( $('#plugin-hook select').size() > 0 ){
            $('#plugin-hook select').uniform();
          }
        }
      }
    }    

    <?php if(osc_locale_thousands_sep()!='' || osc_locale_dec_point() != '') { ?>
    $().ready(function(){
      $("#price").blur(function(event) {
        var price = $("#price").attr("value");
        <?php if(osc_locale_thousands_sep()!='') { ?>
        while(price.indexOf('<?php echo osc_esc_js(osc_locale_thousands_sep());  ?>')!=-1) {
          price = price.replace('<?php echo osc_esc_js(osc_locale_thousands_sep());  ?>', '');
        }
        <?php }; ?>
        <?php if(osc_locale_dec_point!='') { ?>
        var tmp = price.split('<?php echo osc_esc_js(osc_locale_dec_point())?>');
        if(tmp.length>2) {
          price = tmp[0]+'<?php echo osc_esc_js(osc_locale_dec_point())?>'+tmp[1];
        }
        <?php }; ?>
        $("#price").attr("value", price);
      });
    });
    <?php }; ?>

    tabberAutomatic();
  </script>
  <!-- end only item-edit.php -->
</head>

<body>
  <h1 class="item_adding"></h1>

  <?php 
    $country = Country::newInstance()->listAll();

    if(osc_is_web_user_logged_in()) {

      // GET LOCATION OF LOGGED USER
      $cookie_loc = osc_item();

      // IF THERE IS JUST 1 COUNTRY, PRE-SELECT IT TO ENABLE REGION DROPDOWN
      if(count($country) == 1) {
        $country = $country[0];
        $cookie_loc['fk_c_country_code'] = $country['pk_c_code'];
      }
    } else {

      // GET LOCATION FROM SEARCH
      if(Params::getParam('sCountry') <> '') {    
        if(strlen(Params::getParam('sCountry')) == 2) {
          $cookie_loc['fk_c_country_code'] = Params::getParam('sCountry');
        } else {
          $country = Country::newInstance()->findByName(Params::getParam('sCountry'));
          $cookie_loc['fk_c_country_code'] = $country['pk_c_code'];
        }
      } else {
        // IF THERE IS JUST 1 COUNTRY, PRE-SELECT IT TO ENABLE REGION DROPDOWN
        if(count($country) == 1) {
          $country = $country[0];
          $cookie_loc['fk_c_country_code'] = $country['pk_c_code'];
        }
      }

      if(Params::getParam('sRegion') <> '') {
        if(is_numeric(Params::getParam('sRegion'))) {
          $cookie_loc['fk_i_region_id'] = Params::getParam('sRegion');
        } else {
          $region = Region::newInstance()->findByName(Params::getParam('sRegion'));
          $cookie_loc['fk_i_region_id'] = $region['pk_i_id'];
        }
      }
    }


    if(Params::getParam('sCity') <> '') {
      if(is_numeric(Params::getParam('sCity'))) {
        $cookie_loc['fk_i_city_id'] = Params::getParam('sCity');
      } else {
        $city = City::newInstance()->findByName(Params::getParam('sCity'), $cookie_loc['fk_i_region_id']);
        $cookie_loc['fk_i_city_id'] = $city['pk_i_id'];
      }
    }
    
    if($cookie_loc['fk_c_country_code'] <> '') {
      $region_list = Region::newInstance()->findByCountry($cookie_loc['fk_c_country_code']);
    }

    if($cookie_loc['fk_i_region_id'] <> '') {
      $city_list = City::newInstance()->findByRegion($cookie_loc['fk_i_region_id']);
    }
  ?>


  <?php osc_current_web_theme_path('header.php') ; ?>

  <ul id="error_list" class="new-item"></ul>

  <div class="content add_item">
    <form name="item" action="<?php echo osc_base_url(true);?>" method="post" enctype="multipart/form-data">
      <fieldset>
      <input type="hidden" name="action" value="item_edit_post" />
      <input type="hidden" name="page" value="item" />
      <input type="hidden" name="id" value="<?php echo osc_item_id() ;?>" /> 
      <input type="hidden" name="secret" value="<?php echo osc_item_secret() ;?>" /> 

      <div id="left">
        <div class="box general_info">
          <?php if(osc_get_preference('drop_cat', 'patricia_theme') == 1) { ?>
            <div class="row catshow multiple">
              <div class="multi-left">
                <label for="catId"><span><?php _e('Category', 'patricia'); ?></span><span class="req">*</span></label>
                <i class="fa fa-arrow-right"></i>
              </div>
              <div class="multi-right">
                <?php ItemForm::category_multiple_selects(null, null, __('Select a category', 'patricia')); ?>
              </div>
            </div>
          <?php } else { ?>
            <div class="row catshow">
              <label for="catId"><span><?php _e('Category', 'patricia'); ?></span><span class="req">*</span></label>
              <i class="fa fa-arrow-right"></i>
              <?php ItemForm::category_select(null, null, __('Select a category', 'patricia')); ?>
            </div>
          <?php } ?>

          <div class="row descshow">
            <?php ItemForm::multilanguage_title_description(); ?>
            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'patricia'); ?></div></div>
          </div>
        </div>
        <?php if( osc_price_enabled_at_items() ) { ?>
        <div class="box price">
          <label for="price"><span><?php _e('Price', 'patricia'); ?></span></label>
          <?php ItemForm::price_input_text(); ?>
          <?php ItemForm::currency_select(); ?>
          <span class="price-del"><?php echo ' ' . __('or', 'patricia') . ' ';?></span>
          <select id="PriceSelect">
          <option value="0"><?php _e('Select option', 'patricia'); ?></option>
          <option value="1"><?php _e('Free', 'patricia'); ?></option>
          <option value="2"><?php _e('Check with seller', 'patricia'); ?></option>
          </select>   
        </div>
        
        <?php } ?>
        <div class="box photos photoshow <?php if(osc_get_preference('image_upload', 'patricia_theme') == 1) { echo 'drag_drop'; } ?>">
          <div id="photos">
            <?php if(osc_images_enabled_at_items()) {
              if(modern_is_fineuploader() && osc_get_preference('image_upload', 'patricia_theme') == 1) {
                ItemForm::ajax_photos();
                echo '</div>';
            } else { ?>
              <div class="row">
                <input type="file" name="photos[]" multiple />
              </div>
            </div>
            <a id="new-pho" href="#" onclick="addNewPhotoPatricia(); uniform_input_file(); return false;"><?php _e('Add new photo', 'patricia'); ?></a>
          <?php } ?>
        </div>
        <?php } ?>

        <!-- PLUGIN HOOKS -->
        <div class="clear"></div>

        <div id="post-hooks">
          <?php ItemForm::plugin_edit_item(); ?>
        </div>
      </div>

      <div id="right">
        
        <!-- SELLER INFORMATION -->
        
        <div class="box seller_info <?php if(osc_is_web_user_logged_in() ) { ?>logged<?php } ?>">
          <h2>
            <div class="left"><i class="fa fa-user"></i></div>
            <div class="right">
              <div class="top"><?php _e('details of', 'patricia'); ?></div>
              <div class="bottom"><?php _e('Seller', 'patricia'); ?></div>
            </div>
          </h2>

          <div class="row">
            <label for="contactName"><?php _e('Name', 'patricia'); ?></label>
            <?php ItemForm::contact_name_text() ; ?>
          </div>
        
          <div class="row">
            <label for="phone"><?php _e('Mobile Phone', 'patricia'); ?></label>
            <?php ItemForm::city_area_text(osc_item()) ; ?>
          </div>
          <div class="row">
            <label for="contactEmail"><span><?php _e('E-mail', 'patricia'); ?></span><span class="req">*</span></label>
            <?php ItemForm::contact_email_text() ; ?>
          </div>
          <div class="row">
            <div id="novy_email_check">
              <?php ItemForm::show_email_checkbox() ; ?>
            </div>
            <label for="showEmail" id="novy_email_show"><?php _e('Show email on listing page', 'patricia'); ?></label>
          </div>
        </div>        

        
        <!-- LOCATION -->
        <div class="box location">
          <h2>
            <div class="left"><i class="fa fa-location-arrow"></i></div>
            <div class="right">
              <div class="top"><?php _e('listing', 'patricia'); ?></div>
              <div class="bottom"><?php _e('Location', 'patricia'); ?></div>
            </div>
          </h2>

          <?php $country = Country::newInstance()->listAll(); ?>
          <div class="row" <?php if(count($country) == 1) { ?>style="display:none;"<?php } ?>>
            <label for="countryId"><?php _e('Country', 'patricia'); ?></label>
            <?php ItemForm::country_select(Country::newInstance()->listAll(), $cookie_loc); ?>
          </div>         

          <div class="row">
            <label for="regionId"><?php _e('Region', 'patricia'); ?></label>
            <?php ItemForm::region_select($region_list, $cookie_loc); ?>
          </div>
          <div class="row">
            <label for="city"><span><?php _e('City', 'patricia'); ?></span></label>
            <?php ItemForm::city_select($city_list, $cookie_loc); ?>
          </div>

          <div class="row">
            <label for="address"><?php _e('Address', 'patricia'); ?></label>
            <?php ItemForm::address_text(osc_item()); ?>
          </div>
        </div>
      </div>


      <script type="text/javascript">var RecaptchaOptions = { theme : 'clean' };</script>
      <?php if( osc_recaptcha_items_enabled() ) {?>
        <div class="box">
          <div class="row">
            <?php osc_show_recaptcha(); ?>
          </div>
        </div>
      <?php }?>  

      <div class="clear"></div>

      <button type="submit" id="blue"><?php _e('Save changes', 'patricia'); ?></button>
      </fieldset>
    </form>
  </div>


  <!-- JAVASCRIPT FOR PRICE ALTERNATIVES -->
  <script type="text/javascript">
    document.getElementById("PriceSelect").onchange = function(){ 
      if (document.getElementById("PriceSelect").value == 0) {
        document.getElementById("price").readOnly=false;
        document.getElementById("price").style.backgroundColor="#ffffff";
        document.getElementById("price").style.color="#444444";
      } else if (document.getElementById("PriceSelect").value == 1) {
        document.getElementById("price").value = 0;
        document.getElementById("price").readOnly=true;
        document.getElementById("price").style.backgroundColor="#f6f6f6";
        document.getElementById("price").style.color="#f6f6f6";
      } else if (document.getElementById("PriceSelect").value == 2) {
        document.getElementById("price").value = "";
        document.getElementById("price").readOnly=true;
        document.getElementById("price").style.backgroundColor="#f6f6f6";
        document.getElementById("price").style.color="#f6f6f6";
      }
    }

   
    $("#catId").click(function(){
      var cat_id = $(this).val();
      var url = '<?php echo osc_base_url(); ?>index.php';
      var result = '';

      if(cat_id != '') {
        if(catPriceEnabled[cat_id] == 1) {
          $("#price").closest("div").show();
        } else {
          $("#price").closest("div").hide();
          $('#price').val('') ;
        }

        $.ajax({
          type: "POST",
          url: url,
          data: 'page=ajax&action=runhook&hook=item_form&catId=' + cat_id,
          dataType: 'html',
          success: function(data){
          $("#plugin-hook").html(data);
        }
      });
    }
  });
  </script>

  <?php if(osc_get_preference('image_upload', 'patricia_theme') <> 1) { ?>
  <script>
    var photoIndex = 0;
    function gebi(id) { return document.getElementById(id); }
    function ce(name) { return document.createElement(name); }
    function re(id) {
      var e = gebi(id);
      e.parentNode.removeChild(e);
    }

    function addNewPhotoPatricia() {
      var max = <?php echo osc_max_images_per_item(); ?>;
      var num_img = $('input[name="photos[]"]').size() + $("a.delete").size();
      if((max!=0 && num_img<max) || max==0) {
        var id = 'p-' + photoIndex++;

        var i = ce('input');
        i.setAttribute('type', 'file');
        i.setAttribute('name', 'photos[]');

        var a = ce('a');
        a.style.fontSize = 'x-small';
        a.style.paddingLeft = '10px';
        a.setAttribute('href', '#');
        a.setAttribute('divid', id);
        a.onclick = function() { re(this.getAttribute('divid')); return false; }
        a.appendChild(document.createTextNode('<?php echo osc_esc_js(__('Remove')); ?>'));

        var d = ce('div');
        d.setAttribute('id', id);
        d.setAttribute('style','padding: 4px 0;')

        d.appendChild(i);
        d.appendChild(a);

        gebi('photos').appendChild(d);

      } else {
        alert('<?php echo osc_esc_js(__('Sorry, you have reached the maximum number of images per listing')); ?>');
      }
    }
    // Listener: automatically add new file field when the visible ones are full.
    setInterval("add_file_field()", 250);
    /**
     * Timed: if there are no empty file fields, add new file field.
     */
    function add_file_field() {
      var count = 0;
      $('input[name="photos[]"]').each(function(index) {
        if ( $(this).val() == '' ) {
          count++;
        }
      });
      var max = <?php echo osc_max_images_per_item(); ?>;
      var num_img = $('input[name="photos[]"]').size() + $("a.delete").size();
      if (count == 0 && (max==0 || (max!=0 && num_img<max))) {
        addNewPhotoPatricia();uniform_input_file(); return false;
      }
    }
  </script>
  <?php } ?>


  <!-- ADD CAMERA ICON TO PICTURE BOX -->
  <script>
    $(document).ready(function(){
      $('#photos .qq-upload-button>div').prepend('<i class="fa fa-camera"></i>&nbsp;</i>');
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

                $("#region").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="regionId" id="regionId" ></select></div>');
                $("#region").remove();

                $("#city").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="cityId" id="cityId" ></select></div>');
                $("#city").remove();
                
                $("#regionId").val("");
                $("#uniform-regionId").find('span').text('<?php _e('Select a region', 'patricia'); ?>');
              } else {

                $("#regionId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a region', 'patricia')); ?>" type="text" name="sRegion" id="region" />');
                $("#regionId").parent().remove();
                
                $("#cityId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a city', 'patricia')); ?>" type="text" name="sCity" id="city" />');
                $("#cityId").parent().remove();

                $("#city").val('');
              }

              $("#regionId").html(result);
              $("#cityId").html('<option selected value=""><?php _e('Select a city', 'patricia'); ?></option>');
              $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');
              $("#cityId").attr('disabled',true);
              $("#uniform-cityId").addClass('disabled');
            }
           });

         } else {

           // add empty select
           $("#region").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="regionId" id="regionId" ><option value=""><?php _e('Select a region', 'patricia'); ?></option></select></div>');
           $("#region").remove();
           
           $("#city").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="cityId" id="cityId" ><option value=""><?php _e('Select a city', 'patricia'); ?></option></select></div>');
           $("#city").remove();

           if( $("#regionId").length > 0 ){
             $("#regionId").html('<option value=""><?php _e('Select a region', 'patricia'); ?></option>');
           } else {
             $("#region").before('<div class="selector" id="uniform-regionId"><span><?php _e('Select a region', 'patricia'); ?></span><select name="regionId" id="regionId" ><option value=""><?php _e('Select a region', 'patricia'); ?></option></select></div>');
             $("#region").remove();
           }

           if( $("#cityId").length > 0 ){
             $("#cityId").html('<option value=""><?php _e('Select a city', 'patricia'); ?></option>');
           } else {
             $("#city").parent().before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="cityId" id="cityId" ><option value=""><?php _e('Select a city', 'patricia'); ?></option></select></div>');
             $("#city").parent().remove();
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

                $("#city").before('<div class="selector" id="uniform-cityId"><span><?php _e('Select a city', 'patricia'); ?></span><select name="cityId" id="cityId" ></select></div>');
                $("#city").remove();

                $("#cityId").val("");
                $("#uniform-cityId").find('span').text('<?php _e('Select a city', 'patricia'); ?>');
              } else {
                result += '<option value=""><?php _e('No cities found', 'patricia'); ?></option>';
                $("#cityId").parent().before('<input type="text" placeholder="<?php echo osc_esc_js(__('Enter a city', 'patricia')); ?>" name="sCity" id="city" />');
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
        $("#city").attr('disabled',true);
        $("#cityId").attr('disabled',true);
        $("#uniform-cityId").addClass('disabled');
      }

      if($("#countryId").length != 0) {
        if( $("#countryId").attr('value') == "")  {
          $("#regionId").attr('disabled',true);
          $("#uniform-regionId").addClass('disabled');
        }
      }


      // MAKE SURE WHEN SELECT LOADS AFTER INPUT, SPAN WRAP IS CORRECTLY FILLED
      $(".row").on('change', '#cityId, #regionId', function() {
        $(this).parent().find('span').text($(this).find("option:selected" ).text());
      });


      // DISABLE NAME & EMAIL TO BE MODIFIED WHEN USER LOGGED IN
      $('.add_item .seller_info.logged input#contactName, .add_item .seller_info.logged input#contactEmail').prop('disabled', true);
      $('.add_item .seller_info.logged input#contactName, .add_item .seller_info.logged input#contactEmail').prop('title', '<?php _e('You can modify your Name and Email in your user profile', 'patricia'); ?>');
    });
  </script>

  <script>
    $(document).ready(function() {

      // TITLE REMAINING CHARACTERS
      var title_max = <?php echo osc_max_characters_per_title(); ?>;
      $('.add_item .title input').attr('maxlength', title_max);
      $('.add_item .title label').append('<div class="title-max-char max-char"></div>');
      $('.title-max-char').html(title_max + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

      $('ul.tabbernav li a').live('click', function(){
        var title_length = $('.add_item .title input:visible').val().length;
        var title_remaining = title_max - title_length;

        $('.title-max-char').html(title_remaining + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

        $('.title-max-char').removeClass('orange').removeClass('red');
        if(title_remaining/title_length <= 0.2 && title_remaining/title_length > 0.1) {
          $('.title-max-char').addClass('orange');
        } else if (title_remaining/title_length <= 0.1) {
          $('.title-max-char').addClass('red');
        }
      });

      $('.add_item .title input:visible').keyup(function() {
        var title_length = $(this).val().length;
        var title_remaining = title_max - title_length;

        $('.title-max-char').html(title_remaining + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

        $('.title-max-char').removeClass('orange').removeClass('red');
        if(title_remaining/title_length <= 0.2 && title_remaining/title_length > 0.1) {
          $('.title-max-char').addClass('orange');
        } else if (title_remaining/title_length <= 0.1) {
          $('.title-max-char').addClass('red');
        }
      });


      // DESCRIPTION REMAINING CHARACTERS
      var desc_max = <?php echo osc_max_characters_per_description(); ?>;
      $('.add_item .description textarea').attr('maxlength', desc_max);
      $('.add_item .description label').append('<div class="desc-max-char max-char"></div>');
      $('.desc-max-char').html(desc_max + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

      $('ul.tabbernav li a').live('click', function(){
        var desc_length = $('.add_item .description textarea:visible').val().length;
        var desc_remaining = desc_max - desc_length;

        $('.desc-max-char').html(desc_remaining + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

        $('.desc-max-char').removeClass('orange').removeClass('red');

        if(desc_remaining/desc_length <= 0.2 && desc_remaining/desc_length > 0.1) {
          $('.desc-max-char').addClass('orange');
        } else if (desc_remaining/desc_length <= 0.1) {
          $('.desc-max-char').addClass('red');
        }
      });

      $('.add_item .description textarea:visible').keyup(function() {
        var desc_length = $(this).val().length;
        var desc_remaining = desc_max - desc_length;

        $('.desc-max-char').html(desc_remaining + ' ' + '<?php _e('characters remaining', 'patricia'); ?>');

        $('.desc-max-char').removeClass('orange').removeClass('red');
        if(desc_remaining/desc_length <= 0.2 && desc_remaining/desc_length > 0.1) {
          $('.desc-max-char').addClass('orange');
        } else if (desc_remaining/desc_length <= 0.1) {
          $('.desc-max-char').addClass('red');
        }
      });
    });


    // CATEGORY CHECK IF PARENT
    <?php if(!osc_selectable_parent_categories()) { ?>
      $(document).ready(function(){
        if(typeof window['categories_' + $('#catId').val()] !== 'undefined'){
          if(eval('categories_' + $('#catId').val()) != '') {
            $('#catId').val('');
          }
        }
      });

      $('#catId').live('change', function(){
        if(typeof window['categories_' + $(this).val()] !== 'undefined'){
          if(eval('categories_' + $(this).val()) != '') {
            $(this).val('');
          }
        }
      });
    <?php } ?>
  </script>

  <script>
    $(document).ready(function(){
      $('.button').click(function(){
        $('select.error').parent().addClass('error');
        $('select.valid').parent().addClass('valid');
        $('#catId').parent().find('select').removeClass('error').removeClass('valid');
        $('#catId.error').parent().find('select').last().addClass('error');
        $('#catId.valid').parent().find('select').last().addClass('valid');
      });

      $('select').change(function(){
        if($(this).val() != '' && $(this).val() != 0) {
          $(this).parent().removeClass('error');
          $(this).parent().addClass('valid');
        } else {
          $(this).parent().removeClass('valid');
          $(this).parent().addClass('error');
        }
      });
    });
  </script>

  <script type="text/javascript">
    // Validation Code
    $(document).ready(function(){
      // Validate description without HTML.
      $.validator.addMethod(
        "minstriptags",
        function(value, element) {
          altered_input = strip_tags(value);
          if (altered_input.length < 3) {
              return false;
          } else {
              return true;
          }
        },
        "Description needs to be longer."
      );

      // Code for form validation
      $("form[name=item]").validate({
        rules: {
          catId: {
              required: true,
              digits: true
          },
                          price: {
              maxlength: 50
          },
          currency: "required",
                                          "photos[]": {
              accept: "png,gif,jpg,jpeg"
          },
                                          contactName: {
              minlength: 3,
              maxlength: 35
          },
          contactEmail: {
              required: true,
              email: true
          },
                          address: {
              minlength: 3,
              maxlength: 100
          }
        },
        messages: {
          catId: "Choose one category.",
                          price: {
              maxlength: "Price: no more than 50 characters."
          },
          currency: "Currency: make your selection.",
                                          "photos[]": {
              accept: "Photo: must be png,gif,jpg,jpeg."
          },
                                          contactName: {
              minlength: "Name: enter at least 3 characters.",
              maxlength: "Name: no more than 35 characters."
          },
          contactEmail: {
              required: "Email: this field is required.",
              email: "Invalid email address."
          },
                          address: {
              minlength: "Address: enter at least 3 characters.",
              maxlength: "Address: no more than 100 characters."
          }
        },
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        },
        submitHandler: function(form){
          $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
          form.submit();
        }
      });
    });


    function strip_tags(html) {
      if (arguments.length < 3) {
        html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
      } else {
        var allowed = arguments[1];
        var specified = eval("["+arguments[2]+"]");
        if (allowed){
          var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
          html=html.replace(new RegExp(regex, 'gi'), '');
        } else{
          var regex='</?(' + specified.join('|') + ')\b[^>]*>';
          html=html.replace(new RegExp(regex, 'gi'), '');
        }
      }
      return html;
    }
  </script>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>	