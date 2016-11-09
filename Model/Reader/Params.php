<?php

namespace GoMage\Feed\Model\Reader;

use Magento\Framework\Validator\Exception as ValidatorException;

/**
 * Class Params
 *
 * @method array getAttributes()
 * @method \Magento\Rule\Model\Condition\Combine getConditions()
 * @method int getStoreId()
 * @method int getVisibility()
 * @method bool getIsOutOfStock()
 * @method bool getIsDisabled()
 */
class Params extends \Magento\Framework\DataObject
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
        if (!isset($data['store_id']) || !$data['store_id']) {
            throw new ValidatorException(__('Store is not specified.'));
        }
        $data['is_out_of_stock'] = isset($data['is_out_of_stock']) && boolval($data['is_out_of_stock']);
        $data['is_disabled']     = isset($data['is_disabled']) && boolval($data['is_disabled']);
        return $data;
    }
}
