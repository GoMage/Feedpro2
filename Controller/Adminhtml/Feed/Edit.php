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
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Controller\ResultFactory;
use GoMage\Core\Helper\Data as coreHelper;
use GoMage\Feed\Model\FeedFactory;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use Magento\Backend\Model\Session;
class Edit extends FeedController
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
        FeedFactory $feed,
        jsonHelper $jsonHelper,
        Session $session,
        coreHelper $coreHelper,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct(
            $context,
            $feed,
            $jsonHelper,
            $session,
            $coreHelper
        );
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->feed->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This feed no exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('gomage_feed/feed/index');
                return $resultRedirect;
            }
        }

        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        if(!$data && $this->getRequest()->getParam('key') && $this->getRequest()->getParam('type')) {
            $data = [];
            $data['key'] = $this->getRequest()->getParam('key');
            $data['type'] = $this->getRequest()->getParam('type');
            if($data) {
                $model->addData($data);
            }
        }
        $this->_coreRegistry->register('current_feed', $model);

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Feeds'));
        $resultPage->getConfig()->getTitle()->prepend($id ? __("Edit Feed '%1'", $model->getName()) : __('New Feed'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Feed') : __('New Feed'),
            $id ? __('Edit Feed') : __('New Feed')
        );
        return $resultPage;
    }
}
