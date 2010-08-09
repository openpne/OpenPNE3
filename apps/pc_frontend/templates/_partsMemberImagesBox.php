<table>
<tr>
<?php $form = new sfForm() ?>
<?php $csrfToken = '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php for ($i = 0; $i < 3; $i++) : ?>
<td>
<?php if (isset($options['images'][$i])) : ?>
<?php $image = $options['images'][$i] ?>
<?php echo image_tag_sf_image($image->getFile(), array('size' => '180x180')) ?><br />
<?php if (isset($options['form'])) : ?>
[
<?php echo link_to(__('Delete'), 'member/deleteImage?member_image_id='.$image->getId().$csrfToken) ?> |
<?php if ($image->getIsPrimary()) : ?>
<?php echo(__('Main Photo')) ?>
<?php else: ?>
<?php echo link_to(__('Main Photo'), 'member/changeMainImage?member_image_id='.$image->getId().$csrfToken) ?>
<?php endif; ?>
]
<?php endif; ?>
<?php else: ?>
<?php echo image_tag('no_image.gif', array('size' => '180x180', 'alt' => '')) ?>
<?php endif; ?>
</td>
<?php endfor; ?>
</tr>
</table>
<?php if (isset($options['form'])) : ?>
<div class="block">
<?php echo $options['form']->renderFormTag(url_for('member/configImage')) ?>
<p>
<?php echo $options['form']['file'] ?>
<?php echo $options['form']->renderHiddenFields(); ?>
</p>
<p><input type="submit" class="input_submit" value="<?php echo __('アップロードする') ?>" /></p>
</form>
<ul>
<li><?php echo __('You can upload 3 photos.') ?></li>
<li><?php echo __('Please make it to GIF･JPEG･PNG within %max_size% bytes.', array('%max_size%' =>  $op_config['image_max_filesize'])) ?></li>
<li><?php echo __('Photograph that hits violation of copyright and portrait right and violence and obscene photograph and other members are seen and revolted a press ban. Please publish by the self-responsibility.') ?></li>
</ul>
</div>
<?php endif; ?>
