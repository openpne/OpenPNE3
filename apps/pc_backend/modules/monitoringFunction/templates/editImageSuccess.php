<?php slot('submenu') ?>
<?php include_component('monitoringFunction', 'subMenu', array('nowUri' => 'monitoringFunction/editImage')) ?>
<?php end_slot() ?>

<?php slot('title', '画像アップロード・削除') ?>

<?php echo $form->renderFormTag(url_for('monitoringFunction/editImage')) ?>
  <table>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input class="input_sbumit" type="submit" value="<?php echo __('画像投稿') ?>" />
      </td>
    </tr>
  </table>
</form>
