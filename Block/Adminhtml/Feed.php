<?php

namespace GoMage\Feed\Block\Adminhtml;

class Feed extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller     = 'feed';
        $this->_headerText     = __('Manage Feeds');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
    }
}
