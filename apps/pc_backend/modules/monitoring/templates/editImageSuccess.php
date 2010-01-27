<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php slot('title', '画像アップロード') ?>

<ul>
<li><?php echo __('画像ファイル名は半角英数とアンダースコア(_)とハイフン(-)のみ使用できます') ?></li>
</ul>

<?php echo $form->renderFormTag(url_for('monitoring/editImage')) ?>
  <table>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input class="input_sbumit" type="submit" value="<?php echo __('画像投稿') ?>" />
      </td>
    </tr>
  </table>
</form>
