<?php

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use Magento\Config\Model\Config\Source\Yesno;
use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Config\Source\Delimiter;
use GoMage\Feed\Model\Config\Source\Enclosure;

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

        if ($model->getType() == Feed::XML_TYPE) {
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
                'is_header',
                'select',
                [
                    'name'   => 'is_header',
                    'label'  => __('Show Header'),
                    'title'  => __('Show Header'),
                    'values' => $this->_yesNo->toOptionArray(),
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
                'delimiter_prefix',
                'text',
                [
                    'name'  => 'delimiter_prefix',
                    'label' => __('Prefix Delimiter'),
                    'title' => __('Prefix Delimiter'),
                ]
            );

            $fieldset->addField(
                'delimiter_suffix',
                'text',
                [
                    'name'  => 'delimiter_suffix',
                    'label' => __('Suffix Delimiter'),
                    'title' => __('Suffix Delimiter'),
                ]
            );

            $fieldset->addField(
                'is_remove_lb',
                'select',
                [
                    'name'   => 'is_remove_lb',
                    'label'  => __('Remove line break symbols'),
                    'title'  => __('Remove line break symbols'),
                    'note'   => __('This option allows to remove line break symbols from a data feed.'),
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
                'GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab\Content\Mapping'
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

        $this->_eventManager->dispatch('gomage_feed_tab_content_prepare_form', ['form' => $form]);

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
