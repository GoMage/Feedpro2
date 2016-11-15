<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Controller\ResultFactory;

class Generate extends FeedController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $redirectResult */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($id) {
            try {
                /** @var \GoMage\Feed\Model\Generator $generator */
                $generator = $this->_objectManager->get('GoMage\Feed\Model\Generator');
                $generator->generate($id);
                
                $this->messageManager->addSuccess(__('Feed has been successfully generated.'));
                $resultRedirect->setPath('gomage_feed/feed/edit', ['id' => $id]);
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('gomage_feed/feed/edit', ['id' => $this->getRequest()->getParam('id')]);
                return $resultRedirect;
            }
        }
        $this->messageManager->addError(__('We can\'t find a feed to generate.'));
        $resultRedirect->setPath('gomage_feed/feed/index');
        return $resultRedirect;
    }
}
