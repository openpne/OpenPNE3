<?php
$topic_or_event_images = $this->conn->fetchAll('SELECT id, name FROM file WHERE name LIKE "t_%";');
$comment_images = $this->conn->fetchAll('SELECT id, name FROM file WHERE name LIKE "tc_%";');

$images = array(
  'topic' => array(),
  'event' => array(),
  'topic_comment' => array(),
  'event_comment' => array(),
);

foreach ($topic_or_event_images as $v)
{
  if (!preg_match('/t_([0-9]+)_([0-9]+)_/', $v['name'], $matches))
  {
    continue;
  }

  $id = $matches[1];
  $number = $matches[2];

  if ($this->conn->fetchOne('SELECT id FROM community_topic WHERE id = ?', array($id)))
  {
    $images['topic'][] = array($id, $v['id'], $number);
  }
  elseif ($this->conn->fetchOne('SELECT id FROM community_event WHERE id = ?', array($id)))
  {
    $images['event'][] = array($id, $v['id'], $number);
  }
}

unset($topic_or_event_images);

foreach ($comment_images as $v)
{
  if (!preg_match('/tc_([0-9]+)_([0-9]+)_/', $v['name'], $matches))
  {
    continue;
  }

  $id = $matches[1];
  $number = $matches[2];

  if ($this->conn->fetchOne('SELECT id FROM community_topic_comment WHERE id = ?', array($id)))
  {
    $images['topic_comment'][] = array($id, $v['id'], $number);
  }
  elseif ($this->conn->fetchOne('SELECT id FROM community_event_comment WHERE id = ?', array($id)))
  {
    $images['event_comment'][] = array($id, $v['id'], $number);
  }
}

unset($comment_images);

$targets = array_keys($images);

foreach ($targets as $target)
{
  if (!count($images[$target]))
  {
    continue;
  }

  echo 'INSERT INTO community_'.$target.'_image VALUES';
  foreach ($images[$target] as $k => $v)
  {
    if ($k > 0)
    {
      echo ', ';
    }
    echo sprintf('(NULL, %d, %d, %d)', $v[0], $v[1], $v[2]);
  }
  echo ";\n";

  unset($images[$target]);
}

?>
