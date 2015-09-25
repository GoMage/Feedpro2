<?php

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends FeedController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $feedIds = $this->getRequest()->getParam('feeds');
        if (!is_array($feedIds)) {
            $this->messageManager->addError(__('Please select feeds.'));
        } else {
            try {
                foreach ($feedIds as $feedId) {
                    $model = $this->_objectManager->create('GoMage\Feed\Model\Feed')->load($feedId);
                    $model->delete();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were deleted.', count($feedIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('gomage_feed/feed/index');
        return $resultRedirect;
    }
}
