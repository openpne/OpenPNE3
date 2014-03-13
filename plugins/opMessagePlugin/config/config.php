<?php
$this->dispatcher->connect('routing.load_configuration', array('opMessagePluginRouting', 'listenToRoutingLoadConfigurationEvent'));

$this->dispatcher->connect('op_action.post_execute_friend_link', array('opMessagePluginObserver', 'listenToPostActionEventSendFriendLinkRequestMessage'));
$this->dispatcher->connect('op_action.post_execute_community_join', array('opMessagePluginObserver', 'listenToPostActionEventSendCommunityJoiningRequestMessage'));
$this->dispatcher->connect('op_action.post_execute_community_changeAdminRequest', array('opMessagePluginObserver', 'listenToPostActionEventSendTakeOverCommunityRequestMessage'));
$this->dispatcher->connect('op_action.post_execute_community_subAdminRequest', array('opMessagePluginObserver', 'listenToPostActionEventSendCommunitySubAdminRequestMessage'));

$this->dispatcher->connect('op_confirmation.list_filter', array('opMessagePluginObserver', 'filterConfirmation'));

$this->dispatcher->connect('form.post_configure', array('opMessagePluginObserver', 'injectMessageFormField'));
