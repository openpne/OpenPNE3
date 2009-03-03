<?php
$options = array(
  'isMultipart' => true,
);

if ($communityForm->isNew())
{
  $options['title'] = __('Create a new community');
  $options['url'] = url_for('community/edit');
}
else
{
  $options['title'] = __('Edit the community');
  $options['url'] = url_for('community/edit?id='.$community->getId());
}

op_include_form('formCommunity', array($communityForm, $communityConfigForm, $communityFileForm), $options);
?>
