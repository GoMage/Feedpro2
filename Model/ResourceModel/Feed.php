<?php
namespace GoMage\Feed\Model\ResourceModel;

class Feed extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gomage_feed', 'id');
    }
}
