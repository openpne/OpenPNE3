<?php slot('firstRow') ?>
<tr>
  <th><?php echo __($settings['Caption']) ?></th>
  <td><?php echo $newValue ?></td>
</tr>
<?php end_slot() ?>

<?php
$options = array(
  'title' => __('Change Settings'),
  'url' => url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))),
  'firstRow' => get_slot('firstRow'),
  'button' => __('Send'),
);
op_include_form('formConfigComplete', $form, $options);
?>
