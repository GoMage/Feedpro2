<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Block\Adminhtml\Feed;

use GoMage\Feed\Model\Feed;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

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
                        'label'   => __('Stop Generation'),
                        'onclick' => 'setLocation(\'' . $url . '\')',
                        'class'   => 'stop'
                    ],
                    0
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
                        'onclick' => 'goMageFeedGenerate(\'' . $url . '\')',
                        'class'   => 'generate'
                    ],
                    0
                );
            }
        }

        if (!$model->getType()) {
            $this->buttonList->update('save', 'label', __('Continue'));
        }

        $this->buttonList->update('save', 'level', -1);
        $this->buttonList->update('delete', 'level', 1);
        $this->buttonList->update('reset', 'level', 0);
    }

    /**
     * @return string
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
