<?php

namespace GoMage\Feed\Model\Output;

class Factory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var array
     */
    protected $_outputs = [
        OutputInterface::NONE              => 'GoMage\Feed\Model\Output\None',
        OutputInterface::INTEGER           => 'GoMage\Feed\Model\Output\Integer',
        OutputInterface::FLOATS            => 'GoMage\Feed\Model\Output\Floats',
        OutputInterface::STRIP_TAGS        => 'GoMage\Feed\Model\Output\StripTags',
        OutputInterface::SPECIAL_ENCODE    => 'GoMage\Feed\Model\Output\SpecialEncode',
        OutputInterface::SPECIAL_DECODE    => 'GoMage\Feed\Model\Output\SpecialDecode',
        OutputInterface::DELETE_SPACE      => 'GoMage\Feed\Model\Output\DeleteSpace',
        OutputInterface::BIG_TO_SMALL      => 'GoMage\Feed\Model\Output\BigToSmall',
        OutputInterface::REMOVE_LINE_BREAK => 'GoMage\Feed\Model\Output\RemoveLineBreak',
    ];

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param int $output
     * @return \GoMage\Feed\Model\Output\OutputInterface
     */
    public function get($output)
    {
        return $this->_objectManager->get($this->_outputs[intval($output)]);
    }

}
