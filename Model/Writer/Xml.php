<?php

namespace GoMage\Feed\Model\Writer;

class Xml extends AbstractWriter
{

    /**
     * @var \GoMage\Feed\Model\Content\Xml
     */
    protected $_content;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName,
        \GoMage\Feed\Model\Content\Xml $content
    ) {
        parent::__construct($filesystem, $fileName);
        $this->_content = $content;
        $this->_fileHandler->write($this->_content->getHeader());
    }

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        $this->_fileHandler->write($this->_content->getFooter());
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
