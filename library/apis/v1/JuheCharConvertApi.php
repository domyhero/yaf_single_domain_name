<?php
/**
 * 聚合字符转换接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Change;
class JuheCharConvertApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $str = $this->getString('str');
        $juhe_type = $this->getString('type');
        $result = Change::requestJuheApi($str, $juhe_type);
        $this->render(0, 'success', $result);
    }

}