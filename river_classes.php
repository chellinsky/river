<?php

//the class defenitions for project, service partner, and volunteer classes go here
class volunteer { 

   // These are the properties
   var $first_name; 
   var $middle_name; 
   var $last_name; 
   var $address; 
   var $city;
   var $state;
   var $zip_code;
   var $phone1;
   var $phone2;
   var $birthdate;
   var $email;
   var $emergency_1;  //this needs to be an array of name, relation, and phone sometime
   var $emergency_2;  //this needs to be an array of name, relation, and phone sometime
   var $medical;  //this is to be a string of all medical information
   var $consent_form;  //boolean form complete
   var $photo_form;  //boolean
   var $key;
   
   //constructor for the class -- perhaps include more here at somepoint, or just add information with funciotns?
   function volunteer($first_name, $last_name) { 
      $this->first_name = $first_name; 
      $this->last_name = $last_name; 
   } 

   function edit_volunteer() { 
   } 

   function check_forms() { 
   } 

   function check_age() { 
   } 

   function print_info() { 
   } 
}

class service_partner {

   // These are the properties
   var $first_name; 
   var $middle_name; 
   var $last_name;
   var $company;
   var $address; 
   var $city;
   var $state;
   var $zip_code;
   var $work_phone;
   var $fax_number;
   var $email;
   var $org_description;
   var $key;
   
   //constructor for the class -- perhaps include more here at somepoint, or just add information with funciotns?
   function service_partner($first_name, $last_name) { 
      $this->first_name = $first_name; 
      $this->last_name = $last_name; 
   } 

   function edit_sp() { 
   } 

   function print_info() { 
   } 
}

class project {

	//  The properties of each project
	var $name;
	var $company;
	var $address;
	var $city;
	var $state;
	var $zip_code;
	var $sp_key;
	var $proj_date;
	var $proj_time;
	var $proj_length;
	var $description;
	var $direction;
	var $tools;
	var $num_volunteers;
	var $key;
	
	//constructor for the class -- needs more
	function project($name, $company) {
		$this->name = $name;
		$this->company = $company;
	}
	
}
	
?>

