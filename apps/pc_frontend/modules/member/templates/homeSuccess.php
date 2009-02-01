<?php use_helper('Javascript') ?>

<?php if ($topGadgets): ?>
<?php slot('op_top') ?>
<?php foreach ($topGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo sortable_element('Top', array('tag' => 'div', 'handle' => 'partsHeading')) ?>
<?php endif; ?>

<?php if ($sideMenuGadgets): ?>
<?php slot('op_sidemenu') ?>
<?php foreach ($sideMenuGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo sortable_element('Left', array('tag' => 'div', 'handle' => 'partsHeading')) ?>
<?php endif; ?>

<?php if ($contentsGadgets): ?>
<?php foreach ($contentsGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php echo sortable_element('Center', array('tag' => 'div', 'handle' => 'partsHeading')) ?>
<?php endif; ?>

<?php if ($bottomGadgets): ?>
<?php slot('op_bottom') ?>
<?php foreach ($bottomGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo sortable_element('Bottom', array('tag' => 'div', 'handle' => 'partsHeading')) ?>
<?php endif; ?>

<?php echo javascript_tag('
$$(".partsHeading").each(function(obj){
  // folding
  Event.observe(obj, "dblclick", function(e){
    Event.element(e).parentNode.childElements().each(function(child){
      if (!child.hasClassName("partsHeading")) {
        child.toggle();
      }
    });
  });
});
') ?>
