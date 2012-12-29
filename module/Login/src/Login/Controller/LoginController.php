<?php

namespace Login\Controller;

use Login\Form\LoginForm;
use Login\Controller\LoginAuthAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

use Zend\Session\Storage\SessionStorage as SessionStorage;
use Zend\Session\Container;

class LoginController extends AbstractActionController
{
    private $adapter;
    
    public function getForm()
    {
        return new LoginForm(array(
            'action' => '/login/process',
            'method' => 'post',
        ));
    }

    public function getAuthAdapter(array $params)   {
        
        if(!isset($this->adapter)) {
           $adapter = new LoginAuthAdapter($params['username'], $params['password']);    
        }
        
        $this->adapter = $adapter;
        
        return $this->adapter;
    }
 
    
    public function indexAction() {
        $form = $this->getForm();
        
         $session = new Container('FredLoyaAdmin');  
        
         if($session->offsetExists('systemMessage')) {
             $message = $session->offsetGet('systemMessage'); 
             $session->offsetUnset('systemMessage');
        } else {
         $message = '';
        }
        
        return array('form' => $form, 'displayMessage' => $message );
    }
    
    public function invalidAction() {
        $form = $this->getForm();
        $form->setAuthenticationMessage('Invalid Login Credentials');
        return array('form' => $form);
    }
    
    public function processAction()
    {
        $request = $this->getRequest();

        // Check if we have a POST request
        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        // Get our form and validate it
        $form = new LoginForm();
        
        if($request->isPost()) {
            $form->setData($request->getPost());
    
            if (!$form->isValid()) {
               // Invalid entries
                $this->view->form = $form;
                return $this->redirect()->toRoute('login'); // re-render the login form
           }
        }
      
        

        // Get our authentication adapter and check credentials
        $adapter = $this->getAuthAdapter($form->getData());
        
        $auth    = new AuthenticationService();
        $storage = new Session();
        
        $auth->setStorage($storage);  
        $auth->setAdapter($adapter);
        
        $result  = $auth->authenticate($adapter);
   
 
        if (!$result->isValid()) {
            // Invalid credentials
            $this->view->form = $form;
            $form->setAuthenticationMessage('Invalid credentials provided');
 
            return $this->redirect()->toRoute('login', array('controller' => 'login', 'action' => 'invalid'));
        } else {
            
            $session = new Container('FredLoyaAdmin');
            
            
                $session->offsetSet('userIsLoggedIn', true);
                $session->offsetSet('authAdapter', $adapter);    
            //}
           
            // We're authenticated! Redirect to the home page            
            return $this->redirect()->toRoute('country');    
        }


        
       
    }

    public function logoutAction()  {
            
        $session = new Container('FredLoyaAdmin');    
        
        
        $session->offsetUnset('userIsLoggedIn');
        $session->offsetUnset('authAdapter');
        
         return $this->redirect()->toRoute('login');
    }
    
}


?>