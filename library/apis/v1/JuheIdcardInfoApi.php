<?php
/**
 * 聚合身份证信息查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\IDCard;
class JuheIdcardInfoApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $cardno = $this->getString('cardno');
        $result = IDCard::getIDCardInfo($cardno);
        $this->render(0, 'success', $result);
    }

}