<?php
class TrekAdmin extends ModelAdmin {
	
	public static $managed_models = array(
		'Checkpoint',
		'Runner',
		'Checkin'
   
	);
	
	
	
	public static $url_segment = 'TrekAdmin';
	public static $menu_title 	= 'Trek Admin';
	
	public static $page_length = 130;
	public static $default_model 	= 'Checkpoint';	
	
	
	
	
	
}

