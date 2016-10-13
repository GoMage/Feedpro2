<?php

namespace GoMage\Feed\Model\Writer;

class Csv extends AbstractWriter
{

    /**
     * @var string
     */
    protected $_delimiter;

    /**
     * @var string
     */
    protected $_enclosure;

    /**
     * @var array
     */
    protected $_headerCols = null;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName,
        $delimiter = ',',
        $enclosure = '"'
    ) {
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;

        parent::__construct($filesystem, $fileName);
    }

    /**
     * Set column names.
     *
     * @param array $headerColumns
     * @throws \Exception
     * @return $this
     */
    public function setHeaderCols(array $headerColumns)
    {
        if (null !== $this->_headerCols) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The header column names are already set.'));
        }
        if ($headerColumns) {
            foreach ($headerColumns as $columnName) {
                $this->_headerCols[$columnName] = false;
            }
            $this->_fileHandler->writeCsv(array_keys($this->_headerCols), $this->_delimiter, $this->_enclosure);
        }
        return $this;
    }

    /**
     * @param  array $data
     */
    public function write(array $data)
    {
        if (null === $this->_headerCols) {
            $this->setHeaderCols(array_keys($data));
        }
        $this->_fileHandler->writeCsv(
            array_merge($this->_headerCols, array_intersect_key($data, $this->_headerCols)),
            $this->_delimiter,
            $this->_enclosure
        );
    }
}
