<?php echo __('Information About the Withdrawal Member') ?> 

<?php echo __('ID') ?> : <?php echo $member->getId() ?> 
<?php echo __('Nickname') ?> : <?php echo $member->getName() ?> 

<?php echo __('Last Login') ?> : <?php if ($member->getLastLoginTime()) : ?><?php echo date('y-m-d H:i:s', $member->getLastLoginTime()) ?><?php endif; ?> 
<?php foreach ($member->getProfiles() as $profile) : ?> 
<?php if ($profile) : ?>
<?php echo $profile->getCaption() ?> : <?php echo $profile->getValue() ?> 
<?php endif; ?>
<?php endforeach; ?> 
<?php echo __('PC E-mail Address') ?> :  <?php echo $member->getConfig('pc_address') ?> 
<?php echo __('Mobile E-mail Address') ?> : <?php echo $member->getConfig('mobile_address') ?> 
