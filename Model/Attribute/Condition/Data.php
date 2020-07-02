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
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Attribute\Condition;

use Magento\Framework\Validator\Exception as ValidatorException;

/**
 * Class Data
 *
 * @method string getCode()
 * @method string getOperator()
 * @method string getValue()
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
        if (!isset($data['code']) || !$data['code']) {
            throw new ValidatorException(__('Condition code is required.'));
        }
        if (!isset($data['operator'])) {
            throw new ValidatorException(__('Condition operator is required.'));
        }
        return $data;
    }

}
