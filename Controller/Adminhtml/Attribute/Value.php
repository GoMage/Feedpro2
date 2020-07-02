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

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use GoMage\Feed\Model\AttributeFactory;
use Magento\Backend\Model\Session;
use Magento\Framework\DataObject;
use GoMage\Core\Helper\Data as coreHelper;
use Magento\Framework\Json\Helper\Data as jsonHelper;
class Value extends AttributeController
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    protected $_productAttributeRepository;

    /**
     * Value constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository
     * @param coreHelper $coreHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        coreHelper $coreHelper,
        AttributeFactory $attribute,
        Session $session,
        jsonHelper $jsonHelper
    ) {
        parent::__construct($context, $coreHelper,$attribute,$session,$jsonHelper);
        $this->_resultJsonFactory          = $resultJsonFactory;
        $this->_productAttributeRepository = $productAttributeRepository;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $response = new DataObject();
        $response->setError(false);
        $values = [];

        $code = $this->getRequest()->getParam('code');

        if ($code) {
            try {
                $attributeOptions = $this->_productAttributeRepository->get($code)->getOptions();
                if ($attributeOptions && is_array($attributeOptions)) {
                    foreach ($attributeOptions as $option) {
                        $values[] = [
                            'value' => $option->getValue(),
                            'label' => $option->getLabel(),
                        ];
                    }
                }
            } catch (\Exception $e) {
                $response->setError(true);
                $response->setMessage(__('Something went wrong.'));
            }
        } else {
            $response->setError(true);
            $response->setMessage(__('Attribute with this code \'%1\' is not defined.', $code));
        }

        $response->setValues($values);
        return $this->_resultJsonFactory->create()->setJsonData($response->toJson());
    }
}
