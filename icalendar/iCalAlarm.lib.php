<?php
/**
* Container for an alarm (used in event and todo)
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
class iCalAlarm
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/


  /**
  * Kind of alarm (0 = DISPLAY, 1 = EMAIL, (not supported: 2 = AUDIO, 3 = PROCEDURE))
  *
  * @var int
  * @access private
  */
  var $action;

  /**
  * Minutes the alarm goes off before the event/todo
  *
  * @var int
  * @access private
  */
  var $trigger;

  /**
  * Headline for the alarm (if action = Display)
  *
  * @var string
  * @access private
  */
  var $summary;

  /**
  * Detailed information for the alarm
  *
  * @var string
  * @access private
  */
  var $description;

  /**
  * If the method is REQUEST, all attendees are listet in the file
  *
  * key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  *
  * @var array
  * @access private
  */
  var $attendee;

  /**
  * Duration between the alarms in minutes
  *
  * @var int
  * @access private
  */
  var $duration;

  /**
  * How often should the alarm be repeated
  *
  * @var int
  * @access private
  */
  var $repeat;

  /**
  * iso code language (en, de,...)
  *
  * @var string
  * @access private
  */
  var $lang;
       
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/

  /**
  * Constructor
  *
  * Only job is to set all the variablesnames
  *
  * @param (int) $action  0 = DISPLAY, 1 = EMAIL, (not supported: 2 = AUDIO, 3 = PROCEDURE)
  * @param (int) $trigger  Minutes the alarm goes off before the event/todo
  * @param (string) $summary  Title for the alarm
  * @param (string) $description  Description
  * @param (array) $attendees  key = attendee name, value = e-mail, second value = role of the attendee [0 = CHAIR | 1 = REQ | 2 = OPT | 3 =NON] (example: array('Michi' => 'flaimo@gmx.net,1'); )
  * @param (int) $duration  Duration between the alarms in minutes
  * @param (int) $repeat  How often should the alarm be repeated
  * @param (string) $lang  Language of the strings used in the event (iso code)
  * @return (void)
  * @access private
  * @since 1.000 - 2002/10/10
  */
  function iCalAlarm($action, $trigger, $summary, $description, $attendees, $duration, $repeat, $lang)
    {
    $this->setAction($action);
    $this->setTrigger($trigger);
    $this->setSummary($summary);
    $this->setDescription($description);
    $this->setAttendees($attendees);
    $this->setDuration($duration);
    $this->setRepeat($repeat);
    $this->setLanguage($lang);
    } // end constructor
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
  

  /**
  * Set $action variable
  *
  * @param (int) $action 0 = DISPLAY, 1 = EMAIL, (not supported: 2 = AUDIO, 3 = PROCEDURE)
  * @return (void)
  * @see getAction(), $action
  * @access private
  * @since 1.021 - 2002/12/24
  */
  function setAction($action = 0)
    {
    $this->action = (int) $action;
    } // end function

  /**
  * Get $action variable
  *
  * @return (string) $action
  * @see setAction(), $action
  * @access public
  * @since 1.021 - 2002/12/24
  */
  function &getAction()
    {
    $action_status = (array) array('DISPLAY', 'EMAIL', 'AUDIO', 'PROCEDURE');
    return (string) ((array_key_exists($this->action, $action_status)) ? $action_status[$this->action] : $action_status[0]);
    } // end function

  /**
  * Set $trigger variable
  *
  * @param (int) $minutes
  * @return (void)
  * @see getTrigger(), $minutes
  * @access private
  * @since 1.021 - 2002/12/24
  */
  function setTrigger($minutes = 0)
    {
    $this->trigger = (int) ((is_int($minutes)) ? $minutes : 0);
    } // end function

  /**
  * Get $trigger variable
  *
  * @return (int) $trigger
  * @see setTrigger(), $trigger
  * @access public
  * @since 1.021 - 2002/12/24
  */
  function &getTrigger()
    {
    return (int) $this->trigger;
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
  * Set $repeat variable
  *
  * @param (int) $int  in minutes
  * @return (void)
  * @see getRepeat(), $repeat
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function setRepeat($int = 0)
    {
    $this->duration = (int) $int;
    } // end function

  /**
  * Get $repeat variable
  *
  * @return (int) $repeat
  * @see setRepeat(), $repeat
  * @access private
  * @since 1.020 - 2002/12/24
  */
  function &getRepeat()
    {
    return (int) $this->duration;
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
    $this->lang = (string) $isocode;
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
  } // end class iCalAlarm
?>