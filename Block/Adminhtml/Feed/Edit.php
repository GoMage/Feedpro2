<?php
namespace GoMage\Feed\Block\Adminhtml\Feed;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'GoMage_Feed';
        $this->_controller = 'adminhtml_feed';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class'          => 'save',
                'label'          => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );

        if ($feed = $this->coreRegistry->registry('current_feed')) {
            if ($feed->getId()) {
                $url = $this->getUrl('gomage_feed/feed/generate', [
                        'id'                                                  => $feed->getId(),
                        \Magento\Store\Api\StoreResolverInterface::PARAM_NAME => $feed->getStoreId()]
                );
                $this->buttonList->add(
                    'generate',
                    [
                        'label'   => __('Generate'),
                        'onclick' => 'setLocation(\'' . $url . '\')',
                        'class'   => 'generate'
                    ],
                    5
                );
            }
        }


    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('current_feed')->getId()) {
            $name = $this->escapeHtml($this->coreRegistry->registry('current_feed')->getName());
            return __("Edit Feed '%1'", $name);
        } else {
            return __('New Feed');
        }
    }
}
