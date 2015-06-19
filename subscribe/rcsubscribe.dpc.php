<?php
$__DPCSEC['RCSUBSCRIBE_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSUBSCRIBE_DPC")) && (seclevel('RCSUBSCRIBE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSUBSCRIBE_DPC",true);

$__DPC['RCSUBSCRIBE_DPC'] = 'rcsubscribe';

//$d = GetGlobal('controller')->require_dpc('subscribe/subscribe.dpc.php');
//require_once($d);

$__EVENTS['RCSUBSCRIBE_DPC'][0]='rcsubscribe';
$__EVENTS['RCSUBSCRIBE_DPC'][1]='unsubscribe';
$__EVENTS['RCSUBSCRIBE_DPC'][2]='subscribe';
$__EVENTS['RCSUBSCRIBE_DPC'][3]='advsubscribe';

$__ACTIONS['RCSUBSCRIBE_DPC'][0]='rcsubscribe';
$__ACTIONS['RCSUBSCRIBE_DPC'][1]='unsubscribe';
$__ACTIONS['RCSUBSCRIBE_DPC'][2]='subscribe';
$__ACTIONS['RCSUBSCRIBE_DPC'][3]='advsubscribe';

$__LOCALE['RCSUBSCRIBE_DPC'][0]='RCSUBSCRIBE_DPC;Subscribe;Subscribe';
$__LOCALE['RCSUBSCRIBE_DPC'][1]='_SUBSCR;Subscribe;Εισαγωγή';
$__LOCALE['RCSUBSCRIBE_DPC'][2]='_USUBSCR;Unsubscribe;Εξαγωγή';
$__LOCALE['RCSUBSCRIBE_DPC'][3]='_SUBSLIST;Subscribers List;Λίστα Συνδρομών';
$__LOCALE['RCSUBSCRIBE_DPC'][4]='_MSG2;Enter your e-mail:;Εισάγετε το e-mail σας:';
$__LOCALE['RCSUBSCRIBE_DPC'][5]='_MSG4;Advance subscription;Περισσότερα';
$__LOCALE['RCSUBSCRIBE_DPC'][6]='_MSG5;Invalid e-mail;Ακυρο e-mail';
$__LOCALE['RCSUBSCRIBE_DPC'][7]='_MSG6;Subscription successfull !;Επιτυχής εισαγωγή !';
$__LOCALE['RCSUBSCRIBE_DPC'][8]='_MSG7;Subscription is active !;Είστε ήδη καταχωρημένος';
$__LOCALE['RCSUBSCRIBE_DPC'][9]='_MSG8;Unsubscription successfull !;Επιτυχής εξαγωγή !';
$__LOCALE['RCSUBSCRIBE_DPC'][10]='_ERROR;Error !;Λάθος !';
$__LOCALE['RCSUBSCRIBE_DPC'][11]='_SUBSCRTEXT;Please send me mail informations about new products;Θέλω να λαμβάνω πληροφορίες για νέα προϊόντα μέσω ηλεκτρονικού ταχυδρομείου';
$__LOCALE['RCSUBSCRIBE_DPC'][12]='_SUBSCRWARN;Please check below to subscribe;Ενεργοποίηση συνδρομής';
$__LOCALE['RCSUBSCRIBE_DPC'][13]='_DERROR;Database Error;Δεν είναι δυνατή η εργασία αυτή τη στιγμή, προσπαθήστε αργότερα';
$__LOCALE['RCSUBSCRIBE_DPC'][14]='_SUBID;A/A;A/A';
$__LOCALE['RCSUBSCRIBE_DPC'][15]='_SUBMAIL;Mail Address;Ταχυδρομείο';
$__LOCALE['RCSUBSCRIBE_DPC'][16]='_SUBDATE;Subscription date;Ημερ. Εισαγωγής';
$__LOCALE['RCSUBSCRIBE_DPC'][17]='SUBSCRIBE_CNF;Subscribers List;Λίστα Συνδρομών';
$__LOCALE['RCSUBSCRIBE_DPC'][18]='_CLICKHERE; click here.; πατηστε εδω.';
//$__LOCALE['RCSUBSCRIBE_DPC'][17]='RCSUBSCRIBE_DPC;Subscribe;Subscribe;';don't create command

$__PARSECOM['RCSUBSCRIBE_DPC']['quickform']='_QUICKRCSUBSCRIBE_';

class rcsubscribe {

    var $title,$msg;

	function rcsubscribe() {
	
	  $this->title = localize('RCSUBSCRIBE_DPC',getlocal());	
	  $this->msg = null;		 
	  
	  $this->t_advsubscr = localize('_MSG4',getlocal());
	  $this->mesout = paramload('RCSUBSCRIBE','umsg');	
	  
	  $this->domain = paramload('RCSUBSCRIBE','domain');
	  $this->tell_it = paramload('RCSUBSCRIBE','tellsubscriptionto');		    	    	   
	}
	
    function event($sAction) {	

       if (!$this->msg) {
  
	     switch ($sAction) {
	
	        case 'subscribe'  ://subscribe 
			                          $this->dosubscribe();
	                                  break;							
							
	        case 'unsubscribe' ://unsubscribe
		                              $this->dounsubscribe();
	                                  break;								  
         }
      }
    }	

    function action($action)  { 

	     //$this->reset_db();
	  
		 $out = $this->title();
		 $out .= $this->form();

	     return ($out);
	}

	function title() {
       $sFormErr = GetGlobal('sFormErr');

       //navigation status            
	   $out = setNavigator($this->title); 
 
       //error message
	   $out .= setError($sFormErr);
	   
	   return ($out);
	}

    function form($action=null)  { 		

       //subscription form
	   if ($action)
	     $filename = seturl("t=$action");      
	   else 
         $filename = seturl("t=subscribe");      
    
       $toprint  = "<FORM action=". "$filename" . " method=post>";
       $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	   $toprint .= $this->t_entermail;
	   $toprint .= "</STRONG><br><INPUT type=\"text\" name=submail maxlenght=\"64\" size=25><br>"; 
	   
	   if ($action) 
	     $toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"$action\">"; 
	   else {
	     $toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"subscribe\">&nbsp;"; 
         $toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"unsubscribe\">";
	   }	 
       $toprint .= "</FONT></FORM>";
	   
	   $data2[] = $toprint; 
  	   $attr2[] = "left";

	   $swin = new window(localize('_SUBSCR',getlocal()),$data2,$attr2);
	   $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	   unset ($swin);	 

       return ($out);
   }

    function quickform() {

        $filename = seturl("t=gfilist&a=&g=");    
  
        $out = "<FORM action=" . $filename . " method=post>";
        $out .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	    $out .= $this->t_entermail;
	    $out .= "</STRONG><br />";
		//$out .= "<label for=\"search\" accesskey=\"s\">Search:</label>";
		$out .= "<INPUT type=\"text\" name=submail maxlenght=\"20\" size=11>"; 
        $out .= $this->submit_button;//"<input type=\"submit\" name=\"Submit\" value=\"Ok\">";   
        $out .="<input type=\"hidden\" name=\"FormAction\" value=\"subscribe\">";
        $out .= '<br />' . seturl("t=advsubscribe" , $this->t_advsubscr);  
        $out .= "</FONT></FORM>";
	  
        return ($out);
    }
	
	function message2go() {
	   
	    $out = $this->mesout . seturl("t=advsubscribe",localize('_CLICKHERE',getlocal()));
		
		return ($out);
	}	

	
	function dosubscribe($mail=null) {
	
	   if (!$mail) $mail = GetParam('submail');
	
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');	
	   
       $dtime = date('Y-m-d h:i:s');	   	
	   
	   if (checkmail($mail))  {
          //check if mail is in database
          $sSQL = "select email from rcsubscribers where email=" . $db->qstr($mail) ; 
	      $result = $db->Execute($sSQL,2);
		  $ret = $db->fetch_array($result);
          //print_r($ret);
		  
          if (!$ret[0]) {

			   $sSQL = "insert into rcsubscribers (email,cdate) " .
			           "values (" .
					   $db->qstr(strtolower($mail)) . "," . $db->qstr($dtime) .
		               ")";  
			   $db->Execute($sSQL,1);		    
			   SetGlobal('sFormErr', localize('_MSG6',getlocal()));	
			   
               if ($this->tell_it) 
			     $this->mailto($mail,$this->tell_it,'New Subscription',$mail);			     							  
		  }
		  else SetGlobal('sFormErr', localize('_MSG7',getlocal()));
	   }
	   else SetGlobal('sFormErr', localize('_MSG5',getlocal()));		   	
	}
	
	function dounsubscribe($mail=null) {
	
	   if (!$mail) 
	     $mail = GetParam('submail');
	   if (!$mail) 
	     $mail = GetReq('submail');	 
	   	
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');	   
	   
	   if (checkmail($mail))  {

		  $sSQL = "delete from rcsubscribers where email=" . $db->qstr($mail) ; 
		  $result = $db->Execute($sSQL,1);
		  
		  SetGlobal('sFormErr',localize('_MSG8',getlocal()));
		  setInfo(localize('_MSG8',getlocal()));
	   }
	   else { 
	     SetGlobal('sFormErr', localize('_MSG5',getlocal()));	  
		 setInfo(localize('_MSG5',getlocal()));
	   }	
	}
	
    function isin($mail) {
	
       $db = GetGlobal('db');
	   
       $sSQL = "SELECT subscriber_id,email,cdate FROM rcsubscribers";	
	   $sSQL .= " WHERE email=" . $db->qstr($mail); 
		
	   $resultset = $db->Execute($sSQL,2);
	   $ret = $db->fetch_array($resultset);	   
	   
	   //echo $mail,$sSQL;

	   if ($ret[1]==$mail) return (true);
	
       return (false);
    }	
	
	function getmails() {
	
       $db = GetGlobal('db');	
	   
       $resultset = $db->Execute("select email from rcsubscribers",2);   

	   $res = $db->fetch_array_all($resultset);
	   foreach ($res as $mail)
	     $ret[] = $mail[0];
	     
	   $out = implode(',',$ret);

	   return $out;	
	}
	
	function mailto($from,$to,$subject=null,$body=null) {

		   
		     if ((defined('SMTPMAIL_DPC')) && 
				 (seclevel('SMTPMAIL_DPC',$this->UserLevelID)) ) {
		       $smtpm = new smtpmail;
		       $smtpm->to = $to; 
			   $smtpm->from = $from; 
			   $smtpm->subject = $subject;
			   $smtpm->body = $body ;
			   
			   $mailerror = $smtpm->smtpsend();
			   
			   unset($smtpm);		   
			   if (!$mailerror) return (true);
			 }
			 
			 return (false);   	
	}	
	
	function reset_db() {
        $db = GetGlobal('db'); 
	 
	    $sSQL0 = "drop table rcsubscribers";
	    $result0 = $db->Execute($sSQL0,1);	
	    if ($result0) $message = "Drop table ...\n";
		
	    //create table
	    $sSQL1 = "create table rcsubscribers
                (
	              subscriber_id integer auto_increment primary key,
	              email varchar(64),
	              cdate datetime				  
                )";	    
	    $result1 = $db->Execute($sSQL1,1);
	    if ($result1) $message .= "Create table ...\n";
	  
	    setInfo($message);	  	
	}		

};
}
?>