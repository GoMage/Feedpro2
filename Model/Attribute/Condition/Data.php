<?php

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
        if (!isset($data['operator']) || !$data['operator']) {
            throw new ValidatorException(__('Condition operator is required.'));
        }
        return $data;
    }

}
