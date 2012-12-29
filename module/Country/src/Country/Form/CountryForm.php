<?php
namespace Country\Form;


use Country\Form\Element\ContinentSelect;
use Country\Form\Element\CapitalSelect;
use Zend\Form\Form;


class CountryForm extends Form {
    
    public $code; 
    public $cityTable;
    public $countryTable;  //Country Table to be able to query continents;
    
    
    public function __construct($name = null, $code = null, $countryTable = null, $cityTable = null) {
        
        parent::__construct('country');
        $this->setAttribute('method', 'post');
        
        
        $code != null ? 
        
                $this->add(array(
                                'name' => 'code',
                                'attributes' => array('type' => 'hidden') 
                           ))
           :
                $this->add(array('name' => 'code',
                                 'attributes' => array('type' => 'text', 'id' => 'countryCode'),
                                 'options' => array('label' => 'Country Code: '),
                                 ));
                   
        $this->add(array(
                        'name' => 'name',
                        'attributes' => array('type' => 'text','id' => 'countryName'),
                        'options'    => array('label' => 'Country Name: ')
                  ));           
         
                      
                   
        $this->add(new ContinentSelect($countryTable), array('name' => 'continent'));
                  
        $code != null ?          
                $this->add(new CapitalSelect($code, $cityTable), array('name' => 'capital'))
        :
                $this->add(array('name' => 'capital',
                                 'attributes' => array('type' => 'hidden', 'value' => 'null') 
                                ));          
                  
        $this->add(array(
                        'name' => 'population',
                        'attributes' => array('type' => 'text', 'id' => 'population'),
                        'options'    => array('label' => 'Population: ')
                  ));
        
        $this->add(array(
                        'name' => 'submit',
                        'attributes' => array('type'  => 'submit',
                                              'value' => 'Save Changes',
                                              'id'    => 'saveButton'),
                  ));
        
    }
    
}
