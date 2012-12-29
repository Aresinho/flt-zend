<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Country;

use Country\Model\Country;
use Country\Model\CountryTable;
use Country\Model\City;
use Country\Model\CityTable;
use Country\Model\CountryLanguage;
use Country\Model\CountryLanguageTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()  {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()  {
        return array(
            'factories' => array(
                'Country\Model\CountryTable' =>  function($sm) {
                    $tableGateway = $sm->get('CountryTableGateway');
                    $table = new CountryTable($tableGateway);
                    return $table;
                },
                'CountryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Country());
                    return new TableGateway('Country', $dbAdapter, null, $resultSetPrototype);
                },
                'Country\Model\CityTable' =>  function($sm) {
                    $tableGateway = $sm->get('CityTableGateway');
                    $table = new CityTable($tableGateway);
                    return $table;
                },
                'CityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new City());
                    return new TableGateway('City', $dbAdapter, null, $resultSetPrototype);
                },
                'Country\Model\CountryLanguageTable' =>  function($sm) {
                    $tableGateway = $sm->get('CountryLanguageTableGateway');
                    $table = new CountryLanguageTable($tableGateway);
                    return $table;
                },
                'CountryLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CountryLanguage());
                    return new TableGateway('CountryLanguage', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
