<?php
/**
 * 彩票活动。
 * @author winerQin
 * @date 2016-10-13
 */

namespace services;

use common\YCore;
use models\DbBase;
use models\LotteryActivity;
use winer\Validator;
use models\LotteryResult;
class LotteryService extends BaseService {

    public static $lottery_dict = [
        1 => '双色球',
        2 => '大乐透'
    ];

    /**
     * 管理后台获取彩票活动列表。
     * @param number $activity_status 活动状态。-1全部、0报名参与中、1活动进行中、2活动结束。
     * @param number $display 活动是否显示：-1不限制、1是、0否。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminLotteryActivityList($activity_status = -1, $display = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_activity ';
        $columns = ' aid, bet_number, bet_money, bet_count, person_limit, open_apply_time, start_time, '
                 . ' end_time, prize_money, apply_count, display, modified_by, modified_time, created_by, created_time';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
        if ($display != - 1) {
            $where .= ' AND display = :display ';
            $params[':display'] = $display;
        }
        if ($activity_status != - 1) {
            switch ($activity_status) {
                case 0:
                    $where .= ' AND open_apply_time <= :open_apply_time AND start_time > :start_time';
                    $params[':open_apply_time'] = $_SERVER['REQUEST_TIME'];
                    $params[':start_time']      = $_SERVER['REQUEST_TIME'];
                    break;
                case 1:
                    $where .= ' AND start_time = :start_time AND end_time >= :end_time';
                    $params[':start_time'] = $_SERVER['REQUEST_TIME'];
                    $params[':end_time']   = $_SERVER['REQUEST_TIME'];
                    break;
                case 2:
                    $where .= ' AND end_time < :end_time';
                    $params[':end_time'] = $_SERVER['REQUEST_TIME'];
                    break;
                default:
                    YCore::exception(-1, '活动状态错误');
                    break;
            }
        }
        $order_by = ' ORDER BY aid DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $result = [
            'list'   => $list,
            'total'  => $total,
            'page'   => $page,
            'count'  => $count,
            'isnext' => self::IsHasNextPage($total, $page, $count)
        ];
        return $result;
    }

    /**
     * 获取彩票活动列表[前台调用]。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getLotteryActivityList($page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_activity ';
        $columns = ' aid, bet_number, bet_money, bet_count, person_limit, open_apply_time, start_time, end_time, prize_money, apply_count';
        $where   = ' WHERE status = :status AND display = :display AND open_apply_time <= :time';
        $time    = $_SERVER['REQUEST_TIME'];
        $params  = [
            ':status'  => 1,
            ':display' => 1,
            ':time'    => $time
        ];
        $order_by = ' ORDER BY aid DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            if ($item['start_time'] <= $time && $time <= $item['end_time']) {
                $item['activity_status'] = 1; // 活动进行中。
            } else if ($time > $item['end_time']) {
                $item['activity_status'] = 2; // 活动已经结束。
            } else {
                $item['activity_status'] = 0; // 活动报名中。
            }
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

    /**
     * 获取彩票活动详情[前台]。
     * @param number $aid 活动ID。
     * @return array
     */
    public static function getLotteryActivityDetail($aid) {
        $lottery_activity_model = new LotteryActivity();
        $columns = [
            'aid', 'bet_number', 'bet_money', 'bet_count',
            'person_limit', 'open_apply_time', 'start_time',
            'end_time', 'prize_money', 'apply_count'
        ];
        $detail = $lottery_activity_model->fetchOne($columns, ['aid' => $aid, 'status' => 1, 'display' => 1]);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        return $detail;
    }

    /**
     * 获取彩票活动详情[管理后台]。
     * @param number $aid 活动ID。
     * @return array
     */
    public static function getAdminLotteryActivityDetail($aid) {
        $lottery_activity_model = new LotteryActivity();
        $columns = [
            'aid', 'bet_number', 'bet_money', 'bet_count',
            'person_limit', 'open_apply_time', 'start_time',
            'end_time', 'prize_money', 'apply_count',
            'display', 'created_time', 'modified_time'
        ];
        $detail = $lottery_activity_model->fetchOne($columns, ['aid' => $aid, 'status' => 1, 'display' => 1]);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        return $detail;
    }

