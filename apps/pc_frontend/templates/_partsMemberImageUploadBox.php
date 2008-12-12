<div class="dparts memberImageUploadBox"><div class="parts">
<div class="partsHeading"><h3><?php echo(__('写真を編集する')) ?></h3></div>
<table>
<tr>
<?php for ($i = 0; $i < 3; $i++) : ?>
<td>
<?php if (isset($option['images'][$i])) : ?>
<?php $image = $option['images'][$i] ?>
<?php echo image_tag_sf_image($image->getFile(), array('size' => '180x180')) ?><br />
[
<?php echo link_to(__('削除'), 'member/deleteImage?member_image_id='.$image->getId()) ?> |
<?php if ($image->getIsPrimary()) : ?>
<?php echo(__('メイン写真')) ?>
<?php else: ?>
<?php echo link_to(__('メイン写真'), 'member/changeMainImage?member_image_id='.$image->getId()) ?>
<?php endif; ?>
]
<?php else: ?>
<?php echo image_tag('no_image.gif', array('size' => '180x180', 'alt' => '')) ?>
<?php endif; ?>
</td>
<?php endfor; ?>
</tr>
</table>

<div class="block">
<?php echo $option['form']->renderFormTag(url_for('member/configImage')) ?>
<p><?php echo $option['form']['file'] ?></p>
<p><input type="submit" value="<?php echo __('アップロードする') ?>" /></p>
</form>
<ul>
<li><?php echo __('写真は最大3枚までアップロードできます。') ?></li>
<li><?php echo __('300KB以内のGIF・JPEG・PNGにしてください。') ?></li>
<li><?php echo __('著作権や肖像権の侵害にあたる写真、暴力的・卑猥な写真、他のメンバーが見て不快に感じる写真の掲載は禁止しております。掲載はご自身の責任でお願いいたします。') ?></li>
</ul>
</div>
</div></div>
