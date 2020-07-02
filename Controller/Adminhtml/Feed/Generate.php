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
use GoMage\Feed\Model\Generator;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Framework\Controller\ResultInterface;
use GoMage\Core\Helper\Data as coreHelper;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use GoMage\Feed\Model\FeedFactory;

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
     * Generate constructor.
     * @param Action\Context $context
     * @param ResultJsonFactory $resultJsonFactory
     * @param Generator $generator
     * @param coreHelper $coreHelper
     */
    public function __construct(
        Action\Context $context,
        ResultJsonFactory $resultJsonFactory,
        Generator $generator,
        FeedFactory $feed,
        jsonHelper $jsonHelper,
        Session $session,
        coreHelper $coreHelper
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->generator = $generator;

        parent::__construct($context, $feed, $jsonHelper, $session, $coreHelper);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $page = (int)$this->getRequest()->getParam('page') ?: 1;

        $result = $this->resultJsonFactory->create();
        if ($id) {
            try {
                $writeMode = 'a'; //write after all in file.
                if ($page == 1) {
                    $writeMode = 'w'; //clear file and then write.
                }
                $resultModel = $this->generator->generate($id, $page, $writeMode);

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
