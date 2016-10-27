<?php

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
