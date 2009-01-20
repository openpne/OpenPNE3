<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
* Implementation of a developers backend for accessing Amazon's retail and
* assosciate services.
*
* PHP versions 4 and 5
*
* LICENSE: Copyright 2004 John Downey. All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* o Redistributions of source code must retain the above copyright notice, this
*   list of conditions and the following disclaimer.
* o Redistributions in binary form must reproduce the above copyright notice,
*   this list of conditions and the following disclaimer in the documentation
*   and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE FREEBSD PROJECT "AS IS" AND ANY EXPRESS OR
* IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
* MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO
* EVENT SHALL THE FREEBSD PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
* INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
* OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
* NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
* EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* The views and conclusions contained in the software and documentation are
* those of the authors and should not be interpreted as representing official
* policies, either expressed or implied, of The PEAR Group.
*
* @category  Web Services
* @package   Services_Amazon
* @author    John Downey <jdowney@gmail.com>
* @author    Tatsuya Tsuruoka <ttsuruoka@p4life.jp>
* @copyright 2004 John Downey
* @license   http://www.freebsd.org/copyright/freebsd-license.html 2 Clause BSD License
* @version   CVS: $Id: AmazonECS4.php,v 1.4 2006/08/04 10:31:00 ttsuruoka Exp $
* @link      http://pear.php.net/package/Services_Amazon/
* @filesource
*/

/**
* Uses PEAR class for error management
*/
require_once 'PEAR.php';

/**
* Uses HTTP_Request class to send and receive data from Amazon web servers
*/
require_once 'HTTP/Request.php';

/**
* Uses XML_Unserializer class to parse data received from Amazon
*/
require_once 'XML/Unserializer.php';

/**
* A default base URL that is specific to the locale
*
* - Amazon.com (US)
*   http://webservices.amazon.com/onca/xml?Service=AWSECommerceService
* - Amazon.co.uk (UK)
*   http://webservices.amazon.co.uk/onca/xml?Service=AWSECommerceService
* - Amazon.de (DE)
*   http://webservices.amazon.de/onca/xml?Service=AWSECommerceService
* - Amazon.co.jp (JP)
*   http://webservices.amazon.co.jp/onca/xml?Service=AWSECommerceService
* - Amazon.fr (FR)
*   http://webservices.amazon.fr/onca/xml?Service=AWSECommerceService
* - Amazon.ca (CA)
*   http://webservices.amazon.ca/onca/xml?Service=AWSECommerceService
*/
if (!defined('SERVICES_AMAZON_BASEURL')) {
    define('SERVICES_AMAZON_BASEURL', 'http://webservices.amazon.com/onca/xml?Service=AWSECommerceService');
}
/**
* A service version
*
* Use this to retrieve a particular version of the Amazon ECS.
*/
if (!defined('SERVICES_AMAZON_ECSVERSION')) {
    define('SERVICES_AMAZON_ECSVERSION', '2005-10-05');
}

/**
* Class for accessing and retrieving information from Amazon's Web Services
*
* @package Services_Amazon
* @author  John Downey <jdowney@gmail.com>
* @author  Tatsuya Tsuruoka <ttsuruoka@p4life.jp>
* @access  public
* @version Release: 0.7.1
* @uses    PEAR
* @uses    HTTP_Request
* @uses    XML_Unserializer
*/
class Services_AmazonECS4
{
    /**
    * An Amazon AccessKey/Subscription ID used when quering Amazon servers
    *
    * @access private
    * @var    string
    */
    var $_keyid = null;

    /**
    * An Amazon Associate ID used in the URL's so a commision may be payed
    *
    * @access private
    * @var    string
    */
    var $_associd = null;

    /**
    * A base URL used to build the query for the Amazon servers
    *
    * @access private
    * @var    string
    */
    var $_baseurl = SERVICES_AMAZON_BASEURL;

    /**
    * A service version
    *
    * @access private
    * @var    string
    */
    var $_version = SERVICES_AMAZON_ECSVERSION;

