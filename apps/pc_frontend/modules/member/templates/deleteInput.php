<?php op_include_box('informationThisPage',
  '<p>'.__('本当に%1%から退会しますか？', array('%1%' => $op_config['sns_name'])).'</p>'.
  '<p>'.__('退会する場合は、以下のフォームにパスワードを入力してください。').'</p>') ?>

<?php
op_include_form('passwordForm', $form, array(
  'title' => __('%1%を退会する', array('%1%' => $op_config['sns_name'])),
  'url' => 'member/delete',
))
?>
