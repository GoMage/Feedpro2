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
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Attribute\Edit\Tab;

class Condition extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \GoMage\Feed\Helper\Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

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
            'conditions_fieldset',
            ['legend' => __('Conditions and Values'), 'class' => 'fieldset-wide']
        );

        $field = $fieldset->addField(
            'content',
            'text',
            ['name' => 'content', 'class' => 'requried-entry', 'value' => $model->getContent()]
        );

        $renderer = $this->getLayout()
            ->createBlock('GoMage\Feed\Block\Adminhtml\Attribute\Edit\Tab\Condition\Rows')
            ->setTemplate('GoMage_Feed::attribute/edit/condition/rows.phtml');

        $field->setRenderer($renderer);

        $fieldset->addField(
            'default_value',
            'select',
            [
                'name'     => 'default_value',
                'required' => true,
                'label'    => __('Use Default Attribute'),
                'title'    => __('Use Default Attribute'),
                'values'   => $this->_helper->getProductAttributes(),
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
        return __('Conditions and Values');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Conditions and Values');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

}
