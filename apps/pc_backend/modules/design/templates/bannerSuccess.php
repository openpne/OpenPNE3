<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php $imgParam = array('size' => '180x180', 'alt' => '') ?>

<?php echo $form->renderGlobalErrors() ?>

<h2><?php echo __('%1% settings', array('%1%' => $form->getObject()->getCaption())) ?></h2>

<?php if ($bannerList): ?>
<div>
<ul>
<?php foreach ($bannerList as $b) : ?>
<li><?php echo link_to(__('%1% settings', array('%1%' => $b->getCaption())), 'design/banner?id='.$b->getId()) ?></li>
<?php endforeach ?>
</ul>
</div>
<?php endif ?>

<form action="" method="post">
<?php echo $form->renderHiddenFields() ?>
<table>
<tbody>
<tr>
<th><input type="radio" name="banner[is_use_html]" value="0" <?php if (!$form->getObject()->getIsUseHtml()): ?>checked="checked"<?php endif ?> /></th>
<td>
<?php if (count($bannerImageList)): ?>
<p><?php echo __('Select banner images you want to display') ?></p>
<table><tbody>
<?php for ($i = 0; isset($bannerImageList[$i]); $i += 4): ?>
<tr>
<?php for ($j = $i; $j < $i + 4 && isset($bannerImageList[$j]); $j++): ?>
<?php $bannerImage = $bannerImageList[$j] ?>
<th>
<?php
echo link_to(
  image_tag_sf_image($bannerImage->getFile(), $imgParam),
  sf_image_path($bannerImage->getFile())
)
?>
</th>
<?php endfor ?>
</tr>
<tr>
<?php for ($j = $i; $j < $i + 4 && isset($bannerImageList[$j]); $j++): ?>
<?php $bannerImage = $bannerImageList[$j] ?>
<td><ul>
<li>
<?php echo $form['banner_use_image_id]['.$bannerImage->getId()]->render() ?>
</li>
<li><label><?php echo __('Link place') ?>:</label><?php echo $bannerImage->getUrl() ?></li>
<li><label><?php echo __('Banner name') ?>:</label><?php echo $bannerImage->getName() ?></li>
<li><?php echo link_to( __('Modify'), 'design/banneredit?id='.$bannerImage->getId()) ?></li>
<li><?php echo link_to(__('Delete'), 'design/bannerdelete?id='.$bannerImage->getId()) ?></li>
</ul></td>
<?php endfor ?>
</tr>
<?php endfor ?>
</tbody></table>
<?php else: ?>
<p><?php echo __('No uploaded image') ?></p>
<?php endif ?>
<p class="add_banner_image"><?php echo link_to(__('Add a banner image'), 'design/banneradd') ?></p>
</td>
</tr>
<tr>
<th><input type="radio" name="banner[is_use_html]" value="1" <?php if ($form->getObject()->getIsUseHtml()): ?>checked="checked"<?php endif ?> /></th>
<td>
<p><?php echo __('It displays by arbitrary HTML') ?></p>
<?php echo $form['html']->render() ?>
</td>
</tr>
</tbody>
</table>
<input type="submit" value="<?php echo __('Decide settings') ?>" />
</form>
