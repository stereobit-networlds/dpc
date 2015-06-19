<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['MODEVENT_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("MODEVENT_DPC")) && (seclevel('MODEVENT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MODEVENT_DPC",true);

$__DPC['MODEVENT_DPC'] = 'event_modify';

$__EVENTS['MODEVENT_DPC'][0]='modevent';
$__EVENTS['MODEVENT_DPC'][1]='modevent_ok';

$__ACTIONS['MODEVENT_DPC'][0]='modevent';
$__ACTIONS['MODEVENT_DPC'][1]='modevent_ok';

$__LOCALE['MODEVENT_DPC'][0] = '_EMSG_OK;Ok !;Ok !';
$__LOCALE['MODEVENT_DPC'][1] = '_EMSG_ERROR;Error !;Error !';
$__LOCALE['MODEVENT_DPC'][2] = '_MODEVENT;Modify Event;Μεταβολή Γεγονότος';

class event_modify {

	var $message;
		
    var $res;		

	function event_modify() { 
	 
	   $this->message = null;	    
	   $this->res = null;
	}

    function event($evn) {
       switch ($evn) {		
	     case "modevent" : $this->selectevent(); break;		  
	     case "modevent_ok" : $this->updateevent(); break;		 
       }	
	}	

	function action($act) {
	  
      switch ($act) {		  	
	     case "modevent" : $out = $this->showevent(); break;		  
	     case "modevent_ok" : $out = $this->modifyevent(); break;		   					 	    								
      }		
	    
	  return ($out);
	}
	
	function selectevent() {
	   $db = GetGlobal('db');
	   $g = getReq('g');
	
	  
       $sSQL = "select " . 
	           "e_organizer,e_date,e_startat,e_endat,e_location,e_transparency,e_categories," .
			   "e_title,e_descr,e_class,e_attendees,e_priority,e_frequency,e_recurrency,e_freqstep," .
			   "e_daysofweek,e_startday,e_exceptiondays,e_alarmin,e_status,e_url,e_lang,e_durationmin," .  		   
			   "e_freebusy,e_group FROM events ";
	   $sSQL .= "WHERE e_id=" . $g;		   										         
    
	   //print $sSQL;
       $results = $db->Execute($sSQL);
	   
       if (!$results->fields) $this->message = localize('_EMSG_ERROR',getlocal());				
	                     else $this->res = $results->fields;
	   
	   //print_r($this->res);
	}
	
    function showevent() {
	 $g = getReq('g');
	 
     $dater = new datepicker();		 
	 
     $myaction = seturl("t=modevent&a=&g=$g");   
	    
     $wout = setNavigator(seturl("t=events",localize('EVENTSRV_DPC',getlocal())),
	                      localize('_MODEVENT',getlocal())); 	 
	 
     //$out .= "<FORM name=\"modevt\" action=". "$myaction" . " method=post>"; 	
	 	 
     //error message
     $wout .= setError($sFormErr);		  
	 
	// Creating the new form
	// Parameters are: <title>, <form name>, <FORM_METHOD_POST|FORM_METHOD_GET>, <action>
	$form = new form(localize('_ADDEVENT',getlocal()), "modevt", FORM_METHOD_POST, $myaction);
	
	// Adding three different groups of fields	
	// Parameters for addGroup are: <group internal name>, <group title>
	$form->addGroup			("personal",			"1.");
	$form->addGroup			("contacto",			"2.");
	$form->addGroup			("otros",				"3.");

	$form->addElement		("personal",			new form_element_date		(localize('_DATEPUB',getlocal()),    "modevt",	"datepub",  reverse_datetime($this->res[1]),				"forminput",	        20,				255,	1));		
 	$orgs = explode(",",$this->res[0]);	
	$form->addElement		("personal",			new form_element_text		(localize('_ORGANIZER',getlocal()),  "organizer",			$orgs[0],				"forminput",	        20,				255,	0));
	$form->addElement		("personal",			new form_element_text		(localize('_ORGANIZER',getlocal()),  "organizer2",			$orgs[1],				"forminput",		    20,				255,	0));
	$form->addElement		("personal",			new form_element_text		(localize('_ELOCATION',getlocal()),  "location",			$this->res[4],			"forminput",	        20,				255,	0));
	$cats = explode(",",$this->res[6]);	
	$form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories",			$cats[0], 				"forminput",			20,				255,	0));
	$form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories2",			$cats[1],				"forminput",	        20,				255,	0));
	$form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories3",			$cats[2],				"forminput",	        20,				255,	0));
    $form->addElement		("personal",			new form_element_text		(localize('_ECATEGORIES',getlocal()),"categories4",			$cats[3],				"forminput",	        20,				255,	0));
	$form->addElement		("personal",			new form_element_text		(localize('_ETITLE',getlocal()),     "title",			    $this->res[7],			"forminput",			20,				255,	0));
	$form->addElement		("personal",			new form_element_textarea   (localize('_EDESCR',getlocal()),     "description",			$this->res[8],			"formtextarea",			"100%",				8));	

	$form->addElement		("contacto",			new form_element_date		(localize('_STARTAT',getlocal()),    "modevt",	"startat",  reverse_datetime($this->res[2]),			"forminput",	        20,				255,	1));	
	$form->addElement		("contacto",			new form_element_date		(localize('_ENDAT',getlocal()),      "modevt",	"endat",    reverse_datetime($this->res[3]),			"forminput",	        20,				255,	1));		
	$form->addElement		("contacto",			new form_element_text		(localize('_EURL',getlocal()),       "eurl",			    $this->res[20],			"forminput",	        20,				255,	0));
	$form->addElement		("contacto",			new form_element_text		(localize('_ELANG',getlocal()),      "elang",			    $this->res[21],			"forminput",	        20,				255,	0));
    $form->addElement		("contacto",			new form_element_text		(localize('_EATTENDEES',getlocal()), "attendees",			$this->res[10],			"forminput",	        20,				255,	0));	
	$form->addElement		("contacto",			new form_element_combo		(localize('_ETRANS',getlocal()),     "transparency",		$this->res[5],			"formcombo",	 1,				array ("0" => "No", "1" => "Yes"), 1));
	$form->addElement		("contacto",			new form_element_combo		(localize('_ECLASS',getlocal()),     "class",				$this->res[9],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
	$form->addElement		("contacto",			new form_element_combo		(localize('_EGROUP',getlocal()),     "group",				$this->res[24],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));		
	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EPRIOR',getlocal()),     "priority",			$this->res[11],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EFREQ',getlocal()),      "frequency",			$this->res[12],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));
	$form->addElement		("otros",			    new form_element_combo		(localize('_EFREQSTEP',getlocal()),  "freqstep",			$this->res[14],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9"), 0));	
 	$form->addElement		("otros",               new form_element_text		(localize('_ERECUR',getlocal()),     "recurrency",		    $this->res[13],			"forminput",			20,				255,	0));				
	 //days of week..
	 $dow = explode(",",$this->res[15]);
	 $wod = array();
	 for($i=0;$i<count($dow);$i++)
	   $wod[] = $dow[$i]+1;	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek1",			$wod[0],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));		
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek2",			$wod[1],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek3",			$wod[2],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek4",			$wod[3],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek5",			$wod[4],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek6",			$wod[5],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDOFW',getlocal()),      "dayofweek7",			$wod[6],			"formcombo",	 1,				getdaysarray(($this->res[16]),1), 0));		
	$form->addElement		("otros",			    new form_element_combo		(localize('_ESTARTDAY',getlocal()),  "startday",			($this->res[16]),	"formcombo",	 1,				getdaysarray(0,0), 0));		
	 //exception dates..
	 $xxdays = explode(",",$this->res[17]);		 
	$form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"modevt","exdate01",	    $xxdays[0],				"forminput",			20,				255,	1));	
	$form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"modevt","exdate02",	    $xxdays[1],				"forminput",			20,				255,	1));	
	$form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"modevt","exdate03",	    $xxdays[2],				"forminput",			20,				255,	1));	
	$form->addElement		("otros",				new form_element_date		(localize('_EXDATE',getlocal()),"modevt","exdate04",	    $xxdays[3],				"forminput",			20,				255,	1));	
	$form->addElement		("otros",   			new form_element_text		(localize('_EALMIN',getlocal()),     "almin",			    $this->res[18],			"forminput",			20,				255,	0));		
	$form->addElement		("otros",			    new form_element_combo		(localize('_ESTATUS',getlocal()),    "status",				$this->res[19],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2"), 0));	
	$form->addElement		("otros",			    new form_element_combo		(localize('_EDURMIN',getlocal()),    "durmin",				$this->res[22],			"formcombo",	 0,				array ("0" => "0", "1" => "1","2" => "2", "3" => "3","4" => "4", "5" => "5","6" => "6", "7" => "7","8" => "8", "9" => "9", "10" => "10"), 0));						
	$form->addElement		("otros",		    	new form_element_text		(localize('_EFRBUSY',getlocal()),    "freebusy",		    $this->res[23],			"forminput",			20,				255,	0));	
	
	// Adding a hidden field
	$form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "modevent_ok"));
	
	// Showing the form
	$out = $form->getform ();		
	$fwin = new window(localize('_MODEVENT',getlocal()),$out);
	$wout .= $fwin->render();	
	unset ($fwin);		     
	 
     return ($wout);
    }
	
	function updateevent() {
	  $db = GetGlobal('db');
	  
	  $g = getReq('g');
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
	  /* global $datepub,$organizer,$organizer2,$location,
	         $categories,$categories2,$categories3,$categories4,$title,$description,
			 $startat,$endat,$eurl,$elang,$class,$transparency,$attendees,
			 $priority,$frequency,$freqstep,$recurrency,
			 $dayofweek1,$dayofweek2,$dayofweek3,$dayofweek4,$dayofweek5,$dayofweek6,$dayofweek7,
			 $startday,$exdate01,$exdate02,$exdate03,$exdate04,
			 $alarmin,$status,$durmin,$freebusy,$group;	 */
			 
	   //prepare
	    
	   $days_of_week = explode(",","$dayofweek1,$dayofweek2,$dayofweek3,$dayofweek4,$dayofweek5,$dayofweek6,$dayofweek7");
	   $dw = array();   
	   for($i=0;$i<7;$i++) {
	     if ($days_of_week[$i]!=0) $dw[] = ($days_of_week[$i]-1);
	   }
	   $daysofweek = implode(",",$dw);		 //echo ">>>>",$mydaysofweek;
			 
			 
	   //update		 
       $sSQL = "update events set ";
			   
	   $sSQL .= "e_organizer=" . $db->qstr($organizer.",".$organizer2) . "," . 
			    "e_date=" . $db->qstr(reverse_datetime($datepub)) . "," . 
                "e_startat=" . $db->qstr(reverse_datetime($startat)) . "," . 
                "e_endat=" . $db->qstr(reverse_datetime($endat)) . "," . 
                "e_location=" . $db->qstr($location) . "," .
                "e_transparency=" . $transparency . "," . 
                "e_categories=" . $db->qstr($categories.",".$categories2.",".$categories3.",".$categories4) . "," . 
			    "e_title=" . $db->qstr($title) . "," . 
                "e_descr=" . $db->qstr($description) . "," . 
                "e_class=" . $class . "," . 
                "e_attendees=" . $db->qstr($attendees) . "," .
                "e_priority=" . $priority . "," .   
			    "e_frequency=" . $frequency . "," . 
                "e_recurrency=" . $db->qstr($recurrency) . "," . 
                "e_freqstep=" . $freqstep . "," . 
                "e_daysofweek=" . $db->qstr($daysofweek) . "," .
                "e_startday=" . $startday . "," .				
			    "e_exceptiondays=" . $db->qstr($exdate01.",".$exdate02.",".$exdate03.",".$exdate04) . "," . 
				"e_alarmin=" . $db->qstr($alarmin) . "," .
                "e_status=" . $status . "," . 
                "e_url=" . $db->qstr($eurl) . "," .
                "e_lang=" . $db->qstr($elang) . "," .					
                "e_durationmin=" . $durmin . "," .				
                "e_freebusy=" . $db->qstr($freebusy) . "," .					
                "e_group=" . $group;	
				
	   $sSQL .= " where e_id=" . $g;						   
	  
	   //print $sSQL;
       $db->Execute($sSQL);
	   
       if($db->Affected_Rows()) $this->message = localize('_EMSG_OK',getlocal());
	                       else $this->message = localize('_EMSG_ERROR',getlocal());
	}  	
	
    function modifyevent() {	
	 
	 $d =  new dialog(localize('_MODEVENT',getlocal()),$this->message,"t=events","events");
	 $out = $d->render();
	 unset($d);
	 
	 return ($out);
	}
	
};
}
}
else die("DATABASE DPC REQUIRED!");
?>