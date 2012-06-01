<?php

namespace PhlyPeep\Model;

use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Form\Form;

class PeepForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setupElements();
    }

    protected function setupElements()
    {
        $this->add(array(
            'name' => 'peep_text',
            'attributes' => array(
                'type'        => 'textarea',
                'placeholder' => 'What are you thinking now?',
                'label'       => "What's on your mind?",
            ),
        ));

        $this->add(new CsrfElement('secure'));

        $this->add(array(
            'name'       => 'peep',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Peep!',
            ),
        ));
    }
}
