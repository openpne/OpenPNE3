<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Manage Apps') ?>
<?php end_slot() ?>

<table>
<?php $searchForm->renderFormTag(url_for('opOpenSocialPlugin/list'), array('method' => 'get')) ?>
<?php echo $searchForm ?>
<td colspan="2"><input type="submit" value="<?php echo __('Search') ?>"/></td>
</table>

<?php if ($pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, 'opOpenSocialPlugin/list?page=%d', array('use_current_query_string' => true)) ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<ul>
<?php foreach ($pager->getResults() as $application): ?>
<li><?php echo link_to($application->getTitle(),'opOpenSocialPlugin/info?id='.$application->getId()) ?></li>
<?php endforeach; ?>
</ul>
<?php include_slot('pager') ?>
<?php else: ?>
<?php echo __('There are no app.') ?>
<?php echo __('The app can be installed from %0%.', array('%0%' => link_to(__('Add App'), 'opOpenSocialPlugin/add'))) ?>
<?php endif; ?>
