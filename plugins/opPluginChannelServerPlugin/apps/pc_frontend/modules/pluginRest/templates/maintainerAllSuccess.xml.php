<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<m xmlns="http://pear.php.net/dtd/rest.allmaintainers"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xsi:schemaLocation="http://pear.php.net/dtd/rest.allmaintainers
                       http://pear.php.net/dtd/rest.allmaintainers.xsd"
>
<?php foreach ($handles as $handle): ?>
<?php if ($handle->value): ?>
 <h xlink:href="<?php echo url_for('@plugin_rest_maintainer_info?name='.$handle->value) ?>"><?php echo $handle->value ?></h>
<?php endif; ?>
<?php endforeach; ?>
</m>
