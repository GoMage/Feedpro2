<?php
namespace GoMage\Feed\Model\Logger;

use Monolog\Logger;
use Magento\Framework\Filesystem\DriverInterface;
use Monolog\Formatter\LineFormatter;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/feed.log';


    public function __construct(
        DriverInterface $filesystem,
        $filePath = null,
        $fileName = null
    ) {
        if ($fileName) {
            $this->fileName = $fileName;
        }
        parent::__construct($filesystem, $filePath);
    }

}
