<?php

namespace PhlyPeep\Service;

use PhlyPeep\View\PeepForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeepViewFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $authService = $allServices->get('zfcuser_auth_service');
        $helper      = new PeepForm();
        $helper->setAuthService($authService);
        return $helper;
    }
}
