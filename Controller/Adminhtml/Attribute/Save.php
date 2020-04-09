<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

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
                $model = $this->attribute->create();
                $id    = $this->getRequest()->getPost('id');
                if ($id) {
                    $model->load($id);
                }
                if (isset($data['content']) && $data['content']) {
                    $contentArray = $data['content'];
                    $data['content'] = $this->_prepareData($data['content']);
                    $data['content'] = $this->jsonHelper
                        ->jsonEncode($data['content']);
                    $this->validateContent($contentArray);
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
    protected function _proceedToEdit($data)
    {
        $this->_getSession()->setPageData($data);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/attribute/edit', ['id' => $this->getRequest()->getPost('id', null)]);
    }

    /**
     * @param array $content
     * @return void
     *
     * @throws LocalizedException
     */
    private function validateContent(array $content)
    {
        foreach ($content as $row) {
            foreach ($row['conditions'] as $condition) {
                if ($condition['code'] === '') {
                    throw new LocalizedException(__('Condition code is required.'));
                }
            }

            if (is_array($row['value'])) {
                $rowValue = $row['value'];

                if (isset($rowValue['code'])) {
                    if ($rowValue['code'] === '') {
                        throw new LocalizedException(__('Attribute code is required.'));
                    }
                } else {
                    foreach ($rowValue as $value) {
                        if ($value['code'] === '') {
                            throw new LocalizedException(__('Attribute code is required.'));
                        }
                    }
                }
            }
        }
    }
}
