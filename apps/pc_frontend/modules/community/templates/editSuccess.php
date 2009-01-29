<?php
$options = array(
  'isMultipart' => true,
);

if ($form->isNew())
{
  $options['title'] = __('Create a new community');
  $options['url'] = url_for('community/edit');
}
else
{
  $options['title'] = __('Edit the community');
  $options['url'] = url_for('community/edit', $community->getId());
}

op_include_form('formCommunity', $form, $options);
?>
