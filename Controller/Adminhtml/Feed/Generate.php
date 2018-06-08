<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use GoMage\Feed\Model\Generator;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;

class Generate extends FeedController
{
    /**
     * @var ResultJsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param Action\Context $context
     * @param ResultJsonFactory $resultJsonFactory
     * @param Generator $generator
     */
    public function __construct(
        Action\Context $context,
        ResultJsonFactory $resultJsonFactory,
        Generator $generator
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->generator = $generator;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page') ?: 1;

        $result = $this->resultJsonFactory->create();
        if ($id) {
            try {
                $resultModel = $this->generator->generate($id, $page);

                $resultArray = array_merge(
                    $resultModel->getStructuredData(),
                    ['message' => __('You generated the feed.')]
                );

                $result->setData($resultArray);
            } catch (\Exception $e) {
                $result->setData(
                    [
                        'error' => true,
                        'message' => $e->getMessage(),
                    ]
                );
            }
        }

        return $result;
    }
}
