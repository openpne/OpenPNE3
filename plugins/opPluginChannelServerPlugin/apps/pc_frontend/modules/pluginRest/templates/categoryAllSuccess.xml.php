<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<a xmlns="http://pear.php.net/dtd/rest.allcategories"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.allcategories
                       http://pear.php.net/dtd/rest.allcategories.xsd"
>
 <ch><?php echo $channel_name ?></ch>
<?php foreach ($categories as $category): ?>
 <c xlink:href="<?php echo url_for('plugin_rest_category_info', $category) ?>"><?php echo $category->name ?></c>
<?php endforeach; ?>
</a>
