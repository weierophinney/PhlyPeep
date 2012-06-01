<?php

namespace PhlyPeep\Service;

use PhlyPeep\Controller\PeepController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeepControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $authService = $services->get('zfcuser_auth_service');
        $peepService = $services->get('phly-peep-service');
        $controller  = new PeepController();
        $controller->setAuthService($authService);
        $controller->setPeepService($peepService);
        return $controller;
    }
}
