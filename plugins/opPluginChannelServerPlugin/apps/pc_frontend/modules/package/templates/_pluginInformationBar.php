<div class="parts memberImageBox">
<p class="photo"><?php echo link_to(image_tag_sf_image($package->getImageFileName(), array('size' => '120x120')), 'package_home', $package) ?></p>
<p class="text"><?php echo $package->name ?></p>
</div>

<?php

op_include_parts('listBox', 'pluginInformationList', array(
  'list' => array(
    __('License') => $package->license,
    __('Category') => $package->Category,
    __('Count of Users') => $package->user_count,
  ),
));
