<?php

namespace GoMage\Feed\Block\Adminhtml\Attribute\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \GoMage\Feed\Model\Attribute */
        $model = $this->_coreRegistry->registry('current_attribute');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('attribute_');

        $fieldset = $form->addFieldset(
            'main_fieldset',
            ['legend' => __('Attribute Information')]
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'code',
            'text',
            [
                'name'     => 'code',
                'required' => true,
                'label'    => __('Code'),
                'title'    => __('Code'),
                'class'    => 'validate-identifier',
                'note'     => __('For internal use. Must be unique with no spaces'),
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'name'     => 'name',
                'required' => true,
                'label'    => __('Name'),
                'title'    => __('Name'),
                'note'     => __('e.g. "Custom Price", "Google Category"...'),
            ]
        );

        $this->_eventManager->dispatch('gomage_feed_attribute_tab_main_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Attribute Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Attribute Information');
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
