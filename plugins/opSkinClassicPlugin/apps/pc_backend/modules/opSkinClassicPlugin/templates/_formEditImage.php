<?php if (0 === strpos($target, 'skin_')): ?>
<?php echo image_tag(opSkinClassicConfig::get($target.'_image'), array('width' => '180')) ?><br />
<?php else: ?>
<?php echo image_tag(opSkinClassicConfig::get($target.'_image')) ?><br />
<?php endif; ?>
<br />
%input%<br />
%delete% %delete_label%
