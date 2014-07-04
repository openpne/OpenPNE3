<?php
/**
 * @copyright 2005-2008 OpenPNE Project
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 */

require_once 'Services/AmazonECS4.php';

/**
 * OpenPNEでAmazonECSを利用するためのクラス
 *
 * @package OpenPNE
 * @author Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE_Amazon extends Services_AmazonECS4
{
    /**
     * Category(AmazonECS3)とSearchIndexの変換テーブル
     *
     * @var array
     */
    var $_categoryToSearchIndex = array(
        'books-jp' => 'Books',
        'books-us' => 'ForeignBooks',
        'music-jp' => 'Music',
        'classical-jp' => 'Classical',
        'dvd-jp' => 'DVD',
        'videogames-jp' => 'VideoGames',
        'software-jp' => 'Software',
        'electronics-jp' => 'Electronics',
        'kitchen-jp' => 'Kitchen',
        'toys-jp' => 'Toys',
        'sporting-goods-jp' => 'SportingGoods',
        'hpc-jp' => 'HealthPersonalCare',
    );

    function ItemSearch($search_index, $options = array())
    {
        // SearchIndex ではなく Category が渡された
        if (array_key_exists($search_index, $this->_categoryToSearchIndex)) {
            $search_index = $this->_categoryToSearchIndex[$search_index];
        }

        $result =  parent::ItemSearch($search_index, $options);
        return $result;
    }
}

?>
