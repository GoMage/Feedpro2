<?php

namespace GoMage\Feed\Cron;

class Upload
{
    /**
     * @var \GoMage\Feed\Model\ResourceModel\Feed\Collection
     */
    protected $_collection;

    /**
     * @var \GoMage\Feed\Model\Uploader
     */
    protected $_uploader;

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;


    public function __construct(
        \GoMage\Feed\Model\ResourceModel\Feed\Collection $collection,
        \GoMage\Feed\Model\Uploader $uploader,
        \GoMage\Feed\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->_collection = $collection;
        $this->_uploader   = $uploader;
        $this->_helper     = $helper;
        $this->_dateTime   = $dateTime;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $feeds = $this->_collection
            ->addFieldToFilter('upload_day', ['like' => '%' . $this->_dateTime->gmtDate('w') . '%'])
            ->load();

        /** @var \GoMage\Feed\Model\Feed $feed */
        foreach ($feeds as $feed) {
            if (!$feed->getData('is_upload')) {
                continue;
            }
            if ($this->_dateTime->gmtDate('d.m.Y:H') == $this->_dateTime->gmtDate('d.m.Y:H', $feed->getData('cron_uploaded_at'))) {
                continue;
            }
            if ($this->_helper->needRunCron($feed->getData('upload_interval'),
                $feed->getData('upload_hour'),
                $feed->getData('upload_hour_to'),
                $feed->getData('cron_uploaded_at')
            )
            ) {
                continue;
            }

            try {
                $feed->setData('cron_uploaded_at', $this->_dateTime->gmtDate('Y-m-j H:00:00'))->save();
                $this->_uploader->upload($feed->getId());
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
