<?php
/**
 * 成语。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Chengyu extends Base {

    /**
     * 根据汉字查询字典。
     * @param string $word 成语。
     * @return string
     */
    public static function query($word) {
        if (mb_strlen($word, 'UTF-8') === 0) {
            YCore::exception(-1, '必须输入一个汉字');
        }
        $url = "http://v.juhe.cn/chengyu/query";
        $params = [
            'word'  => $word,
            'dtype' => 'json',
            'key'   => '784edd9f93e295cd8a4b0cbd79d66132'
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }

}