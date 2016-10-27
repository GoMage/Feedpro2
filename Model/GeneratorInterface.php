<?php
namespace GoMage\Feed\Model;

interface GeneratorInterface
{
    /**
     * @param  FeedInterface
     * @return bool
     */
    public function generate(FeedInterface $feed);

}