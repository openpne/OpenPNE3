<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<a xmlns="http://pear.php.net/dtd/rest.allpackages"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages
                       http://pear.php.net/dtd/rest.allpackages.xsd"
>
 <c><?php echo $channel_name ?></c>
<?php foreach ($packages as $package): ?>
 <p><?php echo $package->name ?></p>
<?php endforeach; ?>
</a>
