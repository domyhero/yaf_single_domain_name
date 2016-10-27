<?php
/**
 * 聚合菜谱详情查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\CookBook;
class JuheCookBookDetailApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $id = $this->getInt('id');
        $detail = CookBook::getCookDetail($id);
        $this->render(0, 'success', $detail);
    }
}