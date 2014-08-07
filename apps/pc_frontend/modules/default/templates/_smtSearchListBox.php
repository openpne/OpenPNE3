<?php use_helper('opAsset') ?>
<?php op_smt_use_javascript('op_searchbox.js') ?>

<div class="row">
  <div class="input-prepend span12">
    <span class="add-on"><i class="icon-search"></i></span>
    <input type="text" class="searchBox realtime-searchbox"/>
  </div>
</div>
<div class="resultList"></div>
<div class="loadingImage row" style="text-align: center; display: none">
  <?php echo op_image_tag('ajax-loader.gif') ?>
</div>
