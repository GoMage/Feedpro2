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

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Catalog\Api\ProductAttributeMediaGalleryManagementInterface;
use Magento\Framework\DataObject;
use Magento\Catalog\Model\Product\Media\ConfigInterface;

class Image implements CustomMapperInterface
{
    /**
     * @var string
     */
    private $_code;
    /**
     * @var ProductAttributeMediaGalleryManagementInterface
     */
    private $_attributeMediaGalleryManagement;

    /**
     * @var ConfigInterface
     */
    private $_config;

    /**
     * Image constructor.
     * @param $value
     * @param ProductAttributeMediaGalleryManagementInterface $attributeMediaGalleryManagement
     * @param ConfigInterface $config
     */
    public function __construct(
        $value,
        ProductAttributeMediaGalleryManagementInterface $attributeMediaGalleryManagement,
        ConfigInterface $config
    )
    {
        $this->_code = $value;
        $this->_attributeMediaGalleryManagement = $attributeMediaGalleryManagement;
        $this->_config = $config;
    }

    /**
     * @param DataObject $object
     * @return bool|mixed|string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function map(DataObject $object)
    {
        $images = $this->_attributeMediaGalleryManagement->getList($object->getSku());
        $image_id = $this->getImageKeyId();
        if (isset($images[$image_id])) {
            $image = $images[$image_id]->getFile();
            if ($url = $this->getImageUrl($image)) {
                return $url;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getLabel()
    {
        return __('Image');
    }

    /**
     * @return mixed
     */
    private function getImageKeyId()
    {
        return explode('_', $this->_code)[1];
    }

    /**
     * Returns image url
     *
     * @param $image
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getImageUrl($image)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $mediaBaseUrl = $this->_config->getBaseMediaUrl();
                $url = $mediaBaseUrl
                    . $image;

            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}
