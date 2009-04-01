<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('コミュニティリスト')); ?>

<?php echo $form->renderFormTag(url_for('community/list')) ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('検索') ?>" /></td>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('該当するコミュニティは存在しません。') ?></p>
<?php else: ?>
<?php ob_start() ?>
<p><?php op_include_pager_navigation($pager, 'community/list?page=%d') ?></p>
<?php $pagerNavi = ob_get_flush() ?>
<?php foreach ($pager->getResults() as $community): ?>
<?php include_partial('community/communityInfo', array(
  'community' => $community, 
  'moreInfo' => array(
    link_to(__('削除'), 'community/delete?id='.$community->getId())
  )
)); ?>
<?php endforeach; ?>
<?php echo $pagerNavi ?>
<?php endif; ?>
