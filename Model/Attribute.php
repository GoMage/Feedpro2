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

namespace GoMage\Feed\Model;

/**
 * Class Attribute
 *
 * @method string getDefaultValue()
 * @method string getContent()
 * @method string getName()
 * @method string getCode()
 */
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
