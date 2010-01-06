<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php use_helper('Javascript') ?>

<?php
$assignFunctions = '';

foreach ($presetList as $k => $v)
{
  $assignFunctions .= '
function assign_color_'.$k.'()
{';

  foreach ($v as $name => $config)
  {
    if ($name == 'caption' || $name === 'core_color_0')
    {
      continue;
    }

    $assignFunctions .= 'var obj = document.getElementById("color_'.$name.'"); obj.value = "'.$config.'"; reflect_color(obj);';
  }
  $assignFunctions .= '}';
}

echo javascript_tag('
function reflect_color(n) {
    document.getElementById("preview_"+n.name).style.backgroundColor = n.value;
}
'.$assignFunctions); ?>

<h2>携帯版配色設定</h2>

<div class="sampleColors">
<h3 class="item" id="subttl01">プリセットカラー</h3>
<p class="caution" id="c01">※プリセットカラーの呼出し後、かならず「色変更を確定する」を押し配色の設定を確定してください。</p>

<?php foreach ($presetList as $k => $v): ?>
<dl class="presetBox">
    <dt><?php echo __($v['caption']) ?></dt>
    <dd style="background:<?php echo $v['core_color_0'] ?>"><input class="input_submit" type="button" value="この設定を呼び出す" onclick="assign_color_<?php echo $k ?>()" /></dd>
</dl>
<?php endforeach; ?>

<br class="clear" />

</div>


<form action="<?php echo url_for('design/mobileColorConfig') ?>" method="post" name="color">
<input type="hidden" name="m" value="({$module_name})" />
<input type="hidden" name="a" value="do_({$hash_tbl->hash('update_c_sns_config_ktai','do')})" />
<input type="hidden" name="sessid" value="({$PHPSESSID})" />

<div class="bg" style="margin: 10px;">
<h3 class="item" id="subttl02">カラーコードの設定</h3>

<p class="caution" id="c02">※色の指定は16進数表記で行ってください。</p>
<p class="caution" id="c03">※色のプレビューが変更されてもそのままでは色設定は反映されません。必ず確定してください。</p>

<?php echo $form->renderGlobalErrors() ?>
<?php echo $form->renderHiddenFields() ?>
<?php foreach ($form as $field): ?>
<?php if ($field->isHidden()) continue; ?>
<dl class="colorBox">
<dt><?php echo $field->renderLabel() ?></dt>
<?php echo $field->renderError() ?>
<?php echo $field->render(array('onchange' => 'reflect_color(this)')) ?>
</dl>
<?php endforeach; ?>
<br class="clear" />
</div>

<p class="textBtn" id="c04"><input type="submit" value="色変更を確定する" /></p>

</form>

