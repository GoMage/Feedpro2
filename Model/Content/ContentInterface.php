<?php
namespace GoMage\Feed\Model\Content;


interface ContentInterface
{

    /**
     * @return \GoMage\Feed\Model\Feed\Row\Collection
     */
    public function getRows();

}