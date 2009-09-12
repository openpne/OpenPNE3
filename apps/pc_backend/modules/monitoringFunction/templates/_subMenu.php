<?php foreach ($navs as $nav): ?>
<li><?php 
try
{
  if($nowUri == $nav->getUri()) {
    echo '<b>'.$nav->getCaption().'</b>';
  } else {
    echo link_to($nav->getCaption(), $nav->getUri());
  }
}
catch(Exception $e)
{
  echo $nav->getCaption();
}
?></li>
<?php endforeach; ?>
