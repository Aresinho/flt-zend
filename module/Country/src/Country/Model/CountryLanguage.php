<?php
 
namespace Country\Model;

class CountryLanguage
{
    public $countryCode;
    public $language;
    public $isOfficial;
    public $percentage;
    
    public function exchangeArray($data)
    {
             
        $this->countryCode   = (isset($data['CountryCode'])) ? $data['CountryCode'] : null;
        $this->language     = (isset($data['Language'])) ? $data['Language'] : null;
        $this->isOfficial   = (isset($data['IsOfficial'])) ? (($data['IsOfficial'] === 'T') ? true : false) : null;
        $this->percentage   = (isset($data['Percentage'])) ? $data['Percentage'] : null;
    
    }
    
    
}

?>