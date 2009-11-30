<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('HTML Insertion').' ('.__($typeCaptions[$type]).')' ?></h2>

<h3><?php echo __('Footer') ?></h3>
<ul>
<?php foreach (array('footer_before', 'footer_after') as $_type): ?>
  <li><?php echo link_to(__($typeCaptions[$_type]), 'design/html?type='.$_type) ?></li>
<?php endforeach; ?>
</ul>

<h3><?php echo __('All PC Pages') ?></h3>
<ul>
<?php foreach (array('pc_html_head', 'pc_html_top2', 'pc_html_top', 'pc_html_bottom2', 'pc_html_bottom') as $_type): ?>
  <li><?php echo link_to(__($typeCaptions[$_type]), 'design/html?type='.$_type) ?></li>
<?php endforeach; ?>
</ul>

<h3><?php echo __('All Mobile Pages') ?></h3>
<ul>
<?php foreach (array('mobile_html_head', 'mobile_header', 'mobile_footer') as $_type): ?>
  <li><?php echo link_to(__($typeCaptions[$_type]), 'design/html?type='.$_type) ?></li>
<?php endforeach; ?>
</ul>

<table>
<?php echo $form->renderFormTag(url_for('design/html?type='.$type)) ?>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</form>
</table>
