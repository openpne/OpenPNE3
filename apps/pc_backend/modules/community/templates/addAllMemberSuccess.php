<?php
$form = new sfForm();

$redirectUrl = url_for('community/addAllMember?id='.$community->getId()).'?continue=1'
             . '&'.urlencode($form->getCSRFFieldName()).'='.urlencode($form->getCSRFToken());

$sf_response->addHttpMeta('Refresh', '0;URL='.$redirectUrl);
?>

<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Make all members join in this %community%')); ?>

<p>
<?php echo __('Processing... (Remaining %num% records)', array('%num%' => $remaining)) ?>
</p>