    /**
     * 创建彩票活动。
     * @param string $bet_number 投注号码(复式)。
     * @param string $lottery_type 彩票类型。1双色球、2大乐透。
     * @param number $person_limit 人数限制(参与该活动的最大人数)。1~1000之间取值。
     * @param string $open_apply_time 开放参与时间。格式：2016-10-01 09:00:00
     * @param string $start_time 彩票活动开始时间。从这个时间开始计算彩票活动的中奖资金。格式：2016-10-01 09:00:00
     * @param string $end_time 彩票活动结束时间。从这个时间结束计算彩票活动的中奖资金。 格式：2016-10-01 09:00:00
     * @param number $display 是否显示：1是、0否。
     * @param number $admin_id 管理员ID。
     * @return boolean
     */
    public static function addLotteryActivity($bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $admin_id) {
        $data = [
            'bet_number'      => $bet_number,
            'lottery_type'    => $lottery_type,
            'person_limit'    => $person_limit,
            'open_apply_time' => $open_apply_time,
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'display'         => $display
        ];
        $rules = [
            'bet_number'      => '投注号码|require:1000000',
            'lottery_type'    => '彩票类型|require:1000000|integer:1000000|number_between:1000000:1:2',
            'person_limit'    => '人数上限|require:1000000|integer:1000000|number_between:1000000:1:1000',
            'open_apply_time' => '开放参与时间|require:1000000|date:1000000:1',
            'start_time'      => '活动开始时间|require:1000000|date:1000000:1',
            'end_time'        => '活动结束时间|require:1000000|date:1000000:1',
            'display'         => '活动显示状态|require:1000000|integer:1000000|number_between:1000000:0:1'
        ];
        Validator::valido($data, $rules);
        if ($open_apply_time >= $start_time) {
            YCore::exception(-1, '开放参与时间必须小于活动开始时间');
        }
        if ($start_time >= $end_time) {
            YCore::exception(-1, '活动开始时间必须小于结束时间');
        }
        $data['status']          = 1;
        $data['open_apply_time'] = strtotime($open_apply_time);
        $data['start_time']      = strtotime($start_time);
        $data['end_time']        = strtotime($end_time);
        $data['created_by']      = $admin_id;
        $data['created_time']    = $_SERVER['REQUEST_TIME'];
        $lottery_activity_model  = new LotteryActivity();
        $ok = $lottery_activity_model->insert($data);
        if (!$ok) {
            YCore::exception(-1, '创建活动失败');
        }
        return true;
    }

