<?php
$options = array(
  'isMultipart' => true,
);

if ($form->isNew())
{
  $options['title'] = 'コミュニティ作成';
  $options['url'] = 'community/edit';
}
else
{
  $options['title'] = 'コミュニティ編集';
  $options['url'] = 'community/edit?id='.$community->getId();
}

op_include_form('formCommunity', $form, $options);
?>
