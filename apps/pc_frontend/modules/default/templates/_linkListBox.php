<?php slot('_link_list') ?>
<ul>
<?php for ($i = 1; $i <= 10; $i++): ?>
<?php if ($gadget->getConfig('text'.$i) && $gadget->getConfig('url'.$i)): ?>
  <li><?php echo content_tag('a', $gadget->getConfig('text'.$i), array('href' => $gadget->getConfig('url'.$i))) ?></li>
<?php endif; ?>
<?php endfor; ?>
</ul>
<?php end_slot(); ?>

<?php echo op_include_box('linkBox'.$i, get_slot('_link_list'), array('title' => $gadget->getConfig('title') ? $gadget->getConfig('title') : __('リンク集'))) ?>
