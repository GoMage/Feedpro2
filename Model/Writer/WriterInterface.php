<?php
namespace GoMage\Feed\Model\Writer;


interface WriterInterface
{

    const DIRECTORY = 'gomage_feed';

    /**
     * @param  array $data
     */
    public function write(array $data);

}