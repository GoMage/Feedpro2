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

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use GoMage\Core\Helper\Data as coreHelper;

class Type extends FeedController
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Type constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param coreHelper $coreHelper
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        coreHelper $coreHelper
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context,$coreHelper);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');
        $this->_coreRegistry->register('current_feed', $model);

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Feeds'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Feed'));
        $resultPage->addBreadcrumb(__('Feed'), __('New Feed'));
        return $resultPage;
    }
}
