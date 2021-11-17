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

        $fields = ["contact_date", "contact_later", "foo"];
        $record = 1;
        $instances = $this->getInstancesData($project_id, $record, "contact_activity", null);
        dump($instances);

    }

    private function getInstancesData( $project_id , $record, $instrument, $fields=null) {

            //  Proj needed for reducing array
            //  taken form Records::getData with $exportAsLabel
            global $Proj;
            $instrument_menu_name = $Proj->forms[$instrument]["menu"];

            $field_names_array = [];
            //  Get a list of all fields for a form if not specified
            if($fields == null) {
                $fields = $this->getFieldNames($instrument);
            } 
            //  prepare for fields query
            foreach ($fields as $key => $field) {
                $field_names_array[] = '"'.$field . '"';
             }
             $field_names = implode(",", $field_names_array);

            //  Gets all element validation types (mainly to support text field formatting)
            $sql = 'SELECT element_validation_type, field_name FROM redcap_metadata WHERE project_id = ? AND field_name IN('.$field_names.') AND element_validation_type IS NOT NULL';
            $result = $this->query($sql, [$project_id]);

            //  Prepare of fields to formatted
            $array_to_format = [];
            while($row = $result->fetch_object()) {               
                if($row->element_validation_type) {
                    $array_to_format[$row->field_name] = $row->element_validation_type;
                }
            }         

            //  Fetch all data as json so that labels are exported..
            $params = array(
                "project_id" => $project_id,
                "records" =>$record,
                "exportAsLabels" => true,
                "exportDataAccessGroups" => true,
                "return_format" => "json",
                "fields" => $fields
            );

            $data = json_decode(\Redcap::getData($params), true);

            if (!class_exists("Formatter")) include_once("classes/Formatter.php");
            $formatter = new Formatter;

            //  Reset array and handle field formatting
            foreach ($data as $key => $instance) {
                //  Adjust formatting (dates)
                foreach ($array_to_format as $field_to_format => $valtype) {                    
                    $instance[$field_to_format] = $formatter::renderDateFormat($instance[$field_to_format], $valtype);
                }

                $instances[] = $instance;
            }

            return $instances;

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

        if ($type == "list") {

            $response = [];
            foreach ($content as $key => $el) {
                $response[] = Piping::replaceVariablesInLabel($el->value, $record, $event_id, 1, array(), true, $project_id, false);
            }

        }

        if($type == "table") {


            $response = $this->getInstancesData($project_id, $record, "contact_activity", $content->columns);

        }


        //  Send JSON Response
        $this->sendResponse($response);

    }

    public function getFieldForInstrument($instrument) {

        $response =  $this->getFieldNames($instrument);
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