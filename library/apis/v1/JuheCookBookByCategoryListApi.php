<?php
/**
 * 聚合菜谱按照菜谱分类查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\CookBook;
class JuheCookBookByCategoryListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $cid  = $this->getInt('cid');
        $pn   = $this->getInt('pn', 1);
        $rn   = $this->getInt('rn', 20);
        $list = CookBook::byCategoryQuery($cid, $pn, $rn);
        $result = [
            'list' => $list,
            'pn'   => $pn + 1
        ];
        $this->render(0, 'success', $result);
    }
}