<?php foreach ($navs as $nav): ?>
<li><?php 
try
{
  echo link_to($nav->getCaption(), $nav->getUri());
}
catch(Exception $e)
{
  echo $nav->getCaption();
}
?></li>
<?php endforeach; ?>
