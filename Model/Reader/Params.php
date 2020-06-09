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