    /**
    * The time that the Amazon took to process the request
    * 
    * @access private
    * @var    string
    */
    var $_processing_time = null;

    /**
    * The last URL accessed to the Amazon (for debugging)
    *
    * @access private
    * @var    string
    */
    var $_lasturl = null;

    /**
    * The raw result returned from the request
    *
    * @access private
    * @var    string
    */
    var $_raw_result = null;

    /**
    * The cache object
    *
    * @access private
    * @var    object
    */
    var $_cache = null;

    /**
    * The cache expire time
    *
    * Defaults to one hour.
    *
    * @access private
    * @var    integer
    */
    var $_cache_expire = 3600;

    /**
    * Proxy server
    *
    * @access private
    * @var    string
    */
    var $_proxy_host = null;

    /**
    * Proxy port
    *
    * @access private
    * @var    integer
    */
    var $_proxy_port = null;

    /**
    * Proxy username
    *
    * @access private
    * @var    string
    */
    var $_proxy_user = null;

    /**
    * Proxy password
    *
    * @access private
    * @var    string
    */
    var $_proxy_pass = null;

    /**
    * Errors
    *
    * @access private
    * @var    array
    */
    var $_errors = array();

    /**
    * Constructor
    *
    * @access public
    * @param  string $keyid An Amazon Access Key ID used when quering Amazon servers
    * @param  string $associd An Amazon Associate ID used in the URL's so a commision may be payed
    * @see    setAccessKeyID
    * @see    setAssociateID
    * @see    setBaseUrl
    * @see    setVersion
    */
    function Services_AmazonECS4($keyid, $associd = null)
    {
        $this->_keyid = $keyid;
        $this->_associd = $associd;
    }

    /**
    * Retrieves the current version of this classes API
    *
    * @access public
    * @static
    * @return string The API version
    */
    function getApiVersion()
    {
        return '0.7.1';
    }

    /**
    * Sets an Access Key ID
    *
    * @access public
    * @param  string $subid An Access Key ID
    * @return void
    */
    function setAccessKeyID($keyid)
    {
        $this->_keyid = $keyid;
    }

    /**
    * Sets a Subscription ID (for backward compatibility)
    *
    * @access public
    * @param  string $subid A Subscription ID
    * @return void
    */
    function setSubscriptionID($subid)
    {
        $this->_keyid = $subid;
    }

    /**
    * Sets an Associate ID
    *
    * @access public
    * @param  string $associd An Associate ID
    * @return void
    */
    function setAssociateID($associd)
    {
        $this->_associd = $associd;
    }

    /**
    * Sets the base URL
    *
    * @access public
    * @param  string $url The base url
    * @return void
    */
    function setBaseUrl($url)
    {
        $this->_baseurl = $url;
    }

    /**
    * Sets the locale passed when making a query to Amazon
    *
    * Currently US, UK, DE, JP, FR, and CA are supported
    *
    * @access public
    * @param  string $locale The new locale to use
    * @return mixed A PEAR_Error on error, a true on success
    */
    function setLocale($locale)
    {
        $urls = array(
            'US' => 'http://webservices.amazon.com/onca/xml?Service=AWSECommerceService',
            'UK' => 'http://webservices.amazon.co.uk/onca/xml?Service=AWSECommerceService',
            'DE' => 'http://webservices.amazon.de/onca/xml?Service=AWSECommerceService',
            'JP' => 'http://webservices.amazon.co.jp/onca/xml?Service=AWSECommerceService',
            'FR' => 'http://webservices.amazon.fr/onca/xml?Service=AWSECommerceService',
            'CA' => 'http://webservices.amazon.ca/onca/xml?Service=AWSECommerceService',
        );
        $locale = strtoupper($locale);
        if (empty($urls[$locale])) {
            return PEAR::raiseError('Invalid locale');
        }
        $this->setBaseUrl($urls[$locale]);
        return true;
    }

