<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

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
