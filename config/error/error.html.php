<?php
if (sfConfig::has('sf_error_page_path'))
{
  include sfConfig::get('sf_error_page_path');
}
else
{
  $charset = 'utf-8';
  if ('mobile_frontend' === sfConfig::get('sf_app'))
  {
    $charset = 'Shift_JIS';
  }
  header('Content-Type: text/html; charset='.$charset);

  ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php $path = sfConfig::get('sf_relative_url_root', preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : ''))) ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
<title></title>
</head>

<body>
<p>
現在、サーバが混み合っているか、メンテナンス中です。<br>
ご迷惑をおかけいたしますが、しばらく時間を空けて再度アクセスしてください。
</p>
<p>
Currently, our service is too busy or is under maintenance.<br>
We're sorry and please retry accessing later.
</p>
</body>
</html>
<?php
  $html = ob_get_clean();

  if ('mobile_frontend' === sfConfig::get('sf_app'))
  {
    $html = mb_convert_encoding($html, 'Shift_JIS', 'utf-8');
  }

  echo $html;
}
?>
