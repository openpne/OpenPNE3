<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<l xmlns="http://pear.php.net/dtd/rest.categorypackages"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.categorypackages
                       http://pear.php.net/dtd/rest.categorypackages.xsd"
>
<?php foreach ($category->PluginPackage as $package): ?>
 <p xlink:href="<?php echo url_for('plugin_rest_package_info', $package) ?>"><?php echo $package->name ?></p>
<?php endforeach; ?>
</l>
