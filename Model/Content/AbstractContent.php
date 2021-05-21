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

namespace GoMage\Feed\Model\Content;

abstract class AbstractContent implements ContentInterface
{

    /**
     * @var string
     */
    protected $_content;

    /**
     * @var \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected $_collection;

    /**
     * @var \GoMage\Feed\Model\Feed\Row\Data
     */
    protected $_dataRow;

    /**
     * @var \GoMage\Feed\Model\Feed\Row
     */
    protected $_row;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \GoMage\Feed\Model\Feed\Row\Collection
     */
    protected $_rows;

    /**
     * @var \GoMage\Feed\Model\Feed
     */
    protected $_feed;

    /**
     * AbstractContent constructor.
     * @param \GoMage\Feed\Model\Feed\Row\Collection $collection
     * @param \GoMage\Feed\Model\Feed\Row\Data $dataRow
     * @param \GoMage\Feed\Model\Feed\Row $row
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param $content
     */
    public function __construct(
        \GoMage\Feed\Model\Feed\Row\CollectionFactory $collection,
        \GoMage\Feed\Model\Feed\Row\DataFactory $dataRow,
        \GoMage\Feed\Model\Feed\RowFactory $row,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        $content,
        \GoMage\Feed\Model\Feed $feed
    ) {
        $this->_collection = $collection;
        $this->_dataRow = $dataRow;
        $this->_row = $row;
        $this->_jsonHelper = $jsonHelper;
        $this->_content = $content;
        $this->_feed = $feed;
    }

    /**
     * @return array
     */
    protected function setAdditionalData()
    {
        return ['currencyCode' => $this->_feed->getCurrencyCode()];
    }
}
