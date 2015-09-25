<?php

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends AttributeController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $attributeIds = $this->getRequest()->getParam('attributes');
        if (!is_array($attributeIds)) {
            $this->messageManager->addError(__('Please select attributes.'));
        } else {
            try {
                foreach ($attributeIds as $attributeId) {
                    $model = $this->_objectManager->create('GoMage\Feed\Model\Attribute')->load($attributeId);
                    $model->delete();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were deleted.', count($attributeIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('gomage_feed/attribute/index');
        return $resultRedirect;
    }
}
