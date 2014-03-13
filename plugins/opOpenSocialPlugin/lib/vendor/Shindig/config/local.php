<?php
$webprefix = '';
if (sfContext::hasInstance())
{
  $webprefix = sfContext::getInstance()->getRequest()->getScriptName();
}
$shindigConfig = array(
  'debug' => false,

  'allow_plaintext_token' => false,

  'web_prefix' => $webprefix,
  'default_js_prefix' => $webprefix.'/gadgets/js/',
  'default_iframe_prefix' => $webprefix.'/gadgets/ifr?',

  'allow_anonymous_token' => false,

  'token_cipher_key' => Doctrine::getTable('SnsConfig')->get('shindig_token_cipher_key'),
  'token_hmac_key'   => Doctrine::getTable('SnsConfig')->get('shindig_token_hmac_key'),
  'token_max_age'    => Doctrine::getTable('SnsConfig')->get('shindig_token_max_age', 60*60),
  
  'base_path'      => sfConfig::get('sf_plugins_dir').'/opOpenSocialPlugin/lib/vendor/Shindig/',
  'features_path'  => sfConfig::get('sf_plugins_dir').'/opOpenSocialPlugin/lib/vendor/Shindig/features/',
  'container_path' => sfConfig::get('sf_app_cache_dir').'/plugins/opOpenSocialPlugin',

  'private_key_file'  => sfConfig::get('sf_plugins_dir').'/opOpenSocialPlugin/certs/private.key', 
  'public_key_file'   => sfConfig::get('sf_plugins_dir').'/opOpenSocialPlugin/certs/public.crt',
  'private_key_phrase' => Doctrine::getTable('SnsConfig')->get('shindig_private_key_phrase', ""), 

  'remote_content'        => 'opShindigRemoteContent',
  'security_token_signer' => 'opShindigSecurityTokenDecoder',
  'security_token'        => 'opShindigSecurityToken',
  'oauth_lookup_service'  => 'opShindigOAuthLookupService',

  'data_cache'    => 'opCacheStorageFile',
  'feature_cache' => 'opCacheStorageFile',

  'person_service'     => 'opJsonDbOpensocialService',
  'activity_service'   => 'opJsonDbOpensocialService',
  'app_data_service'   => 'opJsonDbOpensocialService',
  'messages_service'   => 'opJsonDbOpensocialService',
  'album_service'      => 'opJsonDbOpensocialService',
  'media_item_service' => 'opJsonDbOpensocialService',

  'cache_time' => Doctrine::getTable('SnsConfig')->get('shindig_cache_time', 60*60),
  'cache_root' => sfConfig::get('sf_app_cache_dir').'/plugins/opOpenSocialPlugin',

  'curl_connection_timeout' => '15',
);
