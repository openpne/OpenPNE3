<?php
$options = array(
  'title' => __('Member Registration'),
  'url'   => url_for('member/registerInput?token='.$token),
  'button' => __('Register'),
);
op_include_form('RegisterForm', $form, $options);
?>

<script type="text/javascript">
//<![CDATA[
var formElement = document.querySelector('#RegisterForm form');
preventDoubleSubmission(formElement);
//]]>
</script>
