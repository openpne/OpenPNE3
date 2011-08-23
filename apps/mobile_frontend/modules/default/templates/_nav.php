<?php if (count($navs)) : ?>
<?php
$list = array();
foreach ($navs as $nav)
{
  if (isset($id))
  {
    if (op_is_accessible_url($nav->getUri().'?id='.$id))
    {
      $list[] = link_to($nav->getCaption(), $nav->getUri().'?id='.$id);
    }
  }
  else
  {
    if (op_is_accessible_url($nav->getUri()))
    {
      $list[] = link_to($nav->getCaption(), $nav->getUri());
    }
  }
}
echo implode(isset($separator) ? $separator : "<br>\n", $list);
?>
<?php if (!isset($line) || $line) : ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endif; ?>
<?php endif; ?>