    /**
    * Sets a version
    *
    * @access public
    * @param  string $version A service version
    * @return void
    */
    function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
    * Enables caching the data
    *
    * Requires Cache to be installed.
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $amazon->setCache('file', array('cache_dir' => 'cache/'));
    * $amazon->setCacheExpire(86400); // 86400 seconds = 24 hours
    * $result = $amazon->BrowseNodeLookup('283155');
    * ?>
    * </code>
    *
    * @access public
    * @param  string $container Name of container class
    * @param  array $container_options Array with container class options
    * @return mixed A PEAR_Error on error, a true on success
    * @see    setCacheExpire()
    */
    function setCache($container = 'file', $container_options = array())
    {
        if(!class_exists('Cache')){
            @include_once 'Cache.php';
        }
        
        @$cache = new Cache($container, $container_options);
        
        if (is_object($cache)) {
            $this->_cache = $cache;
        } else {
            $this->_cache = null;
            return PEAR::raiseError('Cache init failed');
        }

        return true;
    }
    
    /**
    * Sets cache expire time
    * 
    * Amazon dictates that any prices that are displayed that may be over an
    * hour old should be accompanied by some sort of timestamp. You can get
    * around that by expiring any queries that use the time in an hour (3600
    * seconds).
    *
    * @access public
    * @param  integer $secs Expire time in seconds
    * @return void
    * @see    setCache()
    */
    function setCacheExpire($secs)
    {
        $this->_cache_expire = $secs;
    }

    /**
    * Sets a proxy
    *
    * @access public
    * @param string $host Proxy host
    * @param int $port Proxy port
    * @param string $user Proxy username
    * @param string $pass Proxy password
    */
    function setProxy($host, $port = 8080, $user = null, $pass = null)
    {
        $this->_proxy_host = $host;
        $this->_proxy_port = $port;
        $this->_proxy_user = $user;
        $this->_proxy_pass = $pass;
    }

    /**
    * Retrieves all error codes and messages
    *
    * <code>
    * if (PEAR::isError($result)) {
    *     foreach ($amazon->getErrors() as $error) {
    *         echo $error['Code'];
    *         echo $error['Message'];
    *     }
    * }
    * </code>
    *
    * @access public
    * @return array All errors
    */
    function getErrors()
    {
        return $this->_errors;
    }
    
    /**
    * Retrieves the error code and message
    *
    * <code>
    * if (PEAR::isError($result)) {
    *     $error = $amazon->getError();
    *     echo $error['Code'];
    *     echo $error['Message'];
    * }
    * </code>
    *
    * @access public
    * @return array All errors
    */
    function getError()
    {
        return current($this->_errors);
    }

    /**
    * Retrieves the processing time
    *
    * @access public
    * @return string Processing time
    */
    function getProcessingTime()
    {
        return $this->_processing_time;
    }

    /**
    * Retrieves the last URL accessed to the Amazon (for debugging)
    *
    * @access public
    * @return string The Last URL
    */
    function getLastUrl()
    {
        return $this->_lasturl;
    }

    /**
     * Retrieves the raw result
     *
     * @access public
     * @return string The raw result
     */
    function getRawResult()
    {
        return $this->_raw_result;
    }

    /**
    * Retrieves information about a browse node
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $result = $amazon->BrowseNodeLookup('283155'); // 283155='Books'
    * ?>
    * </code>
    *
    * @access public
    * @param  string $browsenode_id The browse node ID
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    */
    function BrowseNodeLookup($browsenode_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'BrowseNodeLookup';
        $params['BrowseNodeId'] = $browsenode_id;
        return $this->_sendRequest($params);
    }

    /**
    * Adds items to an existing remote shopping cart
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $item = array('ASIN' => 'aaaaaaaaaa', 'Quantity' => 1);
    * // $item = array(array('ASIN' => 'aaaaaaaaaa', 'Quantity' => 1),
    * //               array('OfferListingId' => 'bbbbbbbbbb', 'Quantity' => 10),
    * //               array('ASIN' => 'cccccccccc', 'Quantity' => 20));
    * $result = $amazon->CartAdd('[Cart ID]', '[HMAC]', $item);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $cart_id A unique identifier for a cart
    * @param  string $hmac A unique security token
    * @param  array $item Products and the quantities
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CartClear(), CartCreate(), CartModify()
    */
    function CartAdd($cart_id, $hmac, $item, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CartAdd';
        $params['CartId'] = $cart_id;
        $params['HMAC'] = $hmac;
        $params += $this->_assembleItemParameter($item);
        return $this->_sendRequest($params);
    }

