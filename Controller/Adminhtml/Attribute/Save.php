<?php

namespace GoMage\Feed\Controller\Adminhtml\Attribute;

use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Controller\Adminhtml\Attribute as AttributeController;
use Magento\Framework\Exception\LocalizedException;

class Save extends AttributeController
{

    /**
     * Save attribute
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                /** @var \GoMage\Feed\Model\Attribute $model */
                $model = $this->_objectManager->create('GoMage\Feed\Model\Attribute');
                $id    = $this->getRequest()->getPost('id');
                if ($id) {
                    $model->load($id);
                }
                if (isset($data['content']) && $data['content']) {
                    $data['content'] = $this->_prepareData($data['content']);
                    $data['content'] = $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                        ->jsonEncode($data['content']);
                }
                $model->addData($data);
                $model->save();
                $this->messageManager->addSuccess(__('You saved the attribute.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->_proceedToEdit($data);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the attribute.'));
                return $this->_proceedToEdit($data);
            }
            if ($id) {
                return $this->_proceedToEdit($data);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/attribute/index');
    }

    /**
     * @param  array $data
     * @return array
     */
    protected function _prepareData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->_prepareData($value);
            }
        }
        return array_merge($data, []);
    }

    /**
     * Redirect to Edit page
     *
     * @param array $data
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    private function _proceedToEdit($data)
    {
        $this->_getSession()->setPageData($data);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/attribute/edit', ['id' => $this->getRequest()->getPost('id', null)]);
    }
}
