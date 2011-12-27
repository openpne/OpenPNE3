<?php use_helper('Javascript') ?>

<?php op_include_parts('descriptionBox', 'smtHomeBox', $options); ?>

<?php echo javascript_tag('
function storeSort(obj)
{
  var result = "";
  Element.childElements(obj.parentNode).each(function(child, index){
    if (child.id && child.id.match(/_[0-9]+$/)) {
      if (result) {
        result = result + ",";
      }
      result = result + child.id;
    }
  });
  var path = "'.($sf_request->getRelativeUrlRoot() ? $sf_request->getRelativeUrlRoot() : '/').'";
  var expires = new Date();
  expires.setTime((new Date()).getTime() + (10 * 12 * 30 * 24 * 60 * 60 * 1000));
  var pos = obj.parentNode.id;

  opCookie.set("HomeGadget_" + pos + "_sort", result, expires, path);
}

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
        opCookie.set("HomeGadget_" + id + "_toggle", child.visible(), expires, path);
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

["Top", "Left", "Center"].each(function(type){
  var sortInfo = opCookie.get("HomeGadget_" + type + "_sort");
  if (sortInfo)
  {
    var obj = document.getElementById(type);
    var preGadget = null;
    sortInfo.split(",").each(function(value){
      var gadget = document.getElementById(value);
      if (preGadget)
      {
        Element.remove(gadget);
        Insertion.After(preGadget, gadget);
      }
      else
      {
        Element.remove(gadget);
        Insertion.Top(obj, gadget);
      }
      preGadget = gadget;
    });
  }
});

') ?>
