<?php

namespace Country\Model;

use Zend\Db\TableGateway\TableGateway;

class CountryLanguageTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function getLanguagesSpokenInCountryCode($code) {
        
        $code = (string) $code;
        $rowset = $this->tableGateway->select(array('CountryCode' => $code));
        return $rowset;
    }

    public function getCountryLanguageRow($countryCode, $language)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find city with id $id");
        }
        return $row;
    }

    public function saveCountryLanguage(CountryLanguage $countryLanguage)
    {
        $data = array(
            'name' => $city->name,
            'countryCode'  => $city->countryCode,
            'population' => $city->population,
            'district' => $city->district
        );

        $id = (int) $city->id;
        if ($id == null) {
            $this->tableGateway->insert($data);
        } else {
            
            if ($this->getCountry($code)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('City does not exist');
            }
        }
    }

    public function deleteCountryCode($countryCode, $language)  {
        $this->tableGateway->delete(array('CountryCode' => $countryCode, 'Language' => $language));
    }
}

?>