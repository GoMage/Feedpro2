<?php
namespace GoMage\Feed\Block\Adminhtml\Feed;

use GoMage\Feed\Model\Feed;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

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
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        /* @var $model Feed */
        $model = $this->_coreRegistry->registry('current_feed');

        $this->_objectId   = 'id';
        $this->_blockGroup = 'GoMage_Feed';
        $this->_controller = 'adminhtml_feed';

        parent::_construct();

        if ($model && $model->getId()) {

            if ($model->getStatus() == \GoMage\Feed\Model\Config\Source\Status::IN_PROGRESS) {
                $url = $this->getUrl('gomage_feed/feed/stop', [
                        'id' => $model->getId()
                    ]
                );
                $this->buttonList->add(
                    'stop',
                    [
                        'label'   => __('Stop Generate'),
                        'onclick' => 'setLocation(\'' . $url . '\')',
                        'class'   => 'stop'
                    ],
                    5
                );
            } else {
                $url = $this->getUrl('gomage_feed/feed/generate', [
                        'id'                                                  => $model->getId(),
                        \Magento\Store\Api\StoreResolverInterface::PARAM_NAME => $model->getStoreId()]
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

        if (!$model->getType()) {
            $this->buttonList->update('save', 'label', __('Continue'));
        }

    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('current_feed')->getId()) {
            $name = $this->escapeHtml($this->_coreRegistry->registry('current_feed')->getName());
            return __("Edit Feed '%1'", $name);
        } else {
            return __('New Feed');
        }
    }
}
