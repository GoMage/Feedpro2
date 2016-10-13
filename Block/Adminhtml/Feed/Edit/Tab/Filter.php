<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Config\Source\Filter\Type as FilterType;


class Filter extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var FilterType
     */
    protected $_filterType;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param FilterType $filterType
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        FilterType $filterType,
        array $data = []
    ) {

        $this->_filterType = $filterType;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model Feed */
        $model = $this->_coreRegistry->registry('current_feed');


        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('feed_');

        $fieldset = $form->addFieldset(
            'filter_fieldset',
            ['legend' => __('Filter Configuration')]
        );


        $fieldset->addField(
            'filter_type',
            'select',
            [
                'name'   => 'filter_type',
                'label'  => __('Filter All'),
                'title'  => __('Filter All'),
                'values' => $this->_filterType->toOptionArray(),
            ]
        );

        $field = $fieldset->addField(
            'filter',
            'text',
            ['name' => 'filter', 'class' => 'requried-entry', 'value' => $model->getData('filter')]
        );

        $renderer = $this->getLayout()->createBlock(
            'GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Filter\Mapping'
        );
        $field->setRenderer($renderer);


        $form->setValues($model->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('gomage_feed_tab_filter_prepare_form', ['form' => $form]);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Filter Configuration');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Filter Configuration');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