    /**
     * 修改彩票活动。
     * @param number $aid 活动ID
     * @param string $bet_number 投注号码(复式)。
     * @param string $lottery_type 彩票类型。1双色球、2大乐透。
     * @param number $person_limit 人数限制(参与该活动的最大人数)。
     * @param string $open_apply_time 开放参与时间。格式：2016-10-01 09:00:00
     * @param string $start_time 彩票活动开始时间。从这个时间开始计算彩票活动的中奖资金。格式：2016-10-01 09:00:00
     * @param string $end_time 彩票活动结束时间。从这个时间结束计算彩票活动的中奖资金。 格式：2016-10-01 09:00:00
     * @param number $display 是否显示：1是、0否。
     * @param number $admin_id 管理员ID。
     * @return boolean
     */
    public static function editLotteryActivity($aid, $bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $admin_id) {
        $data = [
            'aid'             => $aid,
            'bet_number'      => $bet_number,
            'lottery_type'    => $lottery_type,
            'person_limit'    => $person_limit,
            'open_apply_time' => $open_apply_time,
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'display'         => $display
        ];
        $rules = [
            'aid'             => '活动ID|require:1000000|integer:10000000',
            'bet_number'      => '投注号码|require:1000000',
            'lottery_type'    => '彩票类型|require:1000000|integer:1000000|number_between:1000000:1:2',
            'person_limit'    => '人数上限|require:1000000|integer:1000000|number_between:1000000:1:1000',
            'open_apply_time' => '开放参与时间|require:1000000|date:1000000:1',
            'start_time'      => '活动开始时间|require:1000000|date:1000000:1',
            'end_time'        => '活动结束时间|require:1000000|date:1000000:1',
            'display'         => '活动显示状态|require:1000000|integer:1000000|number_between:1000000:0:1'
        ];
        Validator::valido($data, $rules);
        if ($open_apply_time >= $start_time) {
            YCore::exception(-1, '开放参与时间必须小于活动开始时间');
        }
        if ($start_time >= $end_time) {
            YCore::exception(-1, '活动开始时间必须小于结束时间');
        }
        $where = ['aid' => $aid, 'status' => 1];
        $lottery_activity_model  = new LotteryActivity();
        $detail = $lottery_activity_model->fetchOne([], $where);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        $data['open_apply_time'] = strtotime($open_apply_time);
        $data['start_time']      = strtotime($start_time);
        $data['end_time']        = strtotime($end_time);
        $data['modified_by']     = $admin_id;
        $data['modified_time']   = $_SERVER['REQUEST_TIME'];
        $ok = $lottery_activity_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '创建更新失败');
        }
        return true;
    }

    /**
     * 删除彩票活动。
     * @param number $aid 活动ID。
     * @param number $admin_id 管理员ID。
     * @return boolean
     */
    public static function deleteLotteryActivity($aid, $admin_id) {
        $lottery_activity_model = new LotteryActivity();
        $where = ['aid' => $aid, 'status' => 1];
        $detail = $lottery_activity_model->fetchOne([], $where);
        if (empty($detail)) {
            YCore::exception(-1, '彩票活动不存在');
        }
        $data = [
            'status'        => 2,
            'modified_by'   => $admin_id,
            'modified_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $lottery_activity_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '删除失败');
        }
        return true;
    }

    /**
     * 获取彩票开奖结果。
     * @param number $lottery_type 彩票类型。-1不限、1双色球、2大乐透。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getLotteryResultList($lottery_type = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_result ';
        $columns = ' id, lottery_type, phase_sn, lottery_result, first_prize, second_prize, first_prize_count, '
                 . ' second_prize_count, third_prize_count, fourth_prize_count, fifth_prize_count, sixth_prize_count, lottery_time';
        $where   = ' WHERE status = :status ';
        $time    = $_SERVER['REQUEST_TIME'];
        $params  = [
            ':status'  => 1,
            ':display' => 1,
            ':time'    => $time
        ];
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $item['lottery_time']  = date('Y-m-d H:i:s', $item['lottery_time']);
            $item['lottery_label'] = self::$lottery_dict[$item['lottery_type']];
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

    /**
     * 获取彩票开奖结果详情。
     * @param number $id 彩票开奖结果ID。
     * @return array
     */
    public static function getLotteryResultDetail($id) {
        $lottery_result_model = new LotteryResult();
        $columns = [
            'id', 'lottery_type', 'phase_sn',
            'lottery_result', 'first_prize', 'second_prize',
            'first_prize_count', 'second_prize_count', 'third_prize_count',
            'fourth_prize_count', 'fifth_prize_count', 'sixth_prize_count',
            'lottery_time'
        ];
        $detail = $lottery_result_model->fetchOne($columns, ['id' => $id, 'status' => 1]);
        if (empty($detail)) {
            YCore::exception(-1, '开奖结果不存在');
        }
        return $detail;
    }

    /**
     * 添加彩票开奖结果。
     * @param number $lottery_type 彩票类型。1双色球、2大乐透。
     * @param string $phase_sn 彩票期次。
     * @param string $lottery_result 开奖结果。
     * @param number $first_prize 一等奖奖金。
     * @param number $second_prize 二等奖奖金。
     * @param number $first_prize_count 一等奖中奖人数。
     * @param number $second_prize_count 二等奖中奖人数。
     * @param number $third_prize_count 三等奖中奖人数。
     * @param number $fourth_prize_count 四等奖中奖人数。
     * @param number $fifth_prize_count 五等奖中奖人数。
     * @param number $sixth_prize_count 六等奖中奖人数。
     * @param string $lottery_time 开奖时间。
     * @param number $admin_id 管理员ID。
     * @return array
     */
    public static function addLotteryResult($lottery_type, $phase_sn, $lottery_result, $first_prize, $second_prize, $first_prize_count, $second_prize_count, $third_prize_count, $fourth_prize_count, $fifth_prize_count, $sixth_prize_count, $lottery_time, $admin_id) {
        $data = [
            'lottery_type'       => $lottery_type,
            'phase_sn'           => $phase_sn,
            'lottery_result'     => $lottery_result,
            'first_prize'        => $first_prize,
            'second_prize'       => $second_prize,
            'first_prize_count'  => $first_prize_count,
            'second_prize_count' => $second_prize_count,
            'third_prize_count'  => $third_prize_count,
            'fourth_prize_count' => $fourth_prize_count,
            'fifth_prize_count'  => $fifth_prize_count,
            'sixth_prize_count'  => $sixth_prize_count,
            'lottery_time'       => $lottery_time,
        ];
        $rules = [
            'lottery_type'       => '彩票类型|require:1000000|integer:1000000|number_between:1000000:1:2',
            'phase_sn'           => '彩票期次|require:1000000',
            'lottery_result'     => '开奖结果|require:1000000',
            'first_prize'        => '一等奖奖金|require:1000000|integer:1000000',
            'second_prize'       => '二等奖奖金|require:1000000|integer:1000000',
            'first_prize_count'  => '一等奖中奖人数|require:1000000|integer:1000000',
            'second_prize_count' => '二等奖中奖人数|require:1000000|integer:1000000',
            'third_prize_count'  => '三等奖中奖人数|require:1000000|integer:1000000',
            'fourth_prize_count' => '四等奖中奖人数|require:1000000|integer:1000000',
            'fifth_prize_count'  => '五等奖中奖人数|require:1000000|integer:1000000',
            'sixth_prize_count'  => '六等奖中奖人数|require:1000000|integer:1000000',
            'lottery_time'       => '彩票开奖时间|require:1000000|date:1000000:1',
        ];
        Validator::valido($data, $rules);
        $data['status']       = 1;
        $data['created_by']   = $admin_id;
        $data['created_time'] = $_SERVER['REQUEST_TIME'];
        $lottery_result_model = new LotteryResult();
        $ok = $lottery_result_model->insert($data);
        if (!$ok) {
            YCore::exception(-1, '添加失败');
        }
        return true;
    }

    /**
     * 编辑彩票开奖结果。
     * @param number $id 记录ID。
     * @param number $lottery_type 彩票类型。1双色球、2大乐透。
     * @param string $phase_sn 彩票期次。
     * @param string $lottery_result 开奖结果。
     * @param number $first_prize 一等奖奖金。
     * @param number $second_prize 二等奖奖金。
     * @param number $first_prize_count 一等奖中奖人数。
     * @param number $second_prize_count 二等奖中奖人数。
     * @param number $third_prize_count 三等奖中奖人数。
     * @param number $fourth_prize_count 四等奖中奖人数。
     * @param number $fifth_prize_count 五等奖中奖人数。
     * @param number $sixth_prize_count 六等奖中奖人数。
     * @param string $lottery_time 开奖时间。
     * @param number $admin_id 管理员ID。
     * @return array
     */
    public static function editLotteryResult($id, $lottery_type, $phase_sn, $lottery_result, $first_prize, $second_prize, $first_prize_count, $second_prize_count, $third_prize_count, $fourth_prize_count, $fifth_prize_count, $sixth_prize_count, $lottery_time, $admin_id) {

    }

    /**
     * 删除彩票开奖结果。
     * @param number $id 记录ID。
     * @param number $admin_id 管理员ID。
     * @return boolean
     */
    public static function deleteLotteryResult($id, $admin_id) {
        $lottery_result_model = new LotteryResult();
        $where = ['id' => $id, 'status' => 1];
        $detail = $lottery_result_model->fetchOne([], $where);
        if (empty($detail)) {
            YCore::exception(-1, '彩票开奖结果不存在');
        }
        $data = [
            'status'        => 2,
            'modified_by'   => $admin_id,
            'modified_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $lottery_result_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '删除失败');
        }
        return true;
    }

    /**
     * 用户参与彩票活动。
     * @param number $user_id 用户ID。
     * @param number $aid 活动ID。
     * @return boolean
     */
    public static function doLotteryActivity($user_id, $aid) {

    }
}