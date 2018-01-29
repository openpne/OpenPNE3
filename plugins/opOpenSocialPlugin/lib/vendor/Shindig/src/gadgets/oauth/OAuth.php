<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

class OAuth {
  public static $VERSION_1_0 = "1.0";
  public static $ENCODING = "UTF-8";
  public static $FORM_ENCODED = "application/x-www-form-urlencoded";
  public static $OAUTH_CONSUMER_KEY = "oauth_consumer_key";
  public static $OAUTH_TOKEN = "oauth_token";
  public static $OAUTH_TOKEN_SECRET = "oauth_token_secret";
  public static $OAUTH_SIGNATURE_METHOD = "oauth_signature_method";
  public static $OAUTH_SIGNATURE = "oauth_signature";
  public static $OAUTH_TIMESTAMP = "oauth_timestamp";
  public static $OAUTH_NONCE = "oauth_nonce";
  public static $OAUTH_VERSION = "oauth_version";
  public static $HMAC_SHA1 = "HMAC_SHA1";
  public static $RSA_SHA1 = "RSA_SHA1";
  public static $BEGIN_PRIVATE_KEY = "-----BEGIN PRIVATE KEY-----";
  public static $END_PRIVATE_KEY = "-----END PRIVATE KEY-----";
  public static $OAUTH_PROBLEM = "oauth_problem";
}

/* Generic exception class
 */
class OAuthException extends Exception {
}

class OAuthProblemException extends Exception {
}

class OAuthProtocolException extends Exception {
}

class OAuthConsumer {
  public $key;
  public $secret;
  public $callback_url;
  private $properties = array();

  function __construct($key, $secret, $callback_url = NULL) {
    $this->key = $key;
    $this->secret = $secret;
    $this->callback_url = $callback_url;
  }

  public function getProperty($name) {
    return $this->properties[$name];
  }

  public function setProperty($name, $value) {
    $this->properties[$name] = $value;
  }

}

class OAuthToken {
  // access tokens and request tokens
  public $key;
  public $secret;

  /**
   * key = the token
   * secret = the token secret
   */
  function __construct($key, $secret) {
    $this->key = $key;
    $this->secret = $secret;
  }

  /**
   * generates the basic string serialization of a token that a server
   * would respond to request_token and access_token calls with
   */
  function to_string() {
    return "oauth_token=" . OAuthUtil::urlencodeRFC3986($this->key) . "&oauth_token_secret=" . OAuthUtil::urlencodeRFC3986($this->secret);
  }

  function __toString() {
    return $this->to_string();
  }
}

class OAuthSignatureMethod {

  public function check_signature(&$request, $consumer, $token, $signature) {
    $built = $this->build_signature($request, $consumer, $token);
    return $built == $signature;
  }
}

class OAuthSignatureMethod_HMAC_SHA1 extends OAuthSignatureMethod {

  function get_name() {
    return "HMAC-SHA1";
  }

  public function build_signature($request, $consumer, $token) {
    $base_string = $request->get_signature_base_string();
    $request->base_string = $base_string;
    $key_parts = array($consumer->secret, (isset($token)) ? $token : "");
    $key_parts = array_map(array('OAuthUtil', 'urlencodeRFC3986'), $key_parts);
    $key = implode('&', $key_parts);
    return base64_encode(hash_hmac('sha1', $base_string, $key, true));
  }

  //TODO: Double check this!
  public function check_signature(&$request, $consumer, $token, $signature) {
    $sign = $this->build_signature($request, $consumer, $token);
    return $sign == $signature;
  }
}

class OAuthSignatureMethod_PLAINTEXT extends OAuthSignatureMethod {

  public function get_name() {
    return "PLAINTEXT";
  }

  public function build_signature($request, $consumer, $token) {
    $sig = array(OAuthUtil::urlencodeRFC3986($consumer->secret));
    if ($token) {
      array_push($sig, OAuthUtil::urlencodeRFC3986($token->secret));
    } else {
      array_push($sig, '');
    }
    $raw = implode("&", $sig);
    // for debug purposes
    $request->base_string = $raw;
    return OAuthUtil::urlencodeRFC3986($raw);
  }

  //TODO: Double check this!
  public function check_signature(&$request, $consumer, $token, $signature) {
    $raw = OAuthUtil::urldecodeRFC3986($request->base_string);
    $sig = explode("&", $raw);
    array_pop($sig);
    $secret = array(OAuthUtil::urldecodeRFC3986($consumer->secret));
    return $sig == $secret;
  }
}

class OAuthSignatureMethod_RSA_SHA1 extends OAuthSignatureMethod {
  public static $PRIVATE_KEY = "RSA-SHA1.PrivateKey";

  public function get_name() {
    return "RSA-SHA1";
  }

