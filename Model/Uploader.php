<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model;

use Psr\Log\LoggerInterface;

class Uploader
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    /**
     * @var \GoMage\Feed\Model\Protocol\Factory
     */
    protected $_protocolFactory;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \GoMage\Feed\Helper\Data $helper,
        \GoMage\Feed\Model\Protocol\Factory $protocolFactory
    ) {
        $this->_objectManager   = $objectManager;
        $this->_dateTime        = $date;
        $this->_helper          = $helper;
        $this->_protocolFactory = $protocolFactory;
    }

    public function upload($feedId)
    {
        /** @var \GoMage\Feed\Model\Feed $feed */
        $feed = $this->_objectManager->create('GoMage\Feed\Model\Feed')->load($feedId);
        if ($this->_validate($feed)) {
            $protocol = $this->_protocolFactory->create($feed->getFtpProtocol(),
                [
                    'host'         => $feed->getData('ftp_host'),
                    'port'         => $feed->getData('ftp_port'),
                    'user'         => $feed->getData('ftp_user_name'),
                    'password'     => $feed->getData('ftp_password'),
                    'passive_mode' => $feed->getData('is_ftp_passive'),
                ]
            );

            if ($protocol->execute($this->_helper->getFeedPath($feed->getFullFileName(), true), $feed->getFtpDir())) {
                $feed->setData('uploaded_at', $this->_dateTime->gmtDate('Y-m-j H:i:s'))->save();
            }
        }
    }

    protected function _validate(\GoMage\Feed\Model\Feed $feed)
    {
        if ($feed->getStatus() != \GoMage\Feed\Model\Config\Source\Status::COMPLETED) {
            throw new \Exception(__('Please generate a feed.'));
        }

        if (!$feed->getIsFtp()) {
            throw new \Exception(__('FTP Uploading is disabled for this feed.'));
        }

        if (!$this->_helper->getFeedPath($feed->getFullFileName(), true)) {
            throw new \Exception(__('File not found. Please generate a feed.'));
        }

        return true;
    }


}