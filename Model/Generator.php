<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class Generator extends \Magento\Framework\Object implements GeneratorInterface
{

    /**
     * @var Feed
     */
    protected $_feed;

    /**
     * @var \Magento\Framework\Filesystem\Directory\Write
     */
    protected $_directory;

    /**
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_stream;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateModel;

    /**
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Filesystem $filesystem
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateModel,
        array $data = []
    ) {

        $this->_escaper   = $escaper;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_dateModel = $dateModel;

        parent::__construct($data);
    }

    /**
     * Get file handler
     *
     * @return \Magento\Framework\Filesystem\File\WriteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getStream()
    {
        if ($this->_stream) {
            return $this->_stream;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('File handler unreachable'));
        }
    }

    /**
     * @param Feed $feed
     * @return bool
     */
    public function generate(Feed $feed)
    {
        $this->_feed = $feed;

        $this->_feed->setData('generated_at', $this->_dateModel->date('Y-m-d H:i:s'))
            ->save();

        return true;
    }
}
