<?php use_helper('Javascript') ?>

<?php if (isset($topGadgets)): ?>
<?php slot('op_top') ?>
<?php foreach ($topGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo javascript_tag('$("#Top").sortable({ axis: "y", items: "> div", handle: "div.sortHandle, div.partsHeading", update: function(event,ui){storeSort(event, ui)} });') ?>
<?php endif; ?>

<?php if (isset($sideMenuGadgets)): ?>
<?php slot('op_sidemenu') ?>
<?php foreach ($sideMenuGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo javascript_tag('$("#Left").sortable({ axis: "y", items: "> div", handle: "div.sortHandle, div.partsHeading", update: function(event,ui){storeSort(event, ui)} });') ?>
<?php endif; ?>

<?php if (isset($contentsGadgets)): ?>
<?php foreach ($contentsGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php echo javascript_tag('$("#Center").sortable({ axis: "y", items: "> div", handle: "div.sortHandle, div.partsHeading", update: function(event,ui){storeSort(event, ui)} });') ?>
<?php endif; ?>

<?php if (isset($bottomGadgets)): ?>
<?php slot('op_bottom') ?>
<?php foreach ($bottomGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot() ?>
<?php echo javascript_tag('$("#Bottom").sortable({ item: "div.sortHandle, div.partsHeading" });') ?>
<?php endif; ?>

<?php echo javascript_tag('
function storeSort(event, ui)
{
  var result = "";
  ui.item.parent().children().each(function(index){
    if (this.id && this.id.match(/_[0-9]+$/)) {
      if (result) {
        result = result + ",";
      }
      result = result + this.id;
    }
  });
  var path = "'.($sf_request->getRelativeUrlRoot() ? $sf_request->getRelativeUrlRoot() : '/').'";
  var expires = new Date();
  expires.setTime((new Date()).getTime() + (10 * 12 * 30 * 24 * 60 * 60 * 1000));
  var pos = ui.item.parent().attr("id");

  opCookie.set("HomeGadget_" + pos + "_sort", result, expires, path);
}

function foldObj(obj, display)
{
  obj.parent().children().each(function(index){
    var child = $(this);
    if (!child.hasClass("partsHeading")) {
      if (display == null) {
        child.toggle();
      } else {
        if (display == "true") {
          child.show();
        } else {
          child.hide();
        }
      }

      var size = obj.parent().children().length;
      if (size == index + 1) {  // It is a last loop maybe
        var path = "'.($sf_request->getRelativeUrlRoot() ? $sf_request->getRelativeUrlRoot() : '/').'";
        var id = child.parent().parent().attr("id");
        var expires = new Date();
        expires.setTime((new Date()).getTime() + (10 * 12 * 30 * 24 * 60 * 60 * 1000));
        opCookie.set("HomeGadget_" + id + "_toggle", !child.is(":hidden"), expires, path);
      }
    }
  });
}

$(".partsHeading").each(function(){
  var obj = $(this);
  // folding
  obj.dblclick(function(){
    foldObj(obj);
  });
  var id = obj.parent().parent().attr("id");
  var display = opCookie.get("HomeGadget_"+id+"_toggle");
  if (display != null) {
    foldObj(obj, display);
  }

});

$.each(["Top", "Left", "Center"], function(){
  var sortInfo = opCookie.get("HomeGadget_" + this + "_sort");
  if (sortInfo)
  {
    var obj = $("#"+this);
    $.each(sortInfo.split(","), function(){
      var gadget = $("#"+this);
      gadget.detach().appendTo(obj);
    });
  }
});

') ?>
