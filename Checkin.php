<?php

 
class Checkin extends DataObject  {
   
   public static $db = array(
   	'TheTime' => 'SS_Datetime',
	'Status' => 'Varchar()',
	
			
   );
    
	public static $has_one = array(
		'Runner' => 'Runner',
		'Checkpoint' => 'Checkpoint'
	
	);
	
  		
  public static $default_sort = "ID";
  public static $searchable_fields = array(
     	 'Runner.RaceNumber'
   );
  public static $summary_fields = array(
        'TheTime' => 'Time',
		'Status' => 'Status',
		'Runner.Name' => 'Runner',
		'Runner.RaceNumber' => 'Runner Number',
		'Checkpoint.Name' => 'Checkpoint'
		
	);

	public function populateDefaults() {

    parent::populateDefaults();

    $this->TheTime = date('Y-m-d H:i:s');
	$this->Status ='Yes';

  }
	
	 function getCMSFields() { 
		$fields = parent::getCMSFields();
		
		return $fields; 
	}
	
	function Rname() {
		return $this->Runner->Name;
	}
	
	
  
}
 
