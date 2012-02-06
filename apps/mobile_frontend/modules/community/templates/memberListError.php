<?php op_mobile_page_title($community->getName(), __('%Community% Members')) ?>
<?php echo __('%Community% member does not exist.') ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php echo link_to(__('%Community% Top'), '@community_home?id='.$community->getId()) ?>
