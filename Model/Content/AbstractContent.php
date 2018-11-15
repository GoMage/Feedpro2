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

namespace GoMage\Feed\Model\Content;

abstract class AbstractContent implements ContentInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var string
     */
    protected $_content;

    /**
     * @var \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected $_rows;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $content
    ) {
        $this->_objectManager = $objectManager;
        $this->_content       = $content;
    }
}
