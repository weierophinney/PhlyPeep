<?php
return array(
    'phly_peep' => array(
        'db' => array(
            'adapter' => 'Zend\Db\Adapter\Adapter',
            'table'   => 'peep',
        ),
        'page_size' => 20,
    ),
    'router' => array(
        'routes' => array(
            'phly-peep' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/peep',
                    'defaults' => array(
                        'controller' => 'phly-peep',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'submit' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/process',
                            'defaults' => array(
                                'action' => 'submit',
                            ),
                        ),
                    ),
                    // by user
                    'user' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user/:username',
                            'constraints' => array(
                                'username' => '[a-zA-Z0-9_]+',
                            ),
                            'defaults' => array(
                                'action' => 'username',
                            ),
                        ),
                    ),
                    // by id
                    'status' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/status/:identifier',
                            'constraints' => array(
                                'identifier' => '[a-zA-Z][0-9]{8}',
                            ),
                            'defaults' => array(
                                'action' => 'status',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controller' => array(
        'factories' => array(
            'phly-peep' => 'PhlyPeep\Service\PeepControllerFactory',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'peeptext' => 'PhlyPeep\View\PeepText',
        ),
        'factories' => array(
            'peepform'          => 'PhlyPeep\Service\PeepViewFormFactory',
            'phly-peep-service' => 'PhlyPeep\Service\PeepServiceFactory',
            'phly-peep-table'   => 'PhlyPeep\Service\PeepTableFactory',
        ),
    ),
);
