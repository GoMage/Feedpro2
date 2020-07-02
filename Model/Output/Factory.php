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
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Output;

use Magento\Framework\ObjectManagerInterface;

class Factory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var array
     */
    private $_outputs = [
        OutputInterface::NONE               => None::class,
        OutputInterface::INTEGER            => Integer::class,
        OutputInterface::FLOATS             => Floats::class,
        OutputInterface::STRIP_TAGS         => StripTags::class,
        OutputInterface::SPECIAL_ENCODE     => SpecialEncode::class,
        OutputInterface::SPECIAL_DECODE     => SpecialDecode::class,
        OutputInterface::DELETE_SPACE       => DeleteSpace::class,
        OutputInterface::BIG_TO_SMALL       => BigToSmall::class,
        OutputInterface::REMOVE_LINE_BREAK  => RemoveLineBreak::class,
        OutputInterface::HTML_SPECIAL_CHARS_ENCODE => HtmlSpecialChars::class,
    ];

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string
     * @return OutputInterface
     * @throws \Exception
     */
    public function get($output)
    {
        if (!isset($this->_outputs[$output])) {
            throw new \Exception($output . ' output isn\'t supported');
        }

        return $this->objectManager->get($this->_outputs[$output]);
    }

}
