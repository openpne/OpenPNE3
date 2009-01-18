<?php if ($widgets): ?>
<?php foreach ($widgets as $widget): ?>
<?php if ($widget->isEnabled()): ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
