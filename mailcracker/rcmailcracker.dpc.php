<?php

$__DPCSEC['RCMAILCRACKER_DPC']='1;1;1;1;2;2;2;2;9';

if ((!defined("RCMAILCRACKER_DPC")) && (seclevel('RCMAILCRACKER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCMAILCRACKER_DPC",true);

$__DPC['RCMAILCRACKER_DPC'] = 'rcmailcracker';

$d = GetGlobal('controller')->require_dpc('mailcracker/mailcracker.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('MAILCRACKER_DPC','RCMAILCRACKER_DPC');

$__EVENTS['RCMAILCRACKER_DPC'][0]='cpmailcracker';//overwrite
$__EVENTS['RCMAILCRACKER_DPC'][10]='crackeradminmail';

$__ACTIONS['RCMAILCRACKER_DPC'][0]='cpmailcracker';//overwrite
$__ACTIONS['RCMAILCRACKER_DPC'][10]='crackeradminmail';

$__DPCATTR['RCMAILCRACKER_DPC']['cpmailcracker']='cpmailcracker,0,0,1,0,0,0,0,0,0';

$__LOCALE['RCMAILCRACKER_DPC'][0]='RCMAILCRACKER_DPC;Web Mail;Web Mail';

//overwrite
$__LOCALE['MAILCRACKER_DPC'][39]='_ADMINCRACKER;Logout;Logout';


class rcmailcracker extends mailcracker {
	
    var $mailboxes,$mailpwdes;
	var $title;

	function rcmailcracker() {
    
	   //mailcracker::mailcracker();      	   	   	   
	   
	   $UserSecID = GetGlobal('UserSecID');
	   $UserName = GetGlobal('UserName');
	   $Password = GetGlobal('Password');	   
	   $GRX = GetGlobal('GRX');
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	   
	   $this->active = false;
       $this->username = decode($UserName);	   
	   
       //$this->password = decode($Password);	//echo $this->password,'////'; 
	   //$this->user = decode($UserName);	// . '@'. paramload('MAILCRACKER','domain');//'admin@panikidis.com';	   
	   //OVERWRITE
	   $this->user = GetSessionParam('muser');//'info@re-coding.com';
	   $this->password = GetSessionParam('mpass');//'ofni';	   
	   
	   $this->server = paramload('MAILCRACKER','pop3server');//'192.168.4.201';  
	   
       $mbox_open = "{" . $this->server . ":110/pop3}INBOX";   
       $mbox = @imap_open($mbox_open, $this->user,$this->password);
	   if (is_resource($mbox)) {
	     $this->mbox_info = imap_mailboxmsginfo($mbox);	 
	     $this->msgcount = $this->mbox_info->Nmsgs;
	     imap_close($mbox);
		 $this->active = true;
	   }
	   else
	     $this->active = false;	 
	   $this->txtDelMsg = array();  
	   
	   $this->success = null;
	   $this->to = null;
	   $this->from = null;
	   $this->cc = null;
	   $this->bcc = null;	   	   
	   $this->message = null;	   
	   $this->subject = null;	   
	   
	  
       if ($GRX) {      
             $this->outpoint = loadTheme('point'); 			 
       }
	   else {
             $this->outpoint = "|";	   
	   }	
	   
       if (iniload('JAVASCRIPT')) {	
	   
	      $code = $this->javascript();
	   
		  $js = new jscript;
          $js->load_js($code,"",1);	
		  unset ($js);		
	   }  	      
	   
       $this->maildir = paramload('SHELL','prpath') . paramload('MAILCRACKER','maildir');
       $this->dirmark = paramload('MAILCRACKER','dirmail');	   
	   
	   $this->mattach = paramload('MAILCRACKER','mailattach');
       $this->capacity = paramload('MAILCRACKER','maxattachedsize');
	   	   
       if (GetSessionParam('mailbody')) 
	     $this->mailbody = GetSessionParam('mailbody');
	   else 
	     $this->mailbody = paramload('MAILCRACKER','mailbody');		   
	   
	   if (GetSessionParam('attachments')) 
	     $this->attachments = GetSessionParam('attachments');
	   else 
	     $this->attachments = array();	
		 
		 
	   $this->formerr = null;	
	   
	   if ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
		  
	      //must be the app config param!!!!!!
	      $this->mailboxes = remote_arrayload('RCMAILCRACKER','mailboxes',$this->path);
	      $this->mailpwdes = remote_arrayload('RCMAILCRACKER','mailpwdes',$this->path);		  
	   }	  
	   else {
		  $this->path = paramload('SHELL','prpath')."/";		   

	      $this->mailboxes = arrayload('RCMAILCRACKER','mailboxes');
	      $this->mailpwdes = arrayload('RCMAILCRACKER','mailpwdes');
	   }	
	   
	   $this->title = localize('RCMAILCRACKER_DPC',getlocal());    
	}
	
    function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////	

	    switch ($event) {
		  case 'crackeradminmail': $this->logout_mail(); break; 		  
		  default                : mailcracker::event($event); 
		}
    }	

	function action($action=null) {

	    switch ($action) {
		
		  case 'crackeradminmail' : $out = $this->cracker_mailbox_read(); break; 
		  default                 : $out = mailcracker::action($action);
		}
		
		return ($out);
	}
	
	//overwrite
    function cracker_mailbox_read() {	
	
	    $mu = GetParam('muser');
		$mp = GetParam('mpass');
		
		if ($mu) 
		  SetSessionParam('muser',$mu);
		else
		  $mu = GetSessionParam('muser');
		  
		if ($mp) 
		  SetSessionParam('mpass',$mp);		
		else
		  $mp = GetSessionParam('mpass');  
		  
		//echo $mu,$mp;
		
		if (($mu) && ($mp)) {
	      $this->user = $mu;
	      $this->password = $mp;			   
		  $ret = mailcracker::cracker_mailbox_read();  
		}  
		else 
		  $ret = $this->select_mailbox_form();  
		
		return ($ret);
	}
	
	function select_mailbox_form() {
	
	    //$out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
	   /* if (GetSessionParam('REMOTELOGIN')) 
	     //$out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
		 $out = setNavigator($this->title);
	    else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 		*/
		 
	    if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	    else  
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 			 
	 
        $filename = seturl("t=crackermail",0,1);
	  	  
        $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	    $toprint .= "<STRONG>Mailbox :</STRONG>"; 
		
		if (count($this->mailboxes>0)) {
		  $toprint .= "<select name=\"muser\">";
		  foreach ($this->mailboxes as $id=>$mname)  
		    $toprint .= "<option value=\"$mname\">$mname</option>";	
		  $toprint .= "</select><br>";			
		}
		else 
	      $toprint .= "<input type=\"text\" name=\"muser\" value=\"\" size=\"32\" maxlength=\"128\"><br>";
        
		//if (empty($this->mailpwdes)) {		
	      $toprint .= "<STRONG>Password :</STRONG>"; 		
		  $toprint .= "<input type=\"password\" name=\"mpass\" value=\"\" size=\"32\" maxlength=\"128\"><br>";
		//}  
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"crackermail\">";
        $toprint .= "</FORM>";	   
		
	    $swin = new window("Web Mail",$toprint);
	    $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	    unset ($swin);

        return ($out);
	}
	
	function logout_mail() {
	
	    SetSessionParam('muser',null);
		SetSessionParam('mpass',null);
	}
  
};
}
?>