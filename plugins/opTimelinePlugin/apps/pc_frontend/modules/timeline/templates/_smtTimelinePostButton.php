<script>
$(document).ready(function(){
  $("#postbutton").click(function(){
    $("body > *").hide();
    $(".postform").show();
  });
  $("#gorgon-submit").click(function(){
    $("body > *").show();
    $(".postform").hide();
  });
});
</script>
<div class="postform hide">
  <div class="post_margin row" style="height: 12px;">
    <div class="span12">
      <img src="<?php echo url_for('@homepage'); ?>POST_MARGIN.png" width="320" alt="" />
    </div>
  </div>
  <div id="face" class="row">
    <div class="span2"><?php echo op_image_tag_sf_image($sf_user->getMember()->getImageFileName(), array('size' => '48x48', 'class' => 'rad4')) ?> </div>
    <div class="span3">
      <img src="<?php echo url_for('@homepage'); ?>post_icon.png" alt="" />
    </div>
    <div class="span5">
      <div class="row"><span class="face-name"><?php echo $op_config['sns_name']; ?></span></div>
      <hr class="toumei">
    </div>
  </div>
  <hr class="toumei">
  <div class="row">
    <textarea class="span12" rows="3" ></textarea>
  </div>
  <div class="row">
    <button class="span12 btn small primary" id="gorgon-submit">POST</button>
  </div>
</div>
