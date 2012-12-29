<?php
 
namespace Country\Model;

use Zend\InputFilter\Factory as InputFactory;    
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterAwareInterface;   
use Zend\InputFilter\InputFilterInterface;


class Country implements InputFilterAwareInterface
{
    public $code;
    public $name;
    public $continent;
    public $capital;
    public $population;
    
    protected $inputFilter; 
    
    public function exchangeArray($data)    {
        $this->code       = (isset($data['Code'])) ? $data['Code'] : null;
        $this->name       = (isset($data['Name'])) ? $data['Name'] : null;
        $this->continent  = (isset($data['Continent'])) ? $data['Continent'] : null;
        $this->capital    = (isset($data['Capital'])) ? $data['Capital'] : null;
        $this->population = (isset($data['Population'])) ? $data['Population'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)   {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()    {
            
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();


        if($this->code != null) {
            $inputFilter->add($factory->createInput(array(
                'name'     => 'code',
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
                 )
            )));
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
                'name'     => 'continent',
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
                            'min'      => 5,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
?>