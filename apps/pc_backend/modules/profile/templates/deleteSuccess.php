<h2><?php echo __('Delete profile entry')?></h2>
<p><?php echo __('Do you want to delete this anyway?')?></p>
<p><?php echo __('※All the member\'s data in this entry will be lost.')?></p>
<form action="<?php echo url_for('profile/delete?id=' . $profile->getId()) ?>" method="post">
<input type="submit" value=<?php echo __('Delete')?> />
</form>
