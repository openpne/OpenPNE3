<?php slot('op_top') ?>
<?php if ($topGadgets): ?>
<?php foreach ($topGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php end_slot() ?>

<?php slot('op_sidemenu') ?>
<?php if ($sideMenuGadgets): ?>
<?php foreach ($sideMenuGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php end_slot() ?>

<?php if ($contentsGadgets): ?>
<?php foreach ($contentsGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php slot('op_bottom') ?>
<?php if ($bottomGadgets): ?>
<?php foreach ($bottomGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php end_slot() ?>

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
