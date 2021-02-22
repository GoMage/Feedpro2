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

namespace GoMage\Feed\Block\Adminhtml\Feed\Edit\Tab;

use GoMage\Feed\Model\Feed;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Config\Model\Config\Source\Enabledisable;
use GoMage\Feed\Model\Config\Source\Protocol;

class Ftp extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @var Enabledisable
     */
    protected $_enableDisable;

    /**
     * @var Protocol
     */
    protected $_protocol;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Yesno $yesNo
     * @param Enabledisable $enableDisable
     * @param Protocol $protocol
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        Enabledisable $enableDisable,
        Protocol $protocol,
        array $data = []
    ) {

        $this->_yesNo         = $yesNo;
        $this->_enableDisable = $enableDisable;
        $this->_protocol      = $protocol;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model Feed */
        $model = $this->_coreRegistry->registry('current_feed');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('feed_');

        $fieldset = $form->addFieldset(
            'ftp_fieldset',
            ['legend' => __('FTP Settings')]
        );

        $is_ftp = $fieldset->addField(
            'is_ftp',
            'select',
            [
                'name'   => 'is_ftp',
                'label'  => __('Enabled'),
                'title'  => __('Enabled'),
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );

        $ftp_protocol = $fieldset->addField(
            'ftp_protocol',
            'select',
            [
                'name'   => 'ftp_protocol',
                'label'  => __('Protocol'),
                'title'  => __('Protocol'),
                'values' => $this->_protocol->toOptionArray(),
            ]
        );

        $ftp_host = $fieldset->addField(
            'ftp_host',
            'text',
            [
                'name'  => 'ftp_host',
                'label' => __('Host Name'),
                'title' => __('Host Name'),
                'note'  => __('e.g. "ftp.domain.com"'),
                'class' => 'no-whitespace'
            ]
        );

        $ftp_port = $fieldset->addField(
            'ftp_port',
            'text',
            [
                'name'  => 'ftp_port',
                'label' => __('Port'),
                'title' => __('Port'),
            ]
        );

        $ftp_user_name = $fieldset->addField(
            'ftp_user_name',
            'text',
            [
                'name'  => 'ftp_user_name',
                'label' => __('User Name'),
                'title' => __('User Name'),
            ]
        );

        $ftp_password = $fieldset->addField(
            'ftp_password',
            'password',
            [
                'name'  => 'ftp_password',
                'label' => __('Password'),
                'title' => __('Password'),
            ]
        );

        $ftp_dir = $fieldset->addField(
            'ftp_dir',
            'text',
            [
                'name'  => 'ftp_dir',
                'label' => __('Path'),
                'title' => __('Path'),
                'note'  => __('e.g. "/yourfolder"'),
            ]
        );

        $is_ftp_passive = $fieldset->addField(
            'is_ftp_passive',
            'select',
            [
                'name'   => 'is_ftp_passive',
                'label'  => __('Passive mode'),
                'title'  => __('Passive mode'),
                'values' => $this->_enableDisable->toOptionArray(),
            ]
        );

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Form\Element\Dependence')
                ->addFieldMap($is_ftp->getHtmlId(), $is_ftp->getName())
                ->addFieldMap($ftp_protocol->getHtmlId(), $ftp_protocol->getName())
                ->addFieldMap($ftp_host->getHtmlId(), $ftp_host->getName())
                ->addFieldMap($ftp_port->getHtmlId(), $ftp_port->getName())
                ->addFieldMap($ftp_user_name->getHtmlId(), $ftp_user_name->getName())
                ->addFieldMap($ftp_password->getHtmlId(), $ftp_password->getName())
                ->addFieldMap($ftp_dir->getHtmlId(), $ftp_dir->getName())
                ->addFieldMap($is_ftp_passive->getHtmlId(), $is_ftp_passive->getName())
                ->addFieldDependence($ftp_protocol->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($ftp_host->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($ftp_port->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($ftp_user_name->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($ftp_password->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($ftp_dir->getName(), $is_ftp->getName(), 1)
                ->addFieldDependence($is_ftp_passive->getName(), $is_ftp->getName(), 1)
        );
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('FTP Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('FTP Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

}
