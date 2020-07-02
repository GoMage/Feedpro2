<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Attribute\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * Prepare form
     *
     * @return $this
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

}