  protected function fetch_public_cert(&$request) {
    // not implemented yet, ideas are:
    // (1) do a lookup in a table of trusted certs keyed off of consumer
    // (2) fetch via http using a url provided by the requester
    // (3) some sort of specific discovery code based on request
    //
    // either way should return a string representation of the certificate
    throw Exception("fetch_public_cert not implemented");
  }

  protected function fetch_private_cert(&$request) {
    // not implemented yet, ideas are:
    // (1) do a lookup in a table of trusted certs keyed off of consumer
    //
    // either way should return a string representation of the certificate
    throw new Exception("fetch_private_cert not implemented");
  }

  public function build_signature(&$request, OAuthConsumer $consumer, $token) {
    $base_string = $request->get_signature_base_string();
    // Fetch the private key cert based on the request
    $cert = $consumer->getProperty(OAuthSignatureMethod_RSA_SHA1::$PRIVATE_KEY);
    // Pull the private key ID from the certificate
    //FIXME this function seems to be called both for a oauth.json action where
    // there is no phrase required, but for signed requests too, which do require it
    // this is a dirty hack to make it work .. kinda
    if (! $privatekeyid = @openssl_pkey_get_private($cert)) {
      if (! $privatekeyid = @openssl_pkey_get_private($cert, Shindig_Config::get('private_key_phrase') != '' ? (Shindig_Config::get('private_key_phrase')) : null)) {
        throw new Exception("Could not load private key");
      }
    }
    // Sign using the key
    $signature = '';
    if (($ok = openssl_sign($base_string, $signature, $privatekeyid)) === false) {
      throw new OAuthException("Could not create signature");
    }
    // Release the key resource
    @openssl_free_key($privatekeyid);
    return base64_encode($signature);
  }

  public function check_signature(&$request, $consumer, $token, $signature) {
    $decoded_sig = base64_decode($signature);
    $base_string = $request->get_signature_base_string();
    // Fetch the public key cert based on the request
    $cert = $this->fetch_public_cert($request);
    // Pull the public key ID from the certificate
    $publickeyid = openssl_get_publickey($cert);
    // Check the computed signature against the one passed in the query
    $ok = openssl_verify($base_string, $decoded_sig, $publickeyid);
    // Release the key resource
    @openssl_free_key($publickeyid);
    return $ok == 1;
  }
}

class OAuthRequest {
  public $parameters;
  private $http_method;
  private $http_url;
  public $base_string;
  public static $version = '1.0';

  function __construct($http_method, $http_url, $parameters = NULL) {
    @$parameters or $parameters = array();
    $this->parameters = $parameters;
    $this->http_method = $http_method;
    $this->http_url = $http_url;
  }

