<?php
/**
 * 星座运势。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Constellation extends Base {

    /**
     * 获取星座运势。
     * @param string $cons_name 星座名称，如:白羊座
     * @param string $type 运势类型：today,tomorrow,week,nextweek,month,year
     * @return mixed
     */
    public static function query($cons_name, $type) {
        $url = "http://web.juhe.cn:8080/constellation/getAll";
        $params = [
            'consName' => $cons_name,
            'type'     => $type,
            'key'      => '81ea126cab4f5edec3d1246bf8fe56c5'
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