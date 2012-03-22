<div id="plot<?php echo ucfirst($type) ?>">
<?php if ($gadgets instanceof sfOutputEscaperArrayDecorator) : ?>
<?php foreach ($gadgets as $gadget) : ?>
<div class="sortable" id="plot<?php echo ucfirst($type) ?>_gadget_<?php echo $gadget->getId() ?>">
<?php
echo link_to_function(__($gadgetConfig[$gadget->getName()]['caption']['ja_JP']), 'showModalOnParent(\''.url_for('design/editGadget?id='.$gadget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<div class="emptyGadget">
<?php echo link_to_function(__('ガジェットを追加'), 'showModalOnParent(\''.url_for('design/addGadget?type='.$type).'\')') ?>
</div>
</div>
<?php echo javascript_tag('
$("#plot'.ucfirst($type).'").sortable({
  items: "> div.sortable",
  update: function(event,ui){
    var item_ids = ui.item.parent().sortable("toArray");
    item_ids = $.map(item_ids, function (val) {
      return val.replace(/^.+_(\d+)$/, "$1");
    });

    insertHiddenTags("'.$type.'", item_ids);
  }
});
') ?>
