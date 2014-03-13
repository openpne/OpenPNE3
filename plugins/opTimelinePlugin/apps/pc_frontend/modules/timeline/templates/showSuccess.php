<?php use_helper('opUtil', 'Javascript') ?>
<script type="text/javascript">
//<![CDATA[
var gorgon = {
      'activity_id': <?php echo $activity->getId() ?>,
      'count': 1
<?php if ($isCommunity && $community->isPrivilegeBelong($memberId)): ?>
      ,'target': 'community',
      'target_id': <?php echo $community->getId() ?>
<?php endif; ?>
    };
var viewPhoto = '<?php echo $viewPhoto ?>';
var MAXLENGTH = 140;
var fileMaxSize = '<?php echo opTimelinePluginUtil::getFileSizeMax() ?>';
//]]>
</script>
<?php use_javascript('/opTimelinePlugin/js/jquery.timeline.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/jquery.timeago.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/timeline-loader.api.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/lightbox.js', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/lightbox.css', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/bootstrap.css', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/timeline.css', 'last') ?>

<?php include_partial('timeline/timelineTemplate') ?>

<div class="partsHeading"><h3><?php echo $activity->getMember()->getName(); ?>さんの<?php echo $op_term['activity'] ?></h3></div>

<div class="timeline-large">
  <div id="timeline-loading" style="text-align: center;"><?php echo op_image_tag('ajax-loader.gif', array()) ?></div>
  <div id="timeline-list" data-last-id=""data-loadmore-id="">

  </div>
</div>

