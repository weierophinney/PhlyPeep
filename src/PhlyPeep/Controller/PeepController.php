<?php

namespace PhlyPeep\Controller;

use PhlyPeep\Model;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUser\Entity\User;

class PeepController extends AbstractActionController
{
    protected $auth;
    protected $service;

    public function setPeepService(Model\PeepService $service)
    {
        $this->service = $service;
    }

    public function setAuthService(AuthenticationService $auth)
    {
        $this->auth = $auth;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $page    = $request->getQuery()->get('page', 1);
        return array(
            'peeps' => $this->service->fetchTimeline($page),
        );
    }

    public function submitAction()
    {
        // Do we have a user?
        if (!$this->auth->hasIdentity()) {
            $response = $this->getResponse();
            $response->setStatusCode(401);

            $viewModel = new ViewModel();
            $viewModel->setTemplate('phly-peep/peep/401');
            return $viewModel;
        }

        $identity = $this->auth->getIdentity();
        if (!$identity instanceof User) {
            throw new \DomainException('Unknown authenticated user type encountered');
        }

        $peep = new Model\PeepEntity();
        $form = Model\PeepForm::factory($peep);

        $request              = $this->getRequest();
        $data                 = $request->getPost();
        $data['username']     = $identity->getUsername();
        $data['email']        = $identity->getEmail();
        $data['display_name'] = $identity->getDisplayName();
        $data['timestamp']    = $_SERVER['REQUEST_TIME'];
        $form->setData($data);
        $form->setValidationGroup(
            'peep_text', 'username', 'email', 'display_name', 'timestamp', 'secure'
        );

        if (!$form->isValid()) {
            $viewModel = new ViewModel(array(
                'form' => $form,
            ));
            $viewModel->setTemplate('phly-peep/peep/form-error');
            return $viewModel;
        }

        $this->service->insertPeep($peep);

        return $this->redirect()->toRoute(
            'phly-peep/user', 
            array('username' => $identity->getUsername())
        );
    }

    public function usernameAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $username = $routeMatch->getParam('username', false);
        if (!$username) {
            return $this->redirect()->toRoute('phly-peep');
        }

        $request  = $this->getRequest();
        $page = $request->getQuery()->get('page', 1);

        return array(
            'username' => $username,
            'peeps'    => $this->service->fetchUserTimeline($username, $page),
        );
    }

    public function statusAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $identifier = $routeMatch->getParam('identifier', false);
        if (!$identifier) {
            return $this->redirect()->toRoute('phly-peep');
        }

        return array(
            'peep' =>  $this->service->fetchPeep($identifier),
        );
    }
}
