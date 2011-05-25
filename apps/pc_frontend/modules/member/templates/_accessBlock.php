<?php  // needs $accessBlock parameter ?>
<?php $member = Doctrine::getTable('MemberImage')->findOneByMemberId($accessBlock->getMemberIdTo()); ?>
<div class="ditem"><div class="item">
<table><tbody>
<tr>
<td rowspan="2" class="photo">
<?php if($member): ?>
<?php echo link_to(image_tag_sf_image($member->getFile(), array('size' => '76x76')), '@member_profile?id=' .$accessBlock->getMemberIdTo()); ?><br />
<?php else: ?>
<?php echo link_to(image_tag_sf_image(null, array('size' => '76x76')), '@member_profile?id=' .$accessBlock->getMemberIdTo()); ?><br />
<?php endif; ?>
</td>
<th><?php echo __('Nickname') ?></th>
<td><?php echo op_link_to_member($accessBlock->getMember()) ?></td>
</tr>

<tr class="operation">
<th><?php echo __('Member ID') ?></th>
<td>
<span class="text"><?php echo $accessBlock->getMemberIdTo() ?></span>
<span class ="moreInfo"><?php echo link_to(__('Delete'), '@member_accessBlock_delete_confirm?id='.$accessBlock->getId()) ?></span>
</td>

</tbody></table>
</div></div>
