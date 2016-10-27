<?php
/**
 * 驾照考试题库。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class DriverExamTopic extends Base {

    /**
     * 题目列表。
     * @param string $subject 选择考试科目类型，1：科目1；4：科目4
     * @param string $model 驾照类型，可选择参数为：c1,c2,a1,a2,b1,b2；当subject=4时可省略
     * @param strign $testType 测试类型，rand：随机测试（随机100个题目），order：顺序测试（所选科目全部题目）
     * @return mixed
     */
    public static function query($subject, $model, $testType) {
        $url = "http://api2.juheapi.com/jztk/query";
        $params = [
            'subject'  => $subject,
            'model'    => $model,
            'testType' => $testType,
            'key'      => 'bead6fc852daaeab8e9a0a65777465d6'
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

    /**
     * 题目答案编码含义。
     * @return array
     */
    public static function answerCode() {
        $url = "http://api2.juheapi.com/jztk/answers";
        $params = [
            'key' => 'bead6fc852daaeab8e9a0a65777465d6'
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