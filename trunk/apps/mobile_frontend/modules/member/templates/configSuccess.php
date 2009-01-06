<?php if ($categoryName) : ?>
<?php include_page_title($categoryCaptions[$categoryName]) ?>
<form action="<?php echo url_for('member/config?category='.$categoryName) ?>" method="post">
<?php echo $form ?>
<br>
<center><input type="submit" value="変更する"></center>
</form>
<?php else: ?>
<?php include_page_title(__('設定変更')) ?>
__('ﾒﾆｭｰから設定したい項目を選択してください。'));
<?php endif; ?>
