<div id="plot<?php echo ucfirst($type) ?>">
<?php foreach ($widgets as $widget) : ?>
<div class="widget" id="plot<?php echo ucfirst($type) ?>_widget_<?php echo $widget->getId() ?>">
<?php
echo link_to_function($widgetConfig[$widget->getName()]['caption']['ja_JP'], 'showModalOnParent(\''.url_for('design/homeEditWidget?id='.$widget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<div class="emptyWidget">
<?php echo link_to_function(__('ウィジェットを追加'), 'showModalOnParent(\''.url_for('design/homeAddWidget?type='.$type).'\')') ?>
</div>
</div>
<?php echo sortable_element('plot'.ucfirst($type), array(
  'only' => 'widget',
  'tag'  => 'div',
  'onUpdate' => 'function(s){insertHiddenTags(\''.$type.'\', Sortable.sequence(s, s.id))}',
)) ?>
