<?php

namespace PhlyPeep;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $target = $e->getTarget();
        $events = $target->events();
        $shared = $events->getSharedManager();
        $shared->attach('Zend\Stdlib\DispatchableInterface', 'dispatch',
            array($this, 'onDispatchEnd'), -100);
    }

    public function onDispatchEnd($e)
    {
        $controller = $e->getTarget();
        $class = get_class($controller);

        if (!preg_match('/^PhlyPeep/', $class)) {
            return;
        }

        $viewModel = $e->getResult();

        if (!$viewModel instanceof \Zend\View\Model\ModelInterface) {
           return;
        }

        if (!isset($viewModel->peeps)) {
           return;
        }

        $peeps = $viewModel->peeps;

        if (!$peeps instanceof \Zend\Paginator\Paginator) {
           return;
        }

        $request = $e->getRequest();
        $headers = $request->headers();

        if (!$headers->has('Accept')) {
            return;
        }

        $hasJson = false;
        $accept = $headers->get('Accept');
        foreach ($accept->getPrioritized() as $mediaType) {
            if (0 === strpos($mediaType, 'application/json')) {
                // application/json Accept header found
                $hasJson = true;
                break;
            }
        }

        if (!$hasJson) {
            return;
        }

        $peeps->getItemsByPage(1);



        $peepsArray = array();

        foreach ($peeps as $peep) {
            $peepsArray[] = $peep->getArrayCopy();
        }

        $viewModel->setVariable('peeps', $peepsArray);
    }


}
