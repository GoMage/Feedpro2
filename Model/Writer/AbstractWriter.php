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

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

abstract class AbstractWriter implements WriterInterface
{
    /**
     * @var string
     */
    protected $_fileName;

    /**
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_fileHandler;

    /**
     * @param Filesystem $filesystem
     * @param $fileName
     * @param string $mode
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName,
        string $mode = 'w'
    ) {
        $this->_fileName = $fileName;
        $filePath        = WriterInterface::DIRECTORY . '/' . $this->_fileName;

        $directoryHandle    = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_fileHandler = $directoryHandle->openFile($filePath, $mode);
        $this->_fileHandler->flush();
    }

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
     * @param  array $data
     */
    abstract public function write(array $data);
}
