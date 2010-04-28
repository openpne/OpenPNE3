<?php
$options = array(
  'isMultipart' => true,
);

if ($communityForm->isNew())
{
  $options['title'] = __('Create a new %community%');
  $options['url'] = url_for('@community_edit');
}
else
{
  $options['title'] = __('Edit the %community%');
  $options['url'] = url_for('@community_edit?id='.$community->getId());
}

op_include_form('formCommunity', array($communityForm, $communityConfigForm, $communityFileForm), $options);

if (!$communityForm->isNew() && $isDeleteCommunity)
{
  op_include_parts('buttonBox', 'deleteForm', array(
    'title' => __('Delete this %community%'),
    'body' => __('delete this %community%.if you delete this %community% please to report in advance for all this %community% members.'),
    'button' => __('Delete'),
    'method' => 'get',
    'url' => url_for('@community_delete?id=' . $community->getId()),
  ));
}

?>
