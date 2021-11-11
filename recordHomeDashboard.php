<?php

// Set the namespace defined in your config file
namespace STPH\recordHomeDashboard;

use Exception;
use \Piping;

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

        $label = '[current-instance]';

        $test = Piping::pipeSpecialTags($label, $project_id, 1, $event_id=null, $instance=1, null, false, 
        $participant_id=null, $form=null, $replaceWithUnderlineIfMissing=false);


        $response= Piping::replaceVariablesInLabel($label, 1, $event_id=null, $instance=2, array(),
        $replaceWithUnderlineIfMissing=false, $project_id, $wrapValueInSpan=false, 
        $repeat_instrument="", $recursiveCount=1, $simulation=false, $applyDeIdExportRights=false,
        $form=null, $participant_id=null, $returnDatesAsYMD=false, $ignoreIdentifiers=false);
        //dump($test);

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

            const stph_rhd_getBaseParametersFromBackend = function() {
                return {
                    project_id: '<?= $this->getProjectId() ?>',
                    record: '<?= $_GET['id'] ?>',
                    event: '<?= $this->getEventId() ?>'
                }
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

    public function getElementData($params) {

        $data = \REDCap::getData($params->project_id, 'array', $params->record);
        $this->sendResponse($data);
    }

    public function renderElementContent($type, $content, $params) {
        
        $project_id = $params->project_id;
        $record = $params->record;
        $event_id = $params->event;

        if( $type == "link") {
            
            $label = $content->url;
            
            $response= Piping::replaceVariablesInLabel($label, $record, $event_id, 1, array(), true, $project_id, false);

            if (filter_var($response, FILTER_VALIDATE_URL) === FALSE) {                
                $this->sendError();
            }

        }


        //  Send JSON Response
        $this->sendResponse($response);

    }

    /**
     * Helpers
     */

    private function sendResponse($response) {
        header('Content-Type: application/json; charset=UTF-8');        
        echo json_encode($response);
        exit();
    }

    private function sendError() {
        header('Content-Type: application/json; charset=UTF-8');
        header("HTTP/1.1 400 Bad Request");
        die();
    }
    
}