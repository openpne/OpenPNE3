<?php use_helper('opUtil', 'Javascript', 'opAsset') ?>
<script type="text/javascript">
//<![CDATA[
var gorgon = {
      'activity_id': <?php echo $activity->getId() ?>,
      'count': 1
    };
var MAXLENGTH = 140;
var viewPhoto = '<?php echo $viewPhoto ?>';
//]]>
</script>
<?php op_smt_use_stylesheet('/opTimelinePlugin/css/jquery.colorbox.css') ?>
<?php op_smt_use_stylesheet('/opTimelinePlugin/css/timeline-smartphone.css', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/jquery.timeline.js', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/jquery.timeago.js', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/timeline-loader-smartphone.js', 'last') ?>

<script id="timelineTemplate" type="text/x-jquery-tmpl">
        <div class="timeline-post">
          {{if public_status != 'sns' }}
          <div class="only-border-top"></div>
          {{/if}}
          <a name="timeline-${id}"></a>
          <div class="timeline-post-member-image">
            <a href="${member.profile_url}"><img src="${member.profile_image}" alt="member-image" width="23" class="red6" /></a>
          </div>
          <div class="timeline-post-content">
            <div class="timeline-member-name">
              <a href="${member.profile_url}">{{if member.screen_name}} ${member.screen_name} {{else}} ${member.name} {{/if}}</a>
              <a href="<?php echo url_for('@homepage', array('absolute' => true)) ?>timeline/show/id/${id}">
                <div class="timestamp timeago" title="${created_at}"></div>
              </a>
            </div>
            <div class="timeline-post-body" id="timeline-body-context-${id}">
              {{html body_html}}
            </div>
          </div>

          <div class="timeline-post-control">
            <a href="#timeline-${id}" class="timeline-comment-link">コメントする</a>
            <span class="timeline-public-flag-show">
              {{if public_status == 'friend' }}
              <span class="icon-lock"></span>
              <span class="public-flag"><?php echo $op_term['my_friend'] ?>まで</span>
              {{else public_status == 'private' }}
              <span class="icon-lock"></span>
              <span class="public-flag">公開しない</span>
              {{/if}}
            </span>
          </div>

          <a>
            <div id="timeline-comment-loadmore-${id}" data-timeline-id="${id}" class="timeline-comment-loadmore">
              <i class="icon-comment"></i>&nbsp;以前のコメントを見る
              <span id="timeline-comment-loader-${id}" class="timeline-comment-loader">
                <?php echo op_image_tag('ajax-loader.gif', array()) ?>
              </span>
            </div>
          </a>
          <div class="timeline-post-comments" id="commentlist-${id}">

            <div id="timeline-post-comment-form-${id}" class="timeline-post-comment-form">
            <input class="timeline-post-comment-form-input" type="text" data-timeline-id="${id}" id="comment-textarea-${id}" /><button data-timeline-id="${id}" class="btn btn-primary btn-mini timeline-comment-button" disabled="disabled">投稿</button>
            </div>
            <div id="timeline-post-comment-form-loader-${id}" class="timeline-post-comment-form-loader">
              <?php echo op_image_tag('ajax-loader.gif', array()) ?>
            </div>
          </div>


          {{if public_status != 'sns' }}
          <div class="only-border-bottom"></div>
          {{/if}}
        </div>
</script>

<script id="timelineCommentTemplate" type="text/x-jquery-tmpl">
            <div class="timeline-post-comment">

              <div class="timeline-post-comment-member-image">
                <a href="${member.profile_url}"><img src="${member.profile_image}" alt="" width="23" class="red6" /></a>
              </div>
              <div class="timeline-post-comment-content">
                <div class="timeline-post-comment-name-and-body">
                <a href="${member.profile_url}">{{if member.screen_name}} ${member.screen_name} {{else}} ${member.name} {{/if}}</a>
                </div>
              </div>
              <div class="timestamp timeline-post-control timeago" title="${created_at}"></div>
              <div class="timeline-post-comment-body">
              {{html body_html}}
              </div>
            </div>
          </div>
</script>

<div class="row">
  <div class="gadget_header span12"><?php echo $activity->getMember()->getName(); ?>さんの<?php echo $op_term['activity'] ?></div>
</div>

<div id="timeline-list" class="span12" data-post-baseurl="<?php echo url_for('@homepage'); ?>" data-last-id="" data-loadmore-id="">
</div>
<div id="timeline-list-loader" class="row span12 center show">
<?php op_image_tag('ajax-loader.gif', array('absolute' => true)) ?>
</div>

