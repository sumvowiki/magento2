<?php

namespace Marvelic\PromoLists\Block\Adminhtml\Promotion\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('promotion_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Promotion'));
    }
}
