<?php include_partial('global/header'); ?>
<div id="LayoutA">

<div id="Top">
<?php include_slot('op_top') ?>
</div>

<div id="Sidemenu">
<?php include_slot('op_sidemenu') ?>
</div>

<div id="Contents">
<?php echo $sf_content ?>
</div>

</div>
<?php include_partial('global/footer'); ?>
