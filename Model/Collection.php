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
    protected $_index = 0;

    /**
     * @var array
     */
    protected $_items = [];

    public function __construct()
    {
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->_index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->_items[$this->_index];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->_index;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
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
