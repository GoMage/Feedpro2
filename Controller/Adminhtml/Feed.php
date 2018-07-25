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
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

abstract class Feed extends Action
{
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
        $info = $this->_objectManager->get('GoMage\Feed\Helper\Data')->ga();

        if (isset($info['d']) && isset($info['c']) && (int)$info['c']) {
            return $this->_authorization->isAllowed('GoMage_Feed::feeds');
        }
        $this->messageManager->addError('Please activate GoMage Feed Pro');
        return false;
    }
}
