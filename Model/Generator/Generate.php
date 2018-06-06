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

namespace GoMage\Feed\Model\Generator;

use GoMage\Feed\Model\Config\Source\FeedType;
use GoMage\Feed\Model\Content\Factory as ContentFactory;
use GoMage\Feed\Model\Feed;
use GoMage\Feed\Model\Feed\ResultModel;
use GoMage\Feed\Model\Reader\CollectionFactory as ReaderCollectionFactory;
use GoMage\Feed\Model\Reader\Factory as ReaderFactory;
use GoMage\Feed\Model\Reader\ParamsFactory;
use GoMage\Feed\Model\Writer\Factory as WriterFactory;
use Psr\Log\LoggerInterface;

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
     * @var ReaderCollectionFactory
     */
    private $readerCollectionFactory;

    /**
     * @var \GoMage\Feed\Model\Content\Factory
     */
    private $contentFactory;

    /**
     * @var ResultModel
     */
    private $resultModel;

    /**
     * @param ReaderFactory $readerFactory
     * @param WriterFactory $writerFactory
     * @param ParamsFactory $paramsFactory
     * @param ReaderCollectionFactory $readerCollectionFactory
     * @param ContentFactory $contentFactory
     * @param ResultModel $resultModel
     */
    public function __construct(
        ReaderFactory $readerFactory,
        WriterFactory $writerFactory,
        ParamsFactory $paramsFactory,
        ReaderCollectionFactory $readerCollectionFactory,
        ContentFactory $contentFactory,
        ResultModel $resultModel
    ) {
        $this->readerFactory = $readerFactory;
        $this->writerFactory = $writerFactory;
        $this->paramsFactory = $paramsFactory;
        $this->readerCollectionFactory = $readerCollectionFactory;
        $this->contentFactory = $contentFactory;
        $this->resultModel = $resultModel;
    }

    /**
     * @param Feed $feed
     * @param LoggerInterface $logger
     * @param int|null $page
     * @return ResultModel
     */
    public function execute(Feed $feed, LoggerInterface $logger, $page)
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
        $writer = $this->getWriter($feed);

        if ($page === null) { //need to process all data
            $breakAfterFirstIteration = false;
            $page = 1;
        }

        while ($items = $reader->read($page, $limit)) {
            $logger->info(__('Page - %1', $page));
            foreach ($items as $item) {
                $data = $content->getRows()->calc($item);
                $writer->write($data);
            }
            if ($breakAfterFirstIteration === true) {
                break;
            }
            $page++;
        }

        $feedSize = $reader->getSize();
        if ($limit > 0) {
            $totalPages = $feedSize / $limit;
        } else {
            $totalPages = 1;
        }

        $this->resultModel->setCurrentPage($page);
        $this->resultModel->setTotalPages($totalPages);

        return $this->resultModel;
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
     *
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     */
    private function getWriter(Feed $feed)
    {
        $arguments = ['fileName' => $feed->getFullFileName()];

        if ($feed->getType() == FeedType::CSV_TYPE) {
            $arguments = array_merge(
                $arguments,
                [
                    'delimiter' => $feed->getDelimiter(),
                    'enclosure' => $feed->getEnclosure(),
                    'isHeader' => boolval($feed->getIsHeader()),
                    'additionHeader' => $feed->getIsAdditionHeader() ? $feed->getAdditionHeader() : ''
                ]
            );
        } else {
            $arguments['content'] = $this->contentFactory->create(
                $feed->getType(),
                [
                    'content' => $feed->getContent(),
                ]
            );
        }

        $writer = $this->writerFactory->create($feed->getType(), $arguments);

        return $writer;
    }
}
