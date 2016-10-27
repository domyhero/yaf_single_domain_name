<?php
/**
 * 文章列表接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\NewsService;

class NewsListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $title  = $this->getInt('title');
        $page   = $this->getInt('page', 1);
        $result = NewsService::getNewsList($title, '', '', '', $page, 20);
        $this->render(0, 'success', $result);
    }

}