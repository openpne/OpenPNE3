<a href="<?php echo sf_image_path($image->getFile()) ?>" target="_blank"><?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120')) ?></a><br />
%input%<br />
%delete% <?php echo __('remove the current file') ?>
