<?php

$this->dispatcher->connect('op_action.post_execute', array('opSkinClassicObserver', 'appendCss'));
$this->dispatcher->connect('response.filter_content', array('opSkinClassicObserver', 'cacheCss'));
