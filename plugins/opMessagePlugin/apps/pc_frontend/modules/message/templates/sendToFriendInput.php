<?php ob_start() ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(image_tag_sf_image($sendMember->getImageFileName(), array('size' => '76x76')), 'member/profile?id='.$sendMember->getId()) ?> </td></tr>
<tr><th><?php echo __('To') ?></th><td><?php echo link_to($sendMember->getName(), 'member/profile?id='.$sendMember->getId()) ?></td></tr>
<?php $firstRow = ob_get_contents() ?>
<?php ob_end_clean() ?>
<?php
$options['title'] = __('Compose Message');
$options['url'] = url_for('message/sendToFriend');
$options['button'] = __('Send');
$options['isMultipart'] = true;
$options['firstRow'] = $firstRow;
op_include_form('formMessage', $form, $options);
