<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Content;

class Csv extends AbstractContent
{

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $content,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($objectManager, $content);
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    public function getRows()
    {
        if (is_null($this->_rows)) {

            $this->_rows = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Collection');

            $content = $this->_jsonHelper->jsonDecode($this->_content);
            foreach ($content as $data) {

                /** @var \GoMage\Feed\Model\Feed\Row\Data $rowData */
                $rowData = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row\Data', ['data' => $data]);

                /** @var \GoMage\Feed\Model\Feed\Row $row */
                $row = $this->_objectManager->create('GoMage\Feed\Model\Feed\Row', ['rowData' => $rowData]);

                $this->_rows->add($row);
            }
        }

        return $this->_rows;
    }
}
