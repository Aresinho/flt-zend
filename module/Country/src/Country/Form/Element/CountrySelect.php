<?php

namespace Country\Form\Element;

use Country\Model\CountryTable;
use Zend\Form\Element\Select;


class CountrySelect extends \Zend\Form\Element\Select {
        
    protected $countryTable;
        
    public function __construct($countryTable) {
            
            
        $this->countryTable = $countryTable;
      
        $this->setAttribute('id','countrySelect');
        $this->setOptions(array('label' => 'Country: '));
        $this->setEmptyOption('Please Select . . .', 'Please select...');
        $options = array();
        
        foreach ($this->countryTable->fetchAll() as $country) {
           $options[$country->code] = $country->name;
        }
        
        $this->setValueOptions($options);
        
    }
}

?>