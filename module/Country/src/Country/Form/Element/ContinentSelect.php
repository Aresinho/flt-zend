<?php

namespace Country\Form\Element;

use Country\Model\CountryTable;

use Zend\Form\Element\Select;


class ContinentSelect extends \Zend\Form\Element\Select {
        
    protected $countryTable;
        
    public function __construct($countryTable) {
            
            
        $this->countryTable = $countryTable;
      
        $this->setAttribute('id','continentSelect');
        $this->setOptions(array('label' => 'Continent: '));
        $this->setEmptyOption('Please Select . . .', 'Please select...');
        $options = array();
        
        //This is far from optimal but I wasn't able to find a way to do a `GROUP BY` predicate with a tablegateway 
        foreach ($this->countryTable->fetchAll() as $country) {
           $options[$country->continent] = $country->continent;
        }
        
        $this->setValueOptions($options);
        
    }
}

?>