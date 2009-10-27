<?php $rawMessage = $sf_data->getRaw('message') ?>
<?php $snsName = $op_config['sns_name'] ?>
<?php echo __('Hello! This is information from %1%.', array('%1%' => $snsName)) ?>

<?php if (isset($name)): ?>

<?php echo __('%1% is inviting in %2% you.', array('%1%' => $name, '%2%' => $snsName)) ?>

<?php if (strlen($rawMessage)): ?>

<?php echo __('---------- The message to you from < %1% > ----------', array('%1%' => $name)) ?>


<?php echo __('---------------------------------------------------') ?>

<?php echo $rawMessage ?>


<?php echo __('---------------------------------------------------') ?>

<?php endif ?>
<?php endif ?>

<?php echo __('If you register(free) of member by the following URL,%br%you can participate in %1%.', array('%1%' => $snsName, '%br%' => "\n")) ?>



<?php echo __('* Participate in %1%', array('%1%' => $snsName)) ?>

<?php if ($isMobile) : ?>
<?php echo app_url_for('mobile_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php else: ?>
<?php echo app_url_for('pc_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php endif; ?>
