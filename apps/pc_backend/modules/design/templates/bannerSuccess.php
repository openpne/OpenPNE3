<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php $imgParam = array('size' => '180x180', 'alt' => '') ?>

<h2><?php echo $banner->getCaption().__('設定') ?></h2>

<?php if ($bannerList): ?>
<div>
<ul>
<?php foreach ($bannerList as $b) : ?>
<li><?php echo link_to($b->getCaption().__('設定'), 'design/banner?id='.$b->getId()) ?></li>
<?php endforeach ?>
</ul>
</div>
<?php endif ?>

<form action="" method="post">
<input type="hidden" name="banner_type_id" value="<?php echo $banner->getId() ?>" />
<table>
<tbody>
<tr>
<th><input type="radio" name="is_use_html" value="0" <?php if (!$banner->getIsUseHtml()): ?>checked="checked"<?php endif ?> /></th>
<td>
<?php if (count($bannerImageList)): ?>
<p><?php echo __('表示したいバナー画像を選択する') ?></p>
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
<?php $name = 'banner_use_image_ids['.$bannerImage->getId().']' ?>
<input type="radio" id="banner_image<?php echo $j ?>" name="<?php echo $name ?>" value="1" <?php if (isset($useBannerImageList[$bannerImage->getId()])): ?> checked="checked"<?php endif ?> />
<label for="banner_image<?php echo $j ?>"><?php echo __('表示する') ?></label>
<input type="radio" id="banner_uimage<?php echo $j ?>" name="<?php echo $name ?>" value="0" <?php if (!isset($useBannerImageList[$bannerImage->getId()])): ?> checked="checked"<?php endif ?> />
<label for="banner_uimage<?php echo $j ?>"><?php echo __('表示しない') ?></label>
</li>
<li><label><?php echo __('URL') ?>:</label><?php echo $bannerImage->getUrl() ?></li>
<li><label><?php echo __('バナー名') ?>:</label><?php echo $bannerImage->getName() ?></li>
<li><?php echo link_to( __('変更'), 'design/banneredit?id='.$bannerImage->getId()) ?></li>
<li><?php echo link_to(__('削除'), 'design/bannerdelete?id='.$bannerImage->getId()) ?></li>
</ul></td>
<?php endfor ?>
</tr>
<?php endfor ?>
</tbody></table>
<?php else: ?>
<p><?php echo __('未設定（アップロードされている画像はありません）') ?></p>
<?php endif ?>
<p class="add_banner_image"><?php echo link_to(__('【バナー画像を追加】'), 'design/banneradd') ?></p>
</td>
</tr>
<tr>
<th><input type="radio" name="is_use_html" value="1" <?php if ($banner->getIsUseHtml()): ?>checked="checked"<?php endif ?> /></th>
<td>
<p><?php echo __('任意HTMLで表示する') ?></p>
<textarea name="html" cols="72" rows="5"><?php echo $banner->getHtml() ?></textarea>
</td>
</tr>
</tbody>
</table>
<input type="submit" value="<?php echo __('設定を確定する') ?>" />
</form>
