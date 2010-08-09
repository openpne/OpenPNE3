<h2>プロフィール項目削除</h2>
<p>本当に削除してもよろしいですか？</p>
<p>※この項目に対するメンバーの入力値も失われます。</p>
<form action="<?php echo url_for('profile/delete?id='.$profile->getId()) ?>" method="post">
<?php $formCSRF = new sfForm(); ?><input type="hidden" name="<?php echo $formCSRF->getCSRFFieldName() ?>" value="<?php echo $formCSRF->getCSRFToken() ?>" />
<input type="submit" value="削除する" />
</form>
