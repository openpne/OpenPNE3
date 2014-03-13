<div id="application_gadget_<?php echo $memberApplication->getApplicationId() ?>" class="dparts box"><div class="parts">
<div id="gadgets-gadget-title-bar-<?php echo $memberApplication->getId() ?>" class="partsHeading">
<p class="link">
<?php if($isOwner): ?>
<?php echo link_to_app_setting(__('Settings'), $memberApplication->getId(), true) ?> | 
<?php endif; ?>
<?php echo link_to(__('App Info'), '@application_info?id='.$application->getId()) ?>
</p>
<h3 id="remote_iframe_<?php echo $memberApplication->getId() ?>_title">
<?php echo link_to_if($isTitleLink, $application->getTitle(), $sf_data->getRaw('titleLinkTo')) ?>
</h3>
</div>
<div class="block">
<iframe width="100%" scrolling="<?php echo $application->getScrolling() ? "yes" : "no" ?>" height="<?php echo ($height) ?>" frameborder="no" src="<?php echo $sf_data->getRaw('iframeUrl') ?>" class="gadgets-gadget" name="remote_iframe_<?php echo $memberApplication->getId() ?>" id="remote_iframe_<?php echo $memberApplication->getId() ?>"></iframe>
</div>
</div></div>
