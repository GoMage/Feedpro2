<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model;

use GoMage\Feed\Model\Config\Source\Status;
use GoMage\Feed\Model\Feed\ResultModel;
use GoMage\Feed\Model\Generator\ApplyServerSettings;
use GoMage\Feed\Model\Generator\Generate;
use GoMage\Feed\Model\Logger\HandlerFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Generator
{
    /**
     * @var FeedFactory
     */
    private $feedFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ApplyServerSettings
     */
    private $applyServerSettings;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var HandlerFactory
     */
    private $handlerFactory;

    /**
     * @var Generate
     */
    private $generate;

    /**
     * @param FeedFactory $feedFactory
     * @param LoggerInterface $logger
     * @param ApplyServerSettings $applyServerSettings
     * @param DateTime $dateTime
     * @param StoreManagerInterface $storeManager
     * @param HandlerFactory $handlerFactory
     * @param Generate $generate
     */
    public function __construct(
        FeedFactory $feedFactory,
        LoggerInterface $logger,
        ApplyServerSettings $applyServerSettings,
        DateTime $dateTime,
        StoreManagerInterface $storeManager,
        HandlerFactory $handlerFactory,
        Generate $generate
    ) {
        $this->feedFactory = $feedFactory;
        $this->logger = $logger;
        $this->applyServerSettings = $applyServerSettings;
        $this->storeManager = $storeManager;
        $this->handlerFactory = $handlerFactory;
        $this->dateTime = $dateTime;
        $this->generate = $generate;
    }

    /**
     * @param  int $feedId
     * @param  int|null $page
     * @param  string $writeMode
     *
     * @return ResultModel
     */
    public function generate($feedId, $page = null, $writeMode = 'w')
    {
        $feed = $this->feedFactory->create()->load($feedId);
        $this->storeManager->setCurrentStore($feed->getStoreId());

        try {
            if ($feed->getStatus() == Status::IN_PROGRESS) {
                throw new \Exception(__('Feed already in progress'));
            }
//            $feed->setStatus(Status::IN_PROGRESS);

            $time = microtime(true);

            $this->prepareLoggerForFeed($feed);
            $this->logger->info(__('Start generation'));

            $this->applyServerSettings->execute();

            $resultModel = $this->generate->execute($feed, $this->logger, $page, $writeMode);

            $feed->setStatus(Status::COMPLETED);

            $time = microtime(true) - $time;
            $time = max([$time, 1]);

            $feed->setData('generation_time', $this->dateTime->gmtDate('H:i:s', $time))
                ->setData('generated_at', $this->dateTime->gmtDate('Y-m-j H:i:s'))
                ->save();

            $resultModel->setFeed($feed);
            $this->logger->info(__('Finish'));
        } catch (\Exception $e) {
            $feed->setStatus(Status::FAILED);
            $this->logger->info($e->getMessage());

            throw new \Exception($e->getMessage());
        }

        return $resultModel;
    }

    /**
     * @param Feed $feed
     *
     * @return void
     */
    private function prepareLoggerForFeed(Feed $feed)
    {
        $logHandler = $this->handlerFactory->create(['fileName' => '/var/log/feed-' . $feed->getId() . '.log']);
        $this->logger->setHandlers([$logHandler]);
    }
}
