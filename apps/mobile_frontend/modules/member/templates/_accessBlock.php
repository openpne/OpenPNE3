<?php $accessBlock->state(Doctrine_RECORD::STATE_CLEAN); ?>
<?php echo op_link_to_member($accessBlock->getMember()) ?>
&nbsp;[<?php echo link_to(__('Delete'), '@member_accessBlock_delete_confirm?id='.$accessBlock->getId()) ?>]
