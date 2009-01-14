<?php if (count($navis)) : ?>
<?php foreach ($navis as $navi) : ?>
<?php if (isset($id)) : ?>
<?php echo link_to($navi->getCaption(), $navi->getUri().'?id='.$id) ?><br>
<?php else : ?>
<?php echo link_to($navi->getCaption(), $navi->getUri()) ?><br>
<?php endif; ?>
<?php endforeach; ?>

<?php if ($type !== 'mobile_home_side' && $type !== 'mobile_global') : ?>
<hr color="#0d6ddf" size="3">
<?php endif; ?>

<?php endif; ?>
