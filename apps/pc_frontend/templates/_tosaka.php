<!-- NCFORM TMPL -->
<div class="ncform hide toggle1">
  <div class="row">
    <div class="span10 offset1 center white font14 toggle1_close">
      通知センター
    </div>
    <div class="span1">
      <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close')) ?>
    </div>
  </div>
  <div class="row">
  </div>
</div>
<!-- NCFORM TMPL -->

<?php include_component('default', 'smtMenu') ?>

<!-- POSTFORM TMPL -->
<div class="postform hide toggle1">
  <div class="row">
    <div class="span10 offset1 center white font14 toggle1_close">
      投稿フォーム
    </div>
    <div class="span1">
      <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close')) ?>
    </div>
  </div>
  <div class="row posttextarea">
    <textarea id="tosaka_postform_body" class="span12" rows="4"></textarea>
  </div>
  <div class="row">
    <button id="tosaka_postform_submit" class="span10 offset1 btn small primary">POST</button>
  </div>
</div>
<!-- POSTFORM TMPL -->

<div id="slot_tosaka">
  <div class="row">
    <div class="span12">
      <div class="row">
        <div class="span4"><?php echo op_image_tag('LOGO.png', array('height' => '32', 'class' => 'menubutton')); ?></div>
        <div class="span4 center"><?php echo op_image_tag('NOTIFY_CENTER.png', array('height' => '32', 'class' => 'ncbutton')) ?></div>
        <div class="span3 offset1 center"><?php echo op_image_tag('POST.png', array('height' => '32', 'class' =>'postbutton')) ?></div>
      </div>
    </div>
  </div>
</div>
