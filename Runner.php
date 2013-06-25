<?php

 
class Runner extends DataObject  implements PermissionProvider {
   
   public static $db = array(
    'Firstname' => 'varchar(20)',
    'Surname' => 'Varchar(50)',
  	'RaceNumber' => 'int',
    'Gender' => 'Varchar(10)',
  	'Tele' => 'Varchar(20)' ,
  	'NOKName' => 'Varchar(30)',
  	'NOKNumber' => 'Varchar(20)',
    'NOKRel' => 'Varchar(50)'
	
			
   );
    public static $has_many =array (
		'Checkins' => 'Checkin',
		
		
	);
  public function Name() {
    return ($this->Firstname . ' ' . $this->Surname);
  }
  		
  public static $default_sort = "Surname";
  public static $searchable_fields = array(
     'Surname',
		 'RaceNumber'
   );
  public static $summary_fields = array(
    'Name' => 'Name',
		'RaceNumber' => 'Race Number',
    'LKP' => 'LKP'
		
	);
	
	 function getCMSFields() { 
		$fields = parent::getCMSFields();
		
		return $fields; 
	}
	function Link($action = 'runner'){
		if(!$action) $action = 'runner';
		return "checkpoints/$action/" . $this->ID; 
	}
	public function providePermissions() {
                return array(
                        'RUNNER_VIEW_VIEW' => 'Read a runner object',
                        'RUNNER_VIEW_EDIT' => 'Edit a runner object',
                        'RUNNER_VIEW_DELETE' => 'Delete a runner object',
                        'RUNNER_VIEW_CREATE' => 'Create a runner object',
						'RUNNER_VIEW_VIEWEXT' => 'Read a runners extended info',
						
                );
        }
	function SuperDuper($Member = null) {
    	return Permission::check('RUNNER_VIEW_VIEWEXT');
	}
	function viewrunners($Member = null) {
      return Permission::check('RUNNER_VIEW_VIEW');
  }
  function LKP() {
    $position = Checkin::get()->filter(array('RunnerID' => $this->ID))->sort('TheTime', desc)->first();
    $positionID = $position->CheckpointID;
    $status = $position->Status;
    $checkpoint = Checkpoint::get()->byid($positionID);
    return $checkpoint->Name . ' ' .$status;
  }
}
 
