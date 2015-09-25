<?php
namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;

class Index extends AttributeController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Dynamic Attributes'));
        $resultPage->addBreadcrumb(__('Manage Dynamic Attributes'), __('Manage Dynamic Attributes'));
        return $resultPage;
    }
}
