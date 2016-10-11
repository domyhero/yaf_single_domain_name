<?php
/**
 * IP转换地址[定位城市]。
 * @author winerQin
 * @date 2016-10-11
 */

namespace Juhe;

use common\YCore;
class IpToAddr extends Base {

    /**
     * 查看历史事件列表。
     * @param string $ip IP地址。
     * @return string
     */
    public static function query($ip) {
        $url = "http://apis.juhe.cn/ip/ip2addr";
        $params = [
            'ip'    => $ip,
            'key'   => '00b329c76bebaf374426cc72371fcbc2',
            'dtype' => 'json'
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                // echo $result['error_code'].":".$result['reason'];
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }
}