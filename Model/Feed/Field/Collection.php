<?php

namespace GoMage\Feed\Model\Feed\Field;

use GoMage\Feed\Model\Feed\Field;

class Collection
{

    /** @var Field[] */
    protected $_fields;

    /**
     * @param  Field $field
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function add(Field $field)
    {
        $this->_fields[] = $field;
    }

    /**
     * @return Field[]
     */
    public function get()
    {
        return $this->_fields;
    }

}
