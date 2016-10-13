<?php

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Exception\LocalizedException;

class Save extends FeedController
{

    /**
     * Save feed
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($this->getRequest()->isPost() && $data) {
            try {
                $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');
                $id    = $this->getRequest()->getPost('id');
                if ($id) {
                    $model->load($id);
                }

                if (isset($data['content']) && $data['content']) {
                    $data['content'] = $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                        ->jsonEncode($data['content']);
                }

                if (isset($data['filter']) && $data['filter']) {
                    $data['filter'] = $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                        ->jsonEncode($data['filter']);
                }

                $model->addData($data);
                $model->save();
                $this->messageManager->addSuccess(__('You saved the feed.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->proceedToEdit($data);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the feed.'));
                return $this->proceedToEdit($data);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/feed/index');
    }

    /**
     * Redirect to Edit page
     *
     * @param array $data
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    private function proceedToEdit($data)
    {
        $this->_getSession()->setPageData($data);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/feed/edit', ['id' => $this->getRequest()->getPost('id', null)]);
    }
}
