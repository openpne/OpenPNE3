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
function foldObj(obj, display)
{
  Element.childElements(obj.parentNode).each(function(child, index){
    if (!child.hasClassName("partsHeading")) {
      if (display == null) {
        child.toggle();
      } else {
        if (display == "true") {
          child.show();
        } else {
          child.hide();
        }
      }

      var size = Element.childElements(obj.parentNode).length;
      if (size == index + 1) {  // It is a last loop maybe
        var path = "'.($sf_request->getRelativeUrlRoot() ? $sf_request->getRelativeUrlRoot() : '/').'";
        var id = child.parentNode.parentNode.id;
        var expires = new Date();
        expires.setTime((new Date()).getTime() + (10 * 12 * 30 * 24 * 60 * 60 * 1000));
        opCookie.set("HomeGadget_"+id+"_toggle", child.visible(), expires, path);
      }
    }
  });
}

$$(".partsHeading").each(function(obj){
  // folding
  Event.observe(obj, "dblclick", function(e){
    foldObj(obj);
  });
  var id = obj.parentNode.parentNode.id;
  var display = opCookie.get("HomeGadget_"+id+"_toggle");
  if (display != null) {
    foldObj(obj, display);
  }
});

') ?>
