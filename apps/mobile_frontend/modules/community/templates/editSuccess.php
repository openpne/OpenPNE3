<?php 
$subtitle = null;
$url = 'community/edit';
if($communityForm->isNew())
{
  $title = __('Create community');
}
else
{
  $title = __('Edit community');
  $subtitle = $community->getName();
  $url .= '?id='.$community->getId();
}
?>

<?php op_mobile_page_title($title, $subtitle) ?>

<form action="<?php echo url_for($url) ?>" method="post">
<table>
<?php echo $communityForm ?>
<?php echo $communityConfigForm ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</table>
</form>
