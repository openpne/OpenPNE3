<p>
Powered by <a href="http://www.openpne.jp/" target="_blank">OpenPNE</a>
<?php if (opToolkit::isSecurePage()) : ?>
<?php echo SnsConfigPeer::get('footer_after'); ?>
<?php else: ?>
<?php echo SnsConfigPeer::get('footer_before'); ?>
<?php endif; ?>
</p>
