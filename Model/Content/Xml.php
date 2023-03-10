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
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Content;

class Xml extends AbstractContent
{

    const BLOCK_PATTERN = '/\\{\\{block\\}\\}(.*)\\{\\{\\/block\\}\\}/s';
    const ROW_PATTERN = '/\\{\\{var:(.+?)(\s.*?)?\\}\\}/s';
    const PARAMS_PATTERN = '/(.*?)\="(.*?)"/s';

    /**
     * @var string
     */
    protected $_header;

    /**
     * @var string
     */
    protected $_footer;

    /**
     * @var string
     */
    protected $_block;

    /**
     * Xml constructor.
     * @param \GoMage\Feed\Model\Feed\Row\CollectionFactory $collection
     * @param \GoMage\Feed\Model\Feed\Row\DataFactory $dataRow
     * @param \GoMage\Feed\Model\Feed\RowFactory $row
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param $content
     * @param \GoMage\Feed\Model\Feed $feed
     * @throws \Exception
     */
    public function __construct(
        \GoMage\Feed\Model\Feed\Row\CollectionFactory $collection,
        \GoMage\Feed\Model\Feed\Row\DataFactory $dataRow,
        \GoMage\Feed\Model\Feed\RowFactory $row,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        $content,
        \GoMage\Feed\Model\Feed $feed
    ) {
        parent::__construct($collection, $dataRow, $row, $jsonHelper, $content, $feed);

        $match = [];
        preg_match(self::BLOCK_PATTERN, $this->_content, $match);

        if (!isset($match[1])) {
            throw new \Exception(__('Invalid Content.'));
        }

        $this->_block = $match[1];
        list($this->_header, $this->_footer) = preg_split(self::BLOCK_PATTERN, $this->_content);
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    public function getRows()
    {
        if (is_null($this->_rows)) {
            $this->_rows = $this->_collection->create();
            $match       = [];
            preg_match_all(self::ROW_PATTERN, $this->_block, $match);
            if (isset($match[1])) {
                foreach ($match[1] as $key => $value) {

                    $type = \GoMage\Feed\Model\Config\Source\Field\TypeInterface::ATTRIBUTE;
                    if (strpos($value, 'custom:') === 0) {
                        $type  = \GoMage\Feed\Model\Config\Source\Field\TypeInterface::DYNAMIC_ATTRIBUTE;
                        $value = str_replace('custom:', '', $value);
                    }
                    if (strpos($value, 'parent:') === 0) {
                        $type  = \GoMage\Feed\Model\Config\Source\Field\TypeInterface::PARENT_ATTRIBUTE;
                        $value = str_replace('parent:', '', $value);
                    }

                    $data = [
                        'name'  => $match[0][$key],
                        'type'  => $type,
                        'value' => $value,
                    ];

                    if (isset($match[2][$key]) && $match[2][$key]) {
                        preg_match_all(self::PARAMS_PATTERN, $match[2][$key], $params);
                        foreach ($params[1] as $_key => $param) {
                            $data[trim($param)] = trim($params[2][$_key]);
                        }
                    }

                    if (is_array($data)) $data['additionalData'] = $this->setAdditionalData();

                    /** @var \GoMage\Feed\Model\Feed\Row\Data $rowData */
                    $rowData = $this->_dataRow->create(['data' => $data]);

                    /** @var \GoMage\Feed\Model\Feed\Row $row */
                    $row = $this->_row->create(['rowData' => $rowData]);

                    $this->_rows->add($row);
                }
            }
        }
        return $this->_rows;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_footer;
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return $this->_block;
    }

}
