<?php

/**
 * GoMage.com
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

namespace GoMage\Feed\Model\Feed;

use GoMage\Feed\Helper\Data;
use GoMage\Feed\Model\Feed;

class ResultModel
{
    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $totalPages;

    /**
     * @var Feed
     */
    private $feed;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @return int
     */
    private function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     * @return void
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = ceil($totalPages);
    }

    /**
     * @return int
     */
    private function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return void
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return Feed
     */
    private function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     * @return void
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * @return array
     */
    public function getStructuredData()
    {
        $result = [
            'currentPage' => $this->getCurrentPage(),
            'totalPages' => $this->getTotalPages()
        ];

        if ($this->getCurrentPage() >= $this->getTotalPages()) {
            $feed = $this->getFeed();
            $result['generationTime'] = $feed->getData('generation_time');
            $url = $this->helper->getFeedUrl($feed->getFullFileName(), $feed->getStoreId());
            $result['url'] = $url;

            $lastGenerated = $feed->getData('generated_at');
            $result['lastGenerated'] = Date('Y-m-d H:i:s', strtotime($lastGenerated));;
        }

        return $result;
    }
}
