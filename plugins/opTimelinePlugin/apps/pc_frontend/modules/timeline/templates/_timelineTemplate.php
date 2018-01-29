<script id="timelineTemplate" type="text/x-jquery-tmpl">
        <div class="timeline-post">
          <a name="timeline-${id}"></a>
          <div class="timeline-post-member-image">
            <a href="${member.profile_url}" title="${member.name}"><img src="${member.profile_image}" alt="${member.name}" /></a>
          </div>
          <div class="timeline-post-content">
            <div class="timeline-member-name">
              <a class="screen-name" href="${member.profile_url}">${member.screen_name}</a>
            </div>
            <div class="timeline-post-body" id="timeline-post-body-${id}">
              {{html body_html}}
            </div>
            <div class="timeline-post-control">
              {{if public_status == 'friend' }}
              <span class="public-flag">公開範囲:<?php echo $op_term['my_friend'] ?>まで公開</span>
              {{else public_status == 'private' }}
              <span class="public-flag">公開範囲:公開しない</span>
              {{/if}}
            </div>
            <div class="timeline-post-control">
            <a class="timeline-comment-link">コメントする</a>{{if member.self==true}} | <a href="#timeline-post-delete-confirm-${id}" class="timeline-post-delete-confirm-link">削除する</a>
            {{/if}} | <a href="<?php echo url_for('@homepage', array('absolute' => true)) ?>timeline/show/id/${id}"><span class="timestamp timeago" title="${created_at}"></span></a>

            <a>
              <div id="timeline-comment-loadmore-${id}" data-timeline-id="${id}" class="timeline-comment-loadmore">
                <i class="icon-comment"></i>&nbsp;以前のコメントを見る
                <span id="timeline-comment-loader-${id}" class="timeline-comment-loader">
                  <?php echo op_image_tag('ajax-loader.gif', array()) ?>
                </span>
              </div>
            </a>
            <span id="timeline-comment-show-control-${id}"></span>
            <a id="timlien-comment-hide-${id}"></a>

            <div class="timeline-post-comments" id="commentlist-${id}">

              <div id="timeline-post-comment-form-${id}" class="timeline-post-comment-form">
              <input class="timeline-post-comment-form-input" data-timeline-id="${id}" id="comment-textarea-${id}" type="text" />
              <button data-timeline-id="${id}" class="btn btn-primary btn-mini timeline-comment-button" disabled="disabled">投稿</button>
              </div>
              <div id="timeline-post-comment-form-loader-${id}" class="timeline-post-comment-form-loader">
              <?php echo op_image_tag('ajax-loader.gif', array()) ?>
              </div>
              <div id="timeline-post-comment-form-error-${id}" class="timeline-post-comment-form-loader">
              </div>
            </div>
          </div>
          {{if member.self==true}}
          <div class="timeline-post-delete-confirm" id="timeline-post-delete-confirm-${id}">
            <div class="partsHeading"><h3>投稿の削除</h3></div>
            <div class="timeline-post-delete-confirm-context">削除してよろしいですか？</div>
            <div class="timeline-post-delete-confirm-content">
              <div class="timeline-post-member-image">
                <a href="${member.profile_url}" title="${member.name}"><img src="${member.profile_image}" alt="${member.name}" /></a>
              </div>
              <div class="timeline-post-content">
                <div class="timeline-member-name">
                  <a class="screen-name" href="${member.profile_url}">${member.screen_name}</a>
                </div>
                <div class="timeline-post-body">
                  {{html body_html}}
                </div>
              </div>
              <div class="timeline-post-delete" style="text-align: center;">
              <button class="timeline-post-delete-button btn btn-danger"data-activity-id="${id}">削除</button>
              </div>
              <div class="timeline-post-delete-loading" style="text-align: center; display: none;">
                <?php echo op_image_tag('ajax-loader.gif') ?>
              </div>
            </div>
          </div>
          {{/if}}
        </div>
      </div>
</script>

<script id="timelineCommentTemplate" type="text/x-jquery-tmpl">
            <div class="timeline-post-comment">
              <div class="timeline-post-comment-member-image">
                <a href="${member.profile_url}"><img src="${member.profile_image}" alt="" width="36" /></a>
              </div>
              <div class="timeline-post-comment-content">
                <div class="timeline-post-comment-name-and-body">
                <a class="screen-name" href="${member.profile_url}">${member.screen_name}</a>
                <span class="timeline-post-comment-body">
                {{html body_html}}
                </span>
                </div>

                <div class="timeline-post-comment-control">
                {{if member.self==true }}
                <a href="#timeline-post-delete-confirm-${id}" class="timeline-post-delete-confirm-link">削除する</a> | 
                {{/if}} 
                <span class="timestamp timeago" title="${created_at}"></span>
                </div>
              </div>
              {{if member.self==true }}
              <div class="timeline-post-delete-confirm" id="timeline-post-delete-confirm-${id}">
                <div class="partsHeading"><h3>投稿の削除</h3></div>
                <div class="timeline-post-delete-confirm-context">削除してよろしいですか？</div>
                <div class="timeline-post-delete-confirm-content">
                  <div class="timeline-post-member-image">
                    <a href="${member.profile_url}" title="${member.name}"><img src="${member.profile_image}" alt="${member.name}" /></a>
                  </div>
                  <div class="timeline-post-content">
                    <div class="timeline-member-name">
                      <a class="screen-name" href="${member.profile_url}">${member.screen_name}</a>
                    </div>
                    <div class="timeline-post-body">
                      {{html body_html}}
                    </div>
                  </div>
                  <div class="timeline-post-delete" style="text-align: center;">
                  <button class="timeline-post-delete-button btn btn-danger"data-activity-id="${id}">削除</button>
                  </div>
                </div>
              </div>
              {{/if}}
            </div>
</script>
