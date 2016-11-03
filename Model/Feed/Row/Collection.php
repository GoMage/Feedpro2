<?php

namespace GoMage\Feed\Model\Feed\Row;

use GoMage\Feed\Model\Feed\Row;

class Collection implements \Iterator
{

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
        reset($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    function current()
    {
        return current($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    function key()
    {
        return key($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    function next()
    {
        next($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    function valid()
    {
        return key($this->_items) !== null;
    }

    /**
     * @param  Row $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function add(Row $row)
    {
        if (isset($this->_items[$row->getName()])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Duplicate row.'));
        }
        $this->_items[$row->getName()] = $row;
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return array
     */
    public function calc(\Magento\Framework\DataObject $object)
    {
        return array_map(function (Row $row) use ($object) {
            return $row->map($object);
        }, $this->_items
        );
    }

    public function getAttributes()
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Feed\Row $row */
        foreach ($this->_items as $row) {
            $attributes = array_merge($attributes, $row->getUsedAttributes());
        }
        return array_unique($attributes);
    }

}
