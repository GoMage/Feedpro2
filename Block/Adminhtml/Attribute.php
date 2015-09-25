<?php

namespace GoMage\Feed\Block\Adminhtml;

class Attribute extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller     = 'attribute';
        $this->_headerText     = __('Manage Dynamic Attributes');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
    }
}
