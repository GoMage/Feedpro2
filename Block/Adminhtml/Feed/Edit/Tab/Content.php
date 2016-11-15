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

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use Magento\Config\Model\Config\Source\Yesno;
use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Config\Source\Csv\Delimiter;
use GoMage\Feed\Model\Config\Source\Csv\Enclosure;

class Content extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @var Delimiter
     */
    protected $_delimiter;

    /**
     * @var Enclosure
     */
    protected $_enclosure;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Yesno $yesNo
     * @param Delimiter $delimiter
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        Delimiter $delimiter,
        Enclosure $enclosure,
        array $data = []
    ) {
        $this->_yesNo     = $yesNo;
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;

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
            'content_fieldset',
            ['legend' => __('Content Settings')]
        );

        if ($model->getType() == \GoMage\Feed\Model\Config\Source\FeedType::XML_TYPE) {
            $fieldset->addField(
                'content',
                'textarea',
                [
                    'name'  => 'content',
                    'class' => 'requried-entry',
                    'label' => __('Content'),
                    'title' => __('Content'),
                ]
            );
        } else {

            $fieldset->addField(
                'delimiter',
                'select',
                [
                    'name'   => 'delimiter',
                    'label'  => __('Delimiter'),
                    'title'  => __('Delimiter'),
                    'values' => $this->_delimiter->toOptionArray(),
                ]
            );

            $fieldset->addField(
                'enclosure',
                'select',
                [
                    'name'   => 'enclosure',
                    'label'  => __('Enclosure'),
                    'title'  => __('Enclosure'),
                    'values' => $this->_enclosure->toOptionArray(),
                ]
            );

            $fieldset->addField(
                'is_header',
                'select',
                [
                    'name'   => 'is_header',
                    'label'  => __('Show Header'),
                    'title'  => __('Show Header'),
                    'values' => $this->_yesNo->toOptionArray(),
                ]
            );

            $isAdditionHeader = $fieldset->addField(
                'is_addition_header',
                'select',
                [
                    'name'   => 'is_addition_header',
                    'label'  => __('Use Addition Header'),
                    'title'  => __('Use Addition Header'),
                    'values' => $this->_yesNo->toOptionArray(),
                ]
            );

            $additionHeader = $fieldset->addField(
                'addition_header',
                'textarea',
                [
                    'name'    => 'addition_header',
                    'label'   => __('Addition Header'),
                    'title'   => __('Addition Header'),
                    'values'  => $this->_yesNo->toOptionArray(),
                    'depends' => 'is_addition_header',
                ]
            );

            $fieldset = $form->addFieldset(
                'mapping_fieldset',
                ['legend' => __('Fields Mapping')]
            );

            $field = $fieldset->addField(
                'content',
                'text',
                ['name' => 'content', 'class' => 'requried-entry', 'value' => $model->getData('content')]
            );

            $renderer = $this->getLayout()->createBlock(
                'GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Content\Csv'
            );
            $field->setRenderer($renderer);

            $this->setChild(
                'form_after',
                $this->getLayout()->createBlock(
                    'Magento\Backend\Block\Widget\Form\Element\Dependence',
                    'gomage.feed.tab_content.element.dependence'
                )
                    ->addFieldMap($isAdditionHeader->getHtmlId(), $isAdditionHeader->getName())
                    ->addFieldMap($additionHeader->getHtmlId(), $additionHeader->getName())
                    ->addFieldDependence(
                        $additionHeader->getName(),
                        $isAdditionHeader->getName(),
                        '1'
                    )
            );
        }


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
        return __('Content Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Content Settings');
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
