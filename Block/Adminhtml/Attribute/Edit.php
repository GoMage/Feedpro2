<?php
namespace GoMage\Feed\Block\Adminhtml\Attribute;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'GoMage_Feed';
        $this->_controller = 'adminhtml_attribute';
        parent::_construct();
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('current_attribute')->getId()) {
            $name = $this->escapeHtml($this->coreRegistry->registry('current_attribute')->getName());
            return __("Edit Dynamic Attribute '%1'", $name);
        } else {
            return __('New Dynamic Attribute');
        }
    }
}
