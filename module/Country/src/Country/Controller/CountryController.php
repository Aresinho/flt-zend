<?php


namespace Country\Controller;

use Country\Form\CountryForm;
use Country\Form\CityForm;
use Country\Model\Country;
use Country\Model\City;
use Login\Controller\LoginAuthAdapter;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class CountryController extends AbstractActionController    {
    
    protected $countryTable;
    protected $cityTable;
    protected $countryLanguageTable;
    private $session;
    private $auth = null;
   
    /*
     * We are relying onDispatch to check the user has access to the requested action
     */
    public function onDispatch( \Zend\Mvc\MvcEvent $e ) {
       $this->checkUserPermissions(); 
       return parent::onDispatch( $e );
    }
    
    public function indexAction()   {
                
        return new ViewModel(array(
            'countries' => $this->getCountryTable()->fetchAll(),
            'userIsLoggedIn' => $this->session->offsetExists('userIsLoggedIn')
        ));   
    }

    public function addAction() {
     
        $form = new CountryForm('country', null, $this->getCountryTable(), $this->getCityTable());
        $form->get('submit')->setValue('Add Country');
        
        $request = $this->getRequest();
       
       
        if($request->isPost()) {
      
            $country = new Country();
            $form->setInputFilter($country->getInputFilter());
            $form->setData($request->getPost());
               
            if($form->isValid()) {
                
                $country->exchangeArray($form->getData());
                $this->getCountryTable()->saveCountry($country);
    
                 return $this->redirect()->toRoute('country');
            }
        }
       
        return array(
            'code' => null, 
            'form' => $form,
        );
         
    }

    public function editAction() {
        
        
        $code = (string) $this->params()->fromRoute('code', 0);
        if (!$code) {
            return $this->redirect()->toRoute('country', array(
                'action' => 'add'
            ));
        }
        
        $country = $this->getCountryTable()->getCountry($code);

        $form  = new CountryForm('country', $code, $this->getCountryTable(), $this->getCityTable()); 
        $form->bind($country);
        
       
        $request = $this->getRequest();
        if ($request->isPost()) {
            
           
            $form->setInputFilter($country->getInputFilter());
            $form->setData($request->getPost());

           
            if ($form->isValid()) {
                $this->getCountryTable()->saveCountry($form->getData());
                return $this->redirect()->toRoute('country');
            }
        }

        return array(
            'code' => $code,
            'form' => $form,
        );   
    }
   
    public function deleteAction()  {
        
        
        $code = (String) $this->params()->fromRoute('code', 0);
        if (!$code) {
            return $this->redirect()->toRoute('country');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');


            if ($del === 'Yes') { //Request confirmed
                $code = (String) $request->getPost('code');
                
                // We do not want orphan cities so we are going to delete all cities on the country that is about to be deleted.
                $cities = $this->getCityTable()->getCitiesInCountryCode($code);
                
                foreach($cities as $city) {
                    $this->getCityTable()->deleteCity($city);
                }
                
                $this->getCountryTable()->deleteCountry($code);
            }

            // Redirect to list of countries
            return $this->redirect()->toRoute('country');
        }

        return array(
            'code'    => $code,
            'country' => $this->getCountryTable()->getCountry($code)
        );
        
    }
    
    public function detailsAction() {
             
         $code = (string) $this->params()->fromRoute('code', 0);    
         $country = $this->getCountryTable()->getCountry($code);

        if(!is_object($capital = $this->getCityTable()->getCity($country->capital))){
            $capital = new Country();
        }
        
         return new ViewModel(array(
            'country'   => $country,
            'cities'    => $this->getCityTable()->getCitiesInCountryCode($code),
            'capital'   => $capital,
            'languages' => $this->getCountryLanguageTable()->getLanguagesSpokenInCountryCode($country->code)
        ));
    }
    
    /*
     *  City Actions
     */
     
     public function addCityAction() {
            
        $form = new CityForm('city', null, $this->getCountryTable());
        $form->get('submit')->setValue('Add City');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $city = new City();
            
            $form->setInputFilter($city->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                    
                $city->exchangeArray($form->getData());
                $countryCode = $city->getInputFilter()->get('countryCode')->getValue();
                $this->getCityTable()->saveCity($city);
                
                return $this->redirect()->toRoute('country', array('controller' => 'country', 'action' => 'details', 'code' => $countryCode));
            }
        }
        
        return array('form' => $form);
    }
    
     public function editCityAction() {
            
        $cityId = (int) $this->params()->fromRoute('code', 0);    
        $city = $this->getCityTable()->getCity($cityId);
        
        $countryCode = $city->countryCode; //For redirection
        
        $form = new CityForm('city', $cityId, $this->getCountryTable());
        $form->bind($city);
        
        
         $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setInputFilter($city->getInputFilter());
            $form->setData($request->getPost());
           
            if ($form->isValid()) {
                $this->getCityTable()->saveCity($form->getData());                
                return $this->redirect()->toRoute('country', array('controller' => 'country', 'action' => 'details', 'code' => $countryCode));
            }
        }
        
        return array('code' => $cityId,
                     'form' => $form);
    }
    
    public function deleteCityAction()  {
        
        $id = (int) $this->params()->fromRoute('code', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('country');
        }

        
        $city = $this->getCityTable()->getCity($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

         
            if ($del === 'Yes') {
                $code = (String) $request->getPost('code');
                $this->getCityTable()->deleteCity($city);
            }

            // Redirect to list of countries
            return $this->redirect()->toRoute('country');
        }

        
        return array(
            'code'    => $id,
            'country' => $this->getCountryTable()->getCountry($city->countryCode),
            'city' => $city
        );
        
    }
    
    
    /*
     * Get Table Functions 
     */
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Country\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function getCityTable()
    {
        if (!$this->cityTable) {
            $sm = $this->getServiceLocator();
            $this->cityTable = $sm->get('Country\Model\CityTable');
        }
        return $this->cityTable;
    }
    
    public function getCountryLanguageTable()
    {
        if (!$this->countryLanguageTable) {
            $sm = $this->getServiceLocator();
            $this->countryLanguageTable = $sm->get('Country\Model\CountryLanguageTable');
        }
        return $this->countryLanguageTable;
    }
    
  
  
    /** Helper Functions **/  
    
    /**
     * ACL implementation based on Authentication
     */
    private function checkUserPermissions() {
            
            
        $this->session = new Container('FredLoyaAdmin');
        $action = $this->getEvent()->getRouteMatch()->getParam('action', 'index');
        
        
        //if(!$this->session->offsetExists('userIsLoggedIn')) {
          //  return $this->redirect()->toRoute('login');
        //}
    
        if(!isset($this->auth)) {
            $this->auth = new AuthenticationService();
            
            if($this->session->offsetExists('authAdapter')) {
                $this->auth->setAdapter($this->session->offsetGet('authAdapter'));    
            } else {
                $this->auth->setAdapter(new \Login\Controller\LoginAuthAdapter());
                $this->auth->authenticate();
            }
        }
        
     
        
            $acl = new Acl();

            $acl->addRole(new Role('guest'))
                ->addRole(new Role('Admin'));

            $resources = array('index', 'details', 'add', 'edit', 'delete', 'add-city', 'edit-city', 'delete-city');
            $guestPermissions = array('index', 'details');
            $adminPermissions = $resources; //Admins get all resources

            
            /*
             * Set Permissions for each role based on the guestPermissions and 
             */
            foreach($resources as $resource) {
                
               $acl->addResource(new Resource($resource)); 
                
               in_array($resource, $guestPermissions) ? $acl->allow('guest', $resource) : $acl->deny('guest', $resource);
               in_array($resource, $adminPermissions) ? $acl->allow('Admin', $resource) : $acl->deny('Admin', $resource);
                
            }
          
        $identity = $this->auth->getIdentity();
          
        if($identity === null) $identity = 'guest';  
          
        if(!$acl->isAllowed($identity, $action)) {
            
              $this->session->offsetSet('systemMessage', 'You need to login first');
              return $this->redirect()->toRoute('login'); 
        }
    }

}