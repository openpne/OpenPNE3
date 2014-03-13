<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<m xmlns="http://pear.php.net/dtd/rest.packagemaintainers"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.packagemaintainers
                       http://pear.php.net/dtd/rest.packagemaintainers.xsd"
>
 <p><?php echo $package->name ?></p>
 <c><?php echo $channel_name ?></c>
<?php foreach ($package->PluginMember as $pluginMember): ?>
 <m>
  <h><?php echo $pluginMember->Member->getConfig('pear_handle') ?></h>
  <a>1</a>
  <r><?php echo $pluginMember->position ?></r>
 </m>
<?php endforeach; ?>
</m>
