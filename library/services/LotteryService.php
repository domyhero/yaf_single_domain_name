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
use models\LotteryUser;
use models\User;
class LotteryService extends BaseService {

    public static $lottery_dict = [
        1 => '双色球',
        2 => '大乐透'
    ];

    /**
     * 管理后台获取彩票活动列表。
     * @param number $activity_status 活动状态。-1全部、0报名参与中、1活动进行中、2活动结束。
     * @param number $lottery_type 彩票类型：1双色球、2大乐透。
     * @param number $display 活动是否显示：-1不限制、1是、0否。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminLotteryActivityList($activity_status = -1, $lottery_type = -1, $display = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_activity ';
        $columns = ' aid, title, bet_number, lottery_type, bet_money, bet_count, person_limit, open_apply_time, start_time, '
                 . ' end_time, prize_money, apply_count, display, modified_by, modified_time, created_by, created_time';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
        if ($display != - 1) {
            $where .= ' AND display = :display ';
            $params[':display'] = $display;
        }
        if ($lottery_type !=  -1) {
            $where .= ' AND lottery_type = :lottery_type ';
            $params[':lottery_type'] = $lottery_type;
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
        foreach ($list as $key => $item) {
            $item['open_apply_time'] = YCore::format_timestamp($item['open_apply_time']);
            $item['start_time']      = YCore::format_timestamp($item['start_time']);
            $item['end_time']        = YCore::format_timestamp($item['end_time']);
            $item['modified_time']   = YCore::format_timestamp($item['modified_time']);
            $item['created_time']    = YCore::format_timestamp($item['created_time']);
            $item['lottery_label']   = self::$lottery_dict[$item['lottery_type']];
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
     * 获取彩票活动列表[前台调用]。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getLotteryActivityList($page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_activity ';
        $columns = ' aid, title, bet_number, lottery_type, bet_money, bet_count, person_limit, open_apply_time, start_time, end_time, prize_money, apply_count';
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
            $item['lottery_label'] = self::$lottery_dict[$item['lottery_type']];
            $item['open_apply_time'] = YCore::format_timestamp($item['open_apply_time']);
            $item['start_time']      = YCore::format_timestamp($item['start_time']);
            $item['end_time']        = YCore::format_timestamp($item['end_time']);
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
            'aid', 'title', 'bet_number', 'bet_money', 'bet_count',
            'person_limit', 'open_apply_time', 'start_time',
            'end_time', 'prize_money', 'apply_count', 'lottery_type'
        ];
        $detail = $lottery_activity_model->fetchOne($columns, ['aid' => $aid, 'status' => 1, 'display' => 1]);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        $detail['open_apply_time'] = YCore::format_timestamp($detail['open_apply_time']);
        $detail['start_time']      = YCore::format_timestamp($detail['start_time']);
        $detail['end_time']        = YCore::format_timestamp($detail['end_time']);
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
            'aid', 'title', 'bet_number', 'bet_money', 'bet_count',
            'person_limit', 'open_apply_time', 'start_time',
            'end_time', 'prize_money', 'apply_count',
            'display', 'created_time', 'modified_time', 'lottery_type'
        ];
        $detail = $lottery_activity_model->fetchOne($columns, ['aid' => $aid, 'status' => 1]);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        $detail['open_apply_time'] = YCore::format_timestamp($detail['open_apply_time']);
        $detail['start_time']      = YCore::format_timestamp($detail['start_time']);
        $detail['end_time']        = YCore::format_timestamp($detail['end_time']);
        $detail['created_time']    = YCore::format_timestamp($detail['created_time']);
        $detail['modified_time']   = YCore::format_timestamp($detail['modified_time']);
        return $detail;
    }

    /**
     * 创建彩票活动。
     * @param string $title 活动标题。
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
    public static function addLotteryActivity($title, $bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $admin_id) {
        $data = [
            'title'           => $title,
            'bet_number'      => $bet_number,
            'lottery_type'    => $lottery_type,
            'person_limit'    => $person_limit,
            'open_apply_time' => $open_apply_time,
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'display'         => $display
        ];
        $rules = [
            'title'           => '活动标题|require:1000000|len:1000000:1:20:1',
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
        $bet_count = 0;
        if ($lottery_type == 1) {
            $bet_count = GameService::ssq_check_be_number($bet_number);
        } else if ($lottery_type == 2) {
            $bet_count = GameService::dlt_check_be_number($bet_number);
        }
        if ($bet_count == 0) {
            YCore::exception(-1, '号码有误');
        }
        $bet_money = $bet_count * 2; // 注数 * 每注金额。
        $data['bet_count']       = $bet_count;
        $data['bet_money']       = $bet_money;
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
     * @param number $aid 活动ID。
     * @param string $title 活动标题。
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
    public static function editLotteryActivity($aid, $title, $bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $admin_id) {
        $data = [
            'title'           => $title,
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
            'title'           => '活动标题|require:1000000|len:1000000:1:20:1',
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
        $bet_count = 0;
        if ($lottery_type == 1) {
            $bet_count = GameService::ssq_check_be_number($bet_number);
        } else if ($lottery_type == 2) {
            $bet_count = GameService::dlt_check_be_number($bet_number);
        }
        if ($bet_count == 0) {
            YCore::exception(-1, '号码有误');
        }
        $where = ['aid' => $aid, 'status' => 1];
        $lottery_activity_model  = new LotteryActivity();
        $detail = $lottery_activity_model->fetchOne([], $where);
        if (empty($detail)) {
            YCore::exception(-1, '活动不存在');
        }
        $bet_money = $bet_count * 2; // 注数 * 每注金额。
        $data['bet_count']       = $bet_count;
        $data['bet_money']       = $bet_money;
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
        $columns = ' id, lottery_type, phase_sn, lottery_result, first_prize, second_prize, first_prize_count, second_prize_count, '
                 . ' third_prize_count, fourth_prize_count, fifth_prize_count, sixth_prize_count, lottery_time, created_time';
        $where   = ' WHERE status = :status ';
        $time    = $_SERVER['REQUEST_TIME'];
        $params  = [
            ':status' => 1
        ];
        if ($lottery_type != -1) {
            $where .= ' AND lottery_type = :lottery_type';
            $params[':lottery_type'] = $lottery_type;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $item['created_time']  = date('Y-m-d H:i:s', $item['created_time']);
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
        $detail['lottery_time'] = YCore::format_timestamp($detail['lottery_time']);
        return $detail;
    }

    /**
     * 添加彩票开奖结果。
     * @param number $lottery_type 彩票类型。1双色球、2大乐透。
     * @param string $phase_sn 彩票期次。
     * @param string $lottery_result 开奖结果。
     * @param number $first_prize 一等奖奖金。
     * @param number $second_prize 二等奖奖金。
     * @param number $first_prize_count 一等奖中奖注数。
     * @param number $second_prize_count 二等奖中奖注数。
     * @param number $third_prize_count 三等奖中奖注数。
     * @param number $fourth_prize_count 四等奖中奖注数。
     * @param number $fifth_prize_count 五等奖中奖注数。
     * @param number $sixth_prize_count 六等奖中奖注数。
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
            'first_prize_count'  => '一等奖中奖注数|require:1000000|integer:1000000',
            'second_prize_count' => '二等奖中奖注数|require:1000000|integer:1000000',
            'third_prize_count'  => '三等奖中奖注数|require:1000000|integer:1000000',
            'fourth_prize_count' => '四等奖中奖注数|require:1000000|integer:1000000',
            'fifth_prize_count'  => '五等奖中奖注数|require:1000000|integer:1000000',
            'sixth_prize_count'  => '六等奖中奖注数|require:1000000|integer:1000000',
            'lottery_time'       => '彩票开奖时间|require:1000000|date:1000000:1',
        ];
        Validator::valido($data, $rules);
        $data['lottery_time'] = strtotime($data['lottery_time']);
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
     * @param number $first_prize_count 一等奖中奖注数。
     * @param number $second_prize_count 二等奖中奖注数。
     * @param number $third_prize_count 三等奖中奖注数。
     * @param number $fourth_prize_count 四等奖中奖注数。
     * @param number $fifth_prize_count 五等奖中奖注数。
     * @param number $sixth_prize_count 六等奖中奖注数。
     * @param string $lottery_time 开奖时间。
     * @param number $admin_id 管理员ID。
     * @return array
     */
    public static function editLotteryResult($id, $lottery_type, $phase_sn, $lottery_result, $first_prize, $second_prize, $first_prize_count, $second_prize_count, $third_prize_count, $fourth_prize_count, $fifth_prize_count, $sixth_prize_count, $lottery_time, $admin_id) {
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
            'first_prize_count'  => '一等奖中奖注数|require:1000000|integer:1000000',
            'second_prize_count' => '二等奖中奖注数|require:1000000|integer:1000000',
            'third_prize_count'  => '三等奖中奖注数|require:1000000|integer:1000000',
            'fourth_prize_count' => '四等奖中奖注数|require:1000000|integer:1000000',
            'fifth_prize_count'  => '五等奖中奖注数|require:1000000|integer:1000000',
            'sixth_prize_count'  => '六等奖中奖注数|require:1000000|integer:1000000',
            'lottery_time'       => '彩票开奖时间|require:1000000|date:1000000:1',
        ];
        Validator::valido($data, $rules);
        $lottery_result_model = new LotteryResult();
        $where = [
            'id'     => $id,
            'status' => 1
        ];
        $detail = $lottery_result_model->fetchOne([], $where);
        if (empty($detail)) {
            YCore::exception(-1, '记录不存在');
        }
        $data['lottery_time']  = strtotime($data['lottery_time']);
        $data['modified_by']   = $admin_id;
        $data['modified_time'] = $_SERVER['REQUEST_TIME'];
        $ok = $lottery_result_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '编辑失败');
        }
        return true;
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
        $lottery_activity_model = new LotteryActivity();
        $activity_detail = $lottery_activity_model->fetchOne([], ['aid' => $aid, 'status' => 1, 'display' => 1]);
        if (empty($activity_detail)) {
            YCore::exception(-1, '活动不存在');
        }
        if ($activity_detail['open_apply_time'] > $_SERVER['REQUEST_TIME']) {
            YCore::exception(-1, '活动还在紧张准备中');
        }
        if ($activity_detail['end_time'] < $_SERVER['REQUEST_TIME']) {
            YCore::exception(-1, '活动已经结束');
        }
        if ($activity_detail['start_time'] < $_SERVER['REQUEST_TIME'] && $activity_detail['end_time'] > $_SERVER['REQUEST_TIME']) {
            YCore::exception(-1, '活动正在开奖中');
        }
        $lottery_user_model = new LotteryUser();
        $user_record = $lottery_user_model->fetchOne([], ['aid' => $aid, 'user_id' => $user_id]);
        if (!empty($user_record)) {
            YCore::exception(-1, '您已经参与,不要重复操作');
        }
        $data = [
            'aid'          => $aid,
            'user_id'      => $user_id,
            'created_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $lottery_user_model->insert($data);
        if (!$ok) {
            YCore::exception(-1, '活动参与失败');
        }
        return true;
    }

    /**
     * 获取彩票活动参与的用户列表。
     * @param number $aid 活动ID。
     * @param string $mobilephone 手机号码。
     * @param string $username 用户名。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getLotteryActivityUserList($aid, $mobilephone = '', $username = '', $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_lottery_user ';
        $columns = ' id, user_id, prize_money, created_time ';
        $where   = ' WHERE aid = :aid ';
        $params  = [
            ':aid' => $aid
        ];
        $user_model = new User();
        if (strlen($mobilephone) > 0) {
            $userinfo = $user_model->fetchOne([], ['mobilephone' => $mobilephone]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        if (strlen($username) > 0) {
            $userinfo = $user_model->fetchOne([], ['username' => $username]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $userinfo = $user_model->fetchOne([], ['user_id' => $item['user_id']]);
            $item['mobilephone']     = $userinfo['mobilephone'];
            $item['username']        = $userinfo['username'];
            $item['reg_time']        = YCore::format_timestamp($userinfo['reg_time']);
            $item['last_login_time'] = YCore::format_timestamp($item['last_login_time']);
            $item['created_time']    = YCore::format_timestamp($item['created_time']);
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