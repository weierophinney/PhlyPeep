<?php

namespace PhlyPeep\Model;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Csrf as CsrfElement;

class PeepForm
{
    public static function factory(PeepEntity $peep = null)
    {
        if (null === $peep) {
            $peep = __NAMESPACE__ . '\PeepEntity';
        }

        $builder = new AnnotationBuilder();
        $form = $builder->createForm($peep);


        $form->add(new CsrfElement('secure'));

        $form->add(array(
            'name'       => 'peep',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Peep!',
            ),
        ));

        if ($peep instanceof PeepEntity) {
            $form->bind($peep);
        }

        return $form;
    }
}
