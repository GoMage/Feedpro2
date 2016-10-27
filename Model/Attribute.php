<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

/**
 * Class Attribute
 *
 * @method string getDefaultValue()
 * @method string getContent()
 * @method string getName()
 * @method string getCode()
 */
class Attribute extends \Magento\Framework\Model\AbstractModel implements AttributeInterface
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

    /**
     * @return string
     */
    public function getAttributeCode()
    {
        return AttributeInterface::PREFIX . $this->getCode();
    }
}
