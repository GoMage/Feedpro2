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

namespace GoMage\Feed\Model\Writer;

use GoMage\Feed\Model\Config\Source\Csv\Enclosure;
use GoMage\Feed\Model\Config\Source\Csv\Delimiter;

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
     * @var bool
     */
    protected $_isHeader;

    /**
     * @var array
     */
    protected $_headerCols = null;

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     * @param Enclosure $enclosureModel
     * @param Delimiter $delimiterModel
     * @param $fileName
     * @param string $fileMode
     * @param int $delimiter
     * @param int $enclosure
     * @param bool $isHeader
     * @param string $additionHeader
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        Enclosure $enclosureModel,
        Delimiter $delimiterModel,
        $fileName,
        string $fileMode,
        $delimiter = Delimiter::COMMA,
        $enclosure = Enclosure::DOUBLE_QUOTE,
        $isHeader = true,
        $additionHeader = ''
    ) {
        parent::__construct($filesystem, $fileName, $fileMode);
        $this->_delimiter = $delimiterModel->getSymbol($delimiter);
        $this->_enclosure = $enclosureModel->getSymbol($enclosure);
        $this->_isHeader  = $isHeader;
        if ($additionHeader) {
            $this->_fileHandler->write($additionHeader);
        }
    }

    /**
     * Set column names.
     *
     * @param array $headerColumns
     * @throws \Exception
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
            if ($this->_isHeader) {
                $this->_fileHandler->writeCsv(array_keys($this->_headerCols), $this->_delimiter, $this->_enclosure);
            }
        }
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
