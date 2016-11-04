<?php

namespace GoMage\Feed\Model;

use Psr\Log\LoggerInterface;

class Generator
{

    /**
     * @var \GoMage\Feed\Model\Feed
     */
    protected $_feed;

    /**
     * @var \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected $_rows;

    /**
     * @var \GoMage\Feed\Model\Reader\ReaderInterface
     */
    protected $_reader;

    /**
     * @var \GoMage\Feed\Model\Writer\WriterInterface
     */
    protected $_writer;

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

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var float
     */
    protected $_time;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \GoMage\Feed\Model\Reader\Factory $readerFactory,
        \GoMage\Feed\Model\Writer\Factory $writerFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $_directoryList,
        LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->_objectManager = $objectManager;
        $this->_readerFactory = $readerFactory;
        $this->_writerFactory = $writerFactory;
        $this->_directoryList = $_directoryList;
        $this->_logger        = $logger;
        $this->_jsonHelper    = $jsonHelper;
        $this->_scopeConfig   = $scopeConfig;
        $this->_dateTime      = $date;
    }

    /**
     * @param  int $feedId
     * @throws \Exception
     */
    public function generate($feedId)
    {
        try {
            $this->_feed = $this->_objectManager->create('GoMage\Feed\Model\Feed')->load($feedId);
            $this->_start();

            $page  = 1;
            $limit = $this->_feed->getLimit();

            while ($items = $this->_getReader()->read($page, $limit)) {
                $this->log(__('Page - %1', $page));
                foreach ($items as $item) {
                    $data = $this->_getRows()->calc($item);
                    $this->_getWriter()->write($data);
                }
                $page++;
            }

            $this->_finish();
        } catch (\Exception $e) {
            $this->_feed->setStatus(\GoMage\Feed\Model\Config\Source\Status::FAILED);
            $this->log($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    protected function _start()
    {
        $this->_time = microtime(true);
        $this->log(__('Start generation'));

        if ($this->_feed->getStatus() == \GoMage\Feed\Model\Config\Source\Status::IN_PROGRESS) {
            throw new \Exception(__('Feed already in progress'));
        }
        $this->_feed->setStatus(\GoMage\Feed\Model\Config\Source\Status::IN_PROGRESS);

        if ($memoryLimit = $this->_scopeConfig->getValue('gomage_feed/server/memory_limit')) {
            ini_set("memory_limit", $memoryLimit . "M");
        }
        if ($fileSize = $this->_scopeConfig->getValue('gomage_feed/server/upload_max_filesize')) {
            ini_set("upload_max_filesize", $fileSize . "M");
        }
        if ($postSize = $this->_scopeConfig->getValue('gomage_feed/server/post_max_size')) {
            ini_set("post_max_size", $postSize . "M");
        }
        $timeLimit = $this->_scopeConfig->getValue('gomage_feed/server/time_limit');
        set_time_limit(intval($timeLimit));
    }

    protected function _finish()
    {
        $this->_feed->setStatus(\GoMage\Feed\Model\Config\Source\Status::COMPLETED);
        $this->_time = microtime(true) - $this->_time;
        $this->_time = max([$this->_time, 1]);
        $this->_feed->setData('generation_time', $this->_dateTime->gmtDate('H:i:s', $this->_time))
            ->setData('generated_at', $this->_dateTime->gmtDate('Y-m-j H:i:s'))
            ->save();
        $this->log(__('Finish'));
    }

    /**
     * @return \GoMage\Feed\Model\Reader\ReaderInterface
     */
    protected function _getReader()
    {
        if (is_null($this->_reader)) {
            $this->_reader = $this->_readerFactory->create('GoMage\Feed\Model\Reader\Collection',
                [
                    'attributes' => $this->_getRows()->getAttributes(),
                    'conditions' => $this->_feed->getConditions(),
                    'storeId'    => $this->_feed->getStoreId(),
                ]
            );
        }
        return $this->_reader;
    }

    /**
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     */
    protected function _getWriter()
    {
        if (is_null($this->_writer)) {
            $this->_writer = $this->_writerFactory->create('GoMage\Feed\Model\Writer\Csv',
                [
                    'fileName'  => $this->_feed->getFullFileName(),
                    'delimiter' => $this->_feed->getDelimiter(),
                    'enclosure' => $this->_feed->getEnclosure(),
                    'isHeader'  => boolval($this->_feed->getIsHeader())
                ]
            );
        }
        return $this->_writer;
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getRows()
    {
        if (is_null($this->_rows)) {
            /** @var \GoMage\Feed\Model\Feed\Row\Collection $rows */
            $this->_rows = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Collection');
            $content     = $this->_jsonHelper->jsonDecode($this->_feed->getContent());
            foreach ($content as $data) {

                /** @var \GoMage\Feed\Model\Feed\Row\Data $rowData */
                $rowData = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Data', ['data' => $data]);

                /** @var \GoMage\Feed\Model\Feed\Row $row */
                $row = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row', ['rowData' => $rowData]);

                $this->_rows->add($row);
            }
        }

        return $this->_rows;
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