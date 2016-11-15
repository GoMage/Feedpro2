<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Attribute\Edit\Tab\Condition;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Rows extends Widget implements RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'attribute/edit/condition/rows.phtml';

    /**
     * Form element instance
     *
     * @var \Magento\Framework\Data\Form\Element\AbstractElement
     */
    protected $_element;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Field\AttributeType
     */
    protected $_type;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Operator
     */
    protected $_operator;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \GoMage\Feed\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \GoMage\Feed\Model\Config\Source\Field\AttributeType $type,
        \GoMage\Feed\Model\Config\Source\Operator $operator,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_helper       = $helper;
        $this->_jsonHelper   = $jsonHelper;
        $this->_type         = $type;
        $this->_operator     = $operator;
        parent::__construct($context, $data);
    }

    /**
     * @return \GoMage\Feed\Model\Attribute
     */
    public function getAttribute()
    {
        return $this->_coreRegistry->registry('current_attribute');
    }

    /**
     * Render HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * Set form element instance
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Price\Group\AbstractGroup
     */
    public function setElement(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this;
    }

    /**
     * Retrieve form element instance
     *
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Prepare feed content values
     *
     * @return array
     */
    public function getValue()
    {
        $data = $this->getElement()->getValue();
        if ($data) {
            $items = $this->_jsonHelper->jsonDecode($data);
            if (is_array($items)) {
                return $data;
            }
        }
        return $this->_jsonHelper->jsonEncode([]);
    }

    /**
     * Retrieve 'add row' button HTML
     *
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }


    /**
     * Prepare global layout
     * Add "Add row" button to layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            ['label' => __('Add New Value'), 'id' => 'add_new_row_button', 'class' => 'add']
        );
        $button->setName('add_new_row_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }


    /**
     * @return array
     */
    public function getProductAttributes()
    {
        return $this->_helper->getProductAttributes();
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->_type->toOptionArray();
    }

    /**
     * @return array
     */
    public function getOperators()
    {
        return $this->_operator->toOptionArray();
    }

}