  /**
   * attempt to build up a request from what was passed to the server
   */
  public static function from_request($http_method = NULL, $http_url = NULL, $parameters = NULL) {
    $scheme = (! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
    @$http_url or $http_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    @$http_method or $http_method = $_SERVER['REQUEST_METHOD'];
    $request_headers = OAuthRequest::get_headers();
    // let the library user override things however they'd like, if they know
    // which parameters to use then go for it, for example XMLRPC might want to
    // do this
    if ($parameters) {
      $req = new OAuthRequest($http_method, $http_url, $parameters);
    } elseif (@substr($request_headers['Authorization'], 0, 5) == "OAuth") {
      // next check for the auth header, we need to do some extra stuff
      // if that is the case, namely suck in the parameters from GET or POST
      // so that we can include them in the signature
      $header_parameters = OAuthRequest::split_header($request_headers['Authorization']);
      if ($http_method == "GET") {
        $req_parameters = $_GET;
      } else if ($http_method = "POST") {
        $req_parameters = $_POST;
      }
      $parameters = array_merge($header_parameters, $req_parameters);
      $req = new OAuthRequest($http_method, $http_url, $parameters);
    } elseif ($http_method == "GET") {
      $req = new OAuthRequest($http_method, $http_url, $_GET);
    } elseif ($http_method == "POST") {
      $req = new OAuthRequest($http_method, $http_url, $_POST);
    }
    return $req;
  }

  /**
   * pretty much a helper function to set up the request
   * @return OAuthRequest
   */
  public static function from_consumer_and_token($consumer, $token, $http_method, $http_url, $parameters = NULL) {
    $parameters = is_array($parameters) ? $parameters : array();
    $defaults = array("oauth_nonce" => OAuthRequest::generate_nonce(), "oauth_timestamp" => OAuthRequest::generate_timestamp(), "oauth_consumer_key" => $consumer->key);
    $parameters = array_merge($defaults, $parameters);
    if (isset($token)) {
      $parameters['oauth_token'] = $token;
    }
    return new OAuthRequest($http_method, $http_url, $parameters);
  }

  public function set_parameter($name, $value) {
    $this->parameters[$name] = $value;
  }

  public function get_parameter($name) {
    return @$this->parameters[$name];
  }

  public function get_parameters() {
    return $this->parameters;
  }

  public function set_parameters($params) {
    return $this->parameters = $params;
  }

  //TODO double check if hash can be used
  public function requireParameters($names) {
    $present = $this->parameters;
    $absent = array();
    foreach ($names as $required) {
      if (! in_array($required, $present)) {
        $absent[] = $required;
      }
    }
    if (count($absent) == 0) {
      throw new OAuthProblemException("oauth_parameters_absent: " . OAuthUtil::urlencodeRFC3986($absent));
    }
  }

  /**
   * Returns the normalized parameters of the request
   *
   * This will be all (except oauth_signature) parameters,
   * sorted first by key, and if duplicate keys, then by
   * value.
   *
   * The returned string will be all the key=value pairs
   * concated by &.
   *
   * @return string
   */
  public function get_signable_parameters() {
    // Grab all parameters
    $params = $this->parameters;
    // Remove oauth_signature if present
    if (isset($params['oauth_signature'])) {
      unset($params['oauth_signature']);
    }
    // Urlencode both keys and values
    $keys = array_map(array('OAuthUtil', 'urlencodeRFC3986'), array_keys($params));
    $values = array_map(array('OAuthUtil', 'urlencodeRFC3986'), array_values($params));
    $params = array_combine($keys, $values);
    // Sort by keys (natsort)
    uksort($params, 'strnatcmp');
    // Generate key=value pairs
    $pairs = array();
    foreach ($params as $key => $value) {
      if (is_array($value)) {
        // If the value is an array, it's because there are multiple
        // with the same key, sort them, then add all the pairs
        natsort($value);
        foreach ($value as $v2) {
          $pairs[] = $key . '=' . $v2;
        }
      } else {
        $pairs[] = $key . '=' . $value;
      }
    }
    // Return the pairs, concated with &
    return implode('&', $pairs);
  }

  /**
   * Returns the base string of this request
   *
   * The base string defined as the method, the url
   * and the parameters (normalized), each urlencoded
   * and the concated with &.
   */
  public function get_signature_base_string() {
    $tmp = $this->parameters;
    $parts = parse_url($this->http_url);
    parse_str(@$parts['query'], $params);
    foreach ($params as $key => $value) {
      if ($key == "signOwner" || $key == "signViewer") {
        continue;
      }
      $this->parameters[$key] = $value;
    }
    $parts = array($this->get_normalized_http_method(), $this->get_normalized_http_url(), $this->get_signable_parameters());
    $parts = array_map(array('OAuthUtil', 'urlencodeRFC3986'), $parts);
    $this->parameters = $tmp;
    return implode('&', $parts);
  }

  /**
   * just uppercases the http method
   */
  public function get_normalized_http_method() {
    return strtoupper($this->http_method);
  }

  /**
   * parses the url and rebuilds it to be
   * scheme://host/path
   */
  public function get_normalized_http_url() {
    $parts = parse_url($this->http_url);
    // FIXME: port should handle according to http://groups.google.com/group/oauth/browse_thread/thread/1b203a51d9590226
    $port = (isset($parts['port']) && $parts['port'] != '80') ? ':' . $parts['port'] : '';
    $path = (isset($parts['path'])) ? $parts['path'] : '';

    return $parts['scheme'] . '://' . $parts['host'] . $port . $path;
  }

  /**
   * builds a url usable for a GET request
   */
  public function to_url() {
    $out = $this->get_normalized_http_url() . "?";
    $out .= $this->to_postdata();
    $parts = parse_url($this->http_url);
    $out .= "&" . @$parts['query'];
    return $out;
  }

  public function get_url() {
    return $this->http_url;
  }

  /**
   * builds the data one would send in a POST request
   */
  public function to_postdata() {
    $total = array();
    foreach ($this->parameters as $k => $v) {
      $total[] = OAuthUtil::urlencodeRFC3986($k) . "=" . OAuthUtil::urlencodeRFC3986($v);
    }
    $out = implode("&", $total);
    return $out;
  }

  /**
   * builds the Authorization: header
   */
  public function to_header() {
    $out = '"Authorization: OAuth realm="",';
    foreach ($this->parameters as $k => $v) {
      if (substr($k, 0, 5) != "oauth") continue;
      $out .= ',' . OAuthUtil::urlencodeRFC3986($k) . '="' . OAuthUtil::urlencodeRFC3986($v) . '"';
    }
    return $out;
  }

  public function __toString() {
    return $this->to_url();
  }

  public function sign_request($signature_method, $consumer, $token) {
    $this->set_parameter("oauth_signature_method", $signature_method->get_name());
    $signature = $this->build_signature($signature_method, $consumer, $token);
    $this->set_parameter("oauth_signature", $signature);
  }

  public function build_signature($signature_method, $consumer, $token) {
    $signature = $signature_method->build_signature($this, $consumer, $token);
    return $signature;
  }

  /**
   * util function: current timestamp
   */
  private static function generate_timestamp() {
    return time();
  }

  /**
   * util function: current nonce
   */
  public static function generate_nonce() {
    $mt = microtime();
    $rand = mt_rand();
    return md5($mt . $rand); // md5s look nicer than numbers
  }

  /**
   * util function for turning the Authorization: header into
   * parameters, has to do some unescaping
   */
  private static function split_header($header) {
    // this should be a regex
    // error cases: commas in parameter values
    $parts = explode(",", $header);
    $out = array();
    foreach ($parts as $param) {
      $param = ltrim($param);
      // skip the "realm" param, nobody ever uses it anyway
      if (substr($param, 0, 5) != "oauth") continue;
      $param_parts = explode("=", $param);
      // rawurldecode() used because urldecode() will turn a "+" in the
      // value into a space
      $out[$param_parts[0]] = rawurldecode(substr($param_parts[1], 1, - 1));
    }
    return $out;
  }

  /**
   * helper to try to sort out headers for people who aren't running apache
   */
  private static function get_headers() {
    if (function_exists('apache_request_headers')) {
      // we need this to get the actual Authorization: header
      // because apache tends to tell us it doesn't exist
      return apache_request_headers();
    }
    // otherwise we don't have apache and are just going to have to hope
    // that $_SERVER actually contains what we need
    $out = array();
    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) == "HTTP_") {
        // this is chaos, basically it is just there to capitalize the first
        // letter of every word that is not an initial HTTP and strip HTTP
        // code from przemek
        $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
        $out[$key] = $value;
      }
    }
    return $out;
  }
}

