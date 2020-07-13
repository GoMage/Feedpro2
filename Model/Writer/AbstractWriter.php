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

namespace GoMage\Feed\Model\Writer;

abstract class AbstractWriter implements WriterInterface
{
    /**
     * @var string
     */
    protected $_filePath;

    /**
     * @var string
     */
    protected $_directoryWrite;

    /**
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_fileHandler;


    /**
     * AbstractWriter constructor.
     * @param \Magento\Framework\Filesystem $filesystem
     * @param string $filePath
     * @param string $directorWrite
     * @param string $mode
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $filePath,
        $directorWrite,
        $mode = WriterInterface::DEFAULT_MODE
    ) {
        $this->_filePath = $filePath;
        $this->_directoryWrite =$directorWrite;
        $directoryHandle    = $filesystem->getDirectoryWrite($directorWrite);
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
