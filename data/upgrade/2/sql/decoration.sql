<?php
$result = array();

foreach ($this->conn->fetchColumn('SELECT tagname FROM c_config_decoration WHERE is_enabled = 0') as $item)
{
  $result[] = str_replace(':', '_', $item);
}
?>

INSERT INTO sns_config (id, name, value) VALUES (NULL, "richtextarea_unenable_buttons", <?php echo $this->conn->quote(serialize($result)) ?>);