    /**
    * Removes all the contents of a remote shopping cart
    *
    * @access public
    * @param  string $cart_id A unique identifier for a cart
    * @param  string $hmac A unique security token
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CartAdd(), CartCreate(), CartGet(), CartModify()
    */
    function CartClear($cart_id, $hmac, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CartClear';
        $params['CartId'] = $cart_id;
        $params['HMAC'] = $hmac;
        return $this->_sendRequest($params);
    }

    /**
    * Creates a new remote shopping cart
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $item = array('ASIN' => 'aaaaaaaaaa', 'Quantity' => 1);
    * // $item = array(array('ASIN' => 'aaaaaaaaaa', 'Quantity' => 1),
    * //               array('ASIN' => 'cccccccccc', 'Quantity' => 20));
    * $result = $amazon->CartCreate($item);
    * ?>
    * </code>
    *
    * @access public
    * @param  array $item Products and the quantities
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CartAdd(), CartClear(), CartGet(), CartModify()
    */
    function CartCreate($item, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CartCreate';
        $params += $this->_assembleItemParameter($item);
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves the contents of a remote shopping cart
    *
    * @access public
    * @param  string $cart_id A unique identifier for a cart
    * @param  string $hmac A unique security token
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CartAdd(), CartClear(), CartCreate(), CartModify()
    */
    function CartGet($cart_id, $hmac, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CartGet';
        $params['CartId'] = $cart_id;
        $params['HMAC'] = $hmac;
        return $this->_sendRequest($params);
    }

    /**
    * Modifies the quantity of items in a cart and changes cart items to saved items
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $item = array('CartItemId' => 'aaaaaaaaaa', 'Quantity' => 1);
    * // $item = array('CartItemId' => 'aaaaaaaaaa', 'Action' => 'SaveForLater');
    * // $item = array(array('CartItemId' => 'aaaaaaaaaa', 'Quantity' => 1),
    * //               array('CartItemId' => 'cccccccccc', 'Quantity' => 20));
    * $result = $amazon->CartModify('[Cart ID]', '[HMAC]', $item);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $cart_id A unique identifier for a cart
    * @param  string $hmac A unique security token
    * @param  array $item The CartItemId and the quantities or the Action
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CartAdd(), CartClear(), CartCreate(), CartGet()
    */
    function CartModify($cart_id, $hmac, $item, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CartModify';
        $params['CartId'] = $cart_id;
        $params['HMAC'] = $hmac;
        $params += $this->_assembleItemParameter($item);
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves publicly available content written by specific Amazon customers
    *
    * @access public
    * @param  string $customer_id A customer ID
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CustomerContentSearch()
    */
    function CustomerContentLookup($customer_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CustomerContentLookup';
        $params['CustomerId'] = $customer_id;
        return $this->_sendRequest($params);
    }

    /**
    * Searches for Amazon customers by name or email address
    *
    * @access public
    * @param  array $customer A customer's name or its email
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    CustomerContentLookup()
    */
    function CustomerContentSearch($customer = null, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'CustomerContentSearch';
        $params += $customer;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves information about operations and response groups
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $result = $amazon->Help('Operation', 'ItemLookup');
    * ?>
    * </code>
    *
    * @access public
    * @param  string $help_type The type of information
    * @param  string $about The name of an operation or a response group
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    */
    function Help($help_type, $about, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'Help';
        $params['HelpType'] = $help_type;
        $params['About'] = $about;
        return $this->_sendRequest($params);
    }
        
    /**
    * Retrieves information for products
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $options = array();
    * $options['ResponseGroup'] = 'Large';
    * $result = $amazon->ItemLookup('[ASIN(s)]', $options);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $item_id Product IDs
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    ItemSearch()
    */
    function ItemLookup($item_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'ItemLookup';
        if (is_array($item_id)) {
            $item_id = implode(',', $item_id);
        }
        $params['ItemId'] = $item_id;
        return $this->_sendRequest($params);
    }

    /**
    * Searches for products
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $options = array();
    * $options['Keywords'] = 'sushi';
    * $options['Sort'] = 'salesrank';
    * $options['ResponseGroup'] = 'ItemIds,ItemAttributes,Images';
    * $result = $amazon->ItemSearch('Books', $options);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $search_index A search index
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    ItemLookup()
    */
    function ItemSearch($search_index, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'ItemSearch';
        $params['SearchIndex'] = $search_index;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves products in a specific list
    *
    * @access public
    * @param  string $list_type The type of list
    * @param  string $list_id A list ID
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    ListSearch()
    */
    function ListLookup($list_type, $list_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'ListLookup';
        $params['ListType'] = $list_type;
        $params['ListId'] = $list_id;
        return $this->_sendRequest($params);
    }

    /**
    * Searches for a wish list, baby registry, or wedding registry
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $keywords = array('Name' => 'hoge');
    * $result = $amazon->ListSearch('WishList', $keywords);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $list_type The type of list
    * @param  array $keywords Parameters to search for
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    ListLookup()
    */
    function ListSearch($list_type, $keywords, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'ListSearch';
        $params['ListType'] = $list_type;
        $params += $keywords;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves information about Amazon zShops and Marketplace products
    *
    * @access public
    * @param  string $id_type The type of ID
    * @param  string $id The exchange ID or the listing ID
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    SellerListingSearch()
    */
    function SellerListingLookup($id_type, $id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'SellerListingLookup';
        $params['IdType'] = $id_type;
        $params['Id'] = $id;
        return $this->_sendRequest($params);
    }

    /**
    * Searches for Amazon zShops and Marketplace products
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $keywords = array('Keywords' => 'pizza');
    * $result = $amazon->SellerListingSearch('zShops', $keywords);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $search_index The type of seller listings
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    * @see    SellerListingLookup()
    */
    function SellerListingSearch($search_index, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'SellerListingSearch';
        $params['SearchIndex'] = $search_index;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves information about specific sellers
    *
    * @access public
    * @param  string $seller_id IDs for Amazon sellers
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    */
    function SellerLookup($seller_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'SellerLookup';
        $params['SellerId'] = $seller_id;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves products that are similar to Amazon products
    *
    * @access public
    * @param  string $item_id Product IDs
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    */
    function SimilarityLookup($item_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'SimilarityLookup';
        if (is_array($item_id)) {
            $item_id = implode(',', $item_id);
        }
        $params['ItemId'] = $item_id;
        return $this->_sendRequest($params);
    }

    /**
    * Retrieves information about the status of financial transactions
    *
    * @access public
    * @param  string $transaction_id Transaction IDs
    * @param  array $options The optional parameters
    * @return array The array of information returned by the query
    */
    function TransactionLookup($transaction_id, $options = array())
    {
        $params = $options;
        $params['Operation'] = 'SimilarityLookup';
        $params['TransactionId'] = $transaction_id;
        return $this->_sendRequest($params);
    }

    /**
    * Combines requests for the same operation into a single request
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $shared = array('SearchIndex' => 'Books',
    *                 'Keywords' => 'php');
    * $params1 = array('ItemPage' => '1');
    * $params2 = array('ItemPage' => '2');
    * $result = $amazon->doBatch('ItemSearch', $shared, $params1, $params2);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $operation The operation
    * @param  array $shared Shared parameters
    * @param  array $params1 The parameters specific to the first request
    * @param  array $params2 The parameters specific to the second request
    * @return array The array of information returned by the query
    */
    function doBatch($operation, $shared, $params1 = array(), $params2 = array())
    {
        $params = array();
        $params['Operation'] = $operation;
        foreach ($shared as $k => $v) {
            $params[$operation . '.Shared.' . $k] = $v;
        }
        foreach ($params1 as $k => $v) {
            $params[$operation . '.1.' . $k] = $v;
        }
        foreach ($params2 as $k => $v) {
            $params[$operation . '.2.' . $k] = $v;
        }
        return $this->_sendRequest($params);
    }

    /**
    * Combines the different operations into a single request
    *
    * Example:
    * <code>
    * <?php
    * $amazon = new Services_AmazonECS4('[your Access Key ID here]');
    * $params1 = array('SearchIndex' => 'Books',
    *                  'Title' => 'sushi');
    * $params2 = array('Keywords' => 'tempura');
    * $result = $amazon->doMultiOperation('ItemSearch', $params1,
    *                                     'SellerListingSearch', $params2);
    * ?>
    * </code>
    *
    * @access public
    * @param  string $operation1 The first operation
    * @param  array $params1 The parameters specific to the first request
    * @param  string $operation2 The second operation
    * @param  array $params2 The parameters specific to the second request
    * @return array The array of information returned by the query
    */
    function doMultiOperation($operation1, $params1, $operation2, $params2)
    {
        $params = array();
        $params['Operation'] = $operation1 . ',' . $operation2;
        foreach ($params1 as $k => $v) {
            $params[$operation1 . '.1.' . $k] = $v;
        }
        foreach ($params2 as $k => $v) {
            $params[$operation2 . '.1.' . $k] = $v;
        }
        return $this->_sendRequest($params);
    }

    /**
    * Assembles the Item parameters
    *
    * @access private
    * @param  array $items The items
    * @return array The item parameters
    */
    function _assembleItemParameter($items)
    {
        $params = array();
        if (!is_array(current($items))) {
            $items = array(0 => $items);
        }
        $i = 1;
        foreach ($items as $item) {
            foreach ($item as $k => $v) {
                $params['Item.' . $i . '.' . $k] = $v;
            }
            $i++;
        }
        return $params;
    }

    /**
    * Ignores the caching of specific operations
    *
    * @access private
    * @param  string $operation The operation
    * @return bool Returns true if the operation isn't cached, false otherwise
    */
    function _ignoreCache($operation)
    {
        $ignore = array('CartAdd', 'CartClear', 'CartGet', 'CartModify', 'TransactionLookup');
        if (!strchr($operation, ',')) {
            return in_array($operation, $ignore);
        }
        $operations = explode(',', $operation);
        foreach ($operations as $v) {
            if (in_array($v, $ignore)) {
                return true;
            }
        }
        return false;
    }

    /**
    * Generates ID used as cache identifier
    *
    * @access private
    * @param  array $params
    * @return string Cache ID
    */
    function _generateCacheId($params)
    {
        unset($params['AWSAccessKeyId']);
        unset($params['AssociateTag']);
        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k . $v;
        }
        return md5($str);
    }

    /**
    * Builds a URL
    *
    * @access private
    * @param array $params
    * @return string URL
    */
    function _buildUrl($params)
    {
        $params['AWSAccessKeyId'] = $this->_keyid;
        $params['AssociateTag'] = $this->_associd;
        $params['Version'] = $this->_version;
        $url = $this->_baseurl;
        foreach ($params as $k => $v) {
            $url .= '&' . $k . '=' . urlencode($v);
        }
        return $url;
    }

    /**
    * Sends a request
    *
    * @access private
    * @param string $url
    * @return string The response
    */
    function _sendHttpRequest($url)
    {
        $http = &new HTTP_Request($url);
        $http->setHttpVer('1.0');
        $http->addHeader('User-Agent', 'Services_AmazonECS4/' . $this->getApiVersion());
        if ($this->_proxy_host) {
            $http->setProxy($this->_proxy_host, $this->_proxy_port, $this->_proxy_user, $this->_proxy_pass);
        }

        $result = $http->sendRequest();
        if (PEAR::isError($result)) {
            return PEAR::raiseError('HTTP_Request::sendRequest failed: ' . $result->message);
        }

        if ($http->getResponseCode() != 200){
            return PEAR::raiseError('Amazon returned invalid HTTP response code ' . $http->getResponseCode());
        }
        return $http->getResponseBody();
    }

    /**
    * Parses raw XML result
    *
    * @access private
    * @param string $raw_result
    * @return string The contents
    */
    function _parseRawResult($raw_result)
    {
        $xml = &new XML_Unserializer();
        $xml->setOption(XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE, true);
        $xml->setOption(XML_UNSERIALIZER_OPTION_FORCE_ENUM,
                        array('Item', 'Review', 'EditorialReview',
                              'Parameter', 'Author', 'Creator', 'ResponseGroup', 'Error'));
        $xml->unserialize($raw_result, false);
        $data = $xml->getUnserializedData();
        if (PEAR::isError($data)) {
            return $data;
        }

        if (isset($data['Error'])) {
            $this->_errors = $data['Error'];
            return PEAR::raiseError(implode(':', $this->getError()));
        }

        if (isset($data['OperationRequest']['RequestProcessingTime'])) {
            $this->_processing_time = $data['OperationRequest']['RequestProcessingTime'];
        }

        if (isset($data['OperationRequest']['Errors'])) {
            $this->_errors = $data['OperationRequest']['Errors']['Error'];
            return PEAR::raiseError(implode(':', $this->getError()));
        }

        // Get values of the second level content elements
        unset($data['xmlns']);
        unset($data['OperationRequest']);
        $contents = array();
        $keys = array_keys($data);
        foreach ($keys as $v) {
            if (strstr($v, 'Response')) {
                $data[$v] = current($data[$v]);
                $contents[$v] = $data[$v];
            } else {
                $contents = $data[$v];
            }
            $result = $this->_checkContentError($data[$v]);
            if (PEAR::isError($result)) {
                return $result;
            }
        }
        return $contents;
    }

    /**
    * Checks error codes at the content elements
    *
    * @access private
    * @param  array $content Values of the content elements
    * @return array mixed A PEAR_Error on error, a true on success
    * @see    _parseRawResult
    */
    function _checkContentError($content)
    {
        if (isset($content['Request']['Errors'])) {
            $this->_errors = $content['Request']['Errors']['Error'];
            return PEAR::raiseError(implode(':', $this->getError()));
        } else if (isset($content[0])) {
            $errors = array();
            foreach ($content as $v) {
                if (isset($v['Request']['Errors']['Error'])) {
                    $errors = array_merge($errors, $v['Request']['Errors']['Error']);
                }
            }
            if (!empty($errors)) {
                $this->_errors = $errors;
                return PEAR::raiseError(implode(':', $this->getError()));
            }
        }
        return true;
    }

    /**
    * Sends the request to Amazon
    *
    * @access private
    * @param  array $params The array of request parameters
    * @return array The array of information returned by the query
    */
    function _sendRequest($params)
    {
        $this->_errors = array();

        if (is_null($this->_keyid)) {
            return PEAR::raiseError('Access Key ID have not been set');
        }

        $url = $this->_buildUrl($params);
        $this->_lasturl = $url;
        if (PEAR::isError($url)) {
            return $url;
        }

        // Return cached data if available
        $cache_id = false;
        if (isset($this->_cache) && !$this->_ignoreCache($params['Operation'])) {
            $cache_id = $this->_generateCacheId($params);
            $cache = $this->_cache->get($cache_id);
            if (!is_null($cache)) {
                $this->_processing_time = 0;
                return $cache;
            }
        }

        $result = $this->_sendHttpRequest($url);
        $this->_raw_result = $result;
        if (PEAR::isError($result)) {
            return $result;
        }

        $contents = $this->_parseRawResult($result);
        if (PEAR::isError($contents)) {
            return $contents;
        }

        if ($cache_id) {
            $this->_cache->save($cache_id, $contents, $this->_cache_expire);
        }

        return $contents;
    }

}
?>
