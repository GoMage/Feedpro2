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
use Magento\Backend\Model\Session;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use Magento\Framework\Registry;
use GoMage\Core\Helper\Data as coreHelper;
use GoMage\Feed\Model\FeedFactory;

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
        FeedFactory $feed,
        jsonHelper $jsonHelper,
        Session $session,
        coreHelper $coreHelper
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $feed, $jsonHelper, $session, $coreHelper);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $model = $this->feed->create();
        $this->_coreRegistry->register('current_feed', $model);

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Feeds'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Feed'));
        $resultPage->addBreadcrumb(__('Feed'), __('New Feed'));
        return $resultPage;
    }
}
