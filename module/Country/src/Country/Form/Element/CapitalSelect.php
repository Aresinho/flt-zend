<?php

namespace Country\Form\Element;

use Country\Model\CountryTable;

use Zend\Form\Element\Select;


class CapitalSelect extends \Zend\Form\Element\Select {
        
    protected $cityTable;
    protected $code;
        
    public function __construct($code, $cityTable) {
            
        $this->code = $code;    
        $this->cityTable = $cityTable;
      
        
        $this->setOptions(array('label' => 'Capital: '));
        $this->setEmptyOption('Please Select . . .', 'Please select...');
        $options = array();
        
        //This is far from optimal but I wasn't able to find a way to do a `GROUP BY` predicate with a tablegateway 
        foreach ($this->cityTable->getCitiesInCountryCode($code) as $city) {
           $options[$city->id] = $city->name;
        }
        
        $this->setValueOptions($options);
        
    }
}

?>