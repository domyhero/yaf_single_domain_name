<?php
/**
 * 聚合菜谱分类接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\CookBook;
class JuheCookBookCategoryApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $parentid = $this->getInt('parentid', 0);
        $result = CookBook::category($parentid);
        $this->render(0, 'success', $result);
    }
}