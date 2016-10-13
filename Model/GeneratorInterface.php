<?php
namespace GoMage\Feed\Model;

use GoMage\Feed\Model\FeedInterface;

interface GeneratorInterface
{
    /**
     * @param  FeedInterface
     * @return bool
     */
    public function generate(FeedInterface $feed);

}