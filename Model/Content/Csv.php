<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Content;

class Csv extends AbstractContent
{

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    public function getRows()
    {
        if (is_null($this->_rows)) {

            $this->_rows = $this->_collection->create();

            $content = $this->_jsonHelper->jsonDecode($this->_content);
            foreach ($content as $data) {
                if (is_array($data)) $data['additionalData'] = $this->setAdditionalData();

                /** @var \GoMage\Feed\Model\Feed\Row\Data $rowData */
                $rowData = $this->_dataRow->create(['data' => $data]);

                /** @var \GoMage\Feed\Model\Feed\Row $row */
                $row = $this->_row->create(['rowData' => $rowData]);

                $this->_rows->add($row);
            }
        }

        return $this->_rows;
    }
}
