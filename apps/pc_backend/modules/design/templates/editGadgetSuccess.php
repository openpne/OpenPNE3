<?php use_helper('Javascript') ?>

<style type="text/css">
dt
{
  background-color: #eeeeee;
  border: 1px solid #aaaaaa;
  margin-top: 10px;
  margin-left: 10px;
  margin-right: 10px;
  padding: 5px;
}
dd
{
  border-left: 1px solid #aaaaaa;
  border-bottom: 1px solid #aaaaaa;
  border-right: 1px solid #aaaaaa;
  padding: 5px;
  margin-bottom: 5px;
  margin-left: 10px;
  margin-right: 10px;
}
</style>

<?php echo javascript_tag("
function deleteGadget(type, id)
{
  var parentIframe = parent.document.getElementsByTagName('iframe')[0];

  var typeId = 'plot' + type.charAt(0).toUpperCase() + type.substr(1, type.length - 1);
  $('#' + typeId + '_gadget_' + id, parentIframe.contentWindow.document).remove();

  var form = parent.document.getElementById('gadgetForm');
  $('.' + type + 'Gadget', form).each(function(){
    if (this.value == id)
    {
      $(this).remove();
      return false;
    }
  });

  parentIframe.contentWindow.parent.adjustByIframeContens(parentIframe);
  parent.document.getElementById('modal').onclick();
}
");
?>

<dl>
<dt>
<?php echo __($config['caption']['ja_JP']) ?><br />
<?php echo link_to_function(__('このガジェットを削除する'), 'deleteGadget(\''.$gadget->getType().'\', \''.$gadget->getId().'\')') ?>
</dt>
<dd><?php echo __($config['description']['ja_JP']) ?></dd>
</dl>

<?php if (!empty($config['config'])) : ?>
<dl>
<dt>設定変更</dt>
<dd>
<table>
<?php echo $form->renderFormTag(url_for('design/editGadget?id='.$gadget->getId())) ?>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="submit" /></td>
</tr>
</form>
</table>
</dd>
</dl>
<?php endif; ?>
