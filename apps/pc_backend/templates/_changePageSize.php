<form action="<?php echo $params['uri'] ?>" method="<?php echo $params['method'] ?>"> 
<p id="numberDisplays">
<strong><?php echo $params['title'] ?></strong>：
<select class="basic" name="<?php echo $params['name'] ?>">
<?php foreach($params['params'] as $param): ?>
<option value="<?php echo $param ?>">
<?php echo $param.$params['unit'] ?>
</option> 
<?php endforeach; ?>
</select>
<span class="textBtnS"><input type="submit" value="変更"></span>
<?php if(!empty($params['caution'])): ?>
<span class="btnCaution"><?php echo $params['caution'] ?></span>
<?php endif; ?>
</p>
</form>
