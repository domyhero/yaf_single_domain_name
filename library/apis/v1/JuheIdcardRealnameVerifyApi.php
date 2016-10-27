<?php
/**
 * 聚合身份证实名验证接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\IDCard;
class JuheIdcardRealnameVerifyApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $realname = $this->getString('realname');
        $cardno   = $this->getString('cardno');
        $result   = IDCard::isRealname($realname, $cardno);
        $this->render(0, 'success', $result);
    }

}