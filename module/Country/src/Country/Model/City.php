<?php
 
namespace Country\Model;

use Zend\InputFilter\Factory as InputFactory;    
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterAwareInterface;   
use Zend\InputFilter\InputFilterInterface;

class City implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $countryCode;
    public $population;
    public $district;
    
    protected $inputFilter; 

    public function exchangeArray($data)
    {
        $this->id         = (isset($data['ID'])) ? $data['ID'] : null;
        $this->name         = (isset($data['Name'])) ? $data['Name'] : null;
        $this->countryCode  = (isset($data['CountryCode'])) ? $data['CountryCode'] : null;
        $this->population   = (isset($data['Population'])) ? $data['Population'] : null;
        $this->district     = (isset($data['District'])) ? $data['District'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)   {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
       
        
        if($this->id != null) { //Editing City
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
               )
            ));
        }
            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'countryCode',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 3,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'population',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
               )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

?>