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

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Config\Source\Extension\Csv;
use GoMage\Feed\Model\Config\Source\Extension\Xml;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var Csv
     */
    protected $_csv;

    /**
     * @var Xml
     */
    protected $_xml;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;
    protected $currencyModel;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Csv $csv,
        Xml $xml,
        \GoMage\Feed\Helper\Data $helper,
        \Magento\Directory\Model\Currency $currencyModel,
        array $data = []
    ) {
        $this->_xml    = $xml;
        $this->timezone = $context->getLocaleDate();
        $this->_csv    = $csv;
        $this->_helper = $helper;
        $this->currencyModel = $currencyModel;
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
        if($model->getData('generated_at'))
        {
            $localizedDateTimeISO = $this->timezone->date(new \DateTime($model->getData('generated_at')))
                ->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
            $model->setData('generated_at', $localizedDateTimeISO);
        }

        if(!$model->getData('type') && $this->getRequest()->getParam('type'))
        {
            $model->setData('type' , $this->getRequest()->getParam('type'));
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('feed_');

        $fieldset = $form->addFieldset(
            'main_fieldset',
            ['legend' => __('Feed Information')]
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField('type', 'hidden', [
            'name' => 'type_feed',
        ]);

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

        $url = $this->_helper->getFeedUrl($model->getFullFileName(), $model->getStoreId());
        $openNewTab = $model->getType() == \GoMage\Feed\Model\Config\Source\FeedType::XML_TYPE ? 'target="_blank"' : '';
        $fieldset->addField(
            'comments',
            'note',
            [
                'label' => __('Access Url'),
                'title' => __('Access Url'),
                'text'  => '<a href="' . $url . '" ' . $openNewTab . '>' . $url . '</a>',
            ]
        );

        $fieldset->addField(
            'filename',
            'text',
            [
                'name' => 'filename',
                'required' => true,
                'label' => __('File Name'),
                'title' => __('File Name'),
                'class' => 'validate-no-spaces'
            ]
        );

        $fieldset->addField(
            'file_ext',
            'select',
            [
                'name'   => 'file_ext',
                'label'  => __('File Extension'),
                'title'  => __('File Extension'),
                'values' => $model->getType() == \GoMage\Feed\Model\Config\Source\FeedType::XML_TYPE ? $this->_xml->toOptionArray() : $this->_csv->toOptionArray(),
            ]
        );

        if (!$model->getFileExt()) {
            $fileExt = $model->getType() == \GoMage\Feed\Model\Config\Source\FeedType::XML_TYPE ?
                \GoMage\Feed\Model\Config\Source\Extension\Xml::XML :
                \GoMage\Feed\Model\Config\Source\Extension\Csv::CSV;
            $model->setFileExt($fileExt);
        }

        $field    = $fieldset->addField(
            'store_id',
            'select',
            [
                'name'     => 'store_id',
                'label'    => __('Store View'),
                'title'    => __('Store View'),
                'required' => true,
                'values'   => $this->_helper->getStoreOptionArray(),
            ]
        );
        $currencyCodes = [];
        foreach ($this->currencyModel->getConfigAllowCurrencies() as $item) {
            $currencyCodes[$item] = $item;
        }
        $field    = $fieldset->addField(
            'currency_code',
            'select',
            [
                'name'     => 'currency_code',
                'label'    => __('Currency code'),
                'title'    => __('Currency code'),
                'required' => true,
                'values'   => $currencyCodes,
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
        );
        $field->setRenderer($renderer);

        $fieldset->addField(
            'generated_at',
            'label',
            [
                'name'  => 'generated_at',
                'label' => __('Last Generated'),
                'title' => __('Last Generated'),
            ]
        );

        $fieldset->addField(
            'generation_time',
            'label',
            [
                'name'  => 'generation_time',
                'label' => __('Generation Time'),
                'title' => __('Generation Time'),
            ]
        );

        $fieldset->addField('uploaded_at',
            'label',
            [
                'name'  => 'uploaded_at',
                'label' => __('Last Uploaded'),
                'title' => __('Last Uploaded'),
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
        return __('Feed Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Feed Information');
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
