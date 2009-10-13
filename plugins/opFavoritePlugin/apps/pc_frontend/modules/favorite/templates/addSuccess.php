<?php use_helper('Date'); ?>
<div class="dparts form"><div class="parts">

<div class="partsHeading"><h3><?php echo __('Do you add member to the favorite?') ?></h3></div>

<table><tbody>

<tr>
<th><?php echo __('Photo') ?></th>
<td><?php echo link_to(image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')), 'member/profile?id=' . $member->getId()); ?></td>
</tr>

<tr>
<th><?php echo __('Nickname') ?></th>
<td><?php echo $member->getName() ?></td>
</tr>

<?php if ($member->getProfile('self_intro') ): ?>
<tr>
<th><?php echo $member->getProfile('self_intro')->getCaption() ?></th>
<td><?php echo nl2br($member->getProfile('self_intro')) ?></td>
</tr>
<?php endif ?>

<tr>
<th><?php echo __('The last login') ?></th>
<td><?php echo distance_of_time_in_words($member->getLastLoginTime()) ?></td>
</tr>

</tbody></table>

<div class="operation">
<ul class="moreInfo button">
<li>
<form method="post" action="">
<input type="hidden" name="add" value="1">
<input type="submit" value=<?php echo __('Yes') ?> class="input_submit"/>
</form>
</li>
<li>
<form action="<?php echo url_for('member/' . $member->getId()) ?>">
<input type="submit" value=<?php echo __('No') ?> class="input_submit"/>
</form>
</li>
</ul>
</div>

</div></div>
