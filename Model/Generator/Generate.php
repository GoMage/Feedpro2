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

namespace GoMage\Feed\Model\Generator;

use GoMage\Feed\Model\Config\Source\FeedType;
use GoMage\Feed\Model\Content\Factory as ContentFactory;
use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Feed\ResultModel;
use GoMage\Feed\Model\Feed\ResultModelFactory;
use GoMage\Feed\Model\Reader\CollectionFactory as ReaderCollectionFactory;
use GoMage\Feed\Model\Reader\Factory as ReaderFactory;
use GoMage\Feed\Model\Reader\ParamsFactory;
use GoMage\Feed\Model\Writer\Factory as WriterFactory;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Math\Calculator;

class Generate
{
    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var \GoMage\Feed\Model\Writer\Factory
     */
    private $writerFactory;

    /**
     * @var ParamsFactory
     */
    private $paramsFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ReaderCollectionFactory
     */
    private $readerCollectionFactory;

    /**
     * @var \GoMage\Feed\Model\Content\Factory
     */
    private $contentFactory;

    /**
     * @var ResultModelFactory
     */
    private $resultModelFactory;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * Generate constructor.
     * @param ReaderFactory $readerFactory
     * @param WriterFactory $writerFactory
     * @param ParamsFactory $paramsFactory
     * @param ReaderCollectionFactory $readerCollectionFactory
     * @param ContentFactory $contentFactory
     * @param ResultModelFactory $resultModelFactory
     * @param StoreManagerInterface $storeManager
     * @param Calculator $calculator
     */
    public function __construct(
        ReaderFactory $readerFactory,
        WriterFactory $writerFactory,
        ParamsFactory $paramsFactory,
        ReaderCollectionFactory $readerCollectionFactory,
        ContentFactory $contentFactory,
        ResultModelFactory $resultModelFactory,
        StoreManagerInterface $storeManager,
        Calculator $calculator
    ) {
        $this->calculator = $calculator;
        $this->storeManager = $storeManager;
        $this->readerFactory = $readerFactory;
        $this->writerFactory = $writerFactory;
        $this->paramsFactory = $paramsFactory;
        $this->readerCollectionFactory = $readerCollectionFactory;
        $this->contentFactory = $contentFactory;
        $this->resultModelFactory = $resultModelFactory;
    }

    /**
     * @param Feed $feed
     * @param LoggerInterface $logger
     * @param int|null $page
     * @param string $fileMode
     * @return ResultModel
     */
    public function execute(Feed $feed, LoggerInterface $logger, $page,  $fileMode)
    {
        $breakAfterFirstIteration = true;
        $limit = $feed->getLimit();
        $content = $this->contentFactory->create(
            $feed->getType(),
            [
                'content' => $feed->getContent(),
            ]
        );

        $reader = $this->getReader($feed, $content->getRows()->getAttributes());

        $feedSize = $reader->getSize();
        if ($limit > 0) {
            $totalPages = $feedSize / $limit;
        } else {
            $totalPages = 1;
        }

        $resultModel = $this->resultModelFactory->create();
        $resultModel->setCurrentPage($page);
        $resultModel->setTotalPages($totalPages);

        $writer = $this->getWriter($feed, $fileMode, $page, $totalPages);

        if ($page === null) { //need to process all data
            $breakAfterFirstIteration = false;
            $page = 1;
        }

        while ($items = $reader->read($page, $limit)) {
            $logger->info(__('Page - %1', $page));
            foreach ($items as $item) {
                if($feed->getCurrencyCode()) {
                    $store = $this->storeManager->getStore($feed->getStoreId());
                    $rates = $store->getBaseCurrency()->getRate($feed->getCurrencyCode());
                    $item->setPrice($this->calculator->deltaRound($item->getPrice() * $rates));
                }
                $data = $content->getRows()->calc($item);
                $writer->write($data);
            }
            if ($breakAfterFirstIteration === true) {
                break;
            }
            $page++;
        }

        return $resultModel;
    }

    /**
     * @param \GoMage\Feed\Model\Feed $feed
     * @param array $attributes
     *
     * @return \GoMage\Feed\Model\Reader\ReaderInterface
     */
    private function getReader(Feed $feed, array $attributes)
    {
        $params = $this->paramsFactory->create(
            [
                'data' => [
                    'attributes' => $attributes,
                    'conditions' => $feed->getConditions(),
                    'store_id' => $feed->getStoreId(),
                    'visibility' => $feed->getVisibility(),
                    'is_out_of_stock' => $feed->getIsOutOfStock(),
                    'is_disabled' => $feed->getIsDisabled(),
                ]
            ]
        );
        $reader = $this->readerCollectionFactory->create(['params' => $params]);

        return $reader;
    }

    /**
     * @param \GoMage\Feed\Model\Feed $feed
     * @param string $fileMode
     * @param $page
     * @param $totalPages
     *
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     */
    private function getWriter(Feed $feed,  $fileMode, $page, $totalPages)
    {
        $arguments = [
            'fileName' => $feed->getFullFileName(),
            'fileMode'  => $fileMode,
        ];

        if ($feed->getType() == FeedType::CSV_TYPE) {
            $arguments = array_merge(
                $arguments,
                [
                    'delimiter' => $feed->getDelimiter(),
                    'enclosure' => $feed->getEnclosure(),
                    'isHeader' => boolval($feed->getIsHeader()),
                    'additionHeader' => $feed->getIsAdditionHeader() ? $feed->getAdditionHeader() : '',
                    'page' => $page
                ]
            );
        } else {
            $content = $this->contentFactory->create(
                $feed->getType(),
                [
                    'content' => $feed->getContent(),
                ]
            );
            $arguments = array_merge(
                $arguments,
                [
                    'content' => $content,
                    'page' => $page,
                    'totalPages' => $totalPages,
                ]
            );
        }

        $writer = $this->writerFactory->create($feed->getType(), $arguments);

        return $writer;
    }
}
