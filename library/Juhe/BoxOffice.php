<?php
/**
 * 票房数据。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class BoxOffice extends Base {

    /**
     * 获取最新标记榜。
     * @param string $area 票房榜的区域,CN-内地，US-北美，HK-香港
     * @return array
     */
    public static function rank($area) {
        $url = "http://v.juhe.cn/boxoffice/rank";
        $params = [
            'area'  => $area,
            'dtype' => 'json',
            'key'   => '9fec3844af0ce881205fb466120c45ed',
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