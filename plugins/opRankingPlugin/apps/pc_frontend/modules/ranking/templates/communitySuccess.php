<?php
$list = array();
for ($i = 0; $i < $ranking['number']; $i++)
{
  $community = $ranking['model'][$i];
  $list[$i][sprintf(__('No%s'), $ranking['rank'][$i])] = $community->getName() . ' ' . sprintf(__(':%smember'), $ranking['count'][$i]);
  $list[$i][__('Category')] = "none";
  $list[$i][__('Manager')] = $ranking['admin'][$i]->getName();
  $list[$i][__('Description')] = nl2br($community->getConfig('description'));
}

$options = array(
  'title'          => sprintf(__("The No1 of the number of community member is '%s'"), $ranking['model'][0]->getName()),
  'link_to_detail' => 'community/%d',
  'model'          => $ranking['model'],
  'list'           => $list,
  'rank'           => $ranking['rank'],
);

slot('op_sidemenu');
include_parts('rankingLink', 'RankingLink');
end_slot();
include_parts('rankingResultList', 'RankingResultList', $options);
