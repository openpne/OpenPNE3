<?php slot('op_sidemenu') ?>
<div class="parts pageNav">
<ul>
<li<?php echo ($listType == 'receive') ? ' class="current"' : '' ?>>
<?php echo ($listType != 'receive' || $forceLink) ? link_to(__('Inbox'), '@receiveList') : __('Inbox') ?>
</li>
<li<?php echo ($listType == 'send') ? ' class="current"' : '' ?>>
<?php echo ($listType != 'send' || $forceLink) ? link_to(__('Sent Message'), '@sendList') : __('Sent Message') ?>
</li>
<li<?php echo ($listType == 'draft') ? ' class="current"' : '' ?>>
<?php echo ($listType != 'draft' || $forceLink) ? link_to(__('Drafts'), '@draftList') : __('Drafts') ?>
</li>
<li<?php echo ($listType == 'dust') ? ' class="current"' : '' ?>>
<?php echo ($listType != 'dust' || $forceLink) ? link_to(__('Trash'), '@dustList') : __('Trash') ?>
</li>
</ul>
</div>
<?php end_slot() ?>
