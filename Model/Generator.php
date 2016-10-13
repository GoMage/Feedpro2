<?php

namespace GoMage\Feed\Model;

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


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \GoMage\Feed\Model\Reader\Factory $readerFactory,
        \GoMage\Feed\Model\Writer\Factory $writerFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $_directoryList
    ) {
        $this->_objectManager = $objectManager;
        $this->_readerFactory = $readerFactory;
        $this->_writerFactory = $writerFactory;
        $this->_directoryList = $_directoryList;
    }

    /**
     * @param  FeedInterface
     * @return bool
     */
    public function generate(FeedInterface $feed)
    {
        $this->_feed = $feed;

        $rows   = $this->_getRows();
        $reader = $this->_getReader($this->_getAttributes($rows), $this->_getFilters(), $this->_feed->getStoreId());
        $writer = $this->_getWriter($this->_feed->getFullFileName());

        $page  = 1;
        $limit = $this->_feed->getLimit();

        $this->log('START');
        $this->log('Page Size:' . $limit);

        //Execution time may be very long
        set_time_limit(0);

        while ($items = $reader->read($page, $limit)) {
            $this->log('Page - ' . $page);
            foreach ($items as $item) {
                $data = $rows->map($item);
                $writer->write($data);
            }
            $page++;
        }
        $this->log('END');

        return true;
    }

    /**
     * @param  int $storeId
     * @param  array $filters
     * @param  array $attributes
     * @return \GoMage\Feed\Model\Reader\ReaderInterface
     */
    protected function _getReader($attributes = [], $filters = [], $storeId = 0)
    {
        return $this->_readerFactory->create('GoMage\Feed\Model\Reader\Collection',
            [
                'attributes' => $attributes,
                'filters'    => $filters,
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
            ['fileName' => $fileName]
        );
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected function _getRows()
    {
        /** @var \GoMage\Feed\Model\Feed\Row\Collection $rowCollection */
        $rows    = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Collection');
        $content = json_decode($this->_feed->getContent(), true);
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
        foreach ($rows->get() as $row) {
            foreach ($row->getFields()->get() as $field) {
                $attributes = array_merge($attributes, $field->getMapper()->getUsedAttributes());
            }
        }
        return array_unique($attributes);
    }

    /**
     * TODO: add filter interface
     *
     * @return array
     */
    protected function _getFilters()
    {
        if ($filters = $this->_feed->getFilter()) {
            return json_decode($filters, true);
        }
        return [];
    }

    /**
     * @deprecated
     * @param string $message
     */
    protected function log($message)
    {
        $file   = $this->_directoryList->getPath('log') . '/feed-' . $this->_feed->getId() . '.log';
        $string = date("m.d.y H:i:s") . ' ' . $message . PHP_EOL;
        file_put_contents($file, $string, FILE_APPEND);
    }

}