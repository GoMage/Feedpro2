<?php

namespace GoMage\Feed\Model\Feed\Row;

use GoMage\Feed\Model\Feed\Row;

class Collection
{

    /** @var Row[] */
    protected $_rows;

    /**
     * @param  Row $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function add(Row $row)
    {
        if (isset($this->_rows[$row->getName()])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Duplicate row.'));
        }
        $this->_rows[$row->getName()] = $row;
    }

    /**
     * @param  $object
     * @return array
     */
    public function map($object)
    {
        return array_map(function ($row) use ($object) {
            return $row->map($object);
        }, $this->_rows
        );
    }

    /**
     * @return Row[]
     */
    public function get()
    {
        return $this->_rows;
    }

}
