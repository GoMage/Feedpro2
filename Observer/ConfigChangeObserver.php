<?php

namespace GoMage\Feed\Observer;

use Magento\Framework\Event\ObserverInterface;

class ConfigChangeObserver implements ObserverInterface
{

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;


    public function __construct(
        \GoMage\Feed\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_helper->a();
    }

}
