<?php if (count($navs)) : ?>
<?php
$list = array();
foreach ($navs as $nav)
{
  if (isset($id))
  {
    $list[] = link_to($nav->getCaption(), $nav->getUri().'?id='.$id);
  }
  else
  {
    $list[] = link_to($nav->getCaption(), $nav->getUri());
  }
}
echo implode(isset($separator) ? $separator : "<br>\n", $list);
?>
<?php if (!isset($line) || $line) : ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endif; ?>
<?php endif; ?>
