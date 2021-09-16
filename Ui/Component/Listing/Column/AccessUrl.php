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
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\GroupRepositoryInterface;


class AccessUrl extends Column
{
    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \GoMage\Feed\Helper\Data $helper,
        array $components = [],
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $fileName                     = $item['filename'] . '.' . $item['file_ext'];
                $url                          = $this->_helper->getFeedUrl($fileName, (int)$item['store_id']);
                $item[$this->getData('name')] = $url ? '<a href="' . $url . '" target="_blank">' . $url . '</a>' : '';
            }
        }

        return $dataSource;
    }
}
