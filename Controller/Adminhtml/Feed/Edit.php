<?php
namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Controller\ResultFactory;

class Edit extends FeedController
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
        $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This feed no exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('gomage_feed/feed/index');
                return $resultRedirect;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->coreRegistry->register('current_feed', $model);

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Feeds'));
        $resultPage->getConfig()->getTitle()->prepend($id ?  __("Edit Feed '%1'", $model->getName()) : __('New Feed'));
        $resultPage->getLayout()->getBlock('gomage.feed.edit')
            ->setData('action', $this->getUrl('gomage_feed/feed/save'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Feed') : __('New Feed'),
            $id ? __('Edit Feed') : __('New Feed')
        );
        return $resultPage;
    }
}
