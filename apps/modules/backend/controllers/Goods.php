<?php
/**
 * 商品管理。
 * @author winerQin
 * @date 2016-06-11
 */
use common\YCore;
use winer\Paginator;
use services\GoodsService;
use services\CategoryService;

class GoodsController extends \common\controllers\Admin {

    /**
     * 商品列表。
     */
    public function listAction() {
        $cat_id = $this->getString('cat_id', -1);
        $updown = $this->getInt('updown', -1);
        $goods_name  = $this->getString('goods_name', '');
        $start_price = $this->getString('start_price', '');
        $end_price   = $this->getString('end_price', '');
        $delted_show = $this->getInt('is_delete_show', 0);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = GoodsService::getBackendGoodsList($updown, $goods_name, $cat_id, $start_price, $end_price, $delted_show, $page, 10);
        $cat_list = CategoryService::getCategoryList(0, CategoryService::CAT_GOODS);
        $this->_view->assign('cat_list', $cat_list);
        $paginator = new Paginator($list['total'], 10);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('updown', $updown);
        $this->_view->assign('goods_name', $goods_name);
        $this->_view->assign('start_price', $start_price);
        $this->_view->assign('end_price', $end_price);
        $this->_view->assign('cat_id', $cat_id);
        $this->_view->assign('is_delete_show', $delted_show);
        $this->_view->assign('list', $list['list']);
    }

    /**
     * 添加商品。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $data = [
                'user_id'        => $this->admin_id,
                'goods_name'     => $this->getString('goods_name'),
                'cat_id'         => $this->getInt('cat_id'),
                'slogan'         => $this->getString('slogan'),
                'weight'         => $this->getInt('weight'),
                'listorder'      => $this->getInt('listorder'),
                'description'    => $this->getString('description'),
                'spec_val'       => $this->getArray('spec_val', []),
                'products'       => $this->getArray('products', []),
                'goods_album'    => $this->getArray('goods_album', []),
                'market_price'   => $this->getFloat('market_price', 0.00),
                'sales_price'    => $this->getFloat('sales_price', 0.00),
                'stock'          => $this->getInt('stock', 0),
                'freight_tpl_id' => $this->getInt('freight_tpl_id', 0)
            ];
            GoodsService::addGoods($data);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 编辑商品。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $data = [
                'goods_id'       => $this->getInt('goods_id'),
                'user_id'        => $this->admin_id,
                'goods_name'     => $this->getString('goods_name'),
                'cat_id'         => $this->getInt('cat_id'),
                'slogan'         => $this->getString('slogan'),
                'weight'         => $this->getInt('weight'),
                'listorder'      => $this->getInt('listorder'),
                'description'    => $this->getString('description'),
                'spec_val'       => $this->getArray('spec_val', []),
                'products'       => $this->getArray('products', []),
                'goods_album'    => $this->getArray('goods_album', []),
                'market_price'   => $this->getFloat('market_price', 0.00),
                'sales_price'    => $this->getFloat('sales_price', 0.00),
                'stock'          => $this->getInt('stock', 0),
                'freight_tpl_id' => $this->getInt('freight_tpl_id', 0)
            ];
            GoodsService::editGoods($data);
            $this->json(true, '保存失败');
        }
    }

    /**
     * 商品删除。
     */
    public function deleteAction() {
        $goods_id = $this->getInt('goods_id');
        GoodsService::deleteGoods($this->admin_id, $goods_id);
        $this->json(true, '删除成功');
    }
}
