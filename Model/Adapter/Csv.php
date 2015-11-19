<?php

namespace GoMage\Feed\Model\Adapter;

class Csv extends \GoMage\Feed\Model\Adapter\AbstractAdapter
{
    /**
     * Field delimiter.
     *
     * @var string
     */
    protected $_delimiter = ',';

    /**
     * Field enclosure character.
     *
     * @var string
     */
    protected $_enclosure = '"';

    /**
     * Source file handler.
     *
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_fileHandler;

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        if (is_object($this->_fileHandler)) {
            $this->_fileHandler->close();
        }
    }

    /**
     * Method called as last step of object instance creation. Can be overrided in child classes.
     *
     * @return $this
     */
    protected function _init()
    {
        $this->_fileHandler = $this->_directoryHandle->openFile($this->_destination, 'w');
        return $this;
    }

    /**
     * Return file extension for downloading.
     *
     * @return string
     */
    public function getFileExtension()
    {
        return 'csv';
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
     * Write row data to source file.
     *
     * @param array $rowData
     * @throws \Exception
     * @return $this
     */
    public function writeRow(array $rowData)
    {
        if (null === $this->_headerCols) {
            $this->setHeaderCols(array_keys($rowData));
        }
        $this->_fileHandler->writeCsv(
            array_merge($this->_headerCols, array_intersect_key($rowData, $this->_headerCols)),
            $this->_delimiter,
            $this->_enclosure
        );
        return $this;
    }

    /**
     * @param  string $delimiter
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
        return $this;
    }

    /**
     * @param  string $enclosure
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->_enclosure = $enclosure;
        return $this;
    }

}
