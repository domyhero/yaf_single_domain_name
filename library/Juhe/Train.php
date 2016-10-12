<?php
/**
 * 火车时刻表。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Train extends Base {

    /**
     * 站到站查询。
     * @param string $cityname 	要查询的城市，如：温州、上海、北京，需要utf8 urlencode。
     * @return string
     */
    public static function query($from, $to) {
        $url = "http://op.juhe.cn/onebox/train/query_ab";
        $params = [
            'from' => $from,
            'to'   => $to,
            'key'  => '4ae8687a4afd7ab77ae629e1c42eda20',
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        print_r($result);
        exit;
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result']['list'];
            } else {
                // echo $result['error_code'].":".$result['reason'];
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }
}