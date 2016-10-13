<?php

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


    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName
    ) {
        $this->_fileName = $fileName;
        $filePath        = WriterInterface::DIRECTORY . '/' . $this->_fileName;

        $directoryHandle    = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_fileHandler = $directoryHandle->openFile($filePath, 'w');
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