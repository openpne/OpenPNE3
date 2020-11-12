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

<h2><?php echo __('Send history') ?></h2>
<?php if (!$pager->getNbResults()): ?>
  <span id="flashNotice" class="flash"><?php echo __('No members matching') ?></span>
<?php else: ?>
  <?php if ($deleteForm->hasGlobalErrors()): ?>
    <?php foreach ($deleteForm->getGlobalErrors() as $name => $error): ?>
        <?php echo __($error) ?>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php echo $deleteForm->renderFormTag(url_for('member/invite'), array('method' => 'post')) ?>
  <input type="hidden" name="page" value="<?php echo $pager->getPage() ?>"/>
  <div>
    <p><?php op_include_pager_navigation($pager, 'member/invite?page=%d', array('use_current_query_string' => true)) ?></p>
    <table>
      <thead>
      <tr>
        <th><?php echo __('Delete') ?></th>
        <th><?php echo __('Send situation') ?></th>
        <th><?php echo __('Email') ?></th>
        <th><?php echo __('Send date') ?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($pager->getResults() as $value): ?>
        <?php $field = $deleteForm[$value->id] ?>
        <tr>
          <td>
            <?php echo $field ?>
          </td>
          <td>
            <?php echo __('Send completion') ?>
          </td>
          <td>
            <?php echo $field->renderLabel() ?>
          </td>
          <td>
            <?php echo $value['created_at'] ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4">
          <?php echo $deleteForm->renderHiddenFields() ?>
          <input value="<?php echo __('Delete') ?>" type="submit">
          <input value="<?php echo __('Reset') ?>" type="reset">
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
  </form>
<?php endif; ?>
