<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Country\Controller\Country' => 'Country\Controller\CountryController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'country' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/country[/:action][/:code]',
                    'constraints' => array(
                        'action' => '[a-zA-Z-][a-zA-Z0-9_-]*',
                        'code'     => '[A-Z0-9-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Country\Controller\Country',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
      'acl' => array(
        'roles' => array(
            'guest'   => null,
            'admin'  => 'guest'
        ),
        'resources' => array(
            'allow' => array(
                'user' => array(
                    'login' => 'guest',
                    'all'   => 'admin'
                )
            )
        )
    ),
    

    'view_manager' => array(
        'template_path_stack' => array(
             __DIR__ . '/../view',
        ),
    ),
);

?>