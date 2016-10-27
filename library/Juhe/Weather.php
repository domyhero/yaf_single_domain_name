<?php
/**
 * 天气。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Weather extends Base {

    /**
     * 天气查询。
     * @param string $cityname 	要查询的城市，如：温州、上海、北京，需要utf8 urlencode。
     * @return string
     */
    public static function query($cityname) {
        $time = $_SERVER['REQUEST_TIME'];
        $url = "http://op.juhe.cn/onebox/weather/query";
        $params = [
            'cityname' => $cityname,
            'dtype'    => 'json',
            'key'      => 'eb93607b12cad2889fe50dbcf650b1a0'
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