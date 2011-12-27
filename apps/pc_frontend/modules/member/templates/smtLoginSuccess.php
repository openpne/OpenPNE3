<form action="<?php echo url_for('member/login?authMode=MailAddress') ?>" method="post">

<div class="row">
<div class="span12">メールアドレス</div>
</div>

<div class="row">
<input type="text" name="authMailAddress[mail_address]" id="authMailAddress_mail_address" class="span12" value="" />
</div>

<div class="row">
<div class="span12">パスワード</div>
</div>

<div class="row">
<input type="password" name="authMailAddress[password]" id="authMailAddress_password" class="span12" value="" />
</div>

<div class="row">
<div class="span8">次回から自動的にログイン</div>
<div class="span4"><input type="checkbox" name="authMailAddress[is_remember_me]" id="authMailAddress_is_remember_me" />
<input value="member/home" type="hidden" name="authMailAddress[next_uri]" id="authMailAddress_next_uri" /></div>
</div>

<div class="row">
<div class="span4"></div><div class="span8"><input type="submit" class="input_submit btn primary" value="ログイン" /></div>
</div>

</form>

