<?php

namespace GoMage\Feed\Model\Protocol;

use Magento\Framework\Validator\Exception as ValidatorException;

/**
 * Class Params
 *
 * @method string getHost)
 * @method int getPort()
 * @method string getUser()
 * @method string getPassword()
 * @method bool getPassiveMode()
 */
class Params extends \Magento\Framework\DataObject
{

    const PORT = 21;

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
        if (!isset($data['host']) || !$data['host']) {
            throw new ValidatorException(__('Host is not specified.'));
        }
        if (!isset($data['user']) || !$data['user']) {
            throw new ValidatorException(__('User is not specified.'));
        }
        if (!isset($data['port']) || !$data['port']) {
            $data['port'] = self::PORT;
        }
        $data['passive_mode'] = isset($data['passive_mode']) && boolval($data['passive_mode']);
        return $data;
    }
}
