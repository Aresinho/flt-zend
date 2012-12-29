<?php
  
  namespace CountryTest\Model;
  
  use Country\Model\Country;
  use PHPUnit_Framework_TestCase;
  
  class WorldTest extends PHPUnit_Framework_TestCase
  {
    public function testCountryInitialState()
    {
        $country = new Country();

        $this->assertNull($country->code, '"artist" should initially be null');
        $this->assertNull($country->name, '"id" should initially be null');
        $this->assertNull($country->continent, '"title" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $country = new Country();
        $data  = array('Code' => 'some code',
                       'Name'     => 'Some Name',
                       'Continent'  => 'some continent');

        $country->exchangeArray($data);

        $this->assertSame($data['Code'], $country->code, '"code" was not set correctly');
        $this->assertSame($data['Name'], $country->name, '"name" was not set correctly');
        $this->assertSame($data['Continent'], $country->continent, '"continent" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $country = new Country();

        $country->exchangeArray(array('code' => 'some code',
                                      'name'     => 'some name',
                                      'continent'  => 'some continent'));
        $country->exchangeArray(array());

        $this->assertNull($country->code, '"code" should have defaulted to null');
        $this->assertNull($country->name, '"name" should have defaulted to null');
        $this->assertNull($country->continent, '"continent" should have defaulted to null');
    }
}

?>