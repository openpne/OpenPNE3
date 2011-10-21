<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('%Community% List')); ?>

<?php $form->getWidgetSchema()->setLabel("name", __('%Community% Name')); ?>
<?php $form->getWidgetSchema()->setLabel("community_category_id", __('%Community% Category')); ?>
<?php echo $form->renderFormTag(url_for('community/list'), array('method' => 'get')) ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Search') ?>" /></td>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('There are no %community% matches search condition.') ?></p>
<?php else: ?>
<?php slot('pager') ?>
<p><?php op_include_pager_navigation($pager, 'community/list?page=%d', array('use_current_query_string' => true)) ?></p>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<?php foreach ($pager->getResults() as $community): ?>
<?php $moreInfo = array(
  link_to(__('Delete'), 'community/delete?id='.$community->getId()),
  link_to(__('Make all members join in this %community%'), 'community/addAllMember?id='.$community->getId())
) ?>
<?php if (!(bool)$community->getConfig('is_default')): ?>
<?php $moreInfo[] = link_to(__('Add this %community% as a default'), 'community/addDefaultCommunity?id='.$community->getId()) ?>
<?php else: ?>
<?php $moreInfo[] = link_to(__('Remove this %community% from the default'), 'community/removeDefaultCommunity?id='.$community->getId()) ?>
<?php endif; ?>
<?php include_partial('community/communityInfo', array(
  'community' => $community, 
  'moreInfo' => $moreInfo,
)); ?>
<?php endforeach; ?>
<?php include_slot('pager') ?>
<?php endif; ?>
