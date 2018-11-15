<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Attribute;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'GoMage_Feed';
        $this->_controller = 'adminhtml_attribute';
        parent::_construct();

        $this->buttonList->update('reset', 'level', 1);
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('current_attribute')->getId()) {
            $name = $this->escapeHtml($this->_coreRegistry->registry('current_attribute')->getName());
            return __("Edit Dynamic Attribute '%1'", $name);
        } else {
            return __('New Dynamic Attribute');
        }
    }
}
