<?php
$fileTable = Doctrine_Core::getTable('File');
$default = $fileTable->getDefaultPagerSize();
$fileSizeList = $fileTable->getAllowedPagerSizeList();
?>
<form action="<?php echo $params['uri'] ?>" method="<?php echo $params['method'] ?>">
<p id="numberDisplays">
<strong><?php echo $params['title'] ?></strong>：
<select class="basic" name="<?php echo $params['name'] ?>">
<?php foreach($fileSizeList as $fileSize): ?>
<option value="<?php echo $fileSize ?>"<?php echo $fileSize == $params['default'] ? ' selected="selected"' : ''; ?>>
<?php echo $fileSize.$params['unit'] ?>
</option>
<?php endforeach; ?>
</select>
<span class="textBtnS"><input type="submit" value="変更"></span>
<?php if(!empty($params['caution'])): ?>
<span class="btnCaution"><?php echo $params['caution'] ?></span>
<?php endif; ?>
</p>
</form>
