<?php

// Set the namespace defined in your config file
namespace STPH\recordHomeDashboard;


if( file_exists("vendor/autoload.php") ){
    require 'vendor/autoload.php';
}

// Declare your module class, which must extend AbstractExternalModule 
class recordHomeDashboard extends \ExternalModules\AbstractExternalModule {

    private $moduleName = "Record Home Dashboard";  

   /**
    * Constructs the class
    *
    */
    public function __construct()
    {        
        parent::__construct();
       // Other code to run when object is instantiated
    }

   /**
    * Hooks Record Home Dashboard module to redcap_every_page_top
    *
    */
    public function redcap_every_page_top($project_id = null) {
        $this->renderModule();
    }

   /**
    * Renders the module
    *
    * @since 1.0.0
    */
    private function renderModule() {
        
        

        print '<p class="record-home-dashboard">'.$this->helloFrom_recordHomeDashboard().'<p>';

    }

    /**
    * Returns a test string including module name.
    *
    * @since 1.0.0
    */    
    public function helloFrom_recordHomeDashboard() {

        
        return $this->tt("hello_from").' '.$this->moduleName;
        

    }

    

    
}