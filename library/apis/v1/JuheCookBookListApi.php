<?php
/**
 * 聚合菜谱大全查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\CookBook;
class JuheCookBookListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $menu = $this->getString('menu');
        $pn   = $this->getInt('pn', 1);
        $rn   = $this->getInt('rn', 20);
        $list = CookBook::query($menu, $pn, $rn);
        $result = [
            'list' => $list,
            'pn'   => $pn + 1
        ];
        $this->render(0, 'success', $result);
    }
}