<?php

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends AttributeController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('edit');
    }
}
