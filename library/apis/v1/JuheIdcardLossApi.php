<?php
/**
 * 聚合身份证挂失查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\IDCard;
class JuheIdcardLossApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $cardno = $this->getString('cardno');
        $result = IDCard::isLoss($cardno);
        $this->render(0, 'success', $result);
    }

}