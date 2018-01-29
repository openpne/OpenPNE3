<?php $application = $options->object ?>
<p class="photo">
<?php $imgParam = array('alt' => $application->getTitle()) ?>
<?php if ($application->getThumbnail()): ?>

<?php echo image_tag($application->getThumbnail(), $imgParam) ?>
<?php else: ?>
<?php $imgParam['size'] = '180x180' ?>
<?php echo image_tag('no_image.gif', $imgParam) ?>
<?php endif; ?>
</p>
<p class="text"><?php echo sprintf('%s(%d)',
  link_to_if($application->getTitleUrl(), $options->object->getTitle(), $application->getTitleUrl(), array('target' => '_blank')),
  $application->countMembers()
) ?></p>
