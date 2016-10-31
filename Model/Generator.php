<?php

namespace GoMage\Feed\Model;

use Psr\Log\LoggerInterface;

class Generator implements GeneratorInterface
{

    /**
     * @var FeedInterface
     */
    protected $_feed;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \GoMage\Feed\Model\Reader\Factory
     */
    protected $_readerFactory;

    /**
     * @var \GoMage\Feed\Model\Writer\Factory
     */
    protected $_writerFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \GoMage\Feed\Model\Logger\Handler
     */
    protected $_logHandler;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \GoMage\Feed\Model\Reader\Factory $readerFactory,
        \GoMage\Feed\Model\Writer\Factory $writerFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $_directoryList,
        LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_objectManager = $objectManager;
        $this->_readerFactory = $readerFactory;
        $this->_writerFactory = $writerFactory;
        $this->_directoryList = $_directoryList;
        $this->_logger        = $logger;
        $this->_jsonHelper    = $jsonHelper;
    }

    /**
     * @param  FeedInterface
     * @return bool
     */
    public function generate(FeedInterface $feed)
    {
        set_time_limit(0);

        $this->_feed = $feed;

        $rows   = $this->_getRows();
        $reader = $this->_getReader($this->_getAttributes($rows), $feed->getConditions(), $this->_feed->getStoreId());
        $writer = $this->_getWriter($this->_feed->getFullFileName());

        $page  = 1;
        $limit = $this->_feed->getLimit();

        $this->log('Start generation', ['limit' => $limit]);

        while ($items = $reader->read($page, $limit)) {
            $this->log('Page - ' . $page);
            foreach ($items as $item) {
                $data = $rows->calc($item);
                $writer->write($data);
            }
            $page++;
        }
        $this->log('Finish');

        return true;
    }

    /**
     * @param  int $storeId
     * @param  $conditions
     * @param  array $attributes
     * @return \GoMage\Feed\Model\Reader\ReaderInterface
     */
    protected function _getReader($attributes = [], $conditions, $storeId = 0)
    {
        return $this->_readerFactory->create('GoMage\Feed\Model\Reader\Collection',
            [
                'attributes' => $attributes,
                'conditions' => $conditions,
                'storeId'    => $storeId,
            ]
        );
    }

    /**
     * @param  string $fileName
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     */
    protected function _getWriter($fileName)
    {
        return $this->_writerFactory->create('GoMage\Feed\Model\Writer\Csv',
            [
                'fileName'  => $fileName,
                'delimiter' => $this->_feed->getDelimiter(),
                'enclosure' => $this->_feed->getEnclosure(),
                'isHeader'  => boolval($this->_feed->getIsHeader())
            ]
        );
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected function _getRows()
    {
        /** @var \GoMage\Feed\Model\Feed\Row\Collection $rows */
        $rows    = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Collection');
        $content = $this->_jsonHelper->jsonDecode($this->_feed->getContent());
        foreach ($content as $data) {

            /** @var \GoMage\Feed\Model\Feed\Row\Data $rowData */
            $rowData = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Data', ['data' => $data]);

            /** @var \GoMage\Feed\Model\Feed\Row $row */
            $row = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row', ['rowData' => $rowData]);

            $rows->add($row);
        }

        return $rows;
    }

    /**
     * @param  \GoMage\Feed\Model\Feed\Row\Collection $rows
     * @return array
     */
    protected function _getAttributes(\GoMage\Feed\Model\Feed\Row\Collection $rows)
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Feed\Row $row */
        foreach ($rows as $row) {
            $attributes = array_merge($attributes, $row->getUsedAttributes());
        }
        return array_unique($attributes);
    }

    /**
     * TODO: add filters collection
     *
     * @return array
     */
    protected function _getFilters()
    {
        if ($filters = $this->_feed->getFilter()) {
            return $this->_jsonHelper->jsonDecode($filters);
        }
        return [];
    }

    /**
     * @param $message
     * @param array $context
     */
    protected function log($message, array $context = [])
    {
        if ($this->_feed && !$this->_logHandler) {
            $this->_logHandler = $this->_objectManager->create('GoMage\Feed\Model\Logger\Handler', [
                    'fileName' => '/var/log/feed-' . $this->_feed->getId() . '.log'
                ]
            );
            $this->_logger->setHandlers([$this->_logHandler]);
        }

        $this->_logger->info($message, $context);
    }

}