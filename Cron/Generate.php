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

namespace GoMage\Feed\Cron;

class Generate
{
    /**
     * @var \GoMage\Feed\Model\ResourceModel\Feed\Collection
     */
    protected $_collection;

    /**
     * @var \GoMage\Feed\Model\Generator
     */
    protected $_generator;

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
        \GoMage\Feed\Model\Generator $generator,
        \GoMage\Feed\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->_collection = $collection;
        $this->_generator  = $generator;
        $this->_helper     = $helper;
        $this->_dateTime   = $dateTime;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $feeds = $this->_collection
            ->addFieldToFilter('generate_day', ['like' => '%' . $this->_dateTime->gmtDate('w') . '%'])
            ->load();

        /** @var \GoMage\Feed\Model\Feed $feed */
        foreach ($feeds as $feed) {
            if (!$feed->getData('is_generate')) {
                continue;
            }
            if ($feed->getData('cron_generated_at') &&
                ($this->_dateTime->gmtDate('d.m.Y:H') == $this->_dateTime->gmtDate('d.m.Y:H', $feed->getData('cron_generated_at')))
            ) {
                continue;
            }
            if (!$this->_helper->needRunCron($feed->getData('generate_interval'),
                $feed->getData('generate_hour'),
                $feed->getData('generate_hour_to'),
                $feed->getData('cron_generated_at')
            )
            ) {
                continue;
            }

            try {
                $feed->setData('cron_generated_at', $this->_dateTime->gmtDate('Y-m-j H:00:00'))->save();
                $this->_generator->generate($feed->getId());
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
