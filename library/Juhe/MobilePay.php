<?php
/**
 * 手机号码充值。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class MobilePay extends Base {

    /**
     * 根据手机号和面值查询商品信息。
     * @param string $phoneno 手机号码。
     * @param string $cardnum 充值金额。
     * @return string
     */
    public static function query($phoneno, $cardnum) {
        $url = "http://op.juhe.cn/ofpay/mobile/telquery";
        $params = [
            'phoneno' => $phoneno,
            'cardnum' => $cardnum,
            'key'     => 'eb39d26d474ca44b44693f458123761d'
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