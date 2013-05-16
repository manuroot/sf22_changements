<?php

namespace Application\CertificatsBundle\doctrine\Extensions;

use DoctrineExtensions\Query\Mysql\GroupConcat;

/**
* Concat using '@@' separator after and before all values
*/
class AtGroupConcat extends GroupConcat
{
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
     $sqlFieldName = $this->expression->dispatch($sqlWalker);

        return
            'CONCAT(\'@@\', GROUP_CONCAT(' .
            ($this->isDistinct ? 'DISTINCT ' : '') .
            "$sqlFieldName ORDER BY $sqlFieldName SEPARATOR '@@'), '@@')";
    }

}
