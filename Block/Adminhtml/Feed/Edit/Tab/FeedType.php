<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use GoMage\Feed\Model\Feed;

class FeedType extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \GoMage\Feed\Model\Config\Source\FeedType
     */
    protected $_type;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \GoMage\Feed\Model\Config\Source\FeedType $type,
        array $data = []
    ) {
        $this->_type = $type;
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
            'type_fieldset',
            ['legend' => __('Feed Type')]
        );

        $fieldset->addField(
            'type',
            'select',
            [
                'name'   => 'type',
                'label'  => __('Feed Type'),
                'title'  => __('Feed Type'),
                'values' => $this->_type->toOptionArray(),
            ]
        );

        $fieldset->addField('switch_type', 'hidden', ['name' => 'switch_type']);
        $model->setData('switch_type', true);

        $form->setValues($model->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('gomage_feed_tab_advanced_prepare_form', ['form' => $form]);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Feed Type');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Feed Type');
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
