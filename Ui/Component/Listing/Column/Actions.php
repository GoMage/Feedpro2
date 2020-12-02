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
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{

    /** @var UrlInterface */
    protected $_urlBuilder;

    /**
     * @var string
     */
    protected $_editUrl;

    /**
     * @var string
     */
    protected $_deleteUrl;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        $editUrl = '',
        $deleteUrl = '',
        $duplicateUrl = '',
        array $components = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        $this->_deleteUrl = $deleteUrl;
        $this->_duplicateUrl = $duplicateUrl;
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
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['edit']   = [
                        'href'  => $this->_urlBuilder->getUrl($this->_editUrl, ['id' => $item['id']]),
                        'label' => __('Edit')
                    ];
                    if ($this->_duplicateUrl) {
                        $item[$name]['duplicate'] = [
                            'href' => $this->_urlBuilder->getUrl($this->_duplicateUrl, ['id' => $item['id']]),
                            'label' => __('Duplicate')
                        ];
                    }
                    $item[$name]['delete'] = [
                        'href'    => $this->_urlBuilder->getUrl($this->_deleteUrl, ['id' => $item['id']]),
                        'label'   => __('Delete'),
                        'confirm' => [
                            'title'   => __('Delete "' . $item['name'] . '"'),
                            'message' => __('Are you sure you wan\'t to delete a "' . $item['name'] . '" record?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
