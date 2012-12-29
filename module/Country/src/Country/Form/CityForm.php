<?php
namespace Country\Form;


use Country\Form\Element\CountrySelect;
use Zend\Form\Form;


class CityForm extends Form {
    
    public $code; 
    public $cityTable;
    public $countryTable;  //Country Table to be able to query continents;
    
    
    public function __construct($name = null, $cityId = null, $countryTable = null) {
        
        parent::__construct('city');
        $this->setAttribute('method', 'post');
        
          
        $this->add(array(
                         'name' => 'id',
                         'attributes' => array('type' => 'hidden', 'value' => $cityId) 
                       ));
                           
         
                   
        $this->add(array(
                        'name' => 'name',
                        'attributes' => array('type' => 'text', 'id' => 'cityName'),
                        'options'    => array('label' => 'City Name: ')
                  ));           
         
                      
                   
        $this->add(new CountrySelect($countryTable), array('name' => 'countryCode'));
                  
        
         $this->add(array(
                        'name' => 'district',
                        'attributes' => array('type' => 'text', 'id' => 'district'),
                        'options'    => array('label' => 'District: ')
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