<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Login\Controller\Login' => 'Login\Controller\LoginController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'login' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/login[/:action][/:code]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Login\Controller\Login',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'country' => __DIR__ . '/../view',
        ),
    ),
);

?>