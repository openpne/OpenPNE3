<p>
Powered by <a href="http://www.openpne.jp/" target="_blank">OpenPNE</a>
<?php if (opToolkit::isSecurePage()) : ?>
<?php echo Doctrine::getTable('SnsConfig')->get('footer_after'); ?>
<?php else: ?>
<?php echo Doctrine::getTable('SnsConfig')->get('footer_before'); ?>
<?php endif; ?>
</p>
