<?php
/**
 * 微信精选。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class WeChatSift extends Base {

    /**
     * 微信精选列表。
     * @param int $pno 当前页数，默认1。
     * @param int $ps 每页返回条数，最大100，默认20。
     * @return array
     */
    public static function query($pno, $ps) {
        $url = "http://v.juhe.cn/weixin/query";
        $params = [
            'pno'   => $pno,
            'ps'    => $ps,
            'dtype' => 'json',
            'key'   => '8a3b4581f251c806325614f1fc4eac24'
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