<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Model\AttributeFactory;
use Magento\Backend\Model\Session;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use GoMage\Core\Helper\Data as coreHelper;
class Edit extends AttributeController
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param coreHelper $coreHelper
     */
    public function __construct(
        Context $context,
        coreHelper $coreHelper,
        AttributeFactory $attribute,
        Session $session,
        jsonHelper $jsonHelper,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct(
            $context,
            $coreHelper,
            $attribute,
            $session,
            $jsonHelper
        );
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->attribute->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This attribute no exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('gomage_feed/attribute/index');
                return $resultRedirect;
            }
        }

        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('current_attribute', $model);

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
