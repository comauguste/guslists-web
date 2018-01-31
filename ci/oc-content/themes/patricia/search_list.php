<div id="list-view">
  <?php while(osc_has_items()) { ?>
    <div class="list-prod">
      <div class="right">
        <?php if( osc_price_enabled_at_items() ) { ?>
          <div class="price"><?php echo osc_item_formated_price(); ?></div>
        <?php } ?>

        <a class="view round2" href="<?php echo osc_item_url(); ?>"><?php _e('view', 'patricia'); ?></a>
        <a class="category" href="<?php echo osc_search_url(array('sCategory' => osc_item_category_id())); ?>"><?php echo osc_item_category(); ?></a>

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
        <span class="date">
          <?php 
            if($item_d == 0 or $item_d  == 1) {
              echo __('published', 'patricia') . ' <span>' . $item_date . '</span>'; 
            } else {
              echo __('published on', 'patricia') . ' <span>' . $item_date . '</span>'; 
            }
          ?>
        </span>

        <span class="viewed">
          <?php echo __('viewed', 'patricia') . ' <span>' . osc_item_views() . 'x' . '</span>'; ?>
        </span>
      </div>

      <div class="left">
        <h3 class="resp-title"><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>

        <?php if(osc_images_enabled_at_items() and osc_count_item_resources() > 0) { ?>
          <a class="big-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>

          <div class="img-bar">
            <?php osc_reset_resources(); ?>
            <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
              <?php if($i < 3) { ?>
                <span class="small-img" id="bar_img_<?php echo $i; ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" <?php echo ($i==0 ? 'class="selected"' : ''); ?> /></span>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } else { ?>
          <a class="big-img no-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
        <?php } ?>
      </div>

      <div class="middle">
        <?php if(osc_item_is_premium()) { ?>
          <div class="flag"><?php _e('top', 'patricia'); ?></div>
        <?php } ?>

        <h3><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>
        <div class="desc <?php if(osc_count_item_resources() > 0) { ?>has_images<?php } ?>"><?php echo osc_highlight(osc_item_description(), 300); ?></div>
        <div class="loc"><i class="fa fa-map-marker"></i><?php echo patricia_location_format(osc_item_country(), osc_item_region(), osc_item_city()); ?></div>
        <div class="author">
          <i class="fa fa-pencil"></i><?php _e('Published by', 'patricia'); ?> 
          <?php if(osc_item_user_id() <> 0) { ?>
            <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php echo osc_item_contact_name(); ?></a>
          <?php } else { ?>
            <?php echo (osc_item_contact_name() <> '' ? osc_item_contact_name() : __('Anonymous', 'patricia')); ?>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>
</div>


