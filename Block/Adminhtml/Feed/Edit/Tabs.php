<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('feed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Feed Information'));
    }
}
