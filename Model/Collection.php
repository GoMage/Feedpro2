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

namespace GoMage\Feed\Model;

class Collection implements \Iterator
{

    /**
     * @var int
     */
    private $_index = 0;

    /**
     * @var array
     */
    private $_items = [];

    public function __construct()
    {
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    function rewind()
    {
        $this->_index = 0;
    }

    /**
     * {@inheritdoc}
     */
    function current()
    {
        return $this->_items[$this->_index];
    }

    /**
     * {@inheritdoc}
     */
    function key()
    {
        return $this->_index;
    }

    /**
     * {@inheritdoc}
     */
    function next()
    {
        ++$this->_index;
    }

    /**
     * {@inheritdoc}
     */
    function valid()
    {
        return isset($this->_items[$this->_index]);
    }

    /**
     * @param mixed $value
     */
    public function add($value)
    {
        $this->_items[] = $value;
        $this->rewind();
    }

}
