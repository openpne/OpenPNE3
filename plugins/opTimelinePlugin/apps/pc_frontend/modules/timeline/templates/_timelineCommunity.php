<?php if ($community->isPrivilegeBelong($memberId)): ?>
<div id="communityTimeline" class="dparts communityTimeline"><div class="parts">

<script type="text/javascript">
//<![CDATA[
var gorgon = {
      'target': 'community',
      'target_id': <?php echo $community->getId(); ?>,
      'count': '20',
      'post': {
        'foreign': 'community',
        'foreignId': '<?php echo $community->getId(); ?>'
      },
      'notify': {
        'lib': '<?php echo url_for('@homepage', array('absolute' => true)); ?>opTimelinePlugin/js/jquery.desktopify.js',
        'title': '<?php echo $community->getName();?> の最新投稿'
        <?php if ($community->getImageFileName()): ?>
        ,'icon': '<?php echo sf_image_path($community->getImageFileName(), array('size' => '48x48',)); ?>'
        <?php endif; ?>
      },
      'timerCount': '5000'
    };
var MAXLENGTH = 140;
var viewPhoto = '<?php echo $viewPhoto ?>';

var fileMaxSizeInfo = {
  'format': '<?php echo $fileMaxSize['format'] ?>',
  'size'  : '<?php echo $fileMaxSize['size'] ?>'
}

//]]>
</script>

<?php use_javascript('/opTimelinePlugin/js/jquery.upload-1.0.2.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/jquery.desktopify.js', 'last'); ?>
<?php use_javascript('/opTimelinePlugin/js/timeline-loader.api.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/counter.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/jquery.timeago.js', 'last') ?>
<?php use_javascript('/opTimelinePlugin/js/lightbox.js', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/lightbox.css', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/counter.css', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/bootstrap.css', 'last') ?>
<?php use_stylesheet('/opTimelinePlugin/css/timeline.css', 'last') ?>

<script type="text/javascript">
$(function(){
  $("#timeline-textarea").focus(function(){
    $('.timeline-postform').css('padding-bottom', '30px');
    $('#timeline-textarea').attr('rows', '3');
    $('#timeline-submit-area').css('display', 'inline');
    if ($.browser.msie && $.browser.version > 6 || $.browser.opera)
    {
      $('#timeline-upload-photo-button').remove();
      $('#timeline-submit-upload').css('display', 'inline');
      $('#timeline-submit-upload').css('position', 'relative');
      $('#timeline-submit-upload').css('left', '0px');
      $('#timeline-submit-upload').css('top', '-3px');
      $('#timeline-submit-upload').css('width', '150px');
      $('#timeline-public-flag').css('display', 'inline');
      $('#timeline-public-flag').css('position', 'relative');
      $('#timeline-public-flag').css('top', '-2px');
      $('#timeline-public-flag').css('left', '200px');
      $('#photo-file-name').remove();
    }
  });
});
</script>
<?php include_partial('timeline/timelineTemplate') ?>
<div class="partsHeading"><h3><?php echo $community->getName() ?><?php echo $op_term['activity'] ?></h3></div>
    <div class="timeline">
      <div class="timeline-postform well">
        <textarea id="timeline-textarea" class="input-xlarge" rows="1" tabindex="1" placeholder="今何してる？"></textarea>
        <div id="timeline-submit-loader"><?php echo op_image_tag('ajax-loader.gif', array()) ?></div>
        <div id="timeline-submit-error"></div>
        <div id="timeline-submit-area">
          <span id="timeline-upload-photo-button" class="btn"><i class="icon-camera"></i></span>
          <span id="photo-remove"><span class="icon-remove"></span></span><span id="photo-file-name"></span>
          <span id="counter"></span>
          <input id="timeline-submit-upload" type="file" name="timeline-submit-upload" enctype="multipart/form-data">
          <button id="timeline-submit-button" class="btn btn-primary timeline-submit" tabindex="2" disabled="disabled">投稿</button>
        </div>
      </div>

      <div id="timeline-loading" style="text-align: center;"><?php echo op_image_tag('ajax-loader.gif', array()) ?></div>
      <div id="timeline-list" data-last-id=""data-loadmore-id="">
      </div>

      <button class="gorgon-button button" id="timeline-loadmore">もっと読む</button>
      <div id="timeline-loadmore-loading"><?php echo op_image_tag('ajax-loader.gif', array()) ?></div>
    </div>

</div></div>
<?php endif; ?>
