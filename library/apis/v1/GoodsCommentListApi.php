<?php
/**
 * 商品评论列表接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\AppraiseService;

class GoodsCommentListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $goods_id = $this->getInt('goods_id');
        $page     = $this->getInt('page', 1);
        $result   = AppraiseService::getGoodsCommentList($goods_id, $page, 20);
        $this->render(0, 'success', $result);
    }
}