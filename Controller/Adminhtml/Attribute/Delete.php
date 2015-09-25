<?php
namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\Controller\ResultFactory;

class Delete extends AttributeController
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
                $model = $this->_objectManager->create('GoMage\Feed\Model\Attribute');
                $model->setId($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the attribute.'));
                $resultRedirect->setPath('gomage_feed/attribute/index');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('gomage_feed/attribute/edit', ['id' => $this->getRequest()->getParam('id')]);
                return $resultRedirect;
            }
        }
        $this->messageManager->addError(__('We can\'t find a attribute to delete.'));
        $resultRedirect->setPath('gomage_feed/attribute/index');
        return $resultRedirect;
    }
}
