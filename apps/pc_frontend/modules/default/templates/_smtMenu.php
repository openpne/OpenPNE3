<!-- MENUFORM TMPL -->
<div class="menuform hide toggle1">
  <div class="row">
    <div class="span10 offset1 white font14 toggle1_close">
      <div class="center">
        MENU
      </div>
    </div>
    <div class="span1">
      <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close')) ?>
    </div>
  </div>

  <div class="menu-middle row">
    <div class="span11 offset1">
      <?php if ($navs): ?>
      <?php foreach ($navs as $nav): ?>
      <?php if (op_is_accessible_url($nav->uri)): ?>
      <?php if('@member_profile_mine' === $nav->uri): ?>
      <?php echo link_to($sf_user->getMember()->getName(), $nav->uri, array('class' => 'btn btn-info', 'id' => sprintf('smtMenu_%1', op_url_to_id($nav->uri, true)))) ?>
      <?php else: ?>
      <?php echo link_to($nav->caption, $nav->uri, array('class' => 'btn', 'id' => sprintf('smtMenu_%1', op_url_to_id($nav->uri, true)))) ?>
      <?php endif ?>
      <?php endif ?>
      <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
</div>
<!-- MENUFORM TMPL -->
