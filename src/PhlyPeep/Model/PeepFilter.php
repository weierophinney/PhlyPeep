<?php

namespace PhlyPeep\Model;

use Zend\InputFilter\InputFilter;

class PeepFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'     => 'identifier',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[a-zA-Z0-9]{8}$/',
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'     => 'username',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[a-zA-Z0-9_]+$/',
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'     => 'email',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => false,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'        => 'display_name',
            'required'    => false,
            'allow_empty' => true,
            'filters'     => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'        => 'timestamp',
            'required'    => true,
            'filters'     => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'        => 'peep_text',
            'required'    => true,
            'allow_empty' => false,
            'filters'     => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 1,
                        'max' => 140,
                    ),
                )
            ),
        ));
    }
}
