<?php
/**
 * IT试题模块业务封装。
 * @author winerQin
 * @date 2016-08-24
 */
namespace services;

use winer\Validator;
use models\ItQuestion;
use common\YCore;
use models\DbBase;
use models\ItTestPaper;

class ItQuestionService extends BaseService {

    /**
     * 获取IT试卷列表。
     *
     * @param string $paper_title 试卷标题。
     * @param number $cat_id 试卷分类。
     * @param string $medal_code 试卷勋章CODE。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getTestPaperList($paper_title = '', $cat_id = -1, $medal_code = '', $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $columns = ' * ';
        $where = ' WHERE status = :status';
        $params = [
            ':status' => 1
        ];
        if (strlen($paper_title) > 0) {
            $where .= ' AND paper_title LIKE :paper_title ';
            $params[':paper_title'] = "%{$paper_title}%";
        }
        if (strlen($medal_code) > 0) {
            $where .= ' AND medal_code = :medal_code ';
            $params[':medal_code'] = $medal_code;
        }
        if ($cat_id != -1) {
            $where .= ' AND cat_id = :cat_id ';
            $params[':cat_id'] = $cat_id;
        }
        $order_by = ' ORDER BY coupon_id DESC ';
        $sql = "SELECT COUNT(1) AS count FROM it_test_paper {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} FROM it_test_paper {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $k => $v) {
            $v['created_time']  = YCore::format_timestamp($v['created_time']);
            $v['modified_time'] = YCore::format_timestamp($v['modified_time']);
            $list[$k] = $v;
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
     * 添加IT试卷。
     *
     * @param number $admin_id 管理员ID。
     * @param number $paper_id 试卷ID。
     * @param string $paper_title 试卷标题。
     * @param string $paper_desc 试卷说明。
     * @param number $cat_id 试卷分类ID。
     * @param string $medal_code 勋章编码CODE。
     * @param number $question_count 试卷试题数量。
     * @param number $score 每题分值。
     * @param number $pass_score 及格分值。
     * @param number $total_time 试题允许时长(秒)。
     * @param number $test_times 参与次数。
     * @param number $pass_times 通过次数。
     * @return boolean
     */
    public static function addTestPaper($admin_id, $paper_id, $paper_title, $paper_desc, $cat_id, $medal_code, $question_count, $score, $pass_score, $total_time, $test_times, $pass_times) {
        $data = [
            'paper_title'    => $paper_title,
            'paper_desc'     => $paper_desc,
            'cat_id'         => $cat_id,
            'medal_code'     => $medal_code,
            'question_count' => $question_count,
            'score'          => $score,
            'pass_score'     => $pass_score,
            'total_time'     => $total_time,
            'total_time'     => $total_time,
            'test_times'     => $test_times,
            '$pass_times'    => $pass_times
        ];
        $rules = [
            'paper_title'    => '试卷标题|require:1000000|len:100000:1:50:1',
            'paper_desc'     => '试卷说明|require:1000000|len:100000:1:255:1',
            'cat_id'         => '试卷分类|require:1000000|integer:1000000',
            'medal_code'     => '勋章编码|require:1000000|len:1000000:1:30:0',
            'question_count' => '试卷试题数量|require:1000000|integer:1000000|number_between:1000000:1:1000:1',
            'score'          => '每题分值|require:1000000|integer:1000000|number_between:1000000:1:100:1',
            'pass_score'     => '及格分值|require:1000000|integer:1000000|number_between:1000000:1:100000:1',
            'total_time'     => '及格分值|require:1000000|integer:1000000|number_between:1000000:1:100000:1',
            'total_time'     => '试题允许时长|require:1000000|integer:1000000|number_between:1000000:1:100000:1',
            'test_times'     => '参与次数|require:1000000|integer:1000000|number_between:1000000:1:10000:1',
            '$pass_times'    => '通过次数|require:1000000|integer:1000000|number_between:1000000:1:10000:1'
        ];
        Validator::valido($data, $rules);
        if ($score * $question_count < $pass_score) {
            YCore::exception(-1, '试卷及格分必须小于等于试卷总分');
        }
        if ($pass_times > $test_times) {
            YCore::exception(-1, '试卷通过次数必须小于等于参与次数');
        }
        $it_question_medal_dict = YCore::dict('it_question_medal');
        if (!in_array($medal_code, $it_question_medal_dict)) {
            YCore::exception(-1, '勋章编码不存在');
        }
        $it_test_paper = new ItTestPaper();
        $it_test_paper_detail = $it_test_paper->fetchOne([], ['medal_code' => $medal_code, 'status' => 1]);
        if (!empty($it_test_paper_detail) && $it_test_paper_detail['paper_id'] = $paper_id) {
            YCore::exception(-1, '该勋章已经被使用了');
        }
        if ($paper_id > 0) {
            $it_test_paper_detail = $it_test_paper->fetchOne([], ['paper_id' => $paper_id, 'status' => 1]);
            if (empty($it_test_paper_detail)) {
                YCore::exception(-1, '该试卷不存在或已经删除');
            }
            $data['modified_by']   = $admin_id;
            $data['modified_time'] = $_SERVER['REQUEST_TIME'];
            $ok = $it_test_paper->insert($data);
            if (!$ok) {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
            $ok = $it_test_paper->update($data, ['paper_id' => $paper_id, 'status' => 1]);
            if (!$ok) {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            $data['status']       = 1;
            $data['created_by']   = $admin_id;
            $data['created_time'] = $_SERVER['REQUEST_TIME'];
            $ok = $it_test_paper->insert($data);
            if (!$ok) {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        }
        return true;
    }

    /**
     * 删除试卷。
     *
     * @param number $admin_id 管理员ID。
     * @param number $paper_id 试卷ID。
     * @return boolean
     */
    public static function deleteTestPaper($admin_id, $paper_id) {
        $where = [
            'paper_id' => $paper_id,
            'status'   => 1
        ];
        $it_test_paper_model = new ItTestPaper();
        $it_test_paper_detail = $it_test_paper_model->fetchOne([], $where);
        if (empty($it_test_paper_detail)) {
            YCore::exception(-1, '试卷不存在或已经删除');
        }
        $data = [
            'status'        => 2,
            'modified_by'   => $admin_id,
            'modified_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $it_test_paper_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
        return true;
    }

    /**
     * 获取试题列表。
     *
     * @param string $ques_title 试题标题。
     * @param number $ques_level 试题难度。
     * @param number $cat_id 试题分类。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getQuestionList($ques_title = '', $ques_level = -1, $cat_id = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $columns = ' * ';
        $where = ' WHERE status = :status';
        $params = [
            ':status' => 1
        ];
        if (strlen($ques_title) > 0) {
            $where .= ' AND ques_title LIKE :ques_title ';
            $params[':ques_title'] = "%{$ques_title}%";
        }
        if ($ques_level != -1) {
            $where .= ' AND ques_level = :ques_level ';
            $params[':ques_level'] = $ques_level;
        }
        if ($cat_id != -1) {
            $where .= ' AND cat_id = :cat_id ';
            $params[':cat_id'] = $cat_id;
        }
        $order_by = ' ORDER BY coupon_id DESC ';
        $sql = "SELECT COUNT(1) AS count FROM it_question {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} FROM it_question {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $k => $v) {
            $v['created_time'] = YCore::format_timestamp($v['created_time']);
            $v['modified_time'] = YCore::format_timestamp($v['modified_time']);
            $list[$k] = $v;
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
     * 添加试题。
     *
     * @param number $admin_id 管理员ID。
     * @param number $ques_id 试题ID。
     * @param string $ques_title 试题题目。
     * @param number $cat_id 试题分类。
     * @param number $ques_level 难度等级：1初级、2中级、3高级。
     * @param string $ques_a A答案。
     * @param string $ques_b B答案。
     * @param string $ques_c C答案。
     * @param string $ques_d D答案。
     * @param string $ques_e E答案。
     * @param string $ques_f F答案。
     * @param string $right_answer 正确答案。
     * @param string $decipher 题目解析。
     * @return boolean
     */
    public static function setQuestion($admin_id, $ques_id, $ques_title, $cat_id, $ques_level, $ques_a, $ques_b, $ques_c, $ques_d, $ques_e, $ques_f, $right_answer, $decipher) {
        $data = [
            'ques_title'   => $ques_title,
            'cat_id'       => $cat_id,
            'ques_level'   => $ques_level,
            'ques_a'       => $ques_a,
            'ques_b'       => $ques_b,
            'ques_c'       => $ques_c,
            'ques_d'       => $ques_d,
            'ques_e'       => $ques_e,
            'ques_f'       => $ques_f,
            'right_answer' => $right_answer,
            'decipher'     => $decipher
        ];
        $rules = [
            'ques_title'   => '试题题目|require:1000000|len:1000000:1:255:1',
            'cat_id'       => '试题分类|require:1000000|integer:1000000',
            'ques_level'   => '试题难度|require:1000000|integer:1000000',
            'ques_a'       => 'A答案|require:1000000|len:1000000:1:255:1',
            'ques_b'       => 'B答案|require:1000000|len:1000000:1:255:1',
            'ques_c'       => 'C答案|len:1000000:1:255:1',
            'ques_d'       => 'D答案|len:1000000:1:255:1',
            'ques_e'       => 'E答案|len:1000000:1:255:1',
            'ques_f'       => 'F答案|len:1000000:1:255:1',
            'right_answer' => '正确答案|require:1000000|len:1000000:1:10:1',
            'decipher'     => '题目解析|require:1000000|len:1000000:1:10000:1'
        ];
        Validator::valido($data, $rules);
        $it_question_model = new ItQuestion();
        if ($ques_id > 0) {
            $where = [
                'ques_id' => $ques_id,
                'status'  => 1
            ];
            $question_detail = $it_question_model->fetchOne([], $where);
            if (empty($question_detail)) {
                YCore::exception(-1, '试题不存在');
            }
            $data['modified_time'] = $_SERVER['REQUEST_TIME'];
            $data['modified_by']   = $admin_id;
            $ok = $it_question_model->update($data, $where);
            if (!$ok) {
                YCore::exception(-1, '修改失败');
            }
        } else {
            $data['status']       = 1;
            $data['created_time'] = $_SERVER['REQUEST_TIME'];
            $data['created_by']   = $admin_id;
            $ok = $it_question_model->insert($data);
            if (!$ok) {
                YCore::exception(-1, '添加失败');
            }
        }
        return true;
    }

    /**
     * 删除试题。
     *
     * @param number $user_id 用户ID。
     * @param number $ques_id 试题ID。
     * @return boolean
     */
    public static function deleteQuestion($user_id, $ques_id) {
        $it_question_model = new ItQuestion();
        $it_question_detail = $it_question_model->fetchOne([], ['ques_id' => $ques_id, 'status' => 1]);
        if (empty($it_question_detail)) {
            YCore::exception(1, '试题不存在或已经删除');
        }
        $data = [
            'modified_time' => $_SERVER['REQUEST_TIME'],
            'modified_by'   => $user_id
        ];
        $ok = $it_question_model->update($data, ['ques_id' => $ques_id, 'status' => 1]);
        if (!$ok) {
            YCore::exception(2, '服务器繁忙,请稍候重试');
        }
        return true;
    }
}