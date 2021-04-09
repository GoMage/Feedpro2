<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use GoMage\Feed\Model\Feed;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Config\Model\Config\Source\Enabledisable;

class Advanced extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @var Enabledisable
     */
    protected $_enableDisable;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Config\Model\Config\Source\Locale\Weekdays
     */
    protected $_weekdays;

    /**
     * @var \GoMage\Feed\Model\Config\Source\DateTime\Hour
     */
    protected $_hour;

    /**
     * @var \GoMage\Feed\Model\Config\Source\DateTime\Interval
     */
    protected $_interval;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        Enabledisable $enableDisable,
        \GoMage\Feed\Model\Config\Source\Visibility $visibility,
        \Magento\Config\Model\Config\Source\Locale\Weekdays $weekdays,
        \GoMage\Feed\Model\Config\Source\DateTime\Hour $hour,
        \GoMage\Feed\Model\Config\Source\DateTime\Interval $interval,
        array $data = []
    ) {
        $this->_yesNo         = $yesNo;
        $this->_enableDisable = $enableDisable;
        $this->_visibility    = $visibility;
        $this->_weekdays      = $weekdays;
        $this->_hour          = $hour;
        $this->_interval      = $interval;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model Feed */
        $model = $this->_coreRegistry->registry('current_feed');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('feed_');

        $fieldset = $form->addFieldset(
            'file_creation_fieldset',
            ['legend' => __('File Creation Settings')]
        );

        $fieldset->addField(
            'limit',
            'text',
            [
                'name' => 'limit',
                'label' => __('Number of Products'),
                'title' => __('Number of Products'),
                'class' => 'validate-not-negative-number integer',
                'required' => true,
                'note' => __('"0" = All products. This option allows to optimize file creation for low memory servers. You have to increase php memory_limit before changing this value to maximum.'),
            ]
        );

        $fieldset->addField(
            'is_out_of_stock',
            'select',
            [
                'name'   => 'is_out_of_stock',
                'label'  => __('Export Out of Stock Products'),
                'title'  => __('Export Out of Stock Products'),
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'is_disabled',
            'select',
            [
                'name'   => 'is_disabled',
                'label'  => __('Export Disabled Products'),
                'title'  => __('Export Disabled Products'),
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'visibility',
            'select',
            [
                'name'   => 'visibility',
                'label'  => __('Products Visibility'),
                'title'  => __('Products Visibility'),
                'values' => $this->_visibility->toOptionArray(),
            ]
        );

        $fieldset = $form->addFieldset(
            'auto_generate_fieldset',
            ['legend' => __('Auto-generate Settings')]
        );

        $fieldset->addField(
            'is_generate',
            'select',
            [
                'name'   => 'is_generate',
                'label'  => __('Status'),
                'title'  => __('Status'),
                'values' => $this->_enableDisable->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'generate_day',
            'multiselect',
            [
                'name'   => 'generate_day[]',
                'label'  => __('Available Days'),
                'title'  => __('Available Days'),
                'values' => $this->_weekdays->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'generate_hour',
            'select',
            [
                'name'   => 'generate_hour',
                'label'  => __('Active From, hour'),
                'title'  => __('Active From, hour'),
                'values' => $this->_hour->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'generate_hour_to',
            'select',
            [
                'name'   => 'generate_hour_to',
                'label'  => __('Active To, hour'),
                'title'  => __('Active To, hour'),
                'values' => $this->_hour->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'generate_interval',
            'select',
            [
                'name'   => 'generate_interval',
                'label'  => __('Interval, hours'),
                'title'  => __('Interval, hours'),
                'values' => $this->_interval->toOptionArray(),
            ]
        );

        $fieldset = $form->addFieldset(
            'auto_upload_fieldset',
            ['legend' => __('Auto-upload Settings')]
        );

        $fieldset->addField(
            'is_upload',
            'select',
            [
                'name'   => 'is_upload',
                'label'  => __('Status'),
                'title'  => __('Status'),
                'values' => $this->_enableDisable->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'upload_day',
            'multiselect',
            [
                'name'   => 'upload_day[]',
                'label'  => __('Available Days'),
                'title'  => __('Available Days'),
                'values' => $this->_weekdays->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'upload_hour',
            'select',
            [
                'name'   => 'upload_hour',
                'label'  => __('Active From, hour'),
                'title'  => __('Active From, hour'),
                'values' => $this->_hour->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'upload_hour_to',
            'select',
            [
                'name'   => 'upload_hour_to',
                'label'  => __('Active To, hour'),
                'title'  => __('Active To, hour'),
                'values' => $this->_hour->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'upload_interval',
            'select',
            [
                'name'   => 'upload_interval',
                'label'  => __('Interval, hours'),
                'title'  => __('Interval, hours'),
                'values' => $this->_interval->toOptionArray(),
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
        return __('Advanced Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Advanced Settings');
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
