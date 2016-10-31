<?php
namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Controller\ResultFactory;

class Edit extends AttributeController
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('GoMage\Feed\Model\Attribute');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This attribute no exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('gomage_feed/attribute/index');
                return $resultRedirect;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->coreRegistry->register('current_attribute', $model);

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Dynamic Attributes'));
        $resultPage->getConfig()->getTitle()->prepend($id ? __("Edit Dynamic Attribute '%1'", $model->getName()) : __('New Dynamic Attribute'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Dynamic Attribute') : __('New Dynamic Attribute'),
            $id ? __('Edit Dynamic Attribute') : __('New Dynamic Attribute')
        );
        return $resultPage;
    }
}
