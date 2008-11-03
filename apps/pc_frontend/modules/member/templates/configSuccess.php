<?php foreach ($forms as $category => $form) : ?>

<?php include_box('form'.$category, $category, '', array(
  'form' => array($form),
  'url' => 'member/config?category=' . $category)
) ?>

<?php endforeach; ?>
