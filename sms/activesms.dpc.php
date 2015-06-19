<?php

$__DPCSEC['ACTIVESMS_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("ACTIVESMS_DPC")) && (seclevel('ACTIVESMS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ACTIVESMS_DPC",true);

$__DPC['ACTIVESMS_DPC'] = 'activesms';

$__EVENTS['ACTIVESMS_DPC'][0]='asms';

$__ACTIONS['ACTIVESMS_DPC'][0]='asms';


class activesms {

    var $userLevelID;
    var $ret;

	function activesms() {
	   $UserSecID = GetGlobal('UserSecID');
       
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0); 	
	   $this->ret = "";
	}

    function event($evn=null) {
	   $param1 = GetGlobal('param1');
	   $param2 = GetGlobal('param2');
	
       switch ($evn) {		
          case "asms"     : $this->sendsms($param1,$param2); 
	                        break;			 	  
       }	
	}	

	function action($act=null) {
        $__USERAGENT = GetGlobal('__USERAGENT');
		
        switch ($__USERAGENT) {
	         case 'HTML' : $out = $this->ret; break;
	         case 'HDML' : $out = $this->ret; break;				 
	         case 'GTK'  : $out = $this->ret; break;
	         case 'TEXT' : $out = $this->ret; break;
	         case 'CLI'  : $out = $this->ret; break;			 
	         case 'WAP'  : $out = $this->ret; break;			 
	         case 'XML'  : break;				 
	    }
			
	    return ($out);
	}
	
	function sendsms($number,$message) {
	
	   if (($number) && ($message)) {	
	
        $instance = new COM("Intellisoftware.ActiveSMS"); 
        $msgid = $instance->SendUnicodeMessage( $number,$message, 15000); 
        $status = $instance->GetSendStatus($msgid);
		
        switch ( $status ) {
		
	        case 2 : //jsSent
			case 9 : //jsSentNotConfirmed
					$this->ret =   "Message has been sent" ;
					break;
			case 7 : //jsErrTimeout
					$this->ret =  "Message not sent, Server too busy [Non-Queued Mode]" ;
					break;
			case 3 : //jsErrNumberInvalid
					$this->ret =  "Message not sent, Invalid Number";
					break;
			case 0 : //jsPending
			case 1 : //jsProcessing
					$this->ret =  "Message has been queued" ;
					break;
			case 4 : //jsErrNetworkFailure
					$this->ret =  "Message not sent, Failure reported by phone network";
					break;
			case 5 : //jsErrPhoneCommsError
					$this->ret =  "Message not sent, Error communicating with handset";
					break;
			case 10 : //jsDeliveryFailed
					$this->ret =  "Message delivery failed";
					break;
			default :
					$this->ret =  "Message not sent, Internal error occured (see EventLog)";
					break;
		}
	   }	 
	   else
	     $this->ret = "Cell number or/and message required!!";			
	}

};
}
?>