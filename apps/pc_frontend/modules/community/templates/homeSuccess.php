<?php echo $community->getName() ?>コミュニティのホームです。

<ul>
<li><?php echo link_to('このコミュニティを編集する', 'community/edit?id=' . $community->getId()) ?></li>
</ul>
