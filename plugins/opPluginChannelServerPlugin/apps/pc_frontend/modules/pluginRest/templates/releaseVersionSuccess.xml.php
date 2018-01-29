<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<r xmlns="http://pear.php.net/dtd/rest.release"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.release
                       http://pear.php.net/dtd/rest.release.xsd"
>
 <p xlink:href="<?php echo url_for('plugin_rest_package_info', $package) ?>"><?php echo $package->name ?></p>
 <c><?php echo $channel_name ?></c>
 <v><?php echo $release->version ?></v>
 <st><?php echo $release->stability ?></st>
 <l><?php echo $package->license ?></l>
 <m><?php echo $release->Member->getConfig('pear_handle') ?></m>
 <s><?php echo $info['summary'] ?></s>
 <d><?php echo $info['description'] ?></d>
 <da><?php echo $info['date'] ?> <?php echo $info['time'] ?></da>
 <n><?php echo $info['notes'] ?></n>
 <f><?php echo $release->File->filesize ?></f>
 <g><?php echo url_for('@plugin_download_without_extension?name='.$package->name.'&version='.$release->version, true) ?></g>
 <x xlink:href="package.<?php echo $release->version ?>.xml"/>
</r>
