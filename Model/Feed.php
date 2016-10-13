<?php
namespace GoMage\Feed\Model;

/**
 * Class Feed
 *
 * @method string getContent()
 */
class Feed extends \Magento\Framework\Model\AbstractModel implements FeedInterface
{

    protected function _construct()
    {
        $this->_init('GoMage\Feed\Model\ResourceModel\Feed');
    }

    /**
     * @return string
     */
    public function getFullFileName()
    {
        return $this->getFilename() . '.' . $this->getFileExt();
    }

}