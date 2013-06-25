<?php
 
class Checkpoint_Controller extends Controller {
     
    private static $allowed_actions = array('show', 'checkpoints', 'index', 'view', 'dosignin', 'signin', 'runner', 'SuperDuper', 'contacts', 'info');
     
    

	public function PageTitle(){
	    return "BHF Page";
    } 
        
    
    public function index($request) {
        return $this->renderWith(array('CheckpointsPage','Page'));
    }
    public function SiteAdmin() { 
		if(Permission::check('ADMIN')) return true; 
	}
   
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
	public function contacts($request) {
        return $this->renderWith(array('ContactsPage','Page'));
    }
    public function info($request) {
        return $this->renderWith(array('InfoPage','Page'));
    }
	function show() {

		Requirements::javascript("mysite/js/focus.js");

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
	
	function getCheckpoints() {
		$place = Checkpoint::get(); 
		return $place; 
	
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