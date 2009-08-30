<?php slot('_yes_form'); ?>
<?php if ($requestedProfiles): ?>
<div class="profileExchangeInformation" style="margin: 1em 0;">
<p><?php echo __('And, this site wants to use your following informations.') ?></p>
<p><?php echo __('The shared information may save in this site permanently.') ?></p>
<p><?php echo __('Please uncheck these informations that you don\'t want to share with the site.') ?></p>

<?php $presetList =  opToolkit::getPresetProfileList() ?>

<ul class="profileList" style="margin: 0.5em 1.5em;">
<?php foreach ($requestedProfiles as $k => $v) : ?>
<li>
<input type="checkbox" name="profiles[]" value="<?php echo $k ?>" checked="checked" id="profile_<?php echo $k ?>" />
<label for="profile_<?php echo $k ?>">
<?php if (0 === strpos($k, 'op_preset_')): ?>
<?php $k = __($presetList[substr($k, 10)]['Caption']) ?>
<?php else: ?>
<?php $k = __(sfInflector::humanize($k), array(), 'profile_exchange'); ?>
<?php endif; ?>
<?php if ($v): ?>
<strong><?php echo $k ?></strong>
<?php else: ?>
<?php echo $k ?>
<?php endif; ?>
</label>
</li>
<?php endforeach; ?>
</ul>

<?php if(in_array(true, $sf_data->getRaw('requestedProfiles'))): ?>
<p><?php echo __('<strong>An emphatic item</strong> is an information that you need to use the site. If you uncheck that item, that action is recognized as clicking "No".') ?></p>
<?php endif; ?>
</div>
<?php endif; ?>
<input type="hidden" name="trust" value="1" />
<p>
<input type="checkbox" name="permanent" value="1" />
<?php echo __('Automatically login to %1% from now on', array('%1%' => parse_url($info->trust_root, PHP_URL_HOST))) ?>
</p>
<?php end_slot(); ?>

<?php slot('_body'); ?>
<p><?php echo __('Do you wish to login to the following site using your %1% ID?', array('%1%' => $op_config['sns_name'])) ?></p>
<p><code><?php echo $info->trust_root ?></code></p>
<?php end_slot(); ?>

<?php
op_include_parts('consentForm', 'trustConfirm', array(
  'title'        => __('Login to %1% using your %2% ID', array(
    '%1%' => parse_url($info->trust_root, PHP_URL_HOST),
    '%2%' => $op_config['sns_name'],
  )),
  'body'         => get_slot('_body'),
  'yes_form'     => get_slot('_yes_form'),
  'consent_from' => $op_config['sns_name'],
  'consent_to'   => parse_url($info->trust_root, PHP_URL_HOST),
  'yes_url'      => url_for('OpenID/trust'),
  'no_url'       => url_for('OpenID/trust'),
));
?>
