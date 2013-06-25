<?php
 
class Bhf_Controller extends Controller {
     
    private static $allowed_actions = array('show', 'checkpoints', 'index', 'view', 'contacts');
     
    

	public function PageTitle(){
	    return "BHF Page";
    } 
    //Show Foundation Examples
    public function all($request) {
        return $this->renderWith(array('Page','Page'));
    }
    //
    public function SiteAdmin() { 
		if(Permission::check('ADMIN')) return true; 
	}
    
    public function index() {
        
		Requirements::css("http://cdn.leafletjs.com/leaflet-0.5/leaflet.css");
		Requirements::css('gis/css/style.css');
		Requirements::css('gis/css/leaflet.draw.css');
		Requirements::css('gis/css/L.Control.MousePosition.css');
		//Requirements::css('themes/simples/css/L.Control.Locate.ie.css');
		Requirements::javascript("http://cdn.leafletjs.com/leaflet-0.5/leaflet.js");
		Requirements::javascript("gis/js/gridrefutils.js");
		Requirements::javascript("gis/js/jscoord.js");
		Requirements::javascript("gis/js/L.Control.MousePosition.js");
		Requirements::javascript("gis/js/gpx.js");
		Requirements::javascript("gis/js/Google.js");
		Requirements::javascript("http://maps.google.com/maps/api/js?v=3.2&sensor=false");
		Requirements::javascript("gis/js/Control.BingGeocoder.js");
		Requirements::javascriptTemplate("gis/js/leaflet.config.js",
            array(
            'MapCenter'=>Convert::raw2js('51.14662, -0.504'),
            'MapZoom'=>Convert::raw2js('10')
            )
        );
		 
		 return $this->renderWith(array('MapPage','Page'));

    }
    public function view($request) {
        return $this->renderWith(array('CheckpointsPage','Page'));
    }
    

   function getCheckpoints() {
		$place = Checkpoint::get(); 
		return $place; 
	
	}

/*********Checkpoint Page****************/	
	public function getCheckpoint()	{
		$Params = $this->getURLParams();
		$URLSegment = Convert::raw2sql($Params['ID']);
		if(is_numeric($URLSegment) && $check = Checkpoint::get()->byID($URLSegment))
		{		
			return $check;
		}
	}
	public function getRunner()	{
		$Params = $this->getURLParams();
		$URLSegment = Convert::raw2sql($Params['ID']);
		if(is_numeric($URLSegment) && $runner = Runner::get()->byID($URLSegment))
		{		
			return $runner;
		}
	}
	
	function show() {		
		if($check = $this->getCheckpoint())
		{
			$Data = array(
				'Checkpoint' => $check,
				
			);
			
			//return our $Data to use on the page
			return $this->Customise($Data)->renderWith(array('TheCheckpoint', 'Page'));
		}
		else
		{
			//Staff member not found
			return $this->httpError(404, 'Sorry that Checkpoint could not be found');
		}
	}
	function runner() {		
		if($runner = $this->getRunner())
		{
			$Data = array(
				'Runner' => $runner,
				
			);
			
			//return our $Data to use on the page
			return $this->Customise($Data)->renderWith(array('TheRunner', 'Page'));
		}
		else
		{
			//Staff member not found
			return $this->httpError(404, 'Sorry that Runner could not be found');
		}
	}
	function checkinlive() {
		return $this->renderWith(array('CheckinLive', 'Page'));
	}


	function livedata() {
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$time = date('r');
		return "data: The server time is: {$time}\n\n";
		flush();
	}

	function getRunners() {
	 	$runner = Runner::get(); 
		return $runner; 
		}
	function getCheckins() {
	 	 return GroupedList::create(Checkin::get()->sort('RunnerID'));
		}
	
	function dosignin(){
		$RunnerNum	= $_POST["RunnerNumber"];
		$therunner = Runner::get()->filter(array('RaceNumber' => $RunnerNum))->first();
		$runID = $therunner->ID;
		
		
		$theID = $_POST["CheckpointID"];
		$in = date("Y-m-d H:i:s");
		$status = $_POST["Status"];
		
		$submission  = new Checkin();
		$submission->CheckpointID = $theID;
		$submission->RunnerID = $runID;
		$submission->TheTime = $in;
		$submission->Status = $status;
		$submission ->write();
	
		Controller::curr()->redirectBack();
	}








}