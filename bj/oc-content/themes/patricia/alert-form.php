<script type="text/javascript">
$(document).ready(function(){
  $(".sub_button").click(function(){
    $.post('<?php echo osc_base_url(true); ?>', {email:$("#alert_email").val(), userid:$("#alert_userId").val(), alert:$("#alert").val(), page:"ajax", action:"alerts"}, 
      function(data){
        if(data==1) { alert('<?php echo osc_esc_js(__('You have sucessfully subscribed to the alert', 'patricia')); ?>'); }
        else if(data==-1) { alert('<?php echo osc_esc_js(__('Invalid email address', 'patricia')); ?>'); }
        else { alert('<?php echo osc_esc_js(__('There was a problem with the alert, please try again later', 'patricia')); ?>');
        };
    });
    return false;
  });

  var sQuery = '<?php echo osc_esc_js(AlertForm::default_email_text()); ?>' ;

  if($('input[name=alert_email]').val() == sQuery) {
    $('input[name=alert_email]').css('color', 'gray');
  }
  $('input[name=alert_email]').click(function(){
    if($('input[name=alert_email]').val() == sQuery) {
      $('input[name=alert_email]').val('');
      $('input[name=alert_email]').css('color', '');
    }
  });
  $('input[name=alert_email]').blur(function(){
    if($('input[name=alert_email]').val() == '') {
      $('input[name=alert_email]').val(sQuery);
      $('input[name=alert_email]').css('color', 'gray');
    }
  });
  $('input[name=alert_email]').keypress(function(){
    $('input[name=alert_email]').css('background','');
  })
});
</script>

<div id="n-block" class="block">
  <div class="head"> 
    <span class="left"><i class="fa fa-bell"></i></span> 
    <span class="next"> 
      <span class="small"><?php _e('subscribe to', 'patricia'); ?></span> 
      <span class="big"><?php _e('Alert', 'patricia'); ?></span> 
    </span>
  </div>
  
  <div class="text"><?php _e('Stay in touch with us and subscribe for newsletters', 'patricia'); ?></div>
  <div class="n-wrap">
    <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert">
      <?php AlertForm::page_hidden(); ?>
      <?php AlertForm::alert_hidden(); ?>

      <?php if(osc_is_web_user_logged_in()) { ?>
        <?php AlertForm::user_id_hidden(); ?>
        <?php AlertForm::email_hidden(); ?>

      <?php } else { ?>
        <?php AlertForm::user_id_hidden(); ?>
        <?php AlertForm::email_text(); ?>
      <?php }; ?>

      <?php if(osc_is_web_user_logged_in()) { ?><div class="alert-logged"><?php } ?>
      <button class="button orange-button round2 sub_button"><?php _e('Subscribe', 'patricia'); ?></button>
      <?php if(osc_is_web_user_logged_in()) { ?></div><?php } ?>

    </form>
  </div>
    
  <div class="under">
    <div class="row"><?php _e('You will get emails about', 'patricia'); ?>:</div>
    <div class="row"><i class="fa fa-tag"></i> <?php _e('New products matching your criteria', 'patricia'); ?></div>
    <div class="row"><i class="fa fa-ban"></i> <?php _e('No spam guarantee', 'patricia'); ?></div>
  </div>
</div>
