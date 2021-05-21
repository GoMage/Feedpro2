<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Feed\Row;

use GoMage\Feed\Model\Feed\Row;

class Collection implements \Iterator
{

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
        reset($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->_items);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return key($this->_items) !== null;
    }

    /**
     * @param  Row $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function add(Row $row)
    {
        if (!isset($this->_items[$row->getName()])) {
            $this->_items[$row->getName()] = $row;
        }
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
