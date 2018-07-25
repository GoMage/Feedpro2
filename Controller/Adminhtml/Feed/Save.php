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
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use Magento\Framework\Controller\ResultFactory;
use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use Magento\Framework\Exception\LocalizedException;

class Save extends FeedController
{

    /**
     * Save feed
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {
                /** @var \GoMage\Feed\Model\Feed $model */
                $model = $this->_objectManager->create('GoMage\Feed\Model\Feed');
                $id    = $this->getRequest()->getPost('id');

                if ($id) {
                    $model->load($id);
                } elseif ($this->getRequest()->getPost('switch_type')) {
                    return $this->_proceedToEdit($data);
                }

                if (isset($data['content']) && is_array($data['content'])) {
                    $data['content'] = $this->_prepareData($data['content']);
                    $data['content'] = $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                        ->jsonEncode($data['content']);
                }

                if (isset($data['rule'])) {
                    $data['conditions'] = $data['rule']['conditions'];
                    unset($data['rule']);
                }

                if (isset($data['generate_day']) && is_array($data['generate_day'])) {
                    $data['generate_day'] = implode(',', $data['generate_day']);
                }

                if (isset($data['upload_day']) && is_array($data['upload_day'])) {
                    $data['upload_day'] = implode(',', $data['upload_day']);
                }

                $model->loadPost($data);
                $model->save();
                $this->messageManager->addSuccess(__('You saved the feed.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->_proceedToEdit($data);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the feed.'));
                return $this->_proceedToEdit($data);
            }
            if ($id) {
                return $this->_proceedToEdit($data);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirectResult->setPath('gomage_feed/feed/index');
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
        return $redirectResult->setPath('gomage_feed/feed/edit', ['id' => $this->getRequest()->getPost('id', null)]);
    }
}