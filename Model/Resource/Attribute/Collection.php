<?php

namespace GoMage\Feed\Model\Resource\Attribute;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('GoMage\Feed\Model\Attribute', 'GoMage\Feed\Model\Resource\Attribute');
    }

}
