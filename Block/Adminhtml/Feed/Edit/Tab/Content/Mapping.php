<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Content;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Mapping extends Widget implements RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'feed/edit/content/mapping.phtml';

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
     * @var \GoMage\Feed\Model\Config\Source\Mapping\Type
     */
    protected $_type;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Mapping\ExtendedType
     */
    protected $_extendedType;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Mapping\Output
     */
    protected $_output;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \GoMage\Feed\Model\Config\Source\Mapping\Type $type
     * @param \GoMage\Feed\Model\Config\Source\Mapping\ExtendedType $extendedType
     * @param \GoMage\Feed\Model\Config\Source\Mapping\Output $output
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \GoMage\Feed\Model\Config\Source\Mapping\Type $type,
        \GoMage\Feed\Model\Config\Source\Mapping\ExtendedType $extendedType,
        \GoMage\Feed\Model\Config\Source\Mapping\Output $output,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
        array $data = []
    ) {

        $this->_coreRegistry      = $registry;
        $this->_jsonHelper        = $jsonHelper;
        $this->_type              = $type;
        $this->_extendedType      = $extendedType;
        $this->_output            = $output;
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
            ['label' => __('Add New Row'), 'onclick' => 'return mappingControl.addItem()', 'class' => 'add']
        );
        $button->setName('add_feed_mapping_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    public function getType()
    {
        return $this->_type->toOptionArray();
    }

    /**
     * @return array
     */
    public function getExtendedType()
    {
        return $this->_extendedType->toOptionArray();
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->_output->toOptionArray();
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        // TODO: add mapper; add dynamic attributes
        $items = $this->_collectionFactory->create()->getItems();

        $items[] = new \Magento\Framework\DataObject([
                'attribute_code' => 'id',
                'store_label'    => __('Product Id')
            ]
        );

        //TODO: Hard code
        $items[] = new \Magento\Framework\DataObject([
                'attribute_code' => 'category_subcategory',
                'store_label'    => __('Category > SubCategory')
            ]
        );

        $items[] = new \Magento\Framework\DataObject([
                'attribute_code' => 'free_shipping_feed',
                'store_label'    => __('* Free Shipping Feed')
            ]
        );

        return $items;
    }

}
