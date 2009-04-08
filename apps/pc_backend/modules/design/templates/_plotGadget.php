<div id="plot<?php echo ucfirst($type) ?>">
<?php if ($gadgets instanceof sfOutputEscaperArrayDecorator) : ?>
<?php foreach ($gadgets as $gadget) : ?>
<div class="sortable" id="plot<?php echo ucfirst($type) ?>_gadget_<?php echo $gadget->getId() ?>">
<?php
echo link_to_function($gadgetConfig[$gadget->getName()]['caption']['ja_JP'], 'showModalOnParent(\''.url_for('design/editGadget?id='.$gadget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<div class="emptyGadget">
<?php echo link_to_function(__('ガジェットを追加'), 'showModalOnParent(\''.url_for('design/addGadget?type='.$type).'\')') ?>
</div>
</div>
<?php echo sortable_element('plot'.ucfirst($type), array(
  'only' => 'sortable',
  'tag'  => 'div',
  'onUpdate' => 'function(s){insertHiddenTags(\''.$type.'\', Sortable.sequence(s, s.id))}',
)) ?>
