<?php

namespace PhlyPeep\Service;

use PhlyPeep\Model\PeepTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeepTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $config    = $services->get('config');
        $config    = $config['phly_peep']['db'];

        $adapter   = $services->get($config['adapter']);
        $tableName = $config['table'];

        $table     = new PeepTable($adapter, $tableName);

        return $table;
    }
}
