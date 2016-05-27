<?php
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
                /** @var \GoMage\Feed\Model\Feed $model */
                $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');
                $model->load($id);
                $model->generate();
                $model->setData('generated_at', date('Y-m-j H:i:s', time()));
                $model->save();

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
