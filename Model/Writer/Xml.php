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
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Writer;

class Xml extends AbstractWriter
{
    /**
     * @var \GoMage\Feed\Model\Content\Xml
     */
    protected $_content;

    /**
     * @var null|int
     */
    private $page;

    /**
     * @var float
     */
    private $totalPages;

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     * @param $fileName
     * @param string $fileMode
     * @param \GoMage\Feed\Model\Content\Xml $content
     * @param $page
     * @param $totalPages
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName,
         $fileMode,
        \GoMage\Feed\Model\Content\Xml $content,
        $page,
        $totalPages
    ) {
        parent::__construct($filesystem, $fileName, $fileMode);
        $this->_content = $content;
        $this->page = $page;
        $this->totalPages = $totalPages;
        if ($page === null || $page == 1) {
            $this->_fileHandler->write($this->_content->getHeader());
        }
    }

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        if ($this->totalPages / $this->page <= 1) {
            $this->_fileHandler->write($this->_content->getFooter());
        }
        parent::__destruct();
    }

    /**
     * @param  array $data
     */
    public function write(array $data)
    {
        $this->_fileHandler->write(strtr($this->_content->getBlock(), $data));
    }
}
