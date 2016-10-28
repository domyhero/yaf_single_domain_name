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
class PaymentService extends BaseService {

    /**
     * 获取金币消费记录。
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
        $game_gold_consume_code_dict = YCore::dict('game_gold_consume_code');
        $users = [];
        foreach ($list as $key => $item) {
            if (isset($users[$item['user_id']])) {
                $userinfo = $users[$item['user_id']];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $item['user_id']]);
                $users[$item['user_id']] = $userinfo;
            }
            $item['consume_type_label'] = self::$consume_type_dict[$item['consume_type']];
            $item['consume_code_label'] = $game_gold_consume_code_dict[$item['consume_code']];
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