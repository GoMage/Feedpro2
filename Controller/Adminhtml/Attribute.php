<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml;

use GoMage\Core\Helper\Data as coreHelper;
use GoMage\Feed\Helper\Data as Helper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Model\AttributeFactory;
use Magento\Backend\Model\Session;
use Magento\Framework\Json\Helper\Data as jsonHelper;
abstract class Attribute extends Action
{
    /**
     * @var jsonHelper
     */
    protected  $jsonHelper;

    /**
     * @var Session
     */
    protected  $session;

    /**
     * @var AttributeFactory
     */
    protected  $attribute;

    /**
     * @var coreHelper
     */
    private $coreHelper;

    /**
     * Attribute constructor.
     * @param Context $context
     * @param coreHelper $coreHelper
     * @param AttributeFactory $attribute
     * @param Session $session
     * @param jsonHelper $jsonHelper
     */
    public function __construct(
        Action\Context $context,
        coreHelper $coreHelper,
        AttributeFactory $attribute,
        Session $session,
        jsonHelper $jsonHelper
    )
    {
        $this->coreHelper = $coreHelper;
        $this->attribute = $attribute;
        $this->session = $session;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function createPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('GoMage_Feed::marketing_feed')
            ->addBreadcrumb(__('GoMage Feeds'), __('GoMage Feeds'));
        return $resultPage;
    }


    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->coreHelper->isA(Helper::MODULE_NAME)) {
            return $this->_authorization->isAllowed('GoMage_Feed::attributes');
        }
        $this->messageManager->addError(__(
            'Please activate the extension in Stores -> Configuration -> GoMage menu <a href="%1">Back to activation</a> ',
            $this->getUrl('adminhtml/system_config/edit/section/gomage_core')
        ));
        return false;
    }
}
