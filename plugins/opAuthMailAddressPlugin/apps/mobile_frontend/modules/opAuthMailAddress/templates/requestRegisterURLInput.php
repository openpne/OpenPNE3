<?php include_customizes('inputForm', 'top') ?>
<p>新規登録をするには以下のﾘﾝｸから、本文を入力せずにﾒｰﾙを送信してください。</p>
<?php include_customizes('inputForm', 'formTop') ?>
<br>
[i:106]<?php echo op_mail_to('register_sns', array(), 'ﾒｰﾙで登録') ?>
<br>
<?php include_customizes('inputForm', 'formBottom') ?>
<?php include_customizes('inputForm', 'bottom') ?>
