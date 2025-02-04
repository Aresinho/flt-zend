<?php

namespace Login\Form;
     
use Zend\Form\Form;     
     
     
class LoginForm extends Form {
        
    public $message;    
        
    public function __construct($name = null)
    {
        
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Username: ',
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password: ',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Login',
                'id' => 'submitbutton',
            ),
        ));
    }
    
    public function setAuthenticationMessage($message) {
        $this->message = $message;
    }
    
    public function getAuthenticationMessage() {
        return $this->message;
    }
    
}


?>