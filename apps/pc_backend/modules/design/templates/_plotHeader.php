<?php use_helper('Javascript') ?>

<?php echo javascript_tag("
function showModalOnParent(url)
{
  var modal = parent.document.getElementById('modal');
  var modalContents = parent.document.getElementById('modal_contents');
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  modalIframe.src = url;
  modalContents.setStyle(parent.getCenterMuchScreen(modalContents));
  new Effect.Appear(modal, {from:0, to:0.7, duration: 0.5, fps: 100});
  new Effect.Appear(modalContents, {from:0, to:1.0, duration: 0.6});
}

function insertHiddenTags(type, ids)
{
  var form = parent.document.getElementById('gadgetForm');
  var hiddens = form.getElementsByClassName(type + 'Gadget');

  while (hiddens.length)
  {
    Element.remove(hiddens[0]);
  }

  for (var i = 0; i < ids.length; i++)
  {
    if (!ids[i])
    {
      continue;
    }

    var obj = document.createElement('input');
    obj.setAttribute('class', type + 'Gadget');
    obj.setAttribute('type', 'hidden');
    obj.setAttribute('name', 'gadget[' + type + '][' + i + ']');
    obj.setAttribute('value', ids[i]);
    new Insertion.Bottom(form, obj);
  }
}

function dropNewGadget(type, name, obj)
{
  var form = parent.document.getElementById('gadgetForm');
  var hiddens = form.getElementsByClassName(type + 'New');
  for (var i = 0; i < hiddens.length; i++)
  {
    if (hiddens[i].value == name)
    {
      Element.remove(hiddens[i]);
      break;
    }
  }
  Element.remove(obj);

  parent.adjustByIframeContens(this.frameElement);
}

");
?>
