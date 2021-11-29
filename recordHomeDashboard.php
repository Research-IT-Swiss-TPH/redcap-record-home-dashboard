<?php

namespace STPH\recordHomeDashboard;


/**
 *  REDCap External Module: Record Home Dashboard
 *  A REDCap External Module that enables to add a customized Dashboard to the Record Homepage.
 * 
 *  @author Ekin Tertemiz, Swiss Tropical and Public Health Institute
 * 
 */

//  used for development
if( file_exists("vendor/autoload.php") ){
    require 'vendor/autoload.php';
}

//  used for list rendering
use \Piping;
use \REDCap;

class recordHomeDashboard extends \ExternalModules\AbstractExternalModule {

   /**
    * 
    * Renders Dashboard on Record Home Page
    * @param string $project_id
    * @return void 
    * @since 1.0.0
    *
    */
    public function redcap_every_page_top($project_id = null) {
        if( $this->isPage('DataEntry/record_home.php') && isset( $_GET['id']) ) {
            $this->renderDashboard();            
        }
    }

   
   /**
    * 
    * Renders the dashboard by including target Wrapper and Javascripts
    * 
    * @return void
    * @since 1.0.0
    *
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
                    $('#center').addClass("<?= $this->displayClasses() ?>")
                    $('#event_grid_table').after('<div id="record_home_dashboard_title"><?= $this->getProjectSetting('dashboard-title') == "" ? "Record Home Dashboard" : $this->getProjectSetting('dashboard-title') ?></div>');
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
                    record: '<?= htmlentities($_GET['id'], ENT_QUOTES) ?>',
                    event: '<?= $this->getEventId() ?>'
                }
            }

        </script>        
        <script src="<?= $this->getUrl("./dist/appRender.js") ?>"></script>
    <?php
    }

   /**  
    * 
    * Gets Display options for current user (xor impersonifed user)
    *   
    * @return mixed
    * @since 1.0.0
    *
    */  
    private function getDisplayOptions() {        
        $current_user = "";
        $userRights = [];
        $userRole = "";
        $displayOptions = [];
        $current_user = ($this->getUser())->username;
        # Check if Impersonification is active (trumps super-user)
        if(\UserRights::isImpersonatingUser()){
            $current_user = $_SESSION['impersonate_user'][PROJECT_ID]['impersonating'];
        }

        $userRights = REDCap::getUserRights($current_user);
        if($userRights) {
            $userRole = $userRights[$current_user]["role_id"];
        }

        if($userRole) {
            $allDisplayOptions = $this->getSubSettings("display-option");
            $displayOptions = reset(array_filter( $allDisplayOptions, function($e) use ($userRole) {
                    return $e["user-role"] == $userRole;
                })
            );
        }

        if($displayOptions) {
            return $displayOptions;
        }

        return null;
    }        
    
   /**
    * 
    * Gets all instances needed for table data and ensures dates are formatted correctly
    * 
    * @param string $project_id
    * @param string $record
    * @param string $instrument
    * @param array $fields
    * @return void 
    * @since 1.0.0
    *
    */    
    private function getInstancesData( $project_id , $record, $instrument, $fields=null) {

        $field_names_array = [];
        $params = [];
        $instances = [];

        //  Get a list of all fields for a form if not specified and limit to 10 
        if($fields == null) {
            $fields = array_slice($this->getFieldNames($instrument), 0, 10);
        } 
        //  Prepare for fields query
        foreach ($fields as $key => $field) {
            $field_names_array[] = '"'.$field . '"';
            }
            $field_names = implode(",", $field_names_array);

        //  Gets all element validation types (mainly to support text field formatting)
        $sql = 'SELECT element_validation_type, field_name FROM redcap_metadata WHERE project_id = ? AND field_name IN('.$field_names.') AND element_validation_type IS NOT NULL';
        $result = $this->query($sql, [$project_id]);

        //  Preparation of fields to be formatted
        $array_to_format = [];
        while($row = $result->fetch_object()) {               
            if($row->element_validation_type) {
                $array_to_format[$row->field_name] = $row->element_validation_type;
            }
        }         

        //  Fetch all data as JSON so that labels (piped values) are exported
        $params = array(
            "project_id" => $project_id,
            "records" =>$record,
            "exportAsLabels" => true,
            "exportDataAccessGroups" => true,
            "return_format" => "json",
            "fields" => $fields
        );
        $data = json_decode(REDCap::getData($params), true);

        //  Use Formatter Class to render date formats correctly
        if (!class_exists("Formatter")) include_once("classes/Formatter.php");
        $formatter = new Formatter;

        //  Reset final array and handle field formatting
        foreach ($data as $key => $instance) {
            //  Adjust formatting (dates)
            foreach ($array_to_format as $field_to_format => $valtype) {                    
                $instance[$field_to_format] = $formatter::renderDateFormat($instance[$field_to_format], $valtype);
            }
            $instances[] = $instance;
        }

        return $instances;
    }
    

