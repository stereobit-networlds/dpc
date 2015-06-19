<?php
/**
* We need the child class
*/
include_once('iCalEvent.lib.php');

/**
* We need the child class
*/
include_once('iCalToDo.lib.php');

/**
* We need the child class
*/
include_once('iCalFreeBusy.lib.php');

/**
* We need the child class
*/
include_once('iCalJournal.lib.php');

/**
* Create a iCalendar file for download
*
* $iCal = new iCal('', 0, '');
* $iCal->addEvent(...);
* $iCal->addToDo(...);
* ...
* $iCal->outputFile('ics'); // output file as isc (xcs and rdf possible)
*
* Date/Time is stored with an absolute "z" value, which means that the
* calendar programm should import the time 1:1 not regarding timezones and
* Daylight Saving Time. MS Outlook imports "z" dates wrong, so you have to
* "correct" the dates BEFORE you add a new event.
* Also if you have an event series and not a single event, you have to use
* "File >> Import" in Outlook to import the whole series and not just the
* first date.
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package iCalendar Everything to generate simple iCal files
* @version 1.021
*/
class iCal
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/

  /**
  * Programm ID for the File
  *
  * @var (string) $prodid
  * @access private
  */ 
  var $prodid;
  
  /**
  * Array with all the iCalEvent objects
  *
  * @var array 
  * @access private  
  */ 
  var $icalevents;

  /**
  * Array with all the iCalToDo objects
  *
  * @var array 
  * @access private  
  */ 
  var $icaltodos;
 
  /**
  * Array with all the freebusy objects
  *
  * @var array 
  * @access private  
  */ 
  var $icalfbs;
  
  /**
  * Array with all the journal objects
  *
  * @var array 
  * @access private  
  */ 
  var $icaljournals;

  
  /**
  * ID number for the event array
  *
  * @var int
  * @access private
  */ 
  var $eventid;
  
  /**
  * ID number for the todo array
  *
  * @var int
  * @access private
  */ 
  var $todoid;
  
  /**
  * ID number for the freebusy array
  *
  * @var int
  * @access private
  */ 
  var $fbid;
  
  /**
  * ID number for the journal array
  *
  * @var int
  * @access private
  */ 
  var $journalid;
  
  /**
  * Output string to be written in the iCal file
  *
  * @var string
  * @access private
  */ 
  var $output;

  /**
  * Format of the output (ics, xcs, rdf)
  *
  * @var string
  * @access private
  */
  var $output_format;
    
  /**
  * Download directory where iCal file will be saved
  *
  * @var string
  * @access private
  */ 
  var $download_dir;
  
  /**
  * Filename for the iCal file to be saved
  *
  * @var string
  * @access private
  */ 
  var $events_filename;
  
  /**
  * Method: PUBLISH (1) or REQUEST (0)
  *
  * @var int
  * @access private
  */ 
  var $method;

  /**
  * Time the entry was created (iCal format)
  *
  * @var string
  * @access private
  */
  var $ical_timestamp;
  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/

  /**
  * Constructor
  *
  * Only job is to set all the variablesnames
  *
  * @param (string) $prodid  ID code for the iCal file (see setProdID)
  * @param (int) $method  PUBLISH (1) or REQUEST (0)
  * @param (string) $downloaddir
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function iCal($prodid = '', $method = 1, $downloaddir = '') 
    {
    $this->setiCalTimestamp();
    $this->checkClass('iCalEvent', __LINE__);
    $this->download_dir = (string) ((strlen(trim($downloaddir)) > 0) ? $downloaddir : 'icaldownload');
    $this->events_filename = (string) time() . '.ics';
    $this->eventid = (int) 0;
    $this->todoid = (int) 0;
    $this->fbid = (int) 0;
    $this->journalid = (int) 0;
    $this->setProdID($prodid);
    $this->setMethod($method);
    $this->icalevents = (array) array();
    $this->icaltodos = (array) array();
    $this->icalfbs = (array) array();
    $this->icaljournals = (array) array();
    } // end constructor
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
 
 
  /**
  * Encodes a string for QUOTE-PRINTABLE
  *
  * @param (string) $quotprint  String to be encoded
  * @return (string)  Encodes string
  * @access private
  * @since 1.001 - 2002/10/19
  * @author Harald Huemer <harald.huemer@liwest.at>
  */
  function quotedPrintableEncode($quotprint = '')
    {
    /*
    //beim Mac Umlaute nicht kodieren !!!! sonst Fehler beim Import
    if ($progid == 3)
      {
      $quotprintenc = preg_replace("~([\x01-\x1F\x3D\x7F-\xBF])~e", "sprintf('=%02X', ord('\\1'))", $quotprint);
      return($quotprintenc);
      }
    //bei Windows und Linux alle Sonderzeichen kodieren
    else
      {*/
    $quotprint = (string) str_replace('\r\n',chr(13) . chr(10),$quotprint);
    $quotprint = (string) str_replace('\n',chr(13) . chr(10),$quotprint);
    $quotprint = (string) preg_replace("~([\x01-\x1F\x3D\x7F-\xFF])~e", "sprintf('=%02X', ord('\\1'))", $quotprint);
    $quotprint = (string) str_replace('\=0D=0A','=0D=0A',$quotprint);
    return (string) $quotprint;
    } // end function
 

 
  /**
  * Checks if the download directory exists, else trys to create it
  *
  * @return (boolean)
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function checkDownloadDir()
    {
    if (!is_dir($this->download_dir))
      {
      return (boolean) ((!mkdir($this->download_dir, 0700)) ? false : true);
      }
    else
      {
      return (boolean) true;
      } // end if
    } // end function  

  
  /**
  * Returns string with the status of an attendee
  *
  * @param (int) $role
  * @return (string) $roles Status
  * @access private
  * @since 1.001 - 2002/10/10   
  */
  function getAttendeeRole($role = 2)
    {
    $roles = (array) array('CHAIR','REQ-PARTICIPANT','OPT-PARTICIPANT','NON-PARTICIPANT');
    return (string) ((array_key_exists($role, $roles)) ? $roles[$role] : $roles[2]);
    } // end function
  
  /**
  * Set $prodid variable
  *
  * @param (string) $prodid
  * @return (void)
  * @see getProdID(), $prodid
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setProdID($prodid = '')
    {
    $this->prodid = (string) ((strlen(trim($prodid)) > 0) ? $prodid : '-//flaimo.com//iCal Class MIMEDIR//EN');
    } // end function
    
  /**
  * Get $prodid variable
  *
  * @return (string) $prodid
  * @see setProdID(), $prodid
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getProdID()
    {
    return (string) $this->prodid;
    } // end function
  
  
  /**
  * Set $method variable
  *
  * @param (int) $method
  * @return (void)
  * @see getMethod(), $method
  * @access private
  * @since 1.001 - 2002/10/10   
  */
  function setMethod($method = 1)
    {
    $this->method = (int) ((is_int($method) && preg_match('(^([0-1]{1})$)', $method)) ? $method : 1);
    } // end function

    
  /**
  * Get $method variable
  *
  * @return (string) $methods
  * @see setMethod(), $methods
  * @access public
  * @since 1.001 - 2002/10/10   
  */
  function getMethod()
    {
    $methods = (array) array('REQUEST','PUBLISH');
    return (string) ((array_key_exists($this->method, $methods)) ? $methods[$this->method] : $methods[1]);
    } // end function

  
  /**
  * Set $ical_timestamp variable
  *
  * @return (void)
  * @see getiCalTimestamp(), $ical_timestamp
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setiCalTimestamp()
    {
    $this->ical_timestamp = (string) date('Ymd\THi00\Z',time());
    } // end function

  /**
  * Get $ical_timestamp variable
  *
  * @return (string) $ical_timestamp
  * @see setiCalTimestamp(), $ical_timestamp
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getiCalTimestamp()
    {
    return (string) $this->ical_timestamp;
    } // end function
  
  /**
  * Get class name
  *
  * @param (int) $int
  * @return (string) $classes
  * @access public
  * @since 1.000 - 2002/10/10   
  */ 
  function &getClassName($int = 0)
    {
    $classes = (array) array('PRIVATE','PUBLIC','CONFIDENTIAL');
    return (string) ((array_key_exists($int, $classes)) ? $classes[$int] : $classes[0]);
    } // end function    

  /**
  * Get status name
  *
  * @param (int) $int
  * @return (string) $statuscode
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function &getStatusName($int = 0)
    {
    $statuscode = (array) array('TENTATIVE','CONFIRMED','CANCELLED');
    return (string) ((array_key_exists($int, $statuscode)) ? $statuscode[$int] : $statuscode[0]);
    } // end function
  
  /**
  * Get frequency name
  *
  * @return (string) $frequencies
  * @see setFrequency(), $frequencies
  * @access public
  * @since 1.010 - 2002/10/26   
  */ 
   function &getFrequencyName($int = 0)
    {
    $frequencies = (array) array('ONCE','SECONDLY','MINUTELY','HOURLY','DAILY','WEEKLY','MONTHLY','YEARLY');
    return (string) ((array_key_exists($int, $frequencies)) ? $frequencies[$int] : $frequencies[0]);
    } // end function   
  
  
  
  
  /**
  * Adds a new Event Object to the Events Array
  *
  * @param (array) $organizer  The organizer - use array('Name', 'name@domain.com')
  * @param (int) $start  Start time for the event (timestamp)
  * @param (int) $end  Start time for the event (timestamp)
  * @param (string) $location  Location
  * @param (int) $transp  Transparancy (0 = OPAQUE | 1 = TRANSPARENT)
  * @param (array) $categories  Array with Strings (example: array('Freetime','Party'))
  * @param (string) $description  Description
  * @param (string) $summary  Title for the event
  * @param (int) $class  (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
  * @param (array) $attendees  key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  * @param (int) $prio  riority = 0-9
  * @param (int) $frequency  frequency: 0 = once, secoundly - yearly = 1-7
  * @param (mixed) $rec_end  recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
  * @param (int) $interval  Interval for frequency (every 2,3,4 weeks...)
  * @param (string) $days  Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
  * @param (string) $weekstart  Startday of the Week ( 0 = Sunday - 6 = Saturday)
  * @param (string) $exept_dates  exeption dates: Array with timestamps of dates that should not be includes in the recurring event
  * @param (int) $alarm  Array with all the alarm information, "''" for no alarm
  * @param (int) $status  Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
  * @param (string) $url  optional URL for that event
  * @param (string) $language  Language of the strings used in the event (iso code)
  * @return (void)
  * @see getEvent(), iCalEvent::iCalEvent()
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function addEvent($organizer, $start, $end, $location, $transp, $categories, $description, $summary, $class, $attendees, $prio, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $alarm, $status, $url, $language)
    {
    $event = (object) new iCalEvent($organizer, $start, $end, $location, $transp, $categories, $description, $summary, $class, $attendees, $prio, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $alarm, $status, $url, $language);
    $this->icalevents[$this->eventid++] = $event;
    unset($event);
    } // end function
  
  /**
  * Fetches an event from the array by the ID number
  *
  * @param (int) $id
  * @return (mixed)
  * @see addEvent(), iCalEvent::iCalEvent()
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function &getEvent($id = 0)
    {
    if (count($this->icalevents) < 1)
      {
      return (string) 'No Dates found';
      }
    elseif (is_int($id) && array_key_exists($id, $this->icalevents))
      {
      return (object) $this->icalevents[$id];
      }
    else
      {
      return (object) $this->icalevents[0];
      } // end if
    } // end function  
  
  /**
  * Returns the array with the iCal Event Objects
  *
  * @return (array) $icalevents
  * @see addEvent(), getEvent()
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function &getEvents()
    {
    return (array) $this->icalevents;
    } // end function  

  /**
  * Deletes an event-object from the event-array
  *
  * @return (boolean) $success
  * @see addEvent()
  * @access public
  * @since 1.011 - 2002/12/21
  */
  function deleteEvent($id = 0)
    {
    if (array_key_exists($id, $this->icalevents))
      {
      $this->icalevents[$id] = '';
      $event_keys = (array) array_keys($this->getEvents());
      
      foreach ($event_keys as $key)
        {
        if (strlen(trim($this->icalevents[$key])) > 0)
          {
          $temp_array[$key] = $this->icalevents[$key];
          } // end if
        } // end foreach

      $this->icalevents = (array) $temp_array;
      unset($temp_array);
      unset($event_keys);
      return (boolean) true;
      }
    else
      {
      return (boolean) false;
      } // end if
    } // end function

    
  /**
  * Adds a new ToDo Object to the ToDo Array
  *
  * @param (string) $summary  Title for the event
  * @param (string) $description  Description
  * @param (string) $location  Location
  * @param (int) $start  Start time for the event (timestamp)
  * @param (int) $duration  Duration of the todo in minutes
  * @param (int) $end  Start time for the event (timestamp)
  * @param (int) $percent  The percent completion of the ToDo
  * @param (int) $prio  riority = 0-9
  * @param (int) $status  Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
  * @param (int) $class  (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
  * @param (array) $organizer  The organizer - use array('Name', 'name@domain.com')
  * @param (array) $attendees  key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  * @param (array) $categories  Array with Strings (example: array('Freetime','Party'))
  * @param (int) $last_mod  Last modification of the to-to (timestamp)
  * @param (array) $alarm  Array with all the alarm information, "''" for no alarm
  * @param (int) $frequency  frequency: 0 = once, secoundly - yearly = 1-7
  * @param (mixed) $rec_end  recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
  * @param (int) $interval  Interval for frequency (every 2,3,4 weeks...)
  * @param (string) $days  Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
  * @param (string) $weekstart  Startday of the Week ( 0 = Sunday - 6 = Saturday)
  * @param (string) $exept_dates  exeption dates: Array with timestamps of dates that should not be includes in the recurring event
  * @param (string) $url  optional URL for that event
  * @param (string) $lang  Language of the strings used in the event (iso code)
  * @return (void)
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function addToDo($summary, $description, $location, $start, $duration, $end, $percent, $prio, $status, $class, $organizer, $attendees, $categories, $last_mod, $alarm, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $url, $lang)
    {
    $todo = (object) new iCalToDo($summary, $description, $location, $start, $duration, $end, $percent, $prio, $status, $class, $organizer, $attendees, $categories, $last_mod, $alarm, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $url, $lang);
    $this->icaltodos[$this->todoid++] = $todo;
    unset($todo);
    } // end function 
    
  /**
  * Fetches an event from the array by the ID number
  *
  * @param (int) $id
  * @return (mixed)
  * @see addToDo(), iCalToDo::iCalToDo()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getToDo($id = 0)
    {
    if (count($this->icaltodos) < 1)
      {
      return (string) 'No ToDos found';
      }
    elseif (is_int($id) && array_key_exists($id, $this->icaltodos))
      {
      return (object) $this->icaltodos[$id];
      }
    else
      {
      return (object) $this->icaltodos[0];
      } // end if
    } // end function  
  
  /**
  * Returns the array with the iCal ToDo Objects
  *
  * @return (array) $icaltodos
  * @see addToDo(), getToDo()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getDoTos()
    {
    return (array) $this->icaltodos;
    } // end function  

  /**
  * Deletes an todo-object from the todo-array
  *
  * @return (boolean) $success
  * @see addToDo()
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function deleteToDo($id = 0)
    {
    if (array_key_exists($id, $this->icaltodos))
      {
      $this->icaltodos[$id] = '';
      $todo_keys = (array) array_keys($this->getToDos());
      
      foreach ($todo_keys as $key)
        {
        if (strlen(trim($this->icaltodos[$key])) > 0)
          {
          $temp_array[$key] = $this->icaltodos[$key];
          } // end if
        } // end foreach

      $this->icaltodos = (array) $temp_array;
      unset($temp_array);
      unset($todo_keys);
      return (boolean) true;
      }
    else
      {
      return (boolean) false;
      } // end if
    } // end function
 
 
  /**
  * Adds a new FreeBusy Object to the ToDo Array
  *
  * @param (int) $start  Start time for fb (timestamp)
  * @param (int) $end  Start time for fb (timestamp)
  * @param (int) $duration  Duration of the fb in minutes
  * @param (array) $organizer  The organizer - use array('Name', 'name@domain.com')
  * @param (array) $attendees  key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  * @param (array) $fb_times  key = timestamp (starting point), value = minutes, secound value = status (0 = FREE, 1 = BUSY, 2 = BUSY-UNAVAILABLE, 3 = BUSY-TENTATIVE)
  * @param (string) $url  optional URL for that event
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function addFreeBusy($start, $end, $duration, $organizer, $attendees, $fb_times, $url)
    {
    $fb = (object) new iCalFreeBusy($start, $end, $duration, $organizer, $attendees, $fb_times, $url);
    $this->icalfbs[$this->fbid++] = $fb;
    unset($fb);
    } // end function 
    
  /**
  * Fetches an freebusy from the array by the ID number
  *
  * @param (int) $id
  * @return (mixed)
  * @see addFreeBusy(), iCalFreeBusy::iCalFreeBusy()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getFreeBusy($id = 0)
    {
    if (count($this->icalfbs) < 1)
      {
      return (string) 'No FreeBusys found';
      }
    elseif (is_int($id) && array_key_exists($id, $this->icalfbs))
      {
      return (object) $this->icalfbs[$id];
      }
    else
      {
      return (object) $this->icalfbs[0];
      } // end if
    } // end function  
  
  /**
  * Returns the array with the iCal ToDo Objects
  *
  * @return (array) $icaltodos
  * @see addFreeBusy(), getFreeBusy()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getFreeBusys()
    {
    return (array) $this->icalfbs;
    } // end function  

  /**
  * Deletes an todo-object from the todo-array
  *
  * @return (boolean) $success
  * @see addFreeBusy()
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function deleteFreeBusy($id = 0)
    {
    if (array_key_exists($id, $this->icalfbs))
      {
      $this->icalfbs[$id] = '';
      $fb_keys = (array) array_keys($this->getFreeBusys());
      
      foreach ($fb_keys as $key)
        {
        if (strlen(trim($this->icalfbs[$key])) > 0)
          {
          $temp_array[$key] = $this->icalfbs[$key];
          } // end if
        } // end foreach

      $this->icalfbs = (array) $temp_array;
      unset($temp_array);
      unset($fb_keys);
      return (boolean) true;
      }
    else
      {
      return (boolean) false;
      } // end if
    } // end function
 
  /**
  * Adds a new Journal Object to the ToDo Array
  *
  * @param (string) $summary  Title for the event
  * @param (string) $description  Description
  * @param (int) $start  Start time for the event (timestamp)
  * @param (int) $created  Creation date for the event (timestamp)
  * @param (int) $last_mod  Last modification date for the event (timestamp)
  * @param (int) $status  Status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
  * @param (int) $class  (0 = PRIVATE | 1 = PUBLIC | 2 = CONFIDENTIAL)
  * @param (array) $organizer  The organizer - use array('Name', 'name@domain.com')
  * @param (array) $attendees  key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  * @param (array) $categories  Array with Strings (example: array('Freetime','Party'))
  * @param (int) $frequency  frequency: 0 = once, secoundly - yearly = 1-7
  * @param (mixed) $rec_end  recurrency end: ('' = forever | integer = number of times | timestring = explicit date)
  * @param (int) $interval  Interval for frequency (every 2,3,4 weeks...)
  * @param (string) $days  Array with the number of the days the event accures (example: array(0,1,5) = Sunday, Monday, Friday
  * @param (string) $weekstart  Startday of the Week ( 0 = Sunday - 6 = Saturday)
  * @param (string) $exept_dates  exeption dates: Array with timestamps of dates that should not be includes in the recurring event
  * @param (string) $url  optional URL for that event
  * @param (string) $lang  Language of the strings used in the event (iso code)
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function addJournal($summary, $description, $start, $created, $last_mod, $status, $class, $organizer, $attendees, $categories, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $url, $lang)
    {
    $journal = (object) new iCalJournal($summary, $description, $start, $created, $last_mod, $status, $class, $organizer, $attendees, $categories, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $url, $lang);
    $this->icaljournals[$this->journalid++] = $journal;
    unset($journal);
    } // end function 
    
  /**
  * Fetches an journal from the array by the ID number
  *
  * @param (int) $id
  * @return (mixed)
  * @see addJournal(), iCalJournal::iCalJournal()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getJournal($id = 0)
    {
    if (count($this->icaljournals) < 1)
      {
      return (string) 'No Journals found';
      }
    elseif (is_int($id) && array_key_exists($id, $this->icaljournals))
      {
      return (object) $this->icaljournals[$id];
      }
    else
      {
      return (object) $this->icaljournals[0];
      } // end if
    } // end function  
  
  /**
  * Returns the array with the iCal journal objects
  *
  * @return (array) $icaljournals
  * @see addJournal(), getJournal()
  * @access public
  * @since 1.020 - 2002/12/24   
  */
  function &getJournals()
    {
    return (array) $this->icaljournals;
    } // end function  

  /**
  * Deletes an journal object from the journal-array
  *
  * @return (boolean) $success
  * @see addJournal()
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function deleteJournal($id = 0)
    {
    if (array_key_exists($id, $this->icaljournals))
      {
      $this->icaljournals[$id] = '';
      $journal_keys = (array) array_keys($this->getJournals());
      
      foreach ($journal_keys as $key)
        {
        if (strlen(trim($this->icaljournals[$key])) > 0)
          {
          $temp_array[$key] = $this->icaljournals[$key];
          } // end if
        } // end foreach

      $this->icaljournals = (array) $temp_array;
      unset($temp_array);
      unset($journal_keys);
      return (boolean) true;
      }
    else
      {
      return (boolean) false;
      } // end if
    } // end function

    
  /**
  * Generates the string for the alarm
  *
  * @param (object) $alarm
  * @param (string) $format  ics | xcs
  * @return (void)
  * @see generateOutput()
  * @access private
  * @since 1.021 - 2002/12/24  
  */
  function generateAlarmOutput($alarm, $format = 'ics')
    {
    $output = (string) '';
    if (is_object($alarm))
      {
      if ($format == 'ics')
        {
        if ($alarm->getTrigger() > 0)
          {
          $output .= (string) "BEGIN:VALARM\r\n";
          $output .= (string) "ACTION:" . $alarm->getAction() . "\r\n";
          $output .= (string) "TRIGGER:-PT" . $alarm->getTrigger() . "M\r\n";
          
          if ($alarm->getAction() == 'DISPLAY' || $alarm->getAction() == 'EMAIL')
            {
            $output .= (string) "DESCRIPTION" . $alarm->getLanguage() . ":" . $alarm->getDescription() . "\r\n";
            } // end if
          
          if ($alarm->getAction() == 'DISPLAY' || $alarm->getAction() == 'EMAIL')
            {
            $output .= (string) "SUMMARY" . $alarm->getLanguage() . ":" . $alarm->getSummary() . "\r\n";
            } // end if
          
          if ($alarm->getDuration() != 0 && $alarm->getRepeat() != 0)
            {
            $output .= (string) "DURATION:" . $alarm->getDuration() . "\r\n";
            $output .= (string) "REPEAT:" . $alarm->getRepeat() . "\r\n";
            } // end if
          
          $output .= (string) $this->generateAttendeesOutput($alarm->getAttendees(), $format);
          $output .= (string) "END:VALARM\r\n";
          }
        } // end if
      elseif ($format == 'xcs')
        {
        if ($alarm->getTrigger() > 0)
          {
          $output .= (string) '<valarm>';
          $output .= (string) '<action>' . $alarm->getAction() . '</action>';
          $output .= (string) '<trigger>-PT' . $alarm->getTrigger() . '</trigger>';
          
          if ($alarm->getAction() == 'DISPLAY' || $alarm->getAction() == 'EMAIL')
            {
            $output .= (string) '<description>' . $alarm->getDescription() . '</description>';
            } // end if
        
          if ($alarm->getAction() == 'DISPLAY' || $alarm->getAction() == 'EMAIL')
            {
            $output .= (string) '<summary>' . $alarm->getSummary() . '</summary>';
            } // end if
          
          if ($alarm->getDuration() != 0 && $alarm->getRepeat() != 0)
            {
            $output .= (string) '<duration>' . $alarm->getDuration() . '</duration>';
            $output .= (string) '<repeat>' . $alarm->getRepeat() . '</repeat>';
            } // end if
          
          $output .= (string) $this->generateAttendeesOutput($alarm->getAttendees(), $format);
          $output .= (string) '</valarm>';          
          } // end if
        } // end if
      } // end if
    return (string) $output;  
    } // end function   

  /**
  * Generates the string for the attendees
  *
  * @param (array) $attendees
  * @param (string) $format  ics | xcs
  * @return (void)
  * @see generateOutput()
  * @access private
  * @since 1.021 - 2002/12/24  
  */
  function generateAttendeesOutput($attendees, $format = 'ics')
    {
    $output = (string) '';
    if ($this->method == 0 && count($attendees) > 0) 
      {
      if ($format == 'ics')
        {
        if (count($attendees) > 0)
          {
          foreach ($attendees as $name => $data)
            {
            $values = (array) explode(',',$data);
            $email = (string) $values[0];
            
            if (strlen(trim($email)) > 5)
              {
              $role = (int) $values[1];
              $output .= (string) "ATTENDEE;ROLE=" . $this->getAttendeeRole($role) . ";CN=" . $name . ":MAILTO:" . $email . "\r\n";
              } // end if
            } // end foreach
          } // end if
        }
      elseif ($format == 'xcs')
        {
        if (count($attendees) > 0)
          {
          foreach ($attendees as $name => $data)
            {
            $values = (array) explode(',',$data);
            $email = (string) $values[0];
            
            if (strlen(trim($email)) > 5)
              {
              $role = (int) $values[1];
              $output .= (string) '<attendee cn="' . $name . '" role="' . $this->getAttendeeRole($role) . '">MAILTO:' . $email . '</attendee>';
              } // end if
            } // end foreach
          } // end if
        } // end if
      } // end if
    return (string) $output;
    } // end function
    
  /**
  * Generates the string to be written in the file later on
  *
  * you can choose between ics, xcs or rdf format. 
  * only ics has been tested; the other two are not, or are not 
  * finished coded yet
  *
  * @param (string) $format  ics | xcs | rdf
  * @return (void)
  * @see getOutput(), writeFile()
  * @access public
  * @since 1.001 - 2002/10/10   
  */
  function generateOutput($format = 'ics')
    {
    $this->output_format = (string) $format;
    
    if ($this->output_format == 'ics')
      {
      $this->output  = (string) "BEGIN:VCALENDAR\r\n";
      $this->output .= (string) "PRODID:" . $this->prodid . "\r\n";
      $this->output .= (string) "VERSION:2.0\r\n";                         
      $this->output .= (string) "METHOD:" . $this->getMethod() . "\r\n";                         

      $eventkeys = (array) array_keys($this->icalevents);
      foreach ($eventkeys as $id)
        {
        $this->output .= (string) "BEGIN:VEVENT\r\n"; 
        $event =& $this->icalevents[$id];
        $this->output .= (string) $this->generateAttendeesOutput($event->getAttendees(), $format);
        
        if (strlen(trim($event->getOrganizerMail())) > 0)
          {
          $name = '';
          if (strlen(trim($event->getOrganizerName())) > 0)
            {
            $name = (string) ";CN=" . $event->getOrganizerName();
            } // end if

          $this->output .= (string) "ORGANIZER" . $name . ":MAILTO:" . $event->getOrganizerMail() . "\r\n";
          } // end if

        $this->output .= (string) "DTSTART:" . $event->getStartDate() . "\r\n";
        $this->output .= (string) "DTEND:" . $event->getEndDate() . "\r\n";
        
        if ($event->getFrequency() > 0)
          {
          $this->output .= (string) "RRULE:FREQ=" . $this->getFrequencyName($event->getFrequency());
          
          if (is_string($event->getRecEnd()))
            {
            $this->output .= (string) ";UNTIL=" . $event->getRecEnd();
            }
          elseif (is_int($event->getRecEnd()))
            {
            $this->output .= (string) ";COUNT=" . $event->getRecEnd();
            } // end if
  
          $this->output .= (string) ";INTERVAL=" . $event->getInterval() . ";BYDAY=" . $event->getDays() . ";WKST=" . $event->getWeekStart() . "\r\n";
          } // end if
        
        if (strlen(trim($event->getExeptDates())) > 0)
          {
          $this->output .= (string) "EXDATE:" . $event->getExeptDates() . "\r\n";
          } // end if
        
        if (strlen(trim($event->getLocation())) > 0)
          {
          $this->output .= (string) "LOCATION" . $event->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($event->getLocation()) . "\r\n";
          } // end if

        $this->output .= (string) "TRANSP:" . $event->getTransp() . "\r\n";
        $this->output .= (string) "SEQUENCE:" . $event->getSequence() . "\r\n";
        $this->output .= (string) "UID:" . $event->getUID() . "\r\n";
        $this->output .= (string) "DTSTAMP:" . $this->ical_timestamp . "\r\n";
        
        if (strlen(trim($event->getCategories())) > 0)
          {
          $this->output .= (string) "CATEGORIES" . $event->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($event->getCategories()) . "\r\n";
          } // end if
        
        if (strlen(trim($event->getDescription())) > 0)
          {
          $this->output .= (string) "DESCRIPTION" . $event->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . str_replace('\n', '=0D=0A=',str_replace('\r', '=0D=0A=', $this->quotedPrintableEncode($event->getDescription()))) . "\r\n";
          } // end if
        
        $this->output .= (string) "SUMMARY" . $event->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($event->getSummary()) . "\r\n";
        $this->output .= (string) "PRIORITY:" . $event->getPriority() . "\r\n";
        $this->output .= (string) "CLASS:" . $this->getClassName($event->getClass()) . "\r\n";
  
        if (strlen(trim($event->getURL())) > 0)
          {
          $this->output .= (string) "URL:" . $event->getURL() . "\r\n";
          } // end if
  
        if (strlen(trim($event->getStatus())) > 0)
          {
          $this->output .= (string) "STATUS:" . $this->getStatusName($event->getStatus()) . "\r\n";
          } // end if
        
        $this->output .= (string) $this->generateAlarmOutput($event->getAlarm(), $format);
        $this->output .= (string) "END:VEVENT\r\n";
        } // end foreach
      
      
      
      $todokeys = (array) array_keys($this->icaltodos);
      foreach ($todokeys as $id)
        {
        $this->output .= (string) "BEGIN:VTODO\r\n"; 
        $todo =& $this->icaltodos[$id];
        $this->output .= (string) $this->generateAttendeesOutput($todo->getAttendees(), $format);
        
        if (strlen(trim($todo->getOrganizerMail())) > 0)
          {
          $name = '';
          if (strlen(trim($todo->getOrganizerName())) > 0)
            {
            $name = (string) ";CN=" . $todo->getOrganizerName();
            } // end if

          $this->output .= (string) "ORGANIZER" . $name . ":MAILTO:" . $todo->getOrganizerMail() . "\r\n";
          } // end if

        $this->output .= (string) "SEQUENCE:" . $todo->getSequence() . "\r\n";
        $this->output .= (string) "UID:" . $todo->getUID() . "\r\n";
        $this->output .= (string) "DTSTAMP:" . $this->ical_timestamp . "\r\n";
        
        if (strlen(trim($todo->getCategories())) > 0)
          {
          $this->output .= (string) "CATEGORIES" . $todo->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($todo->getCategories()) . "\r\n";
          } // end if
        
        if (strlen(trim($todo->getDescription())) > 0)
          {
          $this->output .= (string) "DESCRIPTION" . $todo->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . str_replace('\n', '=0D=0A=',str_replace('\r', '=0D=0A=', $this->quotedPrintableEncode($todo->getDescription()))) . "\r\n";
          } // end if
        
        $this->output .= (string) "SUMMARY" . $todo->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($todo->getSummary()) . "\r\n";
        $this->output .= (string) "PRIORITY:" . $todo->getPriority() . "\r\n";
        $this->output .= (string) "CLASS:" . $this->getClassName($todo->getClass()) . "\r\n";
  
        if (strlen(trim($todo->getLocation())) > 0)
          {
          $this->output .= (string) "LOCATION" . $todo->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($todo->getLocation()) . "\r\n";
          } // end if
  
        if (strlen(trim($todo->getURL())) > 0)
          {
          $this->output .= (string) "URL:" . $todo->getURL() . "\r\n";
          } // end if
  
        if (strlen(trim($todo->getStatus())) > 0)
          {
          $this->output .= (string) "STATUS:" . $this->getStatusName($todo->getStatus()) . "\r\n";
          } // end if
        
        if (strlen(trim($todo->getPercent())) > 0 && $todo->getPercent() > 0)
          {
          $this->output .= (string) "PERCENT-COMPLETE:" . $todo->getPercent() . "\r\n";
          } // end if
        
        if (strlen(trim($todo->getDuration())) > 0 && $todo->getDuration() > 0)
          {
          $this->output .= (string) "DURATION:PT" . $todo->getDuration() . "M\r\n";
          } // end if
       
        if (strlen(trim($todo->getLastMod())) > 0)
          {
          $this->output .= (string) "LAST-MODIFIED:" . $todo->getLastMod() . "\r\n";
          } // end if
       
        if (strlen(trim($todo->getStartDate())) > 0)
          {
          $this->output .= (string) "DTSTART:" . $todo->getStartDate() . "\r\n";
          } // end if
        
        if (strlen(trim($todo->getCompleted())) > 0)
          {
          $this->output .= (string) "COMPLETED:" . $todo->getCompleted() . "\r\n";
          } // end if
        
        if ($todo->getFrequency() != 'ONCE')
          {
          $this->output .= (string) "RRULE:FREQ=" . $todo->getFrequency();
          
          if (is_string($todo->getRecEnd()))
            {
            $this->output .= (string) ";UNTIL=" . $todo->getRecEnd();
            }
          elseif (is_int($todo->getRecEnd()))
            {
            $this->output .= (string) ";COUNT=" . $todo->getRecEnd();
            } // end if
  
          $this->output .= (string) ";INTERVAL=" . $todo->getInterval() . ";BYDAY=" . $todo->getDays() . ";WKST=" . $todo->getWeekStart() . "\r\n";
          } // end if
        
        if (strlen(trim($todo->getExeptDates())) > 0)
          {
          $this->output .= (string) "EXDATE:" . $todo->getExeptDates() . "\r\n";
          } // end if
        
        $this->output .= (string) $this->generateAlarmOutput($todo->getAlarm(), $format);
        $this->output .= (string) "END:VTODO\r\n";
        } // end foreach
    
    
      $journalkeys = (array) array_keys($this->icaljournals);
      foreach ($journalkeys as $id)
        {
        $this->output .= (string) "BEGIN:VJOURNAL\r\n"; 
        $journal =& $this->icaljournals[$id];
        $this->output .= (string) $this->generateAttendeesOutput($journal->getAttendees(), $format);
        
        if (strlen(trim($journal->getOrganizerMail())) > 0)
          {
          $name = '';
          if (strlen(trim($journal->getOrganizerName())) > 0)
            {
            $name = (string) ";CN=" . $journal->getOrganizerName();
            } // end if

          $this->output .= (string) "ORGANIZER" . $name . ":MAILTO:" . $journal->getOrganizerMail() . "\r\n";
          } // end if

        $this->output .= (string) "SEQUENCE:" . $journal->getSequence() . "\r\n";
        $this->output .= (string) "UID:" . $journal->getUID() . "\r\n";
        $this->output .= (string) "DTSTAMP:" . $this->ical_timestamp . "\r\n";
        
        if (strlen(trim($journal->getCategories())) > 0)
          {
          $this->output .= (string) "CATEGORIES" . $journal->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($journal->getCategories()) . "\r\n";
          } // end if
        
        if (strlen(trim($journal->getDescription())) > 0)
          {
          $this->output .= (string) "DESCRIPTION" . $journal->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . str_replace('\n', '=0D=0A=',str_replace('\r', '=0D=0A=', $this->quotedPrintableEncode($journal->getDescription()))) . "\r\n";
          } // end if
        
        $this->output .= (string) "SUMMARY" . $journal->getLanguage() . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($journal->getSummary()) . "\r\n";
        $this->output .= (string) "CLASS:" . $this->getClassName($journal->getClass()) . "\r\n";
  
  
        if (strlen(trim($journal->getURL())) > 0)
          {
          $this->output .= (string) "URL:" . $journal->getURL() . "\r\n";
          } // end if
  
        if (strlen(trim($journal->getStatus())) > 0)
          {
          $this->output .= (string) "STATUS:" . $this->getStatusName($journal->getStatus()) . "\r\n";
          } // end if
       
        if (strlen(trim($journal->getLastMod())) > 0)
          {
          $this->output .= (string) "LAST-MODIFIED:" . $journal->getLastMod() . "\r\n";
          } // end if
       
        if (strlen(trim($journal->getStartDate())) > 0)
          {
          $this->output .= (string) "DTSTART:" . $journal->getStartDate() . "\r\n";
          } // end if
        
        if (strlen(trim($journal->getCreated())) > 0)
          {
          $this->output .= (string) "CREATED:" . $journal->getCreated() . "\r\n";
          } // end if
        
        if ($journal->getFrequency() > 0)
          {
          $this->output .= (string) "RRULE:FREQ=" . $this->getFrequencyName($journal->getFrequency());
          
          if (is_string($journal->getRecEnd()))
            {
            $this->output .= (string) ";UNTIL=" . $journal->getRecEnd();
            }
          elseif (is_int($journal->getRecEnd()))
            {
            $this->output .= (string) ";COUNT=" . $journal->getRecEnd();
            } // end if
  
          $this->output .= (string) ";INTERVAL=" . $journal->getInterval() . ";BYDAY=" . $journal->getDays() . ";WKST=" . $journal->getWeekStart() . "\r\n";
          } // end if
        
        if (strlen(trim($journal->getExeptDates())) > 0)
          {
          $this->output .= (string) "EXDATE:" . $journal->getExeptDates() . "\r\n";
          } // end if
        
        $this->output .= (string) "END:VJOURNAL\r\n";
        } // end foreach

      
      $fbkeys = (array) array_keys($this->icalfbs);
      foreach ($fbkeys as $id)
        {
        $this->output .= (string) "BEGIN:VFREEBUSY\r\n"; 
        $fb =& $this->icalfbs[$id];
        $this->output .= (string) $this->generateAttendeesOutput($fb->getAttendees(), $format);
        
        if (strlen(trim($fb->getOrganizerMail())) > 0)
          {
          $name = '';
          if (strlen(trim($fb->getOrganizerName())) > 0)
            {
            $name = (string) ";CN=" . $fb->getOrganizerName();
            } // end if

          $this->output .= (string) "ORGANIZER" . $name . ":MAILTO:" . $fb->getOrganizerMail() . "\r\n";
          } // end if

        $this->output .= (string) "UID:" . $fb->getUID() . "\r\n";
        $this->output .= (string) "DTSTAMP:" . $this->ical_timestamp . "\r\n";
        
        if (strlen(trim($fb->getURL())) > 0)
          {
          $this->output .= (string) "URL:" . $fb->getURL() . "\r\n";
          } // end if
  
        if (strlen(trim($fb->getDuration())) > 0 && $fb->getDuration() > 0)
          {
          $this->output .= (string) "DURATION:PT" . $fb->getDuration() . "M\r\n";
          } // end if
       

        if (strlen(trim($fb->getStartDate())) > 0)
          {
          $this->output .= (string) "DTSTART:" . $fb->getStartDate() . "\r\n";
          } // end if
        
        if (strlen(trim($fb->getEndDate())) > 0)
          {
          $this->output .= (string) "DTEND:" . $fb->getEndDate() . "\r\n";
          } // end if


        if (count($fb->getFBTimes()) > 0)
          {
          foreach ($fb->getFBTimes() as $timestamp => $data)
            {
            $values = (array) explode(',',$data);
            $this->output .= (string) "FREEBUSY;FBTYPE=" . $values[1] . ":" . $timestamp . "/" . $values[0] . "\r\n";
            } // end foreach
          unset($values);  
          } // end if
        
        $this->output .= (string) "END:VFREEBUSY\r\n";
        } // end foreach
        
      $this->output .= (string) "END:VCALENDAR\r\n";
      } // end if ics
    elseif ($this->output_format == 'xcs')
      {
      $this->output  = (string) '<?xml version="1.0" encoding="UTF-8"?>';
      //$this->output  = (string) '<!DOCTYPE iCalendar PUBLIC "-//IETF//DTD iCalendar//EN" "http://www.ietf.org/internet-drafts/draft-dawson-ical-xml-dtd-02.txt">';
      $this->output .= (string) '<iCalendar>';
      
      if (count($this->icalevents) > 0)
        {      
        $this->output .= (string) '<vCalendar version="2.0" prodid="' . $this->prodid . '" method="' . $this->getMethod() . '">';
        $eventkeys = (array) array_keys($this->icalevents);
        foreach ($eventkeys as $id)
          {
          $this->output .= (string) '<vevent>'; 
          $event =& $this->icalevents[$id];
          $this->output .= (string) $this->generateAttendeesOutput($event->getAttendees(), $format);
          
          if (strlen(trim($event->getOrganizerMail())) > 0)
            {
            $name = '';
            if (strlen(trim($event->getOrganizerName())) > 0)
              {
              $name = (string) ' cn="' . $event->getOrganizerName() . '"';
              } // end if
  
            $this->output .= (string) '<organizer' . $name . '>MAILTO:' . $event->getOrganizerMail() . '</organizer>';
            } // end if
          
          $this->output .= (string) '<dtstart>' . $event->getStartDate() . '</dtstart>';
          $this->output .= (string) '<dtend>' . $event->getEndDate() . '</dtend>';
          
          if ($event->getFrequency() > 0)
            {
            $this->output .= (string) '<rrule>FREQ=' . $this->getFrequencyName($event->getFrequency());
            
            if (is_string($event->getRecEnd()))
              {
              $this->output .= (string) ";UNTIL=" . $event->getRecEnd();
              }
            elseif (is_int($event->getRecEnd()))
              {
              $this->output .= (string) ";COUNT=" . $event->getRecEnd();
              } // end if
    
            $this->output .= (string) ";INTERVAL=" . $event->getInterval() . ";BYDAY=" . $event->getDays() . ";WKST=" . $event->getWeekStart() . '</rrule>';
            } // end if
          
        if (strlen(trim($event->getExeptDates())) > 0)
          {
          $this->output .= (string) '<exdate>' . $event->getExeptDates() . '</exdate>';
          } // end if
          
          $this->output .= (string) '<location>' . $event->getLocation() . '</location>';
          $this->output .= (string) '<transp>' . $event->getTransp() . '</transp>';
          $this->output .= (string) '<sequence>' . $event->getSequence() . '</sequence>';
          $this->output .= (string) '<uid>' . $event->getUID() . '</uid>';
          $this->output .= (string) '<dtstamp>' . $this->ical_timestamp . '</dtstamp>';
          
          if (strlen(trim($event->getCategories())) > 0)
            {
            $this->output .= (string) '<categories>';
  
            foreach ($event->getCategoriesArray() as $item)
              {
              $this->output .= (string) '<item>' . $item . '</item>';
              } // end foreach
  
            $this->output .= (string) '</categories>';
            } // end if
          
          if (strlen(trim($event->getDescription())) > 0)
            {
            $this->output .= (string) '<description>' . $event->getDescription() . '</description>';
            } // end if
          
          $this->output .= (string) '<summary>' . $event->getSummary() . '</summary>';
          $this->output .= (string) '<priority>' . $event->getPriority() . '</priority>';
          $this->output .= (string) '<class>' . $this->getClassName($event->getClass()) . '</class>';
    
          if (strlen(trim($event->getURL())) > 0)
            {
            $this->output .= (string) '<url>' . $event->getURL() . '</url>';
            } // end if
    
          if (strlen(trim($event->getStatus())) > 0)
            {
            $this->output .= (string) '<status>' . $this->getStatusName($event->getStatus()) . '</status>';
            } // end if

          $this->output .= (string) $this->generateAlarmOutput($event->getAlarm(), $format);
          $this->output .= (string) '</vevent>';
          } // end foreach event
  
        $this->output .= (string) '</vCalendar>';
        } // end if count($this->icalevents) > 0
      

      if (count($this->icaltodos) > 0)
        {      
        $this->output .= (string) '<vCalendar version="2.0" prodid="' . $this->prodid . '" method="' . $this->getMethod() . '">';
        $todokeys = (array) array_keys($this->icaltodos);
        foreach ($todokeys as $id)
          {
          $this->output .= (string) '<vtodo>'; 
          $todo =& $this->icaltodos[$id];
          $this->output .= (string) $this->generateAttendeesOutput($todo->getAttendees(), $format);
          
          if (strlen(trim($todo->getOrganizerMail())) > 0)
            {
            $name = '';
            if (strlen(trim($todo->getOrganizerName())) > 0)
              {
              $name = (string) ' cn="' . $todo->getOrganizerName() . '"';
              } // end if
  
            $this->output .= (string) '<organizer' . $name . '>MAILTO:' . $todo->getOrganizerMail() . '</organizer>';
            } // end if
          
          if (strlen(trim($todo->getStartDate())) > 0)
            {
            $this->output .= (string) '<dtstart>' . $todo->getStartDate() . '</dtstart>';
            } // end if
          
          if (strlen(trim($todo->getCompleted())) > 0)
            {
            $this->output .= (string) '<completed>' . $todo->getCompleted() . '</completed>';
            } // end if      
          
          if (strlen(trim($todo->getDuration())) > 0 && $todo->getDuration() > 0)
            {
            $this->output .= (string) '<duration>PT' . $todo->getDuration() . 'M</duration>';
            } // end if             
          
          if (strlen(trim($todo->getLocation())) > 0)
            {
            $this->output .= (string) '<location>' . $todo->getLocation() . '</location>';
            } // end if      

          $this->output .= (string) '<sequence>' . $todo->getSequence() . '</sequence>';
          $this->output .= (string) '<uid>' . $todo->getUID() . '</uid>';
          $this->output .= (string) '<dtstamp>' . $this->ical_timestamp . '</dtstamp>';
          
          if (strlen(trim($todo->getCategories())) > 0)
            {
            $this->output .= (string) '<categories>';
  
            foreach ($todo->getCategoriesArray() as $item)
              {
              $this->output .= (string) '<item>' . $item . '</item>';
              } // end foreach
  
            $this->output .= (string) '</categories>';
            } // end if
          
          if (strlen(trim($todo->getDescription())) > 0)
            {
            $this->output .= (string) '<description>' . $todo->getDescription() . '</description>';
            } // end if
          
          $this->output .= (string) '<summary>' . $todo->getSummary() . '</summary>';
          $this->output .= (string) '<priority>' . $todo->getPriority() . '</priority>';
          $this->output .= (string) '<class>' . $this->getClassName($todo->getClass()) . '</class>';
    
          if (strlen(trim($todo->getURL())) > 0)
            {
            $this->output .= (string) '<url>' . $todo->getURL() . '</url>';
            } // end if
    
          if (strlen(trim($todo->getStatus())) > 0)
            {
            $this->output .= (string) '<status>' . $this->getStatusName($todo->getStatus()) . '</status>';
            } // end if
    
          if (strlen(trim($todo->getPercent())) > 0 && $todo->getPercent() > 0)
            {
            $this->output .= (string) '<percent>' . $todo->getPercent() . '</percent>';
            } // end if   
    
          if (strlen(trim($todo->getLastMod())) > 0)
            {
            $this->output .= (string) '<last-modified>' . $todo->getLastMod() . '</last-modified>';
            } // end if  
    
          if ($todo->getFrequency() > 0)
            {
            $this->output .= (string) '<rrule>FREQ=' . $this->getFrequencyName($todo->getFrequency());
            
            if (is_string($todo->getRecEnd()))
              {
              $this->output .= (string) ";UNTIL=" . $todo->getRecEnd();
              }
            elseif (is_int($todo->getRecEnd()))
              {
              $this->output .= (string) ";COUNT=" . $todo->getRecEnd();
              } // end if
    
            $this->output .= (string) ";INTERVAL=" . $todo->getInterval() . ";BYDAY=" . $todo->getDays() . ";WKST=" . $todo->getWeekStart() . '</rrule>';
            } // end if
    
          if (strlen(trim($todo->getExeptDates())) > 0)
            {
            $this->output .= (string) '<exdate>' . $todo->getExeptDates() . '</exdate>';
            } // end if
    
          $this->output .= (string) $this->generateAlarmOutput($todo->getAlarm(), $format);
          $this->output .= (string) '</vtodo>';
          } // end foreach event
  
        $this->output .= (string) '</vCalendar>';
        } // end if count($this->icaljournals) > 0

      if (count($this->icaljournals) > 0)
        {      
        $this->output .= (string) '<vCalendar version="2.0" prodid="' . $this->prodid . '" method="' . $this->getMethod() . '">';
        $journalkeys = (array) array_keys($this->icaljournals);
        foreach ($journalkeys as $id)
          {
          $this->output .= (string) '<vjournal>'; 
          $journal =& $this->icaljournals[$id];
          $this->output .= (string) $this->generateAttendeesOutput($journal->getAttendees(), $format);
          
          if (strlen(trim($journal->getOrganizerMail())) > 0)
            {
            $name = '';
            if (strlen(trim($journal->getOrganizerName())) > 0)
              {
              $name = (string) ' cn="' . $journal->getOrganizerName() . '"';
              } // end if
  
            $this->output .= (string) '<organizer' . $name . '>MAILTO:' . $journal->getOrganizerMail() . '</organizer>';
            } // end if
          
          if (strlen(trim($journal->getStartDate())) > 0)
            {
            $this->output .= (string) '<dtstart>' . $journal->getStartDate() . '</dtstart>';
            } // end if
          
          if (strlen(trim($journal->getCreated())) > 0 && $journal->getCreated() > 0)
            {
            $this->output .= (string) '<created>' . $journal->getCreated() . '</created>';
            } // end if             
          
          if (strlen(trim($journal->getLastMod())) > 0 && $journal->getLastMod() > 0)
            {
            $this->output .= (string) '<last-modified>' . $journal->getLastMod() . '</last-modified>';
            } // end if  

          $this->output .= (string) '<sequence>' . $journal->getSequence() . '</sequence>';
          $this->output .= (string) '<uid>' . $journal->getUID() . '</uid>';
          $this->output .= (string) '<dtstamp>' . $this->ical_timestamp . '</dtstamp>';
          
          if (strlen(trim($journal->getCategories())) > 0)
            {
            $this->output .= (string) '<categories>';
  
            foreach ($journal->getCategoriesArray() as $item)
              {
              $this->output .= (string) '<item>' . $item . '</item>';
              } // end foreach
  
            $this->output .= (string) '</categories>';
            } // end if
          
          if (strlen(trim($journal->getDescription())) > 0)
            {
            $this->output .= (string) '<description>' . $journal->getDescription() . '</description>';
            } // end if
          
          $this->output .= (string) '<summary>' . $journal->getSummary() . '</summary>';
          $this->output .= (string) '<class>' . $this->getClassName($journal->getClass()) . '</class>';
    
          if (strlen(trim($journal->getURL())) > 0)
            {
            $this->output .= (string) '<url>' . $journal->getURL() . '</url>';
            } // end if
    
          if (strlen(trim($journal->getStatus())) > 0)
            {
            $this->output .= (string) '<status>' . $this->getStatusName($journal->getStatus()) . '</status>';
            } // end if
    
          if ($journal->getFrequency() != 'ONCE')
            {
            $this->output .= (string) '<rrule>FREQ=' . $journal->getFrequency();
            
            if (is_string($journal->getRecEnd()))
              {
              $this->output .= (string) ";UNTIL=" . $journal->getRecEnd();
              }
            elseif (is_int($journal->getRecEnd()))
              {
              $this->output .= (string) ";COUNT=" . $journal->getRecEnd();
              } // end if
    
            $this->output .= (string) ";INTERVAL=" . $journal->getInterval() . ";BYDAY=" . $journal->getDays() . ";WKST=" . $journal->getWeekStart() . '</rrule>';
            } // end if
    
        if (strlen(trim($journal->getExeptDates())) > 0)
          {
          $this->output .= (string) '<exdate>' . $journal->getExeptDates() . '</exdate>';
          } // end if
    
          $this->output .= (string) '</vjournal>';
          } // end foreach event
  
        $this->output .= (string) '</vCalendar>';
        } // end if count($this->icaltodos) > 0

      
      if (count($this->icalfbs) > 0)
        {      
        $this->output .= (string) '<vCalendar version="2.0" prodid="' . $this->prodid . '" method="' . $this->getMethod() . '">';
        $fbkeys = (array) array_keys($this->icalfbs);
        foreach ($fbkeys as $id)
          {
          $this->output .= (string) '<vfreebusy>'; 
          $fb =& $this->icalfbs[$id];
          $this->output .= (string) $this->generateAttendeesOutput($fb->getAttendees(), $format);
          
          if (strlen(trim($fb->getOrganizerMail())) > 0)
            {
            $name = '';
            if (strlen(trim($fb->getOrganizerName())) > 0)
              {
              $name = (string) ' cn="' . $fb->getOrganizerName() . '"';
              } // end if
  
            $this->output .= (string) '<organizer' . $name . '>MAILTO:' . $fb->getOrganizerMail() . '</organizer>';
            } // end if
          
          if (strlen(trim($fb->getStartDate())) > 0)
            {
            $this->output .= (string) '<dtstart>' . $fb->getStartDate() . '</dtstart>';
            } // end if
          
          if (strlen(trim($fb->getEndDate())) > 0)
            {
            $this->output .= (string) '<dtend>' . $fb->getEndDate() . '</dtend>';
            } // end if
          
          if (strlen(trim($fb->getDuration())) > 0 && $fb->getDuration() > 0)
            {
            $this->output .= (string) '<duration>PT' . $fb->getDuration() . 'M</duration>';
            } // end if             

          $this->output .= (string) '<uid>' . $fb->getUID() . '</uid>';
          $this->output .= (string) '<dtstamp>' . $this->ical_timestamp . '</dtstamp>';
    
          if (strlen(trim($fb->getURL())) > 0)
            {
            $this->output .= (string) '<url>' . $fb->getURL() . '</url>';
            } // end if


        if (count($fb->getFBTimes()) > 0)
          {
          foreach ($fb->getFBTimes() as $timestamp => $data)
            {
            $values = (array) explode(',',$data);
            $this->output .= (string) '<freebusy fbtype="' . $values[1] . '">' . $timestamp . '/' . $values[0] . '</freebusy>';
            } // end foreach
          unset($values);  
          } // end if
            
    
          $this->output .= (string) '</vfreebusy>';
          } // end foreach event
  
        $this->output .= (string) '</vCalendar>';
        } // end if count($this->icaltodos) > 0      
      
      $this->output .= (string) '</iCalendar>';
      } // end if xcs
    elseif ($this->output_format == 'rdf')
      {
      $this->output  = (string) '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
      $this->output .= (string) '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/10/swap/pim/ical#" xmlns:i="http://www.w3.org/2000/10/swap/pim/ical#">';
      $this->output .= (string) '<Vcalendar rdf:about="">';
      $this->output .= (string) '<version>2.0</version>';
      $this->output .= (string) '<prodid>' . $this->prodid . '</prodid>';
      $this->output .= (string) '</Vcalendar>';

      $eventkeys = (array) array_keys($this->icalevents);
      foreach ($eventkeys as $id)
        {
        $event =& $this->icalevents[$id];
        $this->output .= (string) '<Vevent>'; 
        $this->output .= (string) '<uid>' . $event->getUID() . '</uid>';
        $this->output .= (string) '<summary>' . $event->getSummary() . '</summary>';
        
        if (strlen(trim($event->getDescription())) > 0)
          {
          $this->output .= (string) '<description>' . $event->getDescription() . '</description>';
          } // end if        
        
        if (strlen(trim($event->getCategories())) > 0)
          {
          $this->output .= (string) '<categories>' . $event->getCategories() . '</categories>';
          } // end if
          
        $this->output .= (string) '<status/>';
        $this->output .= (string) '<class resource="http://www.w3.org/2000/10/swap/pim/ical#private"/>';
        $this->output .= (string) '<dtstart parseType="Resource">';
        $this->output .= (string) '<value>' . $event->getStartDate() . '</value>';
        $this->output .= (string) '</dtstart>';
        $this->output .= (string) '<dtstamp>' . $this->ical_timestamp . '</dtstamp>';
        $this->output .= (string) '<due/>';
        $this->output .= (string) '</Vevent>';
        } // end foreach event

      $this->output .= (string) '</rdf:RDF>';
      } // end if rdf
    if (isset($event)) { unset($event); } 
    } // end function  
  
  
  /**
  * Loads the string into the variable if it hasn't been set before
  *
  * @param (string) $format  ics | xcs | rdf
  * @return (string) $output
  * @see generateOutput(), writeFile()
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getOutput($format = 'ics')
    {
    if (!isset($this->output) || $this->output_format != $format)
      {
      $this->generateOutput($format);
      } // end if
    return (string) $this->output;
    } // end function  


  /**
  * Sends the right header information and outputs the generated content to the browser
  *
  * @param (string) $format  ics | xcs | rdf (only Events)
  * @return (void)
  * @see getOutput()
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function outputFile($format = 'ics')
    {
    if ($format == 'ics')
      {
      header('Content-Type: text/Calendar');
      header('Content-Disposition: inline; filename=iCalendar_dates_' . date('Y-m-d_H-m-s') . '.ics');
      echo $this->getOutput('ics');
      }
    elseif ($format == 'xcs')
      {
      header('Content-Type: text/Calendar');
      header('Content-Disposition: inline; filename=iCalendar_dates_' . date('Y-m-d_H-m-s') . '.xcs');
      echo $this->getOutput('xcs');
      }
    elseif ($format == 'rdf')
      {
      header('Content-Type: text/xml');
      header('Content-Disposition: inline; filename=iCalendar_dates_' . date('Y-m-d_H-m-s') . '.rdf');
      echo $this->getOutput('rdf');
      } // end if
    } // end function


  /**
  * Writes the string into the file and saves it to the download directory
  *
  * @return (void)
  * @see generateOutput(), getOutput()
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function writeFile()
    {
    if ($this->checkDownloadDir() == false)
      {
      die('error creating download directory');
      } // end if
      
    if (!isset($this->output))
      {
      $this->generateOutput();
      } // end if
    $handle = fopen($this->download_dir . '/' . $this->events_filename, 'w');
    fputs($handle, $this->output);
    fclose($handle);
    $this->deleteOldFiles(300);
    if (isset($handle)) { unset($handle); }
    } // end function      
    
  /**
  * Writes the string into the file and saves it to the download directory
  *
  * @param (int) $time  Minimum age of the files (in seconds) before file get deleted
  * @return (void)
  * @see writeFile()
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function deleteOldFiles($time = 300)
    {
    if ($this->checkDownloadDir() == false)
      {
      die('error creating download directory');
      } // end if
    
    if (!is_int($time) || $time < 1)
      {
      $time = (int) 300;
      } // end if
    $handle = opendir($this->download_dir);
    while ($file = readdir($handle))
      {
      if (!eregi("^\.{1,2}$",$file) && !is_dir($this->download_dir . '/' . $file) && eregi("\.ics",$file) && ((time() - filemtime($this->download_dir . '/' . $file)) > $time))
        {
        unlink($this->download_dir . '/' . $file);
        } // end if
      } // end while
    closedir($handle);
    if (isset($handle)) { unset($handle); } 
    if (isset($file)) { unset($file); } 
    } // end function      
  
  /**
  * Returns the full path to the saved file where it can be downloaded.
  *
  * Can be used for "header(Location:..."
  *
  * @return (string)  Full http path
  * @access public
  * @since 1.000 - 2002/10/10   
  */
  function getFilePath()
    {
    $path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
    $port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
    return (string) 'http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/' . $this->download_dir . '/' . $this->events_filename;
    } // end function


  /**
  * Checks if a class is available
  *
  * @return (object) $user  User
  * @access private
  * @since 1.001 - 2002/11/30
  */
  function checkClass($classname = '', $line = '')
    {
    if (strlen(trim($classname)) > 0)
      {
      if (!class_exists($classname))
        {
        if (strlen(trim($line)) > 0)
          {
          $lineinfo = (string) ' at Line ' .$line;
          } // end if

        echo 'Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!';
        die();
        } // end if
      } // end if
    } // end function
/* SEND-BY bei attendees/contact/organizer*/
  } // end class iCal
?>
