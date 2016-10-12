<?php
/**
 * 来电显示。
 * -- 查询手机/固话号码归属地，是否诈骗、营销、广告电话。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class CallDisplay extends Base {

    /**
     * 查询手机/固话号码归属地，是否诈骗、营销、广告电话。
     * @param string $tel 手机号码/坐机号码。
     * @return string
     */
    public static function query($tel) {
        $url = "http://op.juhe.cn/onebox/phone/query";
        $params = [
            'tel'   => $tel,
            'dtype' => 'json',
            'key'   => '0680363aefe9ab7086430378e0af00ed'
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