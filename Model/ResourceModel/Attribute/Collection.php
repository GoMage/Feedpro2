<?php

namespace GoMage\Feed\Model\ResourceModel\Attribute;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('GoMage\Feed\Model\Attribute', 'GoMage\Feed\Model\ResourceModel\Attribute');
    }

}
