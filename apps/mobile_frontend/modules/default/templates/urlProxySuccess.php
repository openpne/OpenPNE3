<?php op_mobile_page_title(__('Link outside URL')) ?>

<?php echo __('This url is the outside of %0%.', array('%0%' => $op_config['sns_name'])) ?>
<hr>
URL <input type="text" value="<?php echo $url ?>">
<hr>
<?php if ($proxys instanceof sfOutputEscaperArrayDecorator && count($proxys)): ?>
<?php $i = 1; ?>
<?php foreach ($proxys as $name => $purl): ?>
<a href="<?php echo strpos($purl, '%s') ? sprintf($purl, urlencode($url)) : $purl.urlencode($url) ?>"<?php echo $i <= 9 ? 'accesskey="'.$i.'"' : '' ?>><?php echo ($i <= 9 ? $i++.'. ' : '').$name ?></a>
<?php endforeach; ?>
<hr>
<?php endif; ?>
<a href="<?php echo $url ?>" accesskey="0"><?php echo '0. '.__('Direct link') ?></a><br>
<a href="mailto:?body=<?php echo urlencode($url) ?>" accesskey="*"><?php echo '*. '.__('Send this URL by E-mail') ?></a>
<?php if ($sf_request->getMobile()->isDoCoMo()): ?>
<br><a href="<?php echo $url ?>" accesskey="#" ifb>#. ﾌﾙﾌﾞﾗｳｻﾞ</a>
<?php elseif ($sf_request->getMobile()->isEZweb()): ?>
<br><a href="device:pcsiteviewer?url=<?php echo urlencode($url) ?>" accesskey="#" >#. PCｻｲﾄﾋﾞｭｰﾜ</a>
<?php endif; ?>

<?php slot('op_mobile_footer', ''); ?>
