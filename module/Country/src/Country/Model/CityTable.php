<?php

namespace Country\Model;

use Zend\Db\TableGateway\TableGateway;

class CityTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()  {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getCitiesInCountryCode($code) {
        
        $code = (string) $code;
        $rowset = $this->tableGateway->select(array('CountryCode' => $code));
        return $rowset;
    }

    public function getCity($id)
    {
        $id  = (int) $id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
       
        if (!$row) {
            return null;
            throw new \Exception("Could not find city with id $id");
        }
        return $row;
    }

    public function saveCity(City $city)
    {
        
        $cityData = $city->getInputFilter(); 
        
        $data = array(
            'id' => $cityData->get('id')->getValue(),
            'name' => $cityData->get('name')->getValue(),
            'countryCode'  => $cityData->get('countryCode')->getValue(),
            'population' => $cityData->get('population')->getValue(),
            'district' => $cityData->get('district')->getValue()
        );
        

        $id = (int) $cityData->get('id')->getValue();
        ;
        if ($id === 0) {
            $this->tableGateway->insert($data);
        } else {
            
            if ($this->getCity($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('City does not exist');
            }
        }
    }

    public function deleteCity(City $city)  {
        $this->tableGateway->delete(array('id' => $city->id));
    }
}

?>