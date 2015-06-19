<?php
//if (defined("DATABASE_DPC")) {

$__DPCSEC['ICALENDAR_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("ICALENDAR_DPC")) && (seclevel('ICALENDAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ICALENDAR_DPC",true);

$__DPC['ICALENDAR_DPC'] = 'iCalendar';

$__EVENTS['ICALENDAR_DPC'][0]='ICAL';

$__ACTIONS['ICALENDAR_DPC'][0]='ICAL';


//$__LOCALE['ICALENDAR_DPC'][0] = 'ICALENDAR_DPC;iCalendar;iCalendar';

//require_once('iCal.lib.php');
GetGlobal('controller')->include_dpc('icalendar/ical.lib.php');

class iCalendar {

    var $days;
	var $organizer;
	var $categories;
	var $attendees;
	var $fb_times;
	var $alarm;
	var $ex_dates;

	function iCalendar() {  
	
      $this->days = (array) array (2,3);
      $this->organizer = (array) array('Kurt', 'kurt2ww@flaimo.com');
      $this->categories = array('Freetime','Party');
      $this->attendees = (array) array(
                          'Michi' => 'flaimo2es@gmx.net,1',
                          'Felix' => ' ,2',
                          'Walter' => 'flaimo2es@gmx.net,3'
                          );  // Name => e-mail,role (see iCalEvent class)

      $this->fb_times = (array) array(
                          time()+23456 => time()+24456 . ',0', // timestamp start => 'timestamp end,status' (for status see class)
                          time()+93956 => time()+95956 . ',1'
                          );

      $this->alarm = (array) array(
                      0, // Action: 0 = DISPLAY, 1 = EMAIL, (not supported: 2 = AUDIO, 3 = PROCEDURE)
                      150,  // Trigger: alarm before the event in minutes
                      'Wake Up!', // Title
                      '...and go shopping', // Description
                      $this->attendees, // Array (key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON])
                      5, // Duration between the alarms in minutes
                      3  // How often should the alarm be repeated
                      );

       $this->ex_dates = (array) array(12345667,78643453);	
	
	}

    function event($evn) {
       switch ($evn) {				  
       }	
	}	

	function action($act) {
      global $__USERAGENT;	
	
      switch ($act) {	
  								
      }		
	  
	  $out = $this->do_the_job();  
	    
	  return ($out);
	}
	
	
	function do_the_job() {
      $iCal = (object) new iCal('', 0, ''); // (ProgrammID, Method (1 = Publish | 0 = Request), Download Directory)

      $iCal->addEvent(
                $this->organizer, // Organizer
                time()+3600, // Start Time
                time()+7200, // End Time
                'Vienna', // Location
                0, // Transparancy (0 = OPAQUE | 1 = TRANSPARENT)
                $this->categories, // Array with Strings
                'See homepage for more details...', // Description
                'Air and Style Snowboard Contest', // Title
                2, // Class (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
                $this->attendees, // Array (key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON])
                5, // Priority = 0-9
                5, // frequency: 0 = once, secoundly - yearly = 1-7
                10, // recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
                2, // Interval for frequency (every 2,3,4 weeks...)
                $this->days, // Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
                0, // Startday of the Week ( 0 = Sunday - 6 = Saturday)
                '', // exeption dates: Array with timestamps of dates that should not be includes in the recurring event
                $this->alarm,  // Sets the time in minutes an alarm appears before the event in the programm. no alarm if empty string or 0
                1, // Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
                'http://flaimo.com/', // optional URL for that event
                'de' // Language of the Strings
               );
    
       //$iCal->addEvent(add more events...);

       $iCal->addToDo(
               'Air and Style Snowboard Contest', // Title
               'See homepage for more details...', // Description
               'Vienna', // Location
               time()+3600, // Start time
               300, // Duration in minutes
               '', // End time
               45, // Percentage complete
               5, // Priority = 0-9
               1, // Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
               0, // Class (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
               $this->organizer, // Organizer
               $this->attendees, // Array (key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON])
               $this->categories, // Array with Strings
               time(), // Last Modification
               $this->alarm, // Sets the time in minutes an alarm appears before the event in the programm. no alarm if empty string or 0
               5, // frequency: 0 = once, secoundly - yearly = 1-7
               10, // recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
               1, // Interval for frequency (every 2,3,4 weeks...)
               $this->days, // Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
               0, // Startday of the Week ( 0 = Sunday - 6 = Saturday)
               '', // exeption dates: Array with timestamps of dates that should not be includes in the recurring event
               'http://flaimo.com/', // optional URL for that event
               'de' // Language of the Strings
              );


        $iCal->addFreeBusy(
                   time()+3600, // Start Time
                   time()+7200, // End Time
                   300, // Duration in minutes
                   $this->organizer, // Organizer
                   $this->attendees, // Array (key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON])
                   $this->fb_times, // Array with all the free/busy times
                   '' // optional URL for that event
                  );

 
        $iCal->addJournal(
                  'Air and Style Snowboard Contest', // Title
                  'See homepage for more details...', // Description
                  time()+3600, // Start time
                  time(), // Created
                  time(), // Last modification
                  1, // Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
                  0, // Class (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
                  $this->organizer, // Organizer
                  $this->attendees, // Array (key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON])
                  $this->categories, // Array with Strings
                  5, // frequency: 0 = once, secoundly - yearly = 1-7
                  10, // recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
                  1, // Interval for frequency (every 2,3,4 weeks...)
                  $this->days, // Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
                  0, // Startday of the Week ( 0 = Sunday - 6 = Saturday)
                  $this->ex_dates, // exeption dates: Array with timestamps of dates that should not be includes in the recurring event
                  'http://flaimo.com/', // optional URL for that event
                  'de' // Language of the Strings
                  );


         $out = $iCal->outputFile('ics'); // output file as ics (xcs and rdf possible)	
		 
		 return ($out);
	}

	
	
  /*  function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewsmdr($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7]);

	   return ($out);
	}		
	
    function viewsmdr($id,$f1,$f2,$f3,$f4,$f5,$f6,$f7) {
	   global $a;
	   
	   $link = seturl("t=smdr&a=$f1&g=&p=$p" , $f1);
	   $link_disa = seturl("t=smdr&a=$f1&g=$f7&p=$p" , $f7);	   
							  
	   $data[] = $id;
	   $attr[] = "left;10%";
	   $data[] = $link;   
	   $attr[] = "left;12%";
	   $data[] = $f2;   
	   $attr[] = "left;12%";
	   $data[] = str_replace(":","/",$f3);    
	   $attr[] = "left;12%";
	   $data[] = $f4;   
	   $attr[] = "left;12%";
	   $data[] = $f5;   
	   $attr[] = "left;12%";	   
	   $data[] = $f6 . "&nbsp";   
	   $attr[] = "left;12%";	
	   
	   if (strstr($f7,"DISA")) $data[] = $link_disa;
	                      else $data[] = $f7 . "&nbsp";   
	   $attr[] = "left;12%";
	   
	   $myarticle = new window('',$data,$attr);
	   
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   global $t,$p,$a,$g,$sort;
	
		             $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=0",  localize('_f0',getlocal()) );
					 $attr[] = "left;10%";							  
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_f1',getlocal()) );
					 $attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_f2',getlocal()) );
					 $attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_f3',getlocal()) );
					 $attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=4" , localize('_f4',getlocal()) );
					 $attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=5" , localize('_f5',getlocal()) );
					 $attr[] = "left;12%";
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=6" , localize('_f6',getlocal()) );
					 $attr[] = "left;12%";					 
					 $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=7" , localize('_f7',getlocal()) );
					 $attr[] = "left;12%";					 

					 $mytitle = new window('',$data,$attr);
					 $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
					 unset ($data);
					 unset ($attr);	
	   
	   return ($out);
	}	*/	
	
};
}
//}
//else die("DATABASE DPC REQUIRED!");
?>