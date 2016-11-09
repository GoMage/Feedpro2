<?php

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