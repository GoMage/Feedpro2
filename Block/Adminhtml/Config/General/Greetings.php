<?php

namespace GoMage\Feed\Block\Adminhtml\Config\General;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Greetings
 * @package GoMage\Feed\Block\Adminhtml\Config\General
 */
class Greetings extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return $this->getModuleInfoHtml();
    }

    /**
     * @return string
     */
    public function getModuleInfoHtml()
    {
        $message = 'Thank you for choosing <a href="https://www.gomage.com/" target="_blank" style="color:#57d68d;">
                    <b>GoMage</b></a>! Our <a href="https://wiki.gomage.com/hc/en-us/categories/115000327891-Feed-Pro-M2" target="_blank" style="color:#57d68d;">
                    <b>online documentation</b></a>  is ready to help you in your journey to success!';

        $html = '<tr><td class="label" colspan="4" style="text-align: left;">
                 <div style="padding:10px; background-color:#f8f8f8; border:1px solid #dddddd;margin-bottom:7px;">'
                . $message . '</div></td></tr>';

        return $html;
    }
}
