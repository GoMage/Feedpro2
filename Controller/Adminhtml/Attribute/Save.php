<?php

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\Exception\LocalizedException;

class Save extends AttributeController
{

    /**
     * Save attribute
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($this->getRequest()->isPost() && $data) {
            try {
                $model = $this->_objectManager->create('GoMage\Feed\Model\Attribute');
                $id    = $this->getRequest()->getPost('id');
                if ($id) {
                    $model->load($id);
                }
                $model->addData($data);
                $model->save();
                $this->messageManager->addSuccess(__('You saved the attribute.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->proceedToEdit($data);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the attribute.'));
                return $this->proceedToEdit($data);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/attribute/index');
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
        return $redirectResult->setPath('gomage_feed/attribute/edit', ['id' => $this->getRequest()->getPost('id', null)]);
    }
}
