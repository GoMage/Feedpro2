<?php
namespace GoMage\Feed\Model\Reader;


interface ReaderInterface
{

    /**
     * @param  int $page
     * @param  int $limit
     * @return mixed
     */
    public function read($page, $limit);

}