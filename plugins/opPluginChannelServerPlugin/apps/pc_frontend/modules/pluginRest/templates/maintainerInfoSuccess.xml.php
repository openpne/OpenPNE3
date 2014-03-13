<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<m xmlns="http://pear.php.net/dtd/rest.maintainer"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.maintainer
                       http://pear.php.net/dtd/rest.maintainer.xsd"
>
 <h><?php echo $config->value ?></h>
 <n><?php echo $config->Member->name ?></n>
 <u><?php echo url_for('@member_profile?id='.$config->member_id, true) ?></u>
</m>
