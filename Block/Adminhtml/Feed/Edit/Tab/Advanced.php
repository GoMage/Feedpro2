<?php

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
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Yesno $yesNo
     * @param Enabledisable $enableDisable
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        Enabledisable $enableDisable,
        array $data = []
    ) {

        $this->_yesNo         = $yesNo;
        $this->_enableDisable = $enableDisable;

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
            'advanced_fieldset',
            ['legend' => __('File Creation Settings')]
        );

        $fieldset->addField(
            'limit',
            'text',
            [
                'name'     => 'limit',
                'label'    => __('Number of Products'),
                'title'    => __('Number of Products'),
                'class'    => 'validate-number',
                'required' => true,
                'note'     => __('"0" = All products. This option allows to optimize file creation for low memory servers. You have to increase php memory_limit before changing this value to maximum.'),
            ]
        );

        //is_out_of_stock

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
