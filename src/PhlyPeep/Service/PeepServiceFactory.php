<?php

namespace PhlyPeep\Service;

use PhlyPeep\Model\PeepService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeepServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $config    = $services->get('config');
        $peepTable = $services->get('phly-peep-table');
        $service   = new PeepService($peepTable);

        if (isset($config['phly_peep']) && isset($config['phly_peep']['page_size'])) {
            $service->setPageSize($config['phly_peep']['page_size']);
        }

        return $service;
    }
}
