<?php
/*
 * This class should be on its on file. 
 */
 
namespace Login\Controller; 
 
use Zend\Authentication\Adapter\AdapterInterface; 
use Zend\Authentication\Result;
 
class LoginAuthAdapter implements AdapterInterface
{
    private $username;
    private $password;
    
    
    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username = null, $password = null)
    {
        // ...
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        
        if($this->username === 'admin' && $this->password === 'admin') {
            $resultCode = Result::SUCCESS;
            $identity = 'Admin';
        } else {
            $resultCode = Result::FAILURE_CREDENTIAL_INVALID;
            $identity = 'guest';
        }
        
        return new \Zend\Authentication\Result($resultCode, $identity);
    }
}