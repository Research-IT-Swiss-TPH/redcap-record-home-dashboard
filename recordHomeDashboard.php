<?php

// Set the namespace defined in your config file
namespace STPH\recordHomeDashboard;


if( file_exists("vendor/autoload.php") ){
    require 'vendor/autoload.php';
}

// Declare your module class, which must extend AbstractExternalModule 
class recordHomeDashboard extends \ExternalModules\AbstractExternalModule {

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
        
        //  check settings
    }   

    public function getBaseUrl(){
        return $this->getUrl("requestHandler.php");
    }


    private function sendResponse($response) {
        header('Content-Type: application/json; charset=UTF-8');        
        echo json_encode($response);
        exit();
    }


    public function getDashboardData() {
        $data = $this->getProjectSetting("dashboard-data");
        $this->sendResponse($data);
    }
    
    public function saveDashboardData($new) {
        $this->setProjectSetting("dashboard-data", $new);
        $this->getDashboardData();
    }
    
}