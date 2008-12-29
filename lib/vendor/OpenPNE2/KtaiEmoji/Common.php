<?php

class OpenPNE_KtaiEmoji_Common
{
    var $carrier_id;
    var $value_list;

    /**
     * constructor
     */
    function OpenPNE_KtaiEmoji_Common()
    {
        $this->carrier_id = '';
        $this->value_list = array();
    }

    function &getInstance()
    {
        static $singleton;
        if (empty($singleton)) {
            $singleton = new OpenPNE_KtaiEmoji_Common();
        }
        return $singleton;
    }

    /**
     * 与えられた絵文字からその絵文字の絵文字コードを取得する
     * 絵文字が存在しない場合はfalseを返す
     */
    function get_emoji_code4emoji($emoji)
    {
        $code_id = $this->get_emoji_code_id4emoji($emoji);
        if ($code_id !== false) {
            $code = $this->carrier_id . ':' . $code_id;
        }
        return $code;
    }

    /**
     * 与えられた絵文字からその絵文字の絵文字コードのIDを取得する
     * 絵文字が存在しない場合はfalseを返す
     */
    function get_emoji_code_id4emoji($emoji)
    {
        return array_search($emoji, $this->value_list);
    }

    /**
     * 与えられた絵文字コードのIDに対応する絵文字を取得する
     */
    function get_emoji4emoji_code_id($emoji_code_id)
    {
        return $this->value_list[$emoji_code_id];
    }
}

?>
