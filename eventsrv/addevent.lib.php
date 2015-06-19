<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['ADDEVENT_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("ADDEVENT_DPC")) && (seclevel('ADDEVENT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ADDEVENT_DPC",true);

$__DPC['ADDEVENT_DPC'] = 'event_add';

$__EVENTS['ADDEVENT_DPC'][0]='addevent_0';
$__EVENTS['ADDEVENT_DPC'][1]='addevent_1';

$__ACTIONS['ADDEVENT_DPC'][0]='addevent_step0';
$__ACTIONS['ADDEVENT_DPC'][1]='addevent_0';
$__ACTIONS['ADDEVENT_DPC'][2]='addevent_step1';
$__ACTIONS['ADDEVENT_DPC'][3]='addevent_1';

//$__LOCALE['ADDEVENT_DPC'][0] = 'ADDEVENT_DPC;Add Event;Add Event';
$__LOCALE['ADDEVENT_DPC'][0] = '_EMSG_OK;Ok !;Ok !';
$__LOCALE['ADDEVENT_DPC'][1] = '_EMSG_ERROR;Error !;Error !';


class event_add {

    var $nowday;
	var $message;

	function event_add() { 
	
	   $this->nowday = date('d-m-Y h:i:s'); 
	   $this->message = null;	    
	
	}

    function event($evn) {
       switch ($evn) {		
	     case "addevent_0" : $this->addevent_0(); break;		  
	     //case "addevent_step1" : $this->step1(); break;		 
       }	
	}	

	function action($act) {
	  
      switch ($act) {		  	
	     case "addevent_step0" : $out = $this->addevent_step0(); break;	
		 case "addevent_0"     :	  
	     case "addevent_step1" : $out = $this->addevent_step1(); break;		   					 	    								
      }		
	    
	  return ($out);
	}
	
	function addevent_0() {
	  $db = GetGlobal('db');
	  
      $datepub = GetReq('datepub');
	  $organizer = GetReq('organizer');
	  $organizer2 = GetReq('organizer2');
	  $location = GetReq('location');
	  $categories = GetReq('categories');
	  $categories2 = GetReq('categories2');
	  $categories3 = GetReq('categories3');
	  $categories4 = GetReq('categories4');
	  $title = GetReq('title');
	  $description = GetReq('description');
      $startat = GetReq('startat');
	  $endat = GetReq('endat');
	  $eurl = GetReq('eurl');
	  $elang = GetReq('elang');
	  $class = GetReq('class');
	  $transparency = GetReq('transparency');
	  $attendees = GetReq('attendees');
	  $priority = GetReq('priority');
	  $frequency = GetReq('frequency');
	  $freqstep = GetReq('freqstep');
	  $recurrency = GetReq('recurrency');
	  $dayofweek1 = GetReq('dayofweek1');
	  $dayofweek2 = GetReq('dayofweek2');
	  $dayofweek3 = GetReq('dayofweek3');
	  $dayofweek4 = GetReq('dayofweek4');
	  $dayofweek5 = GetReq('dayofweek5');
	  $dayofweek6 = GetReq('dayofweek6');
	  $dayofweek7 = GetReq('dayofweek7');
	  $startday = GetReq('startday');
	  $exdate01 = GetReq('exdate01');
	  $exdate02 = GetReq('exdate02');
	  $exdate03 = GetReq('exdate03');
	  $exdate04 = GetReq('exdate04');
	  $alarmin = GetReq('alarmin');
	  $status = GetReq('status');
	  $durmin = GetReq('durmin');
	  $freebusy = GetReq('freebusy');
	  $group = GetGlobal('group');	  
	  /*global $datepub,$organizer,$organizer2,$location,
	         $categories,$categories2,$categories3,$categories4,$title,$description,
			 $startat,$endat,$eurl,$elang,$class,$transparency,$attendees,
			 $priority,$frequency,$freqstep,$recurrency,
			 $dayofweek1,$dayofweek2,$dayofweek3,$dayofweek4,$dayofweek5,$dayofweek6,$dayofweek7,
			 $startday,$exdate01,$exdate02,$exdate03,$exdate04,
			 $alarmin,$status,$durmin,$freebusy,$group;*/
			 
	   //prepare
	    
	   $days_of_week = explode(",","$dayofweek1,$dayofweek2,$dayofweek3,$dayofweek4,$dayofweek5,$dayofweek6,$dayofweek7");
	   $dw = array();
	   for($i=0;$i<7;$i++) {
	     if ($days_of_week[$i]!=0) $dw[] = ($days_of_week[$i]-1);
	   }
	   $daysofweek = implode(",",$dw);				 
	
	  
       $sSQL = "insert into events (" . 
	           "e_organizer,e_date,e_startat,e_endat,e_location,e_transparency,e_categories," .
			   "e_title,e_descr,e_class,e_attendees,e_priority,e_frequency,e_recurrency,e_freqstep," .
			   "e_daysofweek,e_startday,e_exceptiondays,e_alarmin,e_status,e_url,e_lang,e_durationmin," .  		   
			   "e_freebusy,e_group" .
		       ") values (";
			   
	   $sSQL .= $db->qstr($organizer.",".$organizer2) . "," . 
			    $db->qstr(reverse_datetime($datepub)) . "," . 
                $db->qstr(reverse_datetime($startat)) . "," . 
                $db->qstr(reverse_datetime($endat)) . "," . 
                $db->qstr($location) . "," .
                $transparency . "," . 
                $db->qstr($categories.",".$categories2.",".$categories3.",".$categories4) . "," . 
			    $db->qstr($title) . "," . 
                $db->qstr($description) . "," . 
                $class . "," . 
                $db->qstr($attendees) . "," .
                $priority . "," .   
			    $frequency . "," . 
                $db->qstr($recurrency) . "," . 
                $freqstep . "," . 
                $db->qstr($daysofweek) . "," .
                $startday . "," .				
			    $db->qstr($exdate01.",".$exdate02.",".$exdate03.",".$exdate04) . "," . 
				$db->qstr($alarmin) . "," .
                $status . "," . 
                $db->qstr($eurl) . "," .
                $db->qstr($elang) . "," .					
                $durmin . "," .				
                $db->qstr($freebusy) . "," .					
                $group .				
	            ")";											         
    
	   //print $sSQL;
       $db->Execute($sSQL);
	   
       if($db->Affected_Rows()) $this->message = localize('_EMSG_OK',getlocal());
	                       else $this->message = localize('_EMSG_ERROR',getlocal());				
	  
	}
	
    function addevent_step0() {
     $sFormErr = GetGlobal('sFormErr');
	 
     $dater = new datepicker();		 
	 
     $myaction = seturl("t=addevent_step0&a=&g=");   
	    
     $wout = setNavigator(seturl("t=events",localize('EVENTSRV_DPC',getlocal())),
	                      localize('_ADDEVENT',getlocal())); 	
	 	 
     //error message
     $wout .= setError($sFormErr);		  
	 
	 // Creating the new form
	 // Parameters are: <title>, <form name>, <FORM_METHOD_POST|FORM_METHOD_GET>, <action>
	 $form = new form(localize('_ADDEVENT',getlocal()), "addevt", FORM_METHOD_POST, $myaction);
	
	 // Adding three different groups of fields	
	 // Parameters for addGroup are: <group internal name>, <group title>
	 $form->addGroup			("personal",			"1.");
	 $form->addGroup			("contacto",			"2.");
	 $form->addGroup			("otros",				"3.");

	 // Adding some fields to the groups
	 // Parameters for addElement are: <group internal name on wich to include>, <element object>
	 // Where <element object> could be one of these, with its sintaxes:
	 // form_element_text			(<field title>, <input name>, <default value>, <CSS style name>, <length>, <maxlength>, <0|1 Is or not a read-only field>)
	 // form_element_onlytext		(<field title>, <text to show>, <CSS style name>)
	 // form_element_password		(<field title>, <input name>, <default value>, <CSS style name>, <length>, <maxlength>)
	 // form_element_combo			(<field title>, <input name>, <default value>, <CSS style name>, <combo size>, <array of values>, <0|1 Whether or not this combo allows multiple selections>) Where <array of values> is a hash-array like array ("title" => "value", "title" => "value" ...)
	 // form_element_radio			(<field title>, <input name>, <default value>, <CSS style name>, <combo size>, <array of values>) Where <array of values> is a hash-array like array ("title" => "value", "title" => "value" ...)
	 // form_element_checkbox		(<field title>, <input name>, <default value>, <CSS style name>)
	 // form_element_textarea		(<field title>, <input name>, <default value>, <CSS style name>, <number of columns>, <number of rows>)
	 // form_element_hidden			(<input name>, <value>)
	 $form->addElement		("personal",			new form_element_date		(localize('_DATEPUB',getlocal()),    "addevt",	"datepub",  "",				"forminput",	        20,				255,	1));		
	 $form->addElement		("personal",			new form_element_text		(localize('_ORGANIZER',getlocal()),  "organizer",			"",				"forminput",	        20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ORGANIZER',getlocal()),  "organizer2",			"",				"forminput",		    20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ELOCATION',getlocal()),  "location",			"",				"forminput",	        20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories",			"",				"forminput",			20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories2",			"",				"forminput",	        20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories3",			"",				"forminput",	        20,				255,	0));
     $form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories4",			"",				"forminput",	        20,				255,	0));
	 $form->addElement		("personal",			new form_element_text		(localize('_ETITLE',getlocal()),     "title",			    "",				"forminput",			20,				255,	0));
	 $form->addElement		("personal",			new form_element_textarea   (localize('_EDESCR',getlocal()),     "description",			"",				"formtextarea",			40,				3));	

	 $form->addElement		("contacto",			new form_element_date		(localize('_STARTAT',getlocal()),    "addevt",	"startat",  "",			    "forminput",	        20,				255,	1));	
	 $form->addElement		("contacto",			new form_element_date		(localize('_ENDAT',getlocal()),      "addevt",	"endat",    "",			    "forminput",	        20,				255,	1));		
	 $form->addElement		("contacto",			new form_element_text		(localize('_EURL',getlocal()),       "eurl",			    "",				"forminput",	        20,				255,	0));
	 $form->addElement		("contacto",			new form_element_text		(localize('_ELANG',getlocal()),      "elang",			    "",				"forminput",	        20,				255,	0));
     $form->addElement		("contacto",			new form_element_text		(localize('_EATTENDEES',getlocal()), "attendees",			"",				"forminput",	        20,				255,	0));
	 $form->addElement		("contacto",			new form_element_combo		(localize('_ETRANS',getlocal()),     "transparency",		"0",			"formcombo",	 1,				array ("0" => "No", "1" => "Yes"), 1));
	 $form->addElement		("contacto",			new form_element_combo		(localize('_ECLASS',getlocal()),     "class",				"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
	 $form->addElement		("contacto",			new form_element_combo		(localize('_EGROUP',getlocal()),     "group",				"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EPRIOR',getlocal()),     "priority",			"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EFREQ',getlocal()),      "frequency",			"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EFREQSTEP',getlocal()),  "freqstep",			"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
 	 $form->addElement		("otros",               new form_element_text		(localize('_ERECUR',getlocal()),     "recurrency",		    "",				"forminput",			20,				255,	0));				
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek1",			"1",			"formcombo",	 1,				getdaysarray(0,1), 0));		
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek2",			"2",			"formcombo",	 1,				getdaysarray(0,1), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek3",			"3",			"formcombo",	 1,				getdaysarray(0,1), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek4",			"4",			"formcombo",	 1,				getdaysarray(0,1), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek5",			"5",			"formcombo",	 1,				getdaysarray(0,1), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek6",			"6",			"formcombo",	 1,				getdaysarray(0,1), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek7",			"7",			"formcombo",	 1,				getdaysarray(0,1), 0));		
	 $form->addElement		("otros",			    new form_element_combo		(localize('_ESTARTDAY',getlocal()),  "startday",			"0",			"formcombo",	 1,				getdaysarray(0,0), 0));		
	 $form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"addevt","exdate01",	    "",				"forminput",			20,				255,	1));	
	 $form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"addevt","exdate02",	    "",				"forminput",			20,				255,	1));	
	 $form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"addevt","exdate03",	    "",				"forminput",			20,				255,	1));	
	 $form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"addevt","exdate04",	    "",				"forminput",			20,				255,	1));	
	 $form->addElement		("otros",   			new form_element_text		(localize('_EALMIN',getlocal()),     "almin",			    "",				"forminput",			20,				255,	0));		
	 $form->addElement		("otros",			    new form_element_combo		(localize('_ESTATUS',getlocal()),    "status",				"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2"), 0));	
	 $form->addElement		("otros",			    new form_element_combo		(localize('_EDURMIN',getlocal()),    "durmin",				"0",			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9", "10" => "10"), 0));						
	 $form->addElement		("otros",		    	new form_element_text		(localize('_EFRBUSY',getlocal()),    "freebusy",		    "",				"forminput",			20,				255,	0));	
	
	 // Adding a hidden field
	 $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "addevent_0"));
	
	 // Showing the form
	 $out = $form->getform ();		
	 $fwin = new window(localize('_ADDEVENT',getlocal()),$out);
	 $wout .= $fwin->render();	
	 unset ($fwin);	     
	 
     return ($wout);
    }  	
	
    function addevent_step1() {	

	 $d =  new dialog(localize('_ADDEVENT',getlocal()),$this->message,"t=events","events");
	 $out = $d->render();
	 unset($d);	
	 
	 return ($out);
	}
	
};
}
}
else die("DATABASE DPC REQUIRED!");
?>