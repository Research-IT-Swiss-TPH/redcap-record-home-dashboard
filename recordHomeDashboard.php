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
        //  Check if is Record Home Page for record with id
        if( $this->isPage('DataEntry/record_home.php') && isset($_GET['id'])) {
            $this->renderDashboard();            
        }

    }

   /**
    * Renders the module
    *
    * @since 1.0.0
    */
    private function renderDashboard() {
        ?>
        <!-- Insert Dashboard Render Wrapper so Vue.js can mount it -->
        <div id="STPH_DASHBOARD_WRAPPER" style="display: none;">
            <div id="STPH_DASHBOARD_RENDER"></div>            
        </div>
        <link rel="stylesheet" href="<?= $this->getUrl('dist/style.css')?>">
        <script>
            $(function() {
                $(document).ready(function(){
                    $('#event_grid_table').after('<div id="record_home_dashboard_title">Record Home Dashboard</div>');
                    //  We have to move our wrapper element to the correct position and then show it
                    let wrapper = $('#STPH_DASHBOARD_WRAPPER');
                    $('#record_home_dashboard_title').after(wrapper)
                    wrapper.show()
                })
            });
        </script>        
        <script>
            const stph_rhd_getBaseUrlFromBackend = function() {
                    return '<?= $this->getBaseUrl() ?>'
            }            
        </script>        
        <script src="<?= $this->getUrl("./dist/appRender.js") ?>"></script>

        <?php
    }   
    
    /** 
     * Public Methods accessible via Ajax
     */
    public function getBaseUrl(){
        return $this->getUrl("requestHandler.php");
    }

    public function getDashboardData() {
        $data = $this->getProjectSetting("dashboard-data");
        $this->sendResponse($data);
    }
    
    public function saveDashboardData($new) {
        $this->setProjectSetting("dashboard-data", $new);
        $this->getDashboardData();
    }

    /**
     * Helpers
     */

    private function sendResponse($response) {
        header('Content-Type: application/json; charset=UTF-8');        
        echo json_encode($response);
        exit();
    }
    
}