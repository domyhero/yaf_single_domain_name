<?php
/**
 * 新华字典。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class XinHuaDict extends Base {

    /**
     * 根据汉字查询字典。
     * @param string $word 人。只能单字。
     * @return string
     */
    public static function query($word) {
        if (mb_strlen($word, 'UTF-8') != 1) {
            YCore::exception(-1, '只能输入一个汉字');
        }
        $url = "http://v.juhe.cn/xhzd/query";
        $params = [
            'word'  => $word,
            'dtype' => 'json',
            'key'   => '869c92f4209ee349c90f2041a8093af7'
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