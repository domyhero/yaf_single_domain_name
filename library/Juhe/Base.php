<?php
/**
 * 聚合接口基础类。
 * @author winerQin
 * @date 2016-10-11
 */

namespace Juhe;

use common\YCore;
class Base {



    /**
     * 请求接口返回内容
     * @param string $url 请求的URL地址
     * @param string $params 请求的参数
     * @param int $ipost 是否采用POST形式
     * @return string
     */
    protected static function juhecurl($url, $params = '', $ispost = 0) {
        $httpInfo = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if($params) {
                curl_setopt($ch, CURLOPT_URL, "{$url}?{$params}");
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            YCore::exception(-1, '服务器异常');
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}