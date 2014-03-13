<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<a xmlns="http://pear.php.net/dtd/rest.allreleases2"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2
                       http://pear.php.net/dtd/rest.allreleases2.xsd"
>
 <p><?php echo $package->name ?></p>
 <c><?php echo $channel_name ?></c>
<?php foreach ($package->PluginRelease as $release): ?>
 <r><v><?php echo $release->version ?></v><s><?php echo $release->stability ?></s><m>5.2.3</m></r>
</a>
<?php endforeach; ?>