class OAuthUtil {

  public static $AUTH_SCHEME = "OAuth";
  private static $AUTHORIZATION = "\ *[a-zA-Z0-9*]\ +(.*)";
  private static $NVP = "(\\S*)\\s*\\=\\s*\"([^\"]*)\"";

  public static function getPostBodyString(Array $params) {
    $result = '';
    $first = true;
    foreach ($params as $key => $val) {
      if ($first) {
        $first = false;
      } else {
        $result .= '&';
      }
      $result .= OAuthUtil::urlencodeRFC3986($key) . "=" . OAuthUtil::urlencodeRFC3986($val);
    }
    return $result;
  }

  public static function urlencodeRFC3986($string) {
    return str_replace('%7E', '~', rawurlencode($string));
  }

  public static function urldecodeRFC3986($string) {
    return rawurldecode($string);
  }

  /** Return true if the given Content-Type header means FORM_ENCODED. */
  public static function isFormEncoded($contentType) {
    if (! isset($contentType)) {
      return false;
    }
    $semi = strpos($contentType, ";");
    if ($semi >= 0) {
      $contentType = substr($contentType, 0, $semi);
    }
    return strtolower(OAuth::$FORM_ENCODED) == strtolower(trim($contentType));
  }

  public static function addParameters($url, $oauthParams) {
    $url .= strchr($url, '?') === false ? '?' : '&';
    foreach ($oauthParams as $key => $value) {
      $url .= "$key=$value&";
    }
    return $url;
  }

  public static function decodeForm($form) {
    $parameters = array();
    $explodedForm = explode("&", $form);
    foreach ($explodedForm as $params) {
      $value = explode("=", $params);
      if (! empty($value[0]) && ! empty($value[1])) {
        $parameters[OAuthUtil::urldecodeRFC3986($value[0])] = OAuthUtil::urldecodeRFC3986($value[1]);
      }
    }
    return $parameters;
  }

  /**
   * Parse the parameters from an OAuth Authorization or WWW-Authenticate
   * header. The realm is included as a parameter. If the given header doesn't
   * start with "OAuth ", return an empty list.
   */
  public static function decodeAuthorization($authorization) {
    $into = array();
    if ($authorization != null) {
      $m = ereg(self::$AUTHORIZATION, $authorization);
      if ($m !== false) {
        if (strpos($authorization, OAuthUtil::$AUTH_SCHEME) == 0) {
          $authorization = str_replace("OAuth ", "", $authorization);
          $authParams = explode(", ", $authorization);
          foreach ($authParams as $params) {
            $m = ereg(OAuthUtil::$NVP, $params);
            if ($m == 1) {
              $keyValue = explode("=", $params);
              $name = OAuthUtil::urlencodeRFC3986($keyValue[0]);
              $value = OAuthUtil::urlencodeRFC3986(str_replace("\"", "", $keyValue[1]));
              $into[$name] = $value;
            }
          }
        }
      }
    }
    return $into;
  }
}
