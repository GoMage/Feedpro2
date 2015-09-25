<?php

namespace GoMage\Feed\Model\Resource\Feed;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
{
    /**
     * Init collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('GoMage\Feed\Model\Feed', 'GoMage\Feed\Model\Resource\Feed');
    }

}
