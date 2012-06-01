<?php

namespace PhlyPeep\View;

use PhlyPeep\Model;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

class PeepForm extends AbstractHelper
{
    protected $auth;

    public function setAuthService(AuthenticationService $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke($form = null, $template = 'phly-peep/peep/form')
    {
        if (!$this->auth || !$this->auth->hasIdentity()) {
            return $this->view->render('phly-peep/peep/register');
        }

        if (null === $form) {
            $form = new Model\PeepForm();
        }

        return $this->view->render($template, array('form' => $form));
    }
}
