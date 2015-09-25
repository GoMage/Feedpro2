<?php
namespace GoMage\Feed\Model\Resource;

class Attribute extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gomage_feed_attribute', 'id');
    }
}
