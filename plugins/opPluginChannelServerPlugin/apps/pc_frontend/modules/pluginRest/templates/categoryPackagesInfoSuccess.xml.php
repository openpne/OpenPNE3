<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>
<f xmlns="http://pear.php.net/dtd/rest.categorypackageinfo"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.categorypackageinfo
                       http://pear.php.net/dtd/rest.categorypackageinfo.xsd"
>
<?php foreach ($category->PluginPackage as $package): ?>
<pi>
<p>
 <n><?php echo $package->name ?></n>
 <c><?php $channel_name ?></c>
 <ca xlink:href="<?php echo url_for('plugin_rest_category_info', $package->Category) ?>"><?php echo $package->Category->name ?></ca>
 <l><?php echo $package->license ?></l>
 <s><?php echo $package->summary ?></s>
 <d><?php echo $package->description ?></d>
 <r xlink:href="/rest/r/worlddomination"/>
</p>
<a>
<?php foreach ($package->PluginRelease as $release): ?>
 <r><v><?php echo $release->version ?></v><s><?php echo $release->stability ?></s></r>
<?php endforeach; ?>
</a>
<?php foreach ($package->PluginRelease as $release): ?>
<deps>
 <v><?php echo $release->version ?></v>
 <d></d>
</deps>
<?php endforeach; ?>
</pi>
<?php endforeach; ?>
</f>
