<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Attribute\Row;

use Magento\Framework\Validator\Exception as ValidatorException;

/**
 * Class Data
 *
 * @method array getConditions()
 * @method string getType()
 * @method mixed getValue()
 */
class Data extends \Magento\Framework\DataObject
{

    public function __construct(array $data = [])
    {
        $data = $this->validate($data);
        parent::__construct($data);
    }

    /**
     * @param  array $data
     * @return array
     * @throws ValidatorException
     */
    protected function validate(array $data)
    {
        if (!isset($data['conditions']) || !$data['conditions']) {
            throw new ValidatorException(__('Conditions are not specified.'));
        }
        if (!isset($data['type']) || !$data['type']) {
            throw new ValidatorException(__('Type is required field.'));
        }
        return $data;
    }
}
