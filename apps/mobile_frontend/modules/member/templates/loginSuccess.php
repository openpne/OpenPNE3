<?php if (isset($mobileLoginContentsGadgets)) : ?>
<?php foreach ($mobileLoginContentsGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)) ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
