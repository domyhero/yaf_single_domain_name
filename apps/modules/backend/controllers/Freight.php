<?php
/**
 * 运费模板管理。
 *
 * @author winerQin
 * @date 2016-07-21
 */
use services\FreightService;

class FreightController extends \common\controllers\Admin {

    /**
     * 运费模板列表。
     */
    public function listAction() {
        $list = FreightService::getShopFreightList();
        $this->_view->assign('list', $list);
    }

    /**
     * 添加运费模板。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $data = [
                'user_id'      => $this->admin_id,
                'send_time'    => $this->getString('send_time'),
                'bear_freight' => $this->getInt('bear_freight'),
                'rate_step'    => $this->getInt('rate_step'),
                'step_freight' => $this->getInt('fright_type'),
                'freight_name' => $this->getString('freight_name'),
                'fright_type'  => $this->getInt('fright_type'),
                'base_step'    => $this->getInt('base_step'),
                'base_freight' => $this->getInt('base_freight'),
                'no_area'      => $this->getString('no_area', ''),
                'baoyou_fee'   => $this->getInt('baoyou_fee')
            ];
            FreightService::addFreightTpl($data);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 编辑运费模板。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $data = [
                'tpl_id'       => $this->getInt('tpl_id'),
                'user_id'      => $this->admin_id,
                'send_time'    => $this->getString('send_time'),
                'bear_freight' => $this->getInt('bear_freight'),
                'rate_step'    => $this->getInt('rate_step'),
                'step_freight' => $this->getInt('fright_type'),
                'freight_name' => $this->getString('freight_name'),
                'fright_type'  => $this->getInt('fright_type'),
                'base_step'    => $this->getInt('base_step'),
                'base_freight' => $this->getInt('base_freight'),
                'no_area'      => $this->getString('no_area', ''),
                'baoyou_fee'   => $this->getInt('baoyou_fee')
            ];
            FreightService::editFreightTpl($data);
            $this->json(true, '保存成功');
        }
        $tpl_id = $this->getInt('tpl_id');
        $detail = FreightService::getFreightTplDetail($tpl_id);
        $this->_view->assign('detail', $detail);
    }

    /**
     * 删除运费模板。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $tpl_id = $this->getInt('tpl_id');
            FreightService::deleteFreightTpl($this->admin_id, $tpl_id);
            $this->json(true, '删除成功');
        }
    }
}