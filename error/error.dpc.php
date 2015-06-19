<?php
/* Event Trigger Script
 * Usage:
 * Include this file in all your scripts
 * You can then fire off an event from anywhere using
 * 
 * triggerEvent ($Object,$nameOfObjectMethodtoExecute,$params);
 * 
 */

 
if (paramload('DIRECTIVES','e_user_error')) $exer = paramload('DIRECTIVES','e_user_error') . "|";
if (paramload('DIRECTIVES','e_user_warning')) $exer = paramload('DIRECTIVES','e_user_warning') . "|";
if (paramload('DIRECTIVES','e_user_notice')) $exer = paramload('DIRECTIVES','e_user_notice') . "|";
if (paramload('DIRECTIVES','e_warning')) $exer = paramload('DIRECTIVES','e_warning') . "|";
if (paramload('DIRECTIVES','e_error')) $exer = paramload('DIRECTIVES','e_error'); 

//if ($exer) error_reporting ($exer);
 
/* Override the E_USER_NOTICE with EVENT */
define ('EVENT',E_USER_NOTICE);

/* Set PHP's error reporting level */
error_reporting  (E_ERROR | E_WARNING | E_PARSE | EVENT); 

/* Define the event trigger "error" handling function */
function eventTrigger ($eventType,& $event, $errfile, $errline) {
    switch ($eventType) {
        case EVENT:
            $event=unserialize($event);
            $event->object->{$event->execute}($event->params);
            break;
        default:
            die ("[$eventType] Error in $errfile on $errline: $event<br />
");
            break;
    }
}

/* Tell PHP to use the eventTrigger handler
 * This overrides PHP's normal error handling
 */
//$eventTrigger=set_error_handler('eventTrigger');<<<<<<<<<<<<<<<<<<<<<<<<<<<

/* Use this function to fire off events */
function triggerEvent (& $object,$execute,$params=null) {
    $event=new stdClass;
    $event->object=$object;
    $event->execute=$execute;
    $event->params=$params;
    $event=serialize($event);
    trigger_error ($event, EVENT);
}

// Include the above script
//require_once('eventTrigger.php');

class MyEvent {
    var $msg;
    function MyEvent () {
        $this->msg='Hello ';
    }
    function display ($name) {
        echo ($this->msg.$name);
    }
}

//$myEvent=& new MyEvent();

//triggerEvent($myEvent,'display','Harry');

?>
