<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

class Feed extends \Magento\Framework\Model\AbstractModel
{

    const CSV_TYPE = 'csv';
    const XML_TYPE = 'xml';

    /**
     * Init model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('GoMage\Feed\Model\Resource\Feed');
    }

}
