<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Controller\ResultFactory;

class Stop extends FeedController
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
                /** @var \GoMage\Feed\Model\Feed $model */
                $model = $this->feed->create()->load($id);
                $model->setStatus(\GoMage\Feed\Model\Config\Source\Status::STOPPED);

                $this->messageManager->addSuccessMessage(__('Feed has been successfully stopped.'));
                $resultRedirect->setPath('gomage_feed/feed/edit', ['id' => $id]);
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('gomage_feed/feed/edit', ['id' => $this->getRequest()->getParam('id')]);
                return $resultRedirect;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a feed to stop.'));
        $resultRedirect->setPath('gomage_feed/feed/index');
        return $resultRedirect;
    }
}
