<?php
namespace GoMage\Feed\Model;


interface GeneratorInterface
{
    /**
     * @param Feed $feed
     * @return bool
     */
    public function generate(Feed $feed);
}