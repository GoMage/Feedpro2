<?php
namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\DataObject;

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


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository
    ) {
        parent::__construct($context);
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
