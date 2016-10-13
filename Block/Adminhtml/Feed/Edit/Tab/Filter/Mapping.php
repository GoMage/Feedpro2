<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Filter;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Mapping extends Widget implements RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'feed/edit/filter/mapping.phtml';

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
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Filter\Condition
     */
    protected $_condition;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \GoMage\Feed\Model\Config\Source\Filter\Condition $condition,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
        array $data = []
    ) {

        $this->_coreRegistry      = $registry;
        $this->_jsonHelper        = $jsonHelper;
        $this->_condition         = $condition;
        $this->_collectionFactory = $collectionFactory;

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
     * Prepare feed content values
     *
     * @return array
     */
    public function getValues()
    {
        $values = [];
        $data   = $this->getElement()->getValue();

        if ($data) {
            $items = $this->_jsonHelper->jsonDecode($data);
            if (is_array($items)) {
                $items = $this->_sortValues($items);
                foreach ($items as $item) {
                    $values[] = new \Magento\Framework\DataObject($item);
                }
            }
        }
        return $values;
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
            ['label' => __('Add Filter Row'), 'onclick' => 'return filterControl.addItem()', 'class' => 'add']
        );
        $button->setName('add_feed_filter_add_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }


    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_collectionFactory->create()->load();
    }

    /**
     * @return array
     */
    public function getCondition()
    {
        return $this->_condition->toOptionArray();
    }

    //TODO: hard code only for one attribute
    public function getAttributeValues()
    {
        $attribute = $this->_collectionFactory->create()
            ->addFilter('attribute_code', ['eq' => 'brand'])
            ->getFirstItem();

        $options = [];

        if ($attribute->usesSource()) {
            // should attribute has index (option value) instead of a label?
            $index = 'label';

            // only default (admin) store values used
            $attribute->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);

            try {
                foreach ($attribute->getSource()->getAllOptions(false) as $option) {
                    foreach (is_array($option['value']) ? $option['value'] : [$option] as $innerOption) {
                        if (strlen($innerOption['value'])) {
                            // skip ' -- Please Select -- ' option
                            $options[$innerOption['value']] = (string)$innerOption[$index];
                        }
                    }
                }
            } catch (\Exception $e) {
                // ignore exceptions connected with source models
            }
        }

        asort($options);

        return $options;
    }

}
