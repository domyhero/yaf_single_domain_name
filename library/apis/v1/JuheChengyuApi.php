<?php
/**
 * 聚合成语接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Chengyu;
class JuheChengyuApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $word   = $this->getString('word');
        $result = Chengyu::query($word);
        $this->render(0, 'success', $result);
    }
}