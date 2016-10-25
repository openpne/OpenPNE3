<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('%Activity% list')) ?>

<?php echo $form->renderFormTag(url_for(array('sf_route' => 'monitoring_activity_list'))) ?>
<?php echo $form->renderHiddenFields() ?>
<table>
  <tbody>
    <?php echo $form ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="<?php echo __('Send') ?>" /></td>
    </tr>
  </tfoot>
</table>
</form>

<?php if (!$form->hasErrors()): ?>

<p><?php op_include_pager_navigation($pager, '@monitoring_activity_list?page=%d', array('use_current_query_string' => true)) ?></p>

<?php foreach ($pager as $activity): ?>
<table>
  <colgroup>
    <col style="width: 10em" />
    <col />
  </colgroup>
  <tbody>
    <tr>
      <th><?php echo __('ID') ?></th>
      <td><?php echo $activity->id ?></td>
    </tr>
    <tr>
      <th><?php echo __('Created Date') ?></th>
      <td><?php echo $activity->created_at ?></td>
    </tr>
    <tr>
      <th><?php echo __('Author') ?></th>
      <td>ID: <?php echo $activity->member_id ?> (<?php echo $activity->Member->name ?>)</td>
    </tr>
    <tr>
      <th><?php echo __('Public Flag') ?></th>
      <td><?php echo $activity->getPublicFlagCaption() ?></td>
    </tr>
    <tr>
      <th><?php echo __('Body') ?></th>
      <td><?php echo $activity->body ?></td>
    </tr>

    <?php if (null !== $activity->in_reply_to_activity_id): ?>
    <tr>
      <th><?php echo __('Reply To ID') ?></th>
      <td>
        <?php echo $form->renderFormTag(url_for(array('sf_route' => 'monitoring_activity_list'))) ?>
          <?php echo $form[sfForm::getCSRFFieldName()] ?>
          <input type="hidden" name="<?php echo $form['id']->renderName() ?>[text]" value="<?php echo $activity->in_reply_to_activity_id ?>">
          <input type="submit" value="ID: <?php echo $activity->in_reply_to_activity_id ?>" />
        </form>
      </td>
    </tr>
    <?php endif ?>

    <?php if (null !== $activity->foreign_table): ?>
    <tr>
      <th><?php echo __('Foreign Table / ID') ?></th>
      <td><?php echo $activity->foreign_table ?> / <?php echo $activity->foreign_id ?></td>
    </tr>
    <?php endif ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <?php echo link_to(__('Delete'), array('sf_route' => 'monitoring_activity_delete', 'sf_subject' => $activity), array(
          'method' => 'delete',
          'confirm' => __('本当に削除してもよろしいですか？'),
        )) ?>
      </td>
    </tr>
  </tfoot>
</table>
<?php endforeach // pager ?>

<p><?php op_include_pager_navigation($pager, '@monitoring_activity_list?page=%d', array('use_current_query_string' => true)) ?></p>

<?php endif // hasErrors ?>
