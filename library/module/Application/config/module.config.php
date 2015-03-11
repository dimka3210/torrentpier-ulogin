<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'router' => array(
         'routes' => array(
             'application' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/[:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '.*?',
                     ),
                     'defaults' => array(
                         'controller' => 'Application\Controller\Index',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
	
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layouts/layout'           => __DIR__ . '/../view/layouts/layout.phtml',

			'application/index/users' => __DIR__ . '/../view/application/index/users.phtml',
			'application/index/topic' => __DIR__ . '/../view/application/index/topic.phtml',
			'application/index/settings' => __DIR__ . '/../view/application/index/settings.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'application/index/forums' => __DIR__ . '/../view/application/index/forums.phtml',
			'application/index/dl' => __DIR__ . '/../view/application/index/dl.phtml',
			
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
