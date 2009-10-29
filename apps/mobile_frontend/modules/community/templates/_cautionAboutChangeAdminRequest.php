<?php foreach ($communities as $community): ?>
<font color="red">
<?php echo __('You have received the request to take over administrator of "%1%".', array(
  '%1%' => link_to($community->getName(), 'community/home?id='.$community->getId())
)) ?>
&nbsp;
<?php $param = 'id='.$community->getId() ?>
<?php echo link_to(__('Accept'), 'community/changeAdminAccept?'.$param) ?>
&nbsp;
<?php echo link_to(__('Reject'), 'community/changeAdminReject?'.$param) ?>
</font><br>
<?php endforeach; ?>
