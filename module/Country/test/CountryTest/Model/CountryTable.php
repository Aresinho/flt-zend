<?php
namespace CountryTest\Model;

use Country\Model\CountryTable;
use Country\Model\Country;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class CountryTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllCountrys()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $countryTable = new CountryTable($mockTableGateway);

        $this->assertSame($resultSet, $countryTable->fetchAll());
    }
    
    
    public function testCanRetrieveAnCountryByItsId()
{
    $country = new Country();
    $country->exchangeArray(array('id'     => 123,
                                  'artist' => 'The Military Wives',
                                  'title'  => 'In My Dreams'));

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Country());
    $resultSet->initialize(array($country));

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $countryTable = new CountryTable($mockTableGateway);

    $this->assertSame($country, $countryTable->getCountry(123));
}

public function testCanDeleteAnCountryByItsId()
{
    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('delete')
                     ->with(array('id' => 123));

    $countryTable = new CountryTable($mockTableGateway);
    $countryTable->deleteCountry(123);
}

public function testSaveCountryWillInsertNewCountrysIfTheyDontAlreadyHaveAnId()
{
    $countryData = array('artist' => 'The Military Wives', 'title' => 'In My Dreams');
    $country     = new Country();
    $country->exchangeArray($countryData);

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('insert')
                     ->with($countryData);

    $countryTable = new CountryTable($mockTableGateway);
    $countryTable->saveCountry($country);
}

public function testSaveCountryWillUpdateExistingCountrysIfTheyAlreadyHaveAnId()
{
    $countryData = array('id' => 123, 'artist' => 'The Military Wives', 'title' => 'In My Dreams');
    $country     = new Country();
    $country->exchangeArray($countryData);

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Country());
    $resultSet->initialize(array($country));

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                       array('select', 'update'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));
    $mockTableGateway->expects($this->once())
                     ->method('update')
                     ->with(array('artist' => 'The Military Wives', 'title' => 'In My Dreams'),
                            array('id' => 123));

    $countryTable = new CountryTable($mockTableGateway);
    $countryTable->saveCountry($country);
}

public function testExceptionIsThrownWhenGettingNonexistentCountry()
{
    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Country());
    $resultSet->initialize(array());

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $countryTable = new CountryTable($mockTableGateway);

    try {
        $countryTable->getCountry(123);
    }
    catch (\Exception $e)
    {
        $this->assertSame('Could not find row 123', $e->getMessage());
        return;
    }

    $this->fail('Expected exception was not thrown');
}
    
}
?>