<?php
$options = array(
  'isMultipart' => true,
);

if ($form->isNew())
{
  $options['title'] = 'Create a new community';
  $options['url'] = 'community/edit';
}
else
{
  $options['title'] = 'Edit the community';
  $options['url'] = 'community/edit?id='.$community->getId();
}

op_include_form('formCommunity', $form, $options);
?>
