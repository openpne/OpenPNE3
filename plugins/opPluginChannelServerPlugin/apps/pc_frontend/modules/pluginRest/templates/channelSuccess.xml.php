<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<channel version="1.0" xmlns="http://pear.php.net/channel-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/channel-1.0 http://pear.php.net/dtd/channel-1.0.xsd">
  <name><?php echo $channel_name ?></name>
  <summary><?php echo $summary ?></summary>
  <suggestedalias><?php echo $suggestedalias ?></suggestedalias>
  <servers>
    <primary>
      <rest>
        <baseurl type="REST1.0">http://<?php echo $channel_name ?><?php echo url_for('@plugin_rest') ?></baseurl>
        <baseurl type="REST1.1">http://<?php echo $channel_name ?><?php echo url_for('@plugin_rest') ?></baseurl>
        <baseurl type="REST1.2">http://<?php echo $channel_name ?><?php echo url_for('@plugin_rest') ?></baseurl>
        <baseurl type="REST1.3">http://<?php echo $channel_name ?><?php echo url_for('@plugin_rest') ?></baseurl>
      </rest>
    </primary>
  </servers>
</channel>
