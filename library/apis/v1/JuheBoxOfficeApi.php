<?php
/**
 * 聚合票房查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\BoxOffice;
class JuheBoxOfficeApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $area   = $this->getString('area');
        $result = BoxOffice::rank($area);
        $this->render(0, 'success', $result);
    }

}