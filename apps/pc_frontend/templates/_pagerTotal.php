<?php echo __('%first% - %last% of %total%', array('%first%' => $pager->getFirstIndice(), '%last%' => $pager->getLastIndice(), '%total%' => $pager->getNbResults()), 'pager') ?>
