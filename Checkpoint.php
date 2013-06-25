<?php

 
class Checkpoint extends DataObject  implements PermissionProvider  {
   
   public static $db = array(
   	'Name' => 'Varchar(50)',
	'Number' => 'Varchar(10)',
	'Grid' => 'Varchar(20)',
	'LatnLon' => 'Varchar(30)',
	'DistFrom' => 'DECIMAL(3, 1)'
			
   );
     public static $has_many =array (
		'Checkins' => 'Checkin',
		
		
	);
  		
  public static $default_sort = "ID";
  public static $searchable_fields = array(
     	 'Name'
   );
  public static $summary_fields = array(
        'Name' => 'Name',
        'DistFrom' => 'Dist. from start',
		'Grid' => 'Grid Ref',
		'LatnLon' => 'Lat n Lon'

	);
	
	public function providePermissions() {
                return array(
                        'CHECKPOINT_VIEW_VIEW' => 'Read a checkpoint object',
                        'CHECKPOINT_VIEW_EDIT' => 'Edit a checkpoint object',
                        'CHECKPOINT_VIEW_DELETE' => 'Delete a checkpoint object',
                        'CHECKPOINT_VIEW_CREATE' => 'Create a checkpoint object',
						'CHECKPOINT_VIEW_CHECKIN' => 'Add a checkpoint checkin',
						
                );
        }
	
	function expectedRunners() {
		$runners = Runner::get()->where("ID not in (
  select RunnerID from Checkin where Checkin.CheckpointID = $this->ID or  Checkin.Status = 'Retired'

)");
	return $runners->count();
	
	}
	
	function brad() {
		$runners = Runner::get()->where("ID not in (
  select RunnerID from Checkin where Checkin.CheckpointID = $this->ID or  Checkin.Status = 'Retired'

)");
	return $runners;
	
	}
	
	function M2K() { 
		$ratio = 1.609344; 
		$kms = $this->DistFrom * $ratio; 
		return round($kms,2); 
	}
	function disttom() {
		$dist = 62 - $this->DistFrom;
		return $dist;
	}
	function disttoM2K() { 
		$ratio = 1.609344; 
		$kms = $this->disttom() * $ratio; 
		return round($kms,2); 
	}

	function viewcheckpoint($Member = null) {
    	return Permission::check('CHECKPOINT_VIEW_VIEW');
	}
	function addacheckin($Member = null) {
    	return Permission::check('CHECKPOINT_VIEW_CHECKIN');
	}
	public function getLatLon() {
	
	include_once('../mysite/code/gridrefutils.php');
			$grutoolbox = Grid_Ref_Utils::toolbox();
			$uk_grid_reference = $this->Grid;
			//convert to a numeric reference
			$uk_grid_numbers = $grutoolbox->get_UK_grid_nums($uk_grid_reference);
			//convert to global latitude/longitude
			$gps_coords = $grutoolbox->grid_to_lat_long($uk_grid_numbers,$grutoolbox->COORDS_GPS_UK,$grutoolbox->HTML);
	return $gps_coords;
	}
	
	function Link($action = 'show'){
		if(!$action) $action = 'show';
		return "checkpoints/$action/" . $this->ID; 
	}
	
	function spotLocalisation() {
		
        Requirements::javascriptTemplate("gis/js/leaflet.spots.js",
            array(
            'Name'=>Convert::raw2js($this->Name),
			'Grid'=>Convert::raw2js($this->Grid),
			'Number' =>Convert::raw2js($this->Number),
			'expected'=>Convert::raw2js($this->expectedRunners()),
            'GPS'=>Convert::raw2js(str_replace("&deg;","",$this->getLatLon()))
            
            )
        );
    }
	
	
	
	
	function onBeforeWrite(){ 
		$latlon = str_replace("&deg;","",$this->getLatLon()); 
		$this->setField('LatnLon', $latlon);

		parent::onBeforeWrite(); 
	}
	
  
}
 
