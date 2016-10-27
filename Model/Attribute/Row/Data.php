<?php

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
