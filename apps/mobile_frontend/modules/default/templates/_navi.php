<?php if ($navis) : ?>
<?php foreach ($navis as $navi) : ?>
<?php if (isset($id)) : ?>
<?php echo link_to($navi->getCaption(), $navi->getUri().'?id='.$id) ?><br>
<?php else : ?>
<?php echo link_to($navi->getCaption(), $navi->getUri()) ?><br>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
