<?php 
    $i_userId = osc_logged_user_id();
	if(Params::getParam('delete') != '' && osc_is_web_user_logged_in()){
		delete_item(Params::getParam('delete'), $i_userId);
	}

    $itemsPerPage = (Params::getParam('itemsPerPage') != '') ? Params::getParam('itemsPerPage') : 5;
    $iPage        = (Params::getParam('iPage') != '') ? Params::getParam('iPage') : 0;

    Search::newInstance()->addConditions(sprintf("%st_item_watchlist.fk_i_user_id = %d", DB_TABLE_PREFIX, $i_userId));
    Search::newInstance()->addConditions(sprintf("%st_item_watchlist.fk_i_item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
    Search::newInstance()->addTable(sprintf("%st_item_watchlist", DB_TABLE_PREFIX));
    Search::newInstance()->page($iPage, $itemsPerPage);

    $aItems      = Search::newInstance()->doSearch();
    $iTotalItems = Search::newInstance()->count();
    $iNumPages   = ceil($iTotalItems / $itemsPerPage) ;

    View::newInstance()->_exportVariableToView('items', $aItems);
    View::newInstance()->_exportVariableToView('search_total_pages', $iNumPages);
    View::newInstance()->_exportVariableToView('search_page', $iPage) ;

	// delete item from watchlist
	function delete_item($item, $uid){
		$conn = getConnection();
		$conn->osc_dbExec("DELETE FROM %st_item_watchlist WHERE fk_i_item_id = %d AND fk_i_user_id = %d LIMIT 1", DB_TABLE_PREFIX , $item, $uid);
	}
?>

  <div class="content user_account">
  <h1>
    <?php if(function_exists('profile_picture_show')) { profile_picture_show(40); } ?>
    <span><?php _e('Your watchlist', 'watchlist') ; ?></span>
  </h1>
  <div id="sidebar">
    <?php echo osc_private_user_menu() ; ?>
  </div>

  <div id="main" class="ad_list">
    <h2>
      <span>
        <?php printf(_n('You are watching %d item', 'You are watching %d items', $iTotalItems, 'watchlist'), $iTotalItems) ; ?>
      </span>
    </h2>
    <div class="clear"></div>

    <?php if (osc_count_items() == 0) { ?>
      <div class="empty"><?php _e('You don\'t watch any items now', 'watchlist'); ?></div>
    <?php } else { ?>
      <div id="list-view">
        <?php while(osc_has_items()) { ?>
          <div class="list-prod">
            <div class="right">
              <?php if( osc_price_enabled_at_items() ) { ?>
                <div class="price"><?php echo osc_item_formated_price(); ?></div>
              <?php } ?>

              <a class="view round2" href="<?php echo osc_item_url(); ?>"><?php _e('view', 'watchlist'); ?></a>
              <a class="category" href="<?php echo osc_search_url(array('sCategory' => osc_item_category_id())); ?>"><?php echo osc_item_category(); ?></a>

              <?php
                $now = time();
                $your_date = strtotime(osc_item_pub_date());
                $datediff = $now - $your_date;
                $item_d = floor($datediff/(60*60*24));

                if($item_d == 0) {
                  $item_date = __('today', 'watchlist');
                } else if($item_d == 1) {
                  $item_date = __('yesterday', 'watchlist');
                } else {
                  $item_date = date(osc_get_preference('date_format', 'patricia_theme'), $your_date);
                }
              ?>
              <span class="date">
                <?php 
                  if($item_d == 0 or $item_d  == 1) {
                    echo __('published', 'watchlist') . ' <span>' . $item_date . '</span>'; 
                  } else {
                    echo __('published on', 'watchlist') . ' <span>' . $item_date . '</span>'; 
                  }
                ?>
              </span>

              <span class="viewed">
                <?php echo __('viewed', 'watchlist') . ' <span>' . osc_item_views() . 'x' . '</span>'; ?>
              </span>

              <div class="edit-delete resp">
                <a onclick="return confirm('<?php _e('Are you sure you want to remove this listing from watchlist? This action cannot be undone.', 'watchlist'); ?>')" href="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '&delete=' . osc_item_id(); ?>" rel="nofollow"><i class="fa fa-trash-o"></i>&nbsp;<?php _e('Remove from watchlist', 'watchlist'); ?></a>
              </div>
            </div>
            <div class="left">
              <h3 class="resp-title"><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>

              <?php if(osc_images_enabled_at_items() and osc_count_item_resources() > 0) { ?>
                <a class="big-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_item_title(); ?>" alt="<?php echo osc_item_title(); ?>" /></a>

                <div class="img-bar">
                  <?php osc_reset_resources(); ?>
                  <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                    <?php if($i < 3) { ?>
                      <span class="small-img" id="bar_img_<?php echo $i; ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_item_title(); ?>" alt="<?php echo osc_item_title(); ?>" <?php echo ($i==0 ? 'class="selected"' : ''); ?> /></span>
                    <?php } ?>
                  <?php } ?>
                </div>
              <?php } else { ?>
                <a class="big-img no-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_item_title(); ?>" alt="<?php echo osc_item_title(); ?>" /></a>
              <?php } ?>
            </div>

            <div class="middle">
              <?php if(osc_item_is_premium()) { ?>
                <div class="flag"><?php _e('top', 'watchlist'); ?></div>
              <?php } ?>

              <h3><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>
              <div class="desc"><?php echo osc_highlight(osc_item_description(), 300); ?></div>
              <div class="loc"><i class="fa fa-map-marker"></i><?php echo patricia_location_format(osc_item_country(), osc_item_region(), osc_item_city()); ?></div>
              <div class="author">
                <i class="fa fa-pencil"></i><?php _e('Published by', 'watchlist'); ?> 
                <?php if(osc_item_user_id() <> 0) { ?>
                  <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php echo osc_item_contact_name(); ?></a>
                <?php } else { ?>
                  <?php echo (osc_item_contact_name() <> '' ? osc_item_contact_name() : __('Anonymous', 'watchlist')); ?>
                <?php } ?>
              </div>

              <div class="edit-delete">
                <a onclick="return confirm('<?php _e('Are you sure you want to remove this listing from watchlist? This action cannot be undone.', 'watchlist'); ?>')" href="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '&delete=' . osc_item_id(); ?>" rel="nofollow"><i class="fa fa-trash-o"></i>&nbsp;<?php _e('Remove from watchlist', 'watchlist'); ?></a>
              </div>
            </div>            
          </div>
        <?php } ?>
      </div>

      <div class="clear"></div>

      <div class="paginate">
        <?php echo osc_pagination(array('url' => osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '?iPage={PAGE}')); ?>
      </div>
    <?php } ?>
  </div>
</div>
