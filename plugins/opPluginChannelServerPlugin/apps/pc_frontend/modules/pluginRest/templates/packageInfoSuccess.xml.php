<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<p xmlns="http://pear.php.net/dtd/rest.package"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.package
                       http://pear.php.net/dtd/rest.package.xsd"
>
 <n><?php echo $package->name ?></n>
 <c><?php echo $channel_name ?></c>
 <ca xlink:href="<?php echo url_for('plugin_rest_category_info', $package->Category) ?>"><?php echo $package->Category->name ?></ca>
 <l><?php echo $package->license ?></l>
 <s><?php echo $package->summary ?></s>
 <d><?php echo $package->description ?></d>
 <r xlink:href="/rest/r/worlddomination"/>
</p>
