<?php slot('op_sidemenu'); ?>
<?php
$categoryList = array();
foreach ($config as $k => $v)
{
  $categoryList[$k] = link_to(__($v), '@confirmation_list?category='.$k);
}

op_include_parts('pageNav', 'pageNav', array('list' => $categoryList, 'current' => $category));
?>
<?php end_slot(); ?>

<?php if (count($list)): ?>
<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __($config[$category]) ?></h3></div>
<p><?php echo __('You have the following pending requests. Select "Accept" or "Reject".') ?></p>
<?php foreach ($list as $item): ?>
<?php echo $form->renderFormTag(url_for('@confirmation_decision?id='.$item['id'].'&category='.$category)) ?>
<?php echo $form->renderHiddenFields() ?>
<div class="ditem"><div class="item"><table style="background-color: #fff;"><tr>
<td class="photo" rowspan="<?php echo count($item['list']) + 1 ?>">
<?php echo link_to(op_image_tag_sf_image($item['image']['url'], array('size' => '76x76')), $item['image']['link']); ?>
</td>
</tr>
<?php foreach ($item['list'] as $k => $v): ?>
<tr>
<th><?php echo __($k) ?></th><td>
<?php if (isset($v['link'])): ?>
<?php echo link_to(nl2br($v['text']), $v['link']) ?>
<?php else: ?>
<?php echo nl2br($v['text']) ?>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
<tr class="operation">
<td colspan="3"><span class="moreInfo">

<input type="submit" name="accept" value="<?php echo __('Accept') ?>" class="input_submit" />
<input type="submit" value="<?php echo __('Reject') ?>" class="input_submit" />
</span></td>
</tr>
</table></div></div>
</form>
<?php endforeach; ?>

</div></div>
<?php else: ?>
<?php op_include_box('searchMemberResult', __('You don\'t have any pending requests'), array('title' => __($config[$category]))) ?>
<?php endif; ?>