   //  ====    A P I      ====
   //  Called with Axios via RequestHandler

   /**  
    * 
    * Gets Dashboard Data from database
    *
    * @return void
    * @since 1.0.0
    *
    */
    public function getDashboardData() {
        $data = $this->getProjectSetting("dashboard-data");
        $this->sendResponse($data);
    }
    
   /**  
    * 
    * Saves Dashboard Data into database
    *
    * @return void
    * @since 1.0.0
    *
    */    
    public function saveDashboardData($new) {
        $this->setProjectSetting("dashboard-data", $new);
        $this->getDashboardData();
    }

   /**  
    * 
    * Renders Element Content for each type
    *   
    * @param string $type
    * @param object $content
    * @param object $params
    * @return void
    * @since 1.0.0
    *
    */        
    public function renderElementContent($type, $content, $params) {
        
        $project_id = $params->project_id;
        $record = $params->record;
        $event_id = $params->event;

        switch ($type) {
            
            case 'link':
                $response= Piping::replaceVariablesInLabel($content->url, $record, $event_id, 1, array(), true, $project_id, false);
                if (filter_var($response, FILTER_VALIDATE_URL) === FALSE) {                
                    $this->sendError();
                }                
                break;
            
            case 'list';
            $response = [];
            foreach ($content as $key => $el) {
                $response[] = Piping::replaceVariablesInLabel($el->value, $record, $event_id, 1, array(), true, $project_id, false);
            }            
            break;

            case 'table':
            $response = $this->getInstancesData($project_id, $record, $content->instrument, $content->columns);                
            break;

            default:
            $this->sendError();
            break;
        }

        $this->sendResponse($response);
    }

   /**  
    * 
    * Gets field names for an instrument (needed for prefilling list modal)
    *   
    * @param string $instrument
    * @return void
    * @since 1.0.0
    *
    */  
    public function getFieldForInstrument($instrument) {
        $response =  $this->getFieldNames($instrument);
        $this->sendResponse($response);
    }


    //  ====   H E L P E R S      ====

   /**  
    * 
    * Gets Base URL to Request Handler
    *
    * @return string
    * @since 1.0.0
    *
    */
    public function getBaseUrl(){
        return $this->getUrl("requestHandler.php");
    }    

   /**  
    * 
    * Checks if Editor is disabled for current user role
    *   
    * @return bool
    * @since 1.0.0
    *
    */  
    public function isDisabledEditor() {

        $displayOptions = [];
        $displayOptions = $this->getDisplayOptions();
        if($displayOptions == null) {
            return false;
        }
        return $displayOptions["disable-editor"];
    }

   /**  
    * 
    *  Returns classes referencing to display options per user role
    *   
    * @return string
    * @since 1.0.0
    *
    */      
    public function displayClasses() {
        $displayOptions = [];
        $displayOptions = $this->getDisplayOptions();

        $classes = "";

        if($displayOptions["hide-top-render"]) {
            $classes .= " hide-data_table";
        }

        if($displayOptions["hide-bottom-render"]) {
            $classes .= " hide-repeating_forms_table";
        }
        return $classes;
    }    

   /**  
    * 
    * Echoes sucessful JSON response
    *   
    * @return void
    * @since 1.0.0
    *
    */      
    private function sendResponse($response) {
        header('Content-Type: application/json; charset=UTF-8');        
        echo json_encode($response);
        exit();
    }

   /**  
    * 
    * Echoes error JSON response
    *   
    * @return void
    * @since 1.0.0
    *
    */      
    private function sendError() {
        header('Content-Type: application/json; charset=UTF-8');
        header("HTTP/1.1 400 Bad Request");
        die();
    }    
}