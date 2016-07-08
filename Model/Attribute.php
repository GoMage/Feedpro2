<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Init model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('GoMage\Feed\Model\ResourceModel\Attribute');
    }

}
