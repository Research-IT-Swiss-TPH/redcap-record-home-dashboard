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

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class recordHomeDashboard extends \ExternalModules\AbstractExternalModule {


    /** @var object */    
    protected $project_settings;
    protected $hasMultipleEvents;
    protected $hasMultipleArms;
    protected $events;

   /**
    *   Constructs the class
    *   @return void
    *   @since 1.0.0
    *
    */
    public function __construct() {        
        parent::__construct();
        global $Proj;

        $this->project_settings = $Proj;
        $this->hasMultipleEvents= $Proj->longitudinal;
        $this->hasMultipleArms = $Proj->multiple_arms;
        //$this->events = array_keys($Proj->eventsForms);
        $this->events = (is_array($Proj->eventsForms) ? array_keys($Proj->eventsForms) : null);
        $this->table_pk = $Proj->table_pk;
    }    

   /**
    * 
    * Renders Dashboard on Record Home Page
    * @param string $project_id
    * @return void 
    * @since 1.0.0
    *
    */
    public function redcap_every_page_top($project_id = null) {
        $this->dev();
        if( $this->isPage('DataEntry/record_home.php') && isset( $_GET['id']) && defined('USERID') ) {
            $this->renderDashboard();
        }
    }

    private function dev() {

        $params = array(
            "project_id" => 14,
            "records" => 4,
            "events" => [41],
            "exportAsLabels" => true,
            "exportDataAccessGroups" => true,
            "return_format" => "json",
            "fields" => [],
            "returnIncludeRecordEventArray"=> true
        );

        $data = json_decode(REDCap::getData($params), true);
        //dump($data);

        // Mocking Data
        $user_id = 'test_user_1';
        
        //  Context propagation & validation with JWT

        
        global $salt;

        $jwt = $this->generateJWT($salt, $user_id);
        $decoded = JWT::decode($jwt, new Key($salt, 'HS256'));

        //dump($jwt);
        //dump($decoded);

        //  Retrieve user rights for user
        
        $user_rights = REDCap::getUserRights($user_id);
        dump($user_rights);
        $no_access_forms = array_filter($user_rights[$user_id]["forms"], function($value){
            return $value == 0;
        });
        dump($no_access_forms);

        $no_access_fields = [];

        foreach ($no_access_forms as $formname => $form) {
            $no_access_fields =  array_merge($no_access_fields, $this->getFieldNames($formname));
        }
       
        dump($no_access_fields);


        $content = [
            (object)[
                "value" => "[foopublic]",
                "title" => "Public"
            ],
            (object)[
                "value" => "[fooprivate]",
                "title" => "Private"
            ]
        ];

        dump($content);

        $response = [];
        foreach ($content as $key => $el) {

            $field_name = trim($el->value, '[]');

            if(in_array( $field_name, $no_access_fields)) {
                $response[] = "No Access";
            } else {
                $response[] = $field_name;
            }
        }

        dump($response);


        $results = [];
        $fields = ['[foopublic]', '[fooprivate]'];
        $record = 4;
        $event_id = 41;
        $project_id = 14;

        foreach ($fields as $key => $field) {
            $results[] = Piping::replaceVariablesInLabel($field, $record, $event_id, 1, array(), true, $project_id, false, "", 1, false, true);
        }
        
        //dump($results);

        //  2. check if $user_rights can be fetched from session

    }

    //  Module context propagation & validation token (MCPV) Token

    private function generateJWT($key, $user_id) {

        $payload = [
            'user_id' => $user_id,
            'session_id' =>  session_id(),
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];

        return JWT::encode($payload, $key, 'HS256');

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
                    record: '<?= htmlentities($_GET['id'], ENT_QUOTES) ?>'                    
                }
            }

        </script>        
        <script src="<?= $this->getUrl("./dist/appRender.js") ?>"></script>
    <?php
    }

    /**
     * Gets Project Parameters needed to support additional features of REDCap
     * 
     * @return array
     * @since 1.1.0
     * 
     */
    public function getProjectParameters() {
        return [
            "hasMultipleEvents" => $this->hasMultipleEvents,
            "hasMultipleArms" => $this->hasMultipleArms
        ];
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
        $current_user = ($this->getUser())->getUsername();
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
    private function getInstancesData( $project_id , $record, $instrument, $fields=null, $events=[]) {
        
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
        //  Gets field labels (table header output)
        //  Order by field_order!
        $sql = 'SELECT element_validation_type, field_name, element_label FROM redcap_metadata WHERE project_id = ? AND field_name IN('.$field_names.') ORDER BY field_order';
        $result = $this->query($sql, [$project_id]);

        $array_to_format = [];
        $fieldLabels = array();
        while($row = $result->fetch_object()) {
            $label = $row->element_label;
            if (strlen($label) > 30) $label = substr($label, 0, 15)."...".substr($label, -6);
            $fieldLabels[] =array("key"=>$row->field_name, "label"=>$label);
            if($row->element_validation_type) {
                $array_to_format[$row->field_name] = $row->element_validation_type;
            }
        }

        //  Fetch all data as JSON so that labels (piped values) are exported (all events)
        $params = array(
            "project_id" => $project_id,
            "records" =>$record,
            "events" => $events,
            "exportAsLabels" => true,
            "exportDataAccessGroups" => false,
            "return_format" => "json",
            "fields" => $fields,
            "returnIncludeRecordEventArray"=> true
        );

        $data = json_decode(REDCap::getData($params), true);

        //  Filter by instrument
        $instrument_name = REDCap::getInstrumentNames($instrument);
        $mapped = array_map(function($instance) use($instrument_name){
            //  case-insensitive compare because instrument name may be capitalized
            if( strcasecmp($instance["redcap_repeat_instrument"], $instrument_name) == 0 ) {
                //  Remove Record Event Info
                //  Hard coded...
                unset($instance[$this->table_pk]);
                unset($instance["redcap_data_access_group"]);
                unset($instance["redcap_event_name"]);
                unset($instance["redcap_repeat_instrument"]);
                unset($instance["redcap_repeat_instance"]);
                return $instance;
            } else {
                return false;
            }
        }, $data);
        $filtered = array_filter($mapped);        

        //  Use Formatter Class to render date formats correctly
        if (!class_exists("Formatter")) include_once("classes/Formatter.php");
        $formatter = new Formatter;

        //  Reset final array and handle field formatting
        foreach ($filtered as $key => $instance) {
            //  Adjust formatting (dates)
            foreach ($array_to_format as $field_to_format => $valtype) {
                $instance[$field_to_format] = $formatter::renderDateFormat($instance[$field_to_format], $valtype);
            }

            //  Data Access
            

            $instances[] = $instance;
        }

        //  Reverse order so that greates instance number is first
        $reversed = array_reverse($instances);

        return array("items"=> $reversed, "fields"=>$fieldLabels);
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
    public function renderElementContent($type, $content, $params, $event) {
        
        $project_id = $params->project_id;
        $record = $params->record;
        $event_id = $event;
        if($event_id == null) {
            $event_id = $this->getFirstEventId($project_id);
            //$event_id = $this->getEventId();
        }

        //  Check for data access on form level
        $user_id = 'test_user_1';
        $user_rights = REDCap::getUserRights($user_id);
        $no_access_forms = array_keys(array_filter($user_rights[$user_id]["forms"], function($value){
            return $value == 0;
        }));

        $no_access_fields = [];
        foreach ($no_access_forms as $key => $form) {
            $no_access_fields =  array_merge($no_access_fields, $this->getFieldNames($form));
        }

        if(isset($content->instrument) && in_array($content->instrument, $no_access_forms)) {
            $this->sendError(403);
        }
        
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
                $field_name = trim($el->value, '[]');
                if(in_array( $field_name, $no_access_fields)) {
                    $response[] = "*No Access*";
                } else {
                    $response[] = Piping::replaceVariablesInLabel($el->value, $record, $event_id, 1, array(), true, $project_id, false);
                }
                
            }            
            break;

            case 'table':
            $response = $this->getInstancesData($project_id, $record, $content->instrument, $content->columns, $content->event);                
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
    * Gets Repeating Forms for single or multiple events projects
    *
    * @return array
    * @since 1.0.0
    *
    */
    public function getSafeRepeatingForms() {

        $repeatingForms = [];
        if($this->hasMultipleEvents) {                       
            //  Stack repeating forms for all events
            foreach ($this->events as $event) {
                $repeatingForms = array_merge( $repeatingForms, $this->getRepeatingForms($event, PROJECT_ID));
            }
            //  Remove duplicates
            $repeatingForms = array_unique($repeatingForms);
        } else {
            $repeatingForms = $this->getRepeatingForms();
        }
        return $repeatingForms;
    }


    /**
     * Gets event info for current project, that can be used to define event in multple event context
     * redcap_v10.6.28\Classes\Project.php::loadEvents()
     * 
     * @return array
     * @since 1.1.0
     * 
     */
    public function getEventInfo() {

        $eventInfo = [];
        $sql = "select * from redcap_events_metadata e, redcap_events_arms a where a.project_id = " . PROJECT_ID . "
        and a.arm_id = e.arm_id order by a.arm_num, e.day_offset, e.descrip";
        $q = db_query($sql);
        while ($row = db_fetch_assoc($q)){
            $eventInfo[] = array( 'value' => $row['event_id'], 'text' => $row['descrip']);
        }
        db_free_result($q);
        return $eventInfo;
    }

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
    private function sendError($status = 400) {
        header('Content-Type: application/json; charset=UTF-8');

        switch ($status) {
            case 400:
                header("HTTP/1.1 400 Bad Request");
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                break;
            default:
                # code...
                break;
        }
        die();
    }    
}