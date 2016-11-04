<?php
namespace GoMage\Feed\Model\Protocol;


interface ProtocolInterface
{
    const FTP = 0;
    const SSH = 1;

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest);

}