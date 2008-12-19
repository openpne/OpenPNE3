<?php
$options = array(
  'form' => array($form),
  'isMultipart' => true,
);

if ($form->isNew()) {
  $title = 'コミュニティ作成';
  $options['url'] = 'community/edit';
} else {
  $title = 'コミュニティ編集';
  $options['url'] = 'community/edit?id=' . $community->getId();
}
include_box('formCommunity', $title, '', $options);
?>
