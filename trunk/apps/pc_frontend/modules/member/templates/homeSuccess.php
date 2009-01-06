<?php slot('op_top') ?>
<?php if ($topWidgets) : ?>
<?php foreach ($topWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php end_slot() ?>

<?php slot('op_sidemenu') ?>
<?php if ($sideMenuWidgets) : ?>
<?php foreach ($sideMenuWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php end_slot() ?>

<?php if ($contentsWidgets) : ?>
<?php foreach ($contentsWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php include_box('homeTopSampleBox', 'ホーム画面', sprintf('あなたのホームです。(メンバーID:%d, ニックネーム:%s)', $sf_user->getMemberId(), $sf_user->getMember()->getName())); ?>
