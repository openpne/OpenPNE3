<?php use_helper('Javascript') ?>

<?php echo javascript_tag("
function showModalOnParent(url)
{
  var modal = parent.document.getElementById('modal');
  var modalContents = parent.document.getElementById('modal_contents');
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  modalIframe.src = url;
  modalContents.setStyle(parent.getCenterMuchScreen(modalContents));

  modal.style.height   = (parent.document.documentElement.scrollHeight || parent.document.body.scrollHeight) + 'px';
  modal.style.width    = (parent.document.documentElement.scrollWidth  || parent.document.body.scrollWidth)  + 'px';
  modal.style.position = 'absolute';

  new Effect.Appear(modal, {from:0, to:0.7, duration: 0.5, fps: 100});
  new Effect.Appear(modalContents, {from:0, to:1.0, duration: 0.6});
}

function insertHiddenTags(type, ids)
{
  var form = parent.document.getElementById('gadgetForm');
  var hiddens = Element.select(form, '.' + type + 'Gadget');
  for (var i = 0; i < hiddens.length; i++)
  {
    Element.remove(hiddens[i]);
  }

  for (var i = 0; i < ids.length; i++)
  {
    if (!ids[i])
    {
      continue;
    }

    var obj = parent.document.createElement('input');
    obj.setAttribute('class', type + 'Gadget');
    obj.setAttribute('type', 'hidden');
    obj.setAttribute('name', 'gadget[' + type + '][' + i + ']');
    obj.setAttribute('value', ids[i]);
    new Insertion.Bottom(form, obj);
  }
}

function dropNewGadget(type, name, obj)
{
  var form      = parent.document.getElementById('gadgetForm');
  var elements  = form.elements;
  var inputName = 'new[' + type + '][]';
  for (var i = 0; i < elements.length; i++)
  {
    if (elements[i].type == 'hidden' && elements[i].name == inputName && elements[i].value == name)
    {
      Element.remove(elements[i]);
      break;
    }
  }
  Element.remove(obj);

  parent.adjustByIframeContens(this.frameElement);
}
");
?>
