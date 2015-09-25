<?php
namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Controller\ResultFactory;

class Delete extends FeedController
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
                $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');
                $model->setId($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the feed.'));
                $resultRedirect->setPath('gomage_feed/feed/index');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('gomage_feed/feed/edit', ['id' => $this->getRequest()->getParam('id')]);
                return $resultRedirect;
            }
        }
        $this->messageManager->addError(__('We can\'t find a feed to delete.'));
        $resultRedirect->setPath('gomage_feed/feed/index');
        return $resultRedirect;
    }
}
