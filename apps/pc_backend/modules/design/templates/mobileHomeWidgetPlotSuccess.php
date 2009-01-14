<?php use_helper('Javascript') ?>

<?php echo javascript_tag("
function showModalOnParent(url)
{
  var modal = parent.document.getElementById('modal');
  var modalContents = parent.document.getElementById('modal_contents');
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  modalIframe.src = url;
  new Effect.Appear(modal, {from:0, to:0.7});
  new Effect.Appear(modalContents, {from:0, to:1.0});
}

function insertHiddenTags(type, ids)
{
  var form = parent.document.getElementById('widgetForm');
  var hiddens = form.getElementsByClassName(type + 'Widget');

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
    obj.setAttribute('class', type + 'Widget');
    obj.setAttribute('type', 'hidden');
    obj.setAttribute('name', 'widget[' + type + '][' + i + ']');
    obj.setAttribute('value', ids[i]);
    new Insertion.Bottom(form, obj);
  }
}

function dropNewWidget(type, name, obj)
{
  var form = parent.document.getElementById('widgetForm');
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
}

");
?>

<div id="plotBody">
<div id="container">

<div id="plotMobileTop">
<?php foreach ($mobileTopWidgets as $widget) : ?>
<div class="widget" id="plotMobileTop_widget_<?php echo $widget->getId() ?>">
<?php
echo link_to_function($widgetConfig[$widget->getName()]['caption']['ja_JP'], 'showModalOnParent(\''.url_for('design/homeEditWidget?id='.$widget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<div class="emptyWidget">
<?php echo link_to_function(__('ウィジェットを追加'), 'showModalOnParent(\''.url_for('design/homeAddWidget?type=mobileTop').'\')') ?>
</div>
</div>
<?php echo sortable_element('plotMobileTop', array(
  'only' => 'widget',
  'tag'  => 'div',
  'onUpdate' => 'function(s){insertHiddenTags(\'contents\', Sortable.sequence(s, s.id))}',
)) ?>

<div class="fixedWidget">
<?php echo __('メニュー') ?>
</div>

<div id="plotMobileContents">
<?php foreach ($mobileContentsWidgets as $widget) : ?>
<div class="widget" id="plotMobileContents_widget_<?php echo $widget->getId() ?>">
<?php
echo link_to_function($widgetConfig[$widget->getName()]['caption']['ja_JP'], 'showModalOnParent(\''.url_for('design/homeEditWidget?id='.$widget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<div class="emptyWidget">
<?php echo link_to_function(__('ウィジェットを追加'), 'showModalOnParent(\''.url_for('design/homeAddWidget?type=mobileContents').'\')') ?>
</div>
</div>
<?php echo sortable_element('plotMobileContents', array(
  'only' => 'widget',
  'tag'  => 'div',
  'onUpdate' => 'function(s){insertHiddenTags(\'contents\', Sortable.sequence(s, s.id))}',
)) ?>

<div class="fixedWidget">
<?php echo __('設定変更') ?>
</div>

<div id="plotMobileBottom">
<?php foreach ($mobileBottomWidgets as $widget) : ?>
<div class="widget" id="plotMobileBottom_widget_<?php echo $widget->getId() ?>">
<?php
echo link_to_function($widgetConfig[$widget->getName()]['caption']['ja_JP'], 'showModalOnParent(\''.url_for('design/homeEditWidget?id='.$widget->getId()).'\')');
?>
</div>
<?php endforeach; ?>
<div class="emptyWidget">
<?php echo link_to_function(__('ウィジェットを追加'), 'showModalOnParent(\''.url_for('design/homeAddWidget?type=mobileBottom').'\')') ?>
</div>
</div>
<?php echo sortable_element('plotMobileBottom', array(
  'only' => 'widget',
  'tag'  => 'div',
  'onUpdate' => 'function(s){insertHiddenTags(\'contents\', Sortable.sequence(s, s.id))}',
)) ?>

</div>
</div>
