<?php

namespace Country\Model;

use Zend\Db\TableGateway\TableGateway;

class CountryTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()  {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getCountry($code)   {
        $code  = (String) $code;
        $rowset = $this->tableGateway->select(array('code' => $code));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find country with code $code");
        }
        return $row;
    }

    public function saveCountry(Country $country)   {
            
        $countryData = $country->getInputFilter();    
            
        $data = array(
            'name'       => $countryData->get('name')->getValue(),
            'continent'  => $countryData->get('continent')->getValue(),
            'code'       => $countryData->get('code')->getValue(),
            'capital'    => $countryData->get('capital')->getValue(),
            'population' => $countryData->get('population')->getValue()
        );

       
        $code = (String) $countryData->get('code')->getValue();
        
      
        if ($data['capital'] === 'null') { //There is no capital defined yet because it is a new country. Otherwise it should have failed validation
            $this->tableGateway->insert($data);
        } else {
            if(strlen($code) != 3) {
                throw new \Exception('Country Code is Invalid.');
            }
                    
            if ($this->getCountry($code)) {
                $this->tableGateway->update($data, array('code' => $code));
            } else {
                throw new \Exception('Country code does not exist');
            }
        }
    }

    public function deleteCountry($code)  {
        $this->tableGateway->delete(array('code' => $code));
    }
}

?>