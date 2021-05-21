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

namespace GoMage\Feed\Model\Attribute;

class Field extends \GoMage\Feed\Model\Feed\Field
{

    /**
     * @var string
     */
    protected $_prefix;

    public function __construct($type, $value, $prefix, $additionalData, \GoMage\Feed\Model\Mapper\Factory $mapperFactory)
    {
        $this->_prefix = $prefix;
        parent::__construct($type, $value, $additionalData, $mapperFactory);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $value = parent::map($object);
        return $value ? $this->_prefix . $value : '';
    }

}
