<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['EVENTSRV_DPC']='2;1;1;1;1;1;1;1;2';
$__DPCSEC['EVENTCNF_']='2;1;1;1;1;1;1;1;2';
$__DPCSEC['REMINDER_']='2;1;1;1;1;1;1;1;2';

if ( (!defined("EVENTSRV_DPC")) && (seclevel('EVENTSRV_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("EVENTSRV_DPC",true);

$__DPC['EVENTSRV_DPC'] = 'event_srv';

$__EVENTS['EVENTSRV_DPC'][0]='events';
$__EVENTS['EVENTSRV_DPC'][1]='reset_events';
$__EVENTS['EVENTSRV_DPC'][2]='reminder';

$__ACTIONS['EVENTSRV_DPC'][0]='events';
$__ACTIONS['EVENTSRV_DPC'][1]='reset_events';
$__ACTIONS['EVENTSRV_DPC'][2]='events_datespace';
$__ACTIONS['EVENTSRV_DPC'][3]='addreminder';
$__ACTIONS['EVENTSRV_DPC'][4]='reminder';

$__LOCALE['EVENTSRV_DPC'][0] = 'EVENTSRV_DPC;Events;Γεγονότα';
$__LOCALE['EVENTSRV_DPC'][1] = '_ADDEVENT;Add Event;Προσθήκη συμβάντος';
$__LOCALE['EVENTSRV_DPC'][2] = '_ORGANIZER;Organizer;Οργανωτής';
$__LOCALE['EVENTSRV_DPC'][3] = '_DATEPUB;Publish Date;Ημ. Έκδοσης';
$__LOCALE['EVENTSRV_DPC'][4] = '_ELOCATION;Location;Περιοχή';
$__LOCALE['EVENTSRV_DPC'][5] = '_ECATEGORIES;Categories;Κατηγορία';
$__LOCALE['EVENTSRV_DPC'][6] = '_ETITLE;Title;Τίτλος';
$__LOCALE['EVENTSRV_DPC'][7] = '_EDESCR;Description;Περιγραφή';
$__LOCALE['EVENTSRV_DPC'][8] = '_STARTAT;Start at;Εκκινηση';
$__LOCALE['EVENTSRV_DPC'][9] = '_ENDAT;End at;Τέλος';
$__LOCALE['EVENTSRV_DPC'][10] = '_ECLASS;Class;Τάξη';
$__LOCALE['EVENTSRV_DPC'][11] = '_ETRANS;Transparency;Transparency';
$__LOCALE['EVENTSRV_DPC'][12] = '_EATTENDEES;Attendees;Attendees';
$__LOCALE['EVENTSRV_DPC'][13] = '_EPRIOR;Priority;Προτεραιότητα';
$__LOCALE['EVENTSRV_DPC'][14] = '_EFREQ;Frequency;Συχνότητα';
$__LOCALE['EVENTSRV_DPC'][15] = '_ERECUR;Recurrency;Επαναληψη';
$__LOCALE['EVENTSRV_DPC'][16] = '_EFREQSTEP;Frequency Step;Frequency Step';
$__LOCALE['EVENTSRV_DPC'][17] = '_EDOFW;Days Of Week;Ημέρες';
$__LOCALE['EVENTSRV_DPC'][18] = '_ESTARTDAY;Start day;Ημέρα εκκίνησης';
$__LOCALE['EVENTSRV_DPC'][19] = '_EXDATE;Exceptions;Εξαιρέσεις';
$__LOCALE['EVENTSRV_DPC'][20] = '_EALMIN;Alarm (Min);Ειδοποιήση (λεπτά)';
$__LOCALE['EVENTSRV_DPC'][21] = '_ESTATUS;Status;Κατάσταση';
$__LOCALE['EVENTSRV_DPC'][22] = '_EURL;Url;Url';
$__LOCALE['EVENTSRV_DPC'][23] = '_ELANG;Language;Γλώσσα';
$__LOCALE['EVENTSRV_DPC'][24] = '_EDURMIN;Duration (Min);Κατεύθυνση (λεπτά)';
$__LOCALE['EVENTSRV_DPC'][25] = '_EFRBUSY;Free Busy;Free Busy';
$__LOCALE['EVENTSRV_DPC'][26] = '_EGROUP;Group;Ομάδα';
$__LOCALE['EVENTSRV_DPC'][27] = '_ADDREMINDER;Reminder;Υπενθύμηση';

//require_once("addevent.lib.php");
//require_once("modevent.lib.php");
//<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>> a new method introduced to include objects
GetGlobal('controller')->init_object('eventsrv.addevent','lib');
GetGlobal('controller')->init_object('eventsrv.modevent','lib');
GetGlobal('controller')->init_object('eventsrv.viewevent','lib');

class event_srv {

	var $userLevelID;
	var $nowday;

	function event_srv() {  	    
	   $UserSecID = GetGlobal('UserSecID');
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	   $this->nowday = date('d-m-Y');	      	
	}

    function event($evn) {

       switch ($evn) {		
	     case "reset_events" : $this->reset_events(); break;
         case "reminder"     : $prn = $this->reminder();
		                       echo $prn;
							   exit; 
       }	
	}	

	function action($act) {
	  $__USERAGENT = GetGlobal('__USERAGENT');	
	  $apo = GetGlobal('apo');
	  $eos = GetGlobal('eos');
	  
	  //print $apo.$eos;
	  
      //title
      $out = setNavigator(localize('EVENTSRV_DPC',getlocal()));
	  
      if (seclevel('EVENTCNF_',$this->userLevelID)) {	  
  	    $out .= $this->commands();	  
	  }	
	
      switch ($act) {	
          case "events_datespace":	  	
          case "reminder"        : //$out .= $this->reminder(); //continue with show events		  
          case "events"          : $out .= $this->show_events($apo,$eos); break;	   					 	    								
          case "addreminder"     : $d =  new dialog(localize('_ADDREMINDER',getlocal()),localize('_ADDREMINDER',getlocal()),"t=reminder","reminder");
	                               $out .= $d->render();
	                               unset($d);break;	 						   	  
      }		
	    
	    
	  return ($out);
	}
	
	function commands() {
	
       if ( (defined('ADDEVENT_DPC')) && (seclevel('ADDEVENT_DPC',$this->userLevelID)) ) 
	     $out = seturl("t=addevent_step0&a=0&g=0",localize('_ADDEVENT',getlocal()));	
	
	   $out .= "&nbsp;";
		 	 
       if ( (defined('VIEWEVENT_DPC')) && (seclevel('VIEWEVENT_DPC',$this->userLevelID)) ) 
	     $out .= seturl("t=vieweventgroups&a=&g=",localize('_VIEWEVENT',getlocal()));			 
		 
	   return ($out);	 
	}
	
	
	function show_events($fromdt='',$todt='') {
	   $db = GetGlobal('db');    	
	
	   //print $fromdt.$todt;
	   //if (!$fromdt) $fromdt = $this->nowday; //select today events
	
	   $sSQL = "select e_id,e_organizer,e_startat,e_title,e_descr from events";
       if ($fromdt) $sSQL .= " where (e_startat>=" . $db->qstr(trim(reverse_datetime($fromdt))) .")";
 	   if ($todt) $sSQL .= " AND (e_startat<=" . $db->qstr(trim(reverse_datetime($todt))) .")";		   
  
	   //print $sSQL;

       $browser = new browseSQL(localize('EVENTSRV_DPC',getlocal()));
	   $out .= $browser->render($db,$sSQL,"events","events",30,$this,1,1,1,0);
	   unset ($browser);
	   
	   $out .= $this->getdatespace();	      
	     	 
	   return ($out);
	}
	
	
   function reset_events() {
	    $db = GetGlobal('db');

        //delete table if exist
  	    $sSQL = "drop table events";
        $db->Execute($sSQL);
		$sSQL = "create table events " .
                    "(" .
	                "e_id integer auto_increment primary key," .  //id
	                "e_organizer varchar(50) not null," .				  //organizer	
	                "e_date datetime not null," .                          //date of publish 					
	                "e_startat datetime not null," .                       //start time
	                "e_endat datetime ," .                         //end time 
	                "e_location varchar(50) not null," .                  //location
	                "e_transparency integer default 0," .		  //transparency			
	                "e_categories varchar(50)," .                //categories
	                "e_title varchar(50) not null," .	                  //title
	                "e_descr varchar(255)," .                     //description
	                "e_class integer default 2," .				  //class	 
	                "e_attendees varchar(50)," .                 //attendees
	                "e_priority integer default 5," .			  //priority												
	                "e_frequency integer default 0," .            //frequency
	                "e_recurrency varchar(50)," .                //recurency
	                "e_freqstep integer default 1," .			  //frequency step				 								
	                "e_daysofweek varchar(50)," .                //days of week
	                "e_startday integer default 0," .			  //start day of week (0=Sunday,6=Saturday)												
	                "e_exceptiondays varchar(100)," .             //exception days
	                "e_alarmin integer default 0," .             //alarm time in minutes
	                "e_status integer default 1," .			      //status (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)		 
	                "e_url varchar(50)," .                       //url link
	                "e_lang varchar(50)," .					  //language
	                "e_durationmin integer default 1," .		  //duration minutes					
	                "e_freebusy varchar(50)," .				  //freebusy dates	
	                "e_group integer default 0," .				  //group of interest					
					"e_buffer varchar(255)" .                     //future use..
                    ")";
					//print $sSQL;
        $db->Execute($sSQL);   
			
		setInfo(" Events reset successfully!");
    }	
	
	
	function getdatespace() {
	
		  $dater = new datepicker();	
		  
		  $toprint = $dater->renderspace(seturl("t=events&a=&g="),"events_datespace");
		  
		  return ($toprint);
	}	  
	
	function reminder() {
	      $db = GetGlobal('db');
		  $g = getReq('g');
		  
	      //get event data
		  $sSQL = "select " .
		          "e_organizer,e_date,e_startat,e_endat,e_location,e_transparency,e_categories," .
			      "e_title,e_descr,e_class,e_attendees,e_priority,e_frequency,e_recurrency,e_freqstep," .
			      "e_daysofweek,e_startday,e_exceptiondays,e_alarmin,e_status,e_url,e_lang,e_durationmin," .  		   
			      "e_freebusy,e_group" .
				  " from events where e_id=" . $g;
				  
	      //echo $sSQL;
          $res = $db->Execute($sSQL);				  	  
		  //print_r($res->fields);
		  
		  
		  //prepare data
		  $org = explode(",",$res->fields[0]);
		  if (is_array($org)) $organizer = (array)$org;
		                   else $organizer = '';		  
						   
          $cat = explode(",",$res->fields[6]);						   
		  if (is_array($cat)) $categories = (array)$cat;
		                 else $categories = '';	
						   
		  $atte = explode(",",$res->fields[10]);				   	  
		  if (is_array($atte)) $attendees = (array)$atte;
		                  else $attendees = '';
						   
		  $dofw = explode(",",$res->fields[15]);				   
		  if (is_array($dofw)) $daysofweek = (array)$dofw;
		                  else $daysofweek = '';	
						  
		  if ($res->fields[17]!=',,,') { //empty string			  
		  $xdates = explode(",",$res->fields[17]);				  	
		  if (is_array($xdates)) $exception_dates = (array)$xdates;
		                    else $exception_dates = '';
		  }
		  else
		    $exception_dates = '';				   
		  
		  
		  //set event
          $iCal = (object) new iCal('', 0, ''); // (ProgrammID, Method (1 = Publish | 0 = Request), Download Directory)
		  
          $iCal->addEvent(
				$organizer,
				$res->fields[2],
				$res->fields[3],
				$res->fields[4],
				$res->fields[5],								
				$categories,				
				$res->fields[8],				
				$res->fields[7],	
				$res->fields[9],
				$attendees,											
				$res->fields[11],
				$res->fields[12],								
				$res->fields[13],				
				$res->fields[14],				
				$daysofweek,	
				$res->fields[16],
				$exception_dates,				
				$res->fields[18],				
				$res->fields[19],	
				$res->fields[20],
				$res->fields[21]				
               );	
			   
          $data = $iCal->outputFile('ics'); // output file as ics (xcs and rdf possible)			   	  
		  
          return ($data);	  
	}
	
	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewevents($data[0],$data[1],$data[2],$data[3],$data[4]);

	   return ($out);
	}		
	
    function viewevents($id,$organizer,$date,$title,$descr) {
	   $a = GetReq('a');
	   
	   $link = seturl("t=modevent&a=&g=$id&p=" , $title);   
							  
	   $data[] = reverse_datetime($date);
	   $attr[] = "left;20%";
	   $data[] = $organizer;
	   $attr[] = "left;20%";	   
	   $data[] = $link;   
	   $attr[] = "left;20%";
	   $data[] = $descr;   
	   $attr[] = "left;39%";
	   
	   if ( (defined("ICALENDAR_DPC")) && (seclevel('REMINDER_',decode($this->userLevelID))) ) {
	     $data[] = seturl("t=reminder&a=&g=$id","R");   
	     $attr[] = "left;1%";	   
	   }
	   
	   $myarticle = new window('',$data,$attr);
	   
	   //$out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
       if (($a) && ((stristr($organizer,$a)) ||
	                (stristr($date,$a)) ||
					(stristr($title,$a)) ||
					(stristr($descr,$a))) ) $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	                                   else $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");	   
	   
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   $a = GetReq('a');
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $g = GetReq('g');
	   $sort = GetReq('sort');	   	   	   	   	   
	
		             $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=0",  localize('_STARTAT',getlocal()) );
					 $attr[] = "left;20%";							  
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_ORGANIZER',getlocal()) );
					 $attr[] = "left;20%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_ETITLE',getlocal()) );
					 $attr[] = "left;20%";			 
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_EDESCR',getlocal()) );
					 $attr[] = "left;40%";					 

					 $mytitle = new window('',$data,$attr);
					 $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);	
	   
	   return ($out);
	}		
	
};
}
}
else die("DATABASE DPC REQUIRED! (" .__FILE__ . ")");
?>