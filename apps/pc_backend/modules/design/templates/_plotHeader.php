<?php use_helper('Javascript') ?>

<?php echo javascript_tag("
function showModalOnParent(url)
{
  var modal = $('#modal', parent.document);
  var modalContents = $('#modal_contents', parent.document);
  var modalIframe = $('iframe', modalContents)[0];
  modalIframe.src = url;
  modalContents.css(parent.getCenterMuchScreen(modalContents));

  modal.height(parent.document.documentElement.scrollHeight || parent.document.body.scrollHeight);
  modal.width(parent.document.documentElement.scrollWidth || parent.document.body.scrollWidth);
  modal.css('position', 'absolute');

  modal.fadeTo(500, 0.7);
  modalContents.fadeIn(600);
}

function insertHiddenTags(type, ids)
{
  var form = parent.document.getElementById('gadgetForm');

  $('.' + type + 'Gadget', form).remove();

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
    $(obj).appendTo(form);
  }
}

function dropNewGadget(type, name, obj)
{
  var form      = parent.document.getElementById('gadgetForm');
  var elements  = $(form.elements);
  var inputName = 'new[' + type + '][]';
  for (var i = 0; i < elements.length; i++)
  {
    if (elements[i].type == 'hidden' && elements[i].name == inputName && elements[i].value == name)
    {
      $(elements[i]).remove();
      break;
    }
  }
  $(obj).remove();

  parent.adjustByIframeContens(this.frameElement);
}
");
?>
