<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('コミュニティリスト')); ?>

<?php echo $form->renderFormTag(url_for('community/list'), array('method' => 'get')) ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('検索') ?>" /></td>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('該当するコミュニティは存在しません。') ?></p>
<?php else: ?>
<?php slot('pager') ?>
<p><?php op_include_pager_navigation($pager, 'community/list?page=%d', array('use_current_query_string' => true)) ?></p>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<?php foreach ($pager->getResults() as $community): ?>
<?php include_partial('community/communityInfo', array(
  'community' => $community, 
  'moreInfo' => array(
    link_to(__('Delete'), 'community/delete?id='.$community->getId()),
    link_to(__('Make all members join in this community'), 'community/addAllMember?id='.$community->getId())
  )
)); ?>
<?php endforeach; ?>
<?php include_slot('pager') ?>
<?php endif; ?>
