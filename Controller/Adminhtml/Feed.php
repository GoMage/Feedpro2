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

namespace GoMage\Feed\Controller\Adminhtml;

use GoMage\Feed\Helper\Data as Helper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use GoMage\Core\Helper\Data as coreHelper;
use GoMage\Feed\Model\FeedFactory;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use Magento\Backend\Model\Session;

abstract class Feed extends Action
{
    /**
     * @var FeedFactory
     */
    protected $feed;

    /**
     * @var jsonHelper
     */
    protected $jsonHelper;

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var coreHelper
     */
    private  $coreHelper;

    /**
     * Feed constructor.
     * @param Context $context
     * @param coreHelper $coreHelper
     */
    public function __construct(
        Action\Context $context,
        FeedFactory $feed,
        jsonHelper $jsonHelper,
        Session $session,
        coreHelper $coreHelper
    )
    {
        $this->coreHelper = $coreHelper;
        $this->feed = $feed;
        $this->jsonHelper = $jsonHelper;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function createPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('GoMage_Feed::marketing_feed')
            ->addBreadcrumb(__('GoMage Feeds'), __('GoMage Feeds'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->coreHelper->isA(Helper::MODULE_NAME)) {
            return $this->_authorization->isAllowed('GoMage_Feed::feeds');
        }
        $this->messageManager->addError('Please activate GoMage Feed Pro');
        return false;
    }
}
