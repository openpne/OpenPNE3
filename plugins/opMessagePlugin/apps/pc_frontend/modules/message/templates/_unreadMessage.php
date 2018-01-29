<?php if ($unreadMessageCount > 0): ?>
<ul> 
<li>★<span class="caution"><?php echo __('There are new %d messages!', array('%d' => $unreadMessageCount)) ?></span> 
<?php echo link_to('<strong>'.__('Read messages').'</strong>', 'message/index') ?></li> 
</ul>
<?php endif; ?>
