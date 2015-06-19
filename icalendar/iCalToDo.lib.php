<?php
/**
* We need the child class
*/
include_once('iCalAlarm.lib.php');

/**
* Container for a single todo
*
* Last Change: 2002-12-24
*
* @access private
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @package iCalendar Everything to generate simple iCal files
* @version 1.021
*/
class iCalToDo
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/


  /**
  * PRIVATE (0) or PUBLIC (1) or CONFIDENTIAL (2)
  *
  * @var int
  * @access private
  */
  var $class;

  /**
  * end date in iCal format
  *
  * @var string
  * @access private
  */
  var $completed;

  /**
  * Timestamp of the end date
  *
  * @var int
  * @access private
  */
  var $completed_ts;

  /**
  * Detailed information for the todo
  *
  * @var string
  * @access private
  */
  var $description;

  /**
  * start date in iCal format
  *
  * @var string
  * @access private
  */
  var $startdate;

  /**
  * Timestamp of the start date
  *
  * @var int
  * @access private
  */
  var $startdate_ts;

  /**
  * last modification of the ToDo in iCal format
  *
  * @var string
  * @access private
  */
  var $last_mod;

  /**
  * last modification of the ToDo (not the same as the Timestamp)
  *
  * @var int
  * @access private
  */
  var $last_mod_ts;

  /**
  * Location of the ToDo
  *
  * @var string
  * @access private
  */
  var $location;

  /**
  * Organizer of the event; $organizer[0] = Name, $organizer[1] = e-mail
  *
  * @var array
  * @access private
  */
  var $organizer;

  /**
  * The percent completion of a ToDo
  *
  * @var int
  * @access private
  */
  var $percent;

  /**
  * set to 5 (value between 0 and 9)
  *
  * @var int
  * @access private
  */
  var $priority;

  /**
  * set to 0
  *
  * @var int
  * @access private
  */
  var $sequence;

  /**
  * If not empty, contains the status of the event (0 = TENTATIVE, 1 = CONFIRMED, 2 = CANCELLED)
  *
  * @var int
  * @access private
  */
  var $status;

  /**
  * Headline for the ToDo (mostly displayed in your cal programm)
  *
  * @var string
  * @access private
  */
  var $summary;

  /**
  * Automaticaly created: md5 value of the start date + Summary
  *
  * @var string
  * @access private
  */
  var $uid;

  /**
  * If not empty, contains a Link for that event
  *
  * @var string
  * @access private
  */
  var $url;
  
  /**
  * Duration of the ToDo in minutes
  *
  * @var int
  * @access private
  */
  var $duration;
  
  /**
  * If the method is REQUEST, all attendees are listet in the file
  *
  * key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  *
  * @var array
  * @access private
  */
  var $attendees;

  /**
  * String with the categories asigned to the todo
  *
  * @var string
  * @access private
  */
  var $categories;

  /**
  * Array with the categories asigned to the todo (example: array('Freetime','Party'))
  *
  * @var array
  * @access private
  */
  var $categories_array;

  /**
  * iso code language (en, de,...)
  *
  * @var string
  * @access private
  */
  var $lang;
  
  /**
  * 0 = once, 1-7 = secoundly - yearly
  *
  * @var int
  * @access private
  */
  var $frequency;
  
  /**
  * interval of the recurring date (example: every 2,3,4 weeks)
  *
  * @var int
  * @access private
  */
  var $interval;

  /**
  * List of short strings symbolizing the weekdays
  *
  * @var array
  * @access private
  */
  var $shortDaynames;

  /**
  * Short string symbolizing the startday of the week
  *
  * @var string
  * @access private
  */
  var $week_start;

  /**
  * Exeptions dates for the recurring event (Array of timestamps)
  *
  * @var array
  * @access private
  */
  var $exept_dates;

  /**
  * String of days for the recurring event (example: "SU,MO")
  *
  * @var string
  * @access private
  */
  var $rec_days;

  /**
  * If alarm is set, holds alarm object
  *
  * @var object
  * @access private
  */
  var $alarm;
    
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/

  /**
  * Constructor
  *
  * Only job is to set all the variablesnames
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
  function iCalToDo($summary, $description, $location, $start, $duration, $end, $percent, $prio, $status, $class, $organizer, $attendees, $categories, $last_mod, $alarm, $frequency, $rec_end, $interval, $days, $weekstart, $exept_dates, $url, $lang)
    {
    $this->shortDaynames = (array) array('SU','MO','TU','WE','TH','FR','SA');
    $this->setLanguage($lang);
    $this->setOrganizer($organizer);
    $this->setStartDate($start);
    $this->setDuration($duration);
    $this->setCompleted($end);
    $this->setLastMod($last_mod);
    $this->setPercent($percent);
    $this->setLocation($location);
    $this->setSequence(0);
    $this->setCategories($categories);
    $this->setDescription($description);
    $this->setSummary($summary);
    $this->setPriority($prio);
    $this->setClass($class);
    $this->setUID();
    $this->setAttendees($attendees);
    $this->setStatus($status);
    $this->setURL($url);
    $this->setFrequency($frequency);
    $this->setRecEnd($rec_end);
    $this->setInterval($interval);
    $this->setDays($days);
    $this->setWeekStart($weekstart);
    $this->setExeptDates($exept_dates);
    $this->setAlarm($alarm);
    } // end constructor
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  
  /**
  * Set $categories_array variable
  *
  * @param (string) $categories
  * @return (void)
  * @see getCategoriesArray(), $categories_array
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setCategoriesArray($categories)
    {
    $this->categories_array = (array) $categories;
    } // end function

  /**
  * Get $categories_array variable
  *
  * @return (array) $categories_array
  * @see setCategoriesArray(), $categories_array
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getCategoriesArray()
    {
    return (array) $this->categories_array;
    } // end function


  /**
  * Set $categories variable
  *
  * @param (string) $categories
  * @return (void)
  * @see getCategories(), $categories
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setCategories($categories = '')
    {
    $this->setCategoriesArray($categories);
    $this->categories = (string) implode(',',$categories);
    } // end function

  /**
  * Get $categories variable
  *
  * @return (string) $categories
  * @see setCategories(), $categories
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getCategories()
    {
    return (string) $this->categories;
    } // end function


  /**
  * Set $attendees variable
  *
  * @param (array) $attendees
  * @return (void)
  * @see getAttendees(), $attendees
  * @access private
  * @since 1.001 - 2002/10/10
  */
  function setAttendees($attendees = '')
    {
    $this->attendees = (array) ((is_array($attendees)) ? $attendees : array());
    } // end function

  /**
  * Get $attendees variable
  *
  * @return (string) $attendees
  * @see setAttendees(), $attendees
  * @access public
  * @since 1.001 - 2002/10/10
  */
  function &getAttendees()
    {
    return (array) $this->attendees;
    } // end function


  /**
  * Set $organizer variable
  *
  * @param (array) $organizer
  * @return (void)
  * @see getOrganizerName(), getOrganizerMail(), $organizer
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setOrganizer($organizer = '')
    {
    $this->organizer = (array) ((is_array($organizer)) ? $organizer : array('vCalEvent class', 'http://www.flaimo.com'));
    } // end function

  /**
  * Get name from $organizer variable
  *
  * @return (array) $organizer
  * @see setOrganizer(), getOrganizerMail(), $organizer
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function &getOrganizerName()
    {
    return (string) $this->organizer[0];
    } // end function

  /**
  * Get e-mail from $organizer variable
  *
  * @return (array) $organizer
  * @see setOrganizer(), getOrganizerName(), $organizer
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function &getOrganizerMail()
    {
    return (string) $this->organizer[1];
    } // end function


  /**
  * Set $description variable
  *
  * @param (string) $description
  * @return (void)
  * @see getDescription(), $description
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setDescription($description = '')
    {
    $this->description = (string) $description;
    } // end function

  /**
  * Get $description variable
  *
  * @return (string) $description
  * @see setDescription(), $description
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getDescription()
    {
    return (string) $this->description;
    } // end function


  /**
  * Set $url variable
  *
  * @param (string) $url
  * @return (void)
  * @see getURL(), $url
  * @access private
  * @since 1.011 - 2002/12/22
  */
  function setURL($url = '')
    {
    $this->url = (string) $url;
    } // end function

  /**
  * Get $url variable
  *
  * @return (string) $url
  * @see setURL(), $url
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function &getURL()
    {
    return (string) $this->url;
    } // end function


  /**
  * Set $status variable
  *
  * @param (int) $status
  * @return (void)
  * @see getStatus(), $status
  * @access private
  * @since 1.011 - 2002/12/22
  */
  function setStatus($status = 1)
    {
    $this->status = (int) $status;
    } // end function

  /**
  * Get $status variable
  *
  * @return (string) $statuscode
  * @see setStatus(), $status
  * @access public
  * @since 1.011 - 2002/12/22
  */
  function &getStatus()
    {
    return (int) $this->status;
    } // end function


  /**
  * Set $summary variable
  *
  * @param (string) $summary
  * @return (void)
  * @see getSummary(), $summary
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setSummary($summary = '')
    {
    $this->summary = (string) $summary;
    } // end function

  /**
  * Get $summary variable
  *
  * @return (string) $summary
  * @see setSummary(), $summary
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getSummary()
    {
    return (string) $this->summary;
    } // end function


  /**
  * Set $priority variable
  *
  * @param (int) $int
  * @return (void)
  * @see getPriority(), $priority
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setPriority($int = 5)
    {
    $this->priority = (int) ((is_int($int) && preg_match('(^([0-9]{1})$)', $int)) ? $int : 5);
    } // end function

  /**
  * Get $priority variable
  *
  * @return (string) $priority
  * @see setPriority(), $priority
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getPriority()
    {
    return (int) $this->priority;
    } // end function

  /**
  * Set $class variable
  *
  * @param (int) $int
  * @return (void)
  * @see getClass(), $class
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function setClass($int = 0)
    {
    $this->class = (int) $int;
    } // end function
    
  /**
  * Get $class variable
  *
  * @return (string) $class
  * @see setClass(), $class
  * @access public
  * @since 1.000 - 2002/10/10   
  */ 
  function &getClass()
    {
    return (int) $this->class;
    } // end function     
    
  /**
  * Set $uid variable
  *
  * @return (void)
  * @see getUID(), $uid
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setUID()
    {
    $rawid = (string) $this->startdate . 'plus' .  $this->summary;
    $this->uid = (string) md5($rawid);
    } // end function

  /**
  * Get $uid variable
  *
  * @return (string) $uid
  * @see setUID(), $uid
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getUID()
    {
    return (string) $this->uid;
    } // end function


  /**
  * Set $sequence variable
  *
  * @param (int) $int
  * @return (void)
  * @see getSequence(), $sequence
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setSequence($int = 0)
    {
    $this->sequence = (int) 0;
    } // end function

  /**
  * Get $sequence variable
  *
  * @return (int) $sequence
  * @see setSequence(), $sequence
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getSequence()
    {
    return (int) $this->sequence;
    } // end function


  /**
  * Set $location variable
  *
  * @param (string) $location
  * @return (void)
  * @see getLocation(), $location
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setLocation($location = '')
    {
    $this->location = (string) ((strlen(trim($location)) > 0) ? $location : '');
    } // end function

  /**
  * Get $location variable
  *
  * @return (string) $location
  * @see setLocation(), $location
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getLocation()
    {
    return (string) $this->location;
    } // end function


  /**
  * Set $startdate_ts variable
  *
  * @param (int) $timestamp
  * @return (void)
  * @see getStartDateTS(), $startdate_ts
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setStartDateTS($timestamp = 0)
    {
    if (is_int($timestamp) && $timestamp > 0)
      {
      $this->startdate_ts = (int) $timestamp;
      } // end if
    } // end function

  /**
  * Get $startdate_ts variable
  *
  * @return (int) $startdate_ts
  * @see setStartDateTS(), $startdate_ts
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getStartDateTS()
    {
    return (int) $this->startdate_ts;
    } // end function


  /**
  * Set $startdate variable
  *
  * @param (int) $timestamp
  * @return (void)
  * @see getStartDate(), $startdate
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setStartDate($timestamp = 0)
    {
    $this->setStartDateTS($timestamp);
    $this->startdate = (string) date('Ymd\THi00\Z',$this->startdate_ts);
    } // end function

  /**
  * Get $startdate variable
  *
  * @return (int) $startdate
  * @see setStartDate(), $startdate
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getStartDate()
    {
    return (string) $this->startdate;
    } // end function


  /**
  * Set $completed_ts variable
  *
  * @param (int) $timestamp
  * @return (void)
  * @see getCompletedTS(), $completed_ts
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setCompletedTS($timestamp = 0)
    {
    if (is_int($timestamp) && $timestamp > 0)
      {
      $this->completed_ts = (int) $timestamp;
      } // end if
    } // end function

  /**
  * Get $completed_ts variable
  *
  * @return (int) $completed_ts
  * @see setCompletedTS(), $completed_ts
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function &getCompletedTS()
    {
    return (int) $this->completed_ts;
    } // end function


  /**
  * Set $completed variable
  *
  * @param (int) $completed
  * @return (void)
  * @see getCompleted(), $completed
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setCompleted($timestamp = 0)
    {
    $this->setCompletedTS($timestamp);
    $this->completed = (string) date('Ymd\THi00\Z',$this->completed_ts);
    } // end function

  /**
  * Get $completed variable
  *
  * @return (int) $completed
  * @see setCompleted(), $completed
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function &getCompleted()
    {
    return (string) $this->completed;
    } // end function


  /**
  * Set $last_mod_ts variable
  *
  * @param (int) $timestamp
  * @return (void)
  * @see getLastModTS(), $last_mod_ts
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setLastModTS($timestamp = 0)
    {
    if (is_int($timestamp) && $timestamp > 0)
      {
      $this->last_mod_ts = (int) $timestamp;
      } // end if
    } // end function

  /**
  * Get $last_mod_ts variable
  *
  * @return (int) $last_mod_ts
  * @see setLastModTS(), $last_mod_ts
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function &getLastModTS()
    {
    return (int) $this->last_mod_ts;
    } // end function


  /**
  * Set $last_mod variable
  *
  * @param (int) $last_mod
  * @return (void)
  * @see getLastMod(), $last_mod
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setLastMod($timestamp = 0)
    {
    $this->setLastModTS($timestamp);
    $this->last_mod = (string) date('Ymd\THi00\Z',$this->last_mod_ts);
    } // end function

  /**
  * Get $last_mod variable
  *
  * @return (int) $last_mod
  * @see setLastMod(), $last_mod
  * @access public
  * @since 1.020 - 2002/12/24
  */
  function &getLastMod()
    {
    return (string) $this->last_mod;
    } // end function


  /**
  * Set $duration variable
  *
  * @param (int) $int
  * @return (void)
  * @see getPercent(), $percent
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setPercent($int = 0)
    {
    $this->percent = (int) $int;
    } // end function

  /**
  * Get $percent variable
  *
  * @return (int) $percent
  * @see setPercent(), $percent
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function &getPercent()
    {
    return (int) $this->percent;
    } // end function


  /**
  * Set $duration variable
  *
  * @param (int) $int
  * @return (void)
  * @see getDuration(), $duration
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setDuration($int = 0)
    {
    $this->duration = (int) $int;
    } // end function

  /**
  * Get $duration variable
  *
  * @return (int) $duration
  * @see setDuration(), $duration
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function &getDuration()
    {
    return (int) $this->duration;
    } // end function


  /**
  * Checks if a given string is a valid iso-language-code
  *
  * @param (string) $code  String that should validated
  * @return (boolean) isvalid  If string is valid or not
  * @access private
  * @since 1.001 - 2002/10/19
  */
  function isValidLanguageCode($code = '')  // PHP5: protected
    {
    $isvalid = (boolean) false;
    if (preg_match('(^([a-z]{2})$|^([a-z]{2}_[a-z]{2})$|^([a-z]{2}-[a-z]{2})$)',trim($code)) > 0)
      {
      $isvalid = (boolean) true;
      } // end if
    return (boolean) $isvalid;
    } // end function


  /**
  * Set $startdate variable
  *
  * @param (string) $isocode
  * @return (void)
  * @see getStartDate(), $startdate
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function setLanguage($isocode = '')
    {
    $this->lang = (string) (($this->isValidLanguageCode($isocode) == true) ? ';LANGUAGE=' . $isocode : '');
    } // end function

  /**
  * Get $startdate variable
  *
  * @return (int) $startdate
  * @see setStartDate(), $startdate
  * @access public
  * @since 1.000 - 2002/10/10
  */
  function &getLanguage()
    {
    return (string) $this->lang;
    } // end function
    
    
  /**
  * Sets a string with weekdays of the recurring event
  *
  * @param (array) $recdays integers
  * @return (void)
  * @see getDays(), $rec_days
  * @access private
  * @since 1.010 - 2002/10/26
  */
  function setDays($recdays = '')
    {
    $this->rec_days = (string) '';
    if (!is_array($recdays))
      {
      $this->rec_days = (string) $this->shortDaynames[1];
      }
    else
      {
      if (count($recdays) > 0)
        {
        $recdays = array_values(array_unique($recdays));
        } // end if

      foreach ($recdays as $day)
        {
        if (array_key_exists($day, $this->shortDaynames))
          {
          $this->rec_days .= (string) $this->shortDaynames[$day] . ',';
          } // end if
        } // end foreach
      $this->rec_days = (string) substr($this->rec_days,0,strlen($this->rec_days)-1);
      } // end if
    } // end function

  /**
  * Returns a string with recurring days
  *
  * @return (string) $rec_days
  * @see setDays(), $rec_days
  * @access public
  * @since 1.010 - 2002/10/26
  */
   function &getDays()
    {
    return (string) $this->rec_days;
    } // end function


  /**
  * Sets an array of formated exeptions dates based on an array with timestamps
  *
  * @param (array) $exeptdates
  * @return (void)
  * @see getExeptDates(), $exept_dates
  * @access private
  * @since 1.010 - 2002/10/26
  */
  function setExeptDates($exeptdates = '')
    {
    if (!is_array($exeptdates))
      {
      $this->exept_dates = (array) array();
      }
    else
      {
      foreach ($exeptdates as $timestamp)
        {
        $this->exept_dates[] = date('Ymd\THi00\Z',$timestamp);
        } // end foreach
      } // end if
    } // end function

  /**
  * Returns a string with exeptiondates
  *
  * @return (string) $return
  * @see setExeptDates(), $exept_dates
  * @access public
  * @since 1.010 - 2002/10/26
  */
   function &getExeptDates()
    {
    $return = (string) '';
    foreach ($this->exept_dates as $date)
      {
      $return .= (string) $date . ',';
      } // end foreach
    $return = (string) substr($return,0,strlen($return)-1);
    return (string) $return;
    } // end function


  /**
  * Sets the starting day for the week (0 = Sunday)
  *
  * @param (int) $weekstart  0-6
  * @return (void)
  * @see getWeekStart(), $week_start
  * @access private
  * @since 1.010 - 2002/10/26
  */
  function setWeekStart($weekstart = 1)
    {
    $this->week_start = (int) ((is_int($weekstart) && preg_match('(^([0-6]{1})$)', $weekstart)) ? $weekstart : 1);
    } // end function
  /**
  * Get the string from the $week_start variable
  *
  * @return (string) $shortDaynames
  * @see setWeekStart(), $week_start
  * @access public
  * @since 1.010 - 2002/10/26
  */
   function &getWeekStart()
    {
    return (string) ((array_key_exists($this->week_start, $this->shortDaynames)) ? $this->shortDaynames[$this->week_start] : $this->shortDaynames[1]);
    } // end function  


  /**
  * Sets the interval for a recurring event (2 = every 2 [days|weeks|years|...])
  *
  * @param (int) $interval
  * @return (void)
  * @see getInterval(), $interval
  * @access private
  * @since 1.010 - 2002/10/26
  */
  function setInterval($interval = '')
    {
    $this->interval = (int) ((is_int($interval)) ? $interval : 1);
    } // end function  

  /**
  * Get $interval variable
  *
  * @return (int) $interval
  * @see setInterval(), $interval
  * @access public
  * @since 1.010 - 2002/10/26
  */
   function &getInterval()
    {
    return (int) $this->interval;
    } // end function
    
  /**
  * Sets the frequency of a recurring event
  *
  * @param (int) $int  Integer 0-7
  * @return (void)
  * @see getFrequency(), $frequencies
  * @access private
  * @since 1.010 - 2002/10/26 
  */
  function setFrequency($int = 0)
    {
    $this->frequency = (int) $int;
    } // end function  
  
  /**
  * Get $frequency variable
  *
  * @return (string) $frequencies
  * @see setFrequency(), $frequencies
  * @access public
  * @since 1.010 - 2002/10/26   
  */ 
   function &getFrequency()
    {
    return (int) $this->frequency;
    } // end function   

  /**
  * Sets the end for a recurring event (0 = never ending, integer < 4 numbers = number of times, integer >= 4 enddate)
  *
  * @param (int) $freq
  * @return (void)
  * @see getRecEnd(), $rec_end
  * @access private
  * @since 1.010 - 2002/10/26
  */
  function setRecEnd($freq = '')
    {
    if (strlen(trim($freq)) < 1)
      {
      $this->rec_end = 0;
      }
    elseif (is_int($freq) && strlen(trim($freq)) < 4)
      {
      $this->rec_end = $freq;
      }
    else
      {
      $this->rec_end = (string) date('Ymd\THi00\Z',$freq);
      } // end if
    } // end function

  /**
  * Get $rec_end variable
  *
  * @return (mixed) $rec_end
  * @see setRecEnd(), $rec_end
  * @access public
  * @since 1.010 - 2002/10/26
  */
   function &getRecEnd()
    {
    return $this->rec_end;
    } // end function
    
  /**
  * Set $attendees variable
  *
  * @param (array) $attendees
  * @return (void)
  * @see getAttendees(), $attendees
  * @access private
  * @since 1.001 - 2002/10/10
  */
  function setAlarm($alarm = '')
    {
    if (is_array($alarm))
      {
      $this->alarm = (object) new iCalAlarm($alarm[0], $alarm[1], $alarm[2], $alarm[3], $alarm[4], $alarm[5], $alarm[6], $this->lang);
      } // end if
    } // end function

  /**
  * Get $attendees variable
  *
  * @return (string) $attendees
  * @see setAttendees(), $attendees
  * @access public
  * @since 1.001 - 2002/10/10
  */
  function &getAlarm()
    {
    return ((is_object($this->alarm)) ? $this->alarm : false);
    } // end function
  } // end class iCalToDo
?>