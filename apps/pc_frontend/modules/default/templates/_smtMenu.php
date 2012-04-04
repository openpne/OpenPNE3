<ul class="nav">
  <?php if ($navs): ?>
  <?php foreach ($navs as $nav): ?>
  <?php if (op_is_accessible_url($nav->uri)): ?>
  <?php if('@member_profile_mine' === $nav->uri): ?>
  <li class="active"><?php echo link_to($sf_user->getMember()->getName(), $nav->uri, array('id' => sprintf('smtMenu_%1', op_url_to_id($nav->uri, true)))) ?></li>
  <?php else: ?>
  <li><?php echo link_to($nav->caption, $nav->uri, array('id' => sprintf('smtMenu_%1', op_url_to_id($nav->uri, true)))) ?></li>
  <?php endif ?>
  <?php endif ?>
  <?php endforeach ?>
  <li><a href="<?php echo url_for('@homepage') ?>" id="smt-switch"><?php echo __('View this page on regular style') ?></a></li>
  <?php endif ?>
</ul>
