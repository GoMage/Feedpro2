<?php

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
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Csv $csv,
        Xml $xml,
        \GoMage\Feed\Helper\Data $helper,
        array $data = []
    ) {
        $this->_xml    = $xml;
        $this->_csv    = $csv;
        $this->_helper = $helper;
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
            'main_fieldset',
            ['legend' => __('Feed Information')]
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField('type', 'hidden', ['name' => 'type']);

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
        $fieldset->addField(
            'comments',
            'note',
            [
                'label' => __('Access Url'),
                'title' => __('Access Url'),
                'text'  => $url ? '<a href="' . $url . '" target="_blank">' . $url . '</a>' : '',
            ]
        );

        $fieldset->addField(
            'filename',
            'text',
            [
                'name'     => 'filename',
                'required' => true,
                'label'    => __('File Name'),
                'title'    => __('File Name'),
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
