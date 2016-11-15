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

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Content;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Csv extends Widget implements RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'feed/edit/content/csv.phtml';

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
     * @var \GoMage\Feed\Model\Config\Source\Field\BaseType
     */
    protected $_baseType;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Field\ExtendedType
     */
    protected $_extendedType;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Output
     */
    protected $_output;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \GoMage\Feed\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \GoMage\Feed\Model\Config\Source\Field\BaseType $type,
        \GoMage\Feed\Model\Config\Source\Field\ExtendedType $extendedType,
        \GoMage\Feed\Model\Config\Source\Output $output,
        array $data = []
    ) {

        $this->_coreRegistry = $registry;
        $this->_helper       = $helper;
        $this->_jsonHelper   = $jsonHelper;
        $this->_baseType     = $type;
        $this->_extendedType = $extendedType;
        $this->_output       = $output;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve current feed instance
     *
     * @return \GoMage\Feed\Model\Feed
     */
    public function getFeed()
    {
        return $this->_coreRegistry->registry('current_feed');
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
     * @return string
     */
    public function getValue()
    {
        $values = [];
        $data   = $this->getElement()->getValue();

        if ($data) {
            $items = $this->_jsonHelper->jsonDecode($data);
            if (is_array($items)) {
                $values = $this->_sortValues($items);
            }
        }
        return $this->_jsonHelper->jsonEncode($values);
    }

    /**
     * Sort values
     *
     * @param array $data
     * @return array
     */
    protected function _sortValues($data)
    {
        return $data;
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
            ['label' => __('Add New Row'), 'id' => 'add_new_row_button', 'class' => 'add']
        );
        $button->setName('add_new_row_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    public function getBaseTypes()
    {
        return $this->_baseType->toOptionArray();
    }

    /**
     * @return array
     */
    public function getExtendedTypes()
    {
        return $this->_extendedType->toOptionArray();
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        return $this->_output->toOptionArray();
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
    public function getDynamicAttributes()
    {
        return $this->_helper->getDynamicAttributes();
    }

}
