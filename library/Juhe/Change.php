<?php
/**
 * 简/繁/火星字体转换。
 * @author winerQin
 * @date 2016-10-11
 */

namespace Juhe;

use common\YCore;
class Change extends Base {

    /**
     * 调用聚合接口完成转换。
     * @param string $str 转换前的文字。
     * @param int $juhe_type 转换类型。0转简体、1转繁体、2转火星文
     * @return string
     */
    public static function requestJuheApi($str, $juhe_type) {
        $url = "http://japi.juhe.cn/charconvert/change.from";
        $params = [
            'ip'   => $_SERVER['SERVER_ADDR'],              // 需要查询的IP地址或域名
            'text' => $str,                                 // 需要转换字符串
            'type' => $juhe_type,                           // 需要转换成的类型。0：简体 1：繁体  2：火星文
            'key'  => 'bcfc17e7a4ca1a1abebcaf7dd8691c3b',   // 应用APPKEY(应用详细页查询)
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['outstr'];
            } else {
                // echo $result['error_code'].":".$result['reason'];
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }
}