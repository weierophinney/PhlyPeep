<?php

namespace PhlyPeep\Service;

use PhlyPeep\Controller\PeepController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeepControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $authService = $allServices->get('zfcuser_auth_service');
        $peepService = $allServices->get('phly-peep-service');
        $controller  = new PeepController();
        $controller->setAuthService($authService);
        $controller->setPeepService($peepService);
        return $controller;
    }
}
