<?php
/**
 * 支付管理。
 * @author winerQin
 * @date 2016-10-28
 */

namespace services;

use models\User;
use winer\Validator;
use common\YCore;
use models\DbBase;
use models\MallPaymentLog;
class PaymentService extends BaseService {

    const PAYMENT_TYPE_GOLD  = 'gold';  // 金币直充支付类型。
    const PAYMENT_TYPE_GOODS = 'goods'; // 商品购买支付类型。

    /**
     * 支付渠道。
     * @var array
     */
    public static $payment_code_dict = [
        'alipay_app' => '支付宝APP',
        'alipay_wap' => '支付宝WAP',
        'weixin_app' => '微信APP',
        'weixin_wap' => '微信WAP'
    ];

    public static function launchAlipayPay() {

    }

    public static function launchWeChatPay() {

    }

    public static function alipayNotifyUrlProcess() {

    }

    public static function wechatNotifyUrlProcess() {

    }

    /**
     * 记录支付记录。
     * @param number $user_id 用户ID。
     * @param string $payment_code 支付渠道编码。
     * @param number $order_id 订单ID。
     * @param string $serial_number 渠道支付成功之后的流水号。
     * @param float $amount 支付金额。
     * @return boolean
     */
    public static function writePaymentLog($user_id, $payment_code, $order_id, $serial_number, $amount) {
        $data = [
            'user_id'       => $user_id,
            'payment_code'  => $payment_code,
            'order_id'      => $order_id,
            'serial_number' => $serial_number,
            'amount'        => $amount,
            'created_time'  => $_SERVER['REQUEST_TIME']
        ];
        $payment_log_model = new MallPaymentLog();
        $ok = $payment_log_model->insert($data);
        if (!$ok) {
            $data['errmsg'] = '支付记录写入失败';
            YCore::log(-1, json_encode($data));
            return false;
        }
        return true;
    }

    /**
     * 获取支付记录。
     *
     * @param string $username 用户账号。
     * @param string $mobilephone
     * @param string $start_time 开始时间。
     * @param string $end_time 截止时间。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminPaymentLogList($username = '', $mobilephone = '', $start_time = '', $end_time = '', $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM mall_payment_log ';
        $columns = ' * ';
        $where   = ' WHERE 1 ';
        $params  = [];
        $user_model = new User();
        if (strlen($username) > 0) {
            $userinfo = $user_model->fetchOne([], ['username' => $username]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        } else if (strlen($mobilephone) > 0) {
            $userinfo = $user_model->fetchOne([], ['mobilephone' => $mobilephone]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        if (strlen($start_time) > 0) {
            if (!Validator::is_date($start_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time <= :start_time ';
            $params[':start_time'] = strtotime($start_time);
        }
        if (strlen($end_time) > 0) {
            if (!Validator::is_date($end_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time >= :end_time ';
            $params[':end_time'] = strtotime($end_time);
        }
        $order_by = ' ORDER BY payment_id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $users = [];
        foreach ($list as $key => $item) {
            if (isset($users[$item['user_id']])) {
                $userinfo = $users[$item['user_id']];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $item['user_id']]);
                $users[$item['user_id']] = $userinfo;
            }
            $item['payment_code_label'] = self::$payment_code_dict[$item['payment_code']];
            $item['username']           = $userinfo['username'];
            $item['mobilephone']        = $userinfo['mobilephone'];
            $item['email']              = $userinfo['email'];
            $item['created_time']       = YCore::format_timestamp($item['created_time']);
            $list[$key] = $item;
        }
        $result = [
            'list'   => $list,
            'total'  => $total,
            'page'   => $page,
            'count'  => $count,
            'isnext' => self::IsHasNextPage($total, $page, $count)
        ];
        return $result;
    }

}