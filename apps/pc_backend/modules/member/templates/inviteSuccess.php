<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2><?php echo __('Send invitation message') ?></h2>

<p><?php echo __('Enter email addresses of people to invite to %1%.', array('%1%' => $op_config['sns_name'])) ?></p>
<p><?php echo __('Please enter one email address per line.') ?></p>

<?php if ($sf_user->hasFlash('notice')): ?>
<span id="flashNotice" class="flash"><?php echo __($sf_user->getFlash('notice')) ?></span>
<?php endif; ?>

<form action="<?php echo url_for('member/invite') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Send') ?>" /></td>
</tr>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
  <h2><?php echo __('Send history') ?></h2>
  <span id="flashNotice" class="flash"><?php echo __('No members matching') ?></span>
<?php else: ?>
  <h2><?php echo __('Send history') ?></h2>
  <form action="<?php echo url_for('member/invite') ?>" method="post" class="form-horizontal" role="form">
  <div>
    <table>
      <caption>
        <?php op_include_pager_navigation($pager, 'member/invite?page=%d', array('use_current_query_string' => true)) ?>
      </caption>
      <thead>
      <tr>
        <th><?php echo __('Send situation') ?></th>
        <th><?php echo __('Email') ?></th>
        <th><?php echo __('Send date') ?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($pager->getResults() as $value): ?>
      <tr>
        <td>
          <?php echo __('Send completion') ?>
        </td>
        <td>
          <?php echo $value['value'] ?>
        </td>
        <td>
          <?php echo $value['created_at'] ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  </form>
<?php endif; ?>
