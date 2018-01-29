<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('About App') ?>
<?php end_slot() ?>

<table>
<tr><th colspan="2"><?php echo __('About App') ?></th></tr>
<tr><th><?php echo __('Name') ?></th><td><?php echo $application->getTitle() ?></td></tr>
<tr><th><?php echo __('Status') ?></th><td><?php echo $application->isActive() ? __('Active') : __('Inactive') ?></td></tr>
<?php if ($application->getMemberId()): ?>
<tr><th><?php echo __('Member who Added App') ?></th><td><?php echo $application->getAdditionalMember()->getName() ?></td></tr>
<?php endif; ?>
<tr><th><?php echo __('App URL') ?></th><td><?php echo $application->getUrl() ?></td></tr>
<tr><th><?php echo __('Title URL') ?></th><td>
<?php if ($application->getTitleUrl()) : ?>
<?php echo link_to(null,$application->getTitleUrl(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Screenshot') ?></th><td>
<?php if ($application->getScreenshot()) : ?>
<?php echo image_tag($application->getScreenshot(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Thumbnail') ?></th><td>
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Description') ?></th><td><?php echo $application->getDescription() ?></td></tr>
<tr><th><?php echo __('Users') ?></th><td><?php echo $application->countMembers() ?></td></tr>
<tr><th><?php echo __('Last Updated') ?></th><td><?php echo $application->getUpdatedAt() ?></td></tr>
<tr><th colspan="2"><?php echo __('About Author') ?></th></tr>
<tr><th><?php echo __('Name') ?></th><td><?php echo $application->getAuthorEmail() ? mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true)) : $application->getAuthor() ?></td></tr>
<tr><th><?php echo __('Affiliation') ?></th><td><?php echo $application->getAuthorAffiliation() ?></td></tr>
<tr><th><?php echo __('Aboutme') ?></th><td><?php echo $application->getAuthorAboutme() ?></td></tr>
<tr><th><?php echo __('Photo') ?></th><td>
<?php if($application->getAuthorPhoto()) : ?>
<?php echo image_tag($application->getAuthorPhoto(), array('alt' => $application->getAuthor())) ?> 
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Link') ?></th><td>
<?php if ($application->getAuthorLink()) : ?>
<?php echo link_to(null,$application->getAuthorLink(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Quote') ?></th><td><?php echo $application->getAuthorQuote() ?></td></tr>
<tr><td colspan="2">
<?php echo button_to(__('Delete'),'opOpenSocialPlugin/delete?id='.$sf_request->getParameter('id'), array('style' => 'float:left')) ?> 
<?php $form = new sfForm() ?>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/update?id='.$sf_request->getParameter('id')), array('style' => 'float:left')) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Update') ?>" />
</form>
<?php if ($application->isActive()): ?>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/inactivate?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Inactivate') ?>" />
</form>
<?php else: ?>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/activate?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Activate') ?>" />
</form>
<?php endif; ?>
</td></tr>
</table>
