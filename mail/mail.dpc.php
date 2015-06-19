<?php

$__DPCSEC['MAIL_DPC']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ADMINMAIL_']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['SENDMAIL_']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['RSVMAIL_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['MAILACCOUNT_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['PARTMAIL_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ATTACHMENTS_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ORDERMAIL_']='2;1;1;2;2;2;2;2;9';
$__DPCSEC['SUBSCRIBERSMAIL_']='2;1;1;1;1;1;2;2;9';

if ((!defined("MAIL_DPC")) && (seclevel('MAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MAIL_DPC",true);

//require_once("mime.lib.php");
GetGlobal('controller')->include_dpc('mail/mime.lib.php');
//require_once("smtp.lib.php");
GetGlobal('controller')->include_dpc('mail/smtp.lib.php');
//require_once("pop3.lib.php");
GetGlobal('controller')->include_dpc('pop3.lib.php');

$__DPC['MAIL_DPC'] = 'mailbox';
 
$__EVENTS['MAIL_DPC'][0]=localize('_SEND',getlocal());
$__EVENTS['MAIL_DPC'][1]=localize('_ADD2OUTBOX',getlocal());
$__EVENTS['MAIL_DPC'][2]='sendmails';
$__EVENTS['MAIL_DPC'][3]='delmails';
$__EVENTS['MAIL_DPC'][4]='delimap';
$__EVENTS['MAIL_DPC'][5]='attach';
$__EVENTS['MAIL_DPC'][6]='cmaccount';
$__EVENTS['MAIL_DPC'][7]=localize('_CLRMAIL',getlocal());
$__EVENTS['MAIL_DPC'][8]='mail';
$__EVENTS['MAIL_DPC'][9]='pop3';
$__EVENTS['MAIL_DPC'][999]='mailadm';

$__ACTIONS['MAIL_DPC'][0]='mail';
$__ACTIONS['MAIL_DPC'][1]=localize('_SEND',getlocal());
$__ACTIONS['MAIL_DPC'][2]=localize('_ADD2OUTBOX',getlocal());
$__ACTIONS['MAIL_DPC'][3]='sendmails';
$__ACTIONS['MAIL_DPC'][4]='delmails';
$__ACTIONS['MAIL_DPC'][5]='delimap';
$__ACTIONS['MAIL_DPC'][6]='attach';
$__ACTIONS['MAIL_DPC'][7]='cmaccount';
$__ACTIONS['MAIL_DPC'][8]='contact';
$__ACTIONS['MAIL_DPC'][9]=localize('_CLRMAIL',getlocal());
$__ACTIONS['MAIL_DPC'][10]='mailadm';
$__ACTIONS['MAIL_DPC'][11]='pop3';

//$__DPCATTR['MAIL_DPC']['mail']='mail,0,0,1,0,0,0,0,0,0';

$__LOCALE['MAIL_DPC'][0]='MAIL_DPC;Mailbox;Ταχυδρομείο';
$__LOCALE['MAIL_DPC'][1]='_NEWMAIL;New Mail;Νεο Μήνυμα';
$__LOCALE['MAIL_DPC'][2]='_INBOX;Inbox;Εισερχόμενα';
$__LOCALE['MAIL_DPC'][3]='_OUTBOX;Outbox;Εξερχόμενα';
$__LOCALE['MAIL_DPC'][4]='_SNTITEMS;Sent Items;Απεσταλμένα';
$__LOCALE['MAIL_DPC'][5]='_DELITEMS;Deleted Items;Διεγραμμένα';
$__LOCALE['MAIL_DPC'][6]='_ADMMAIL;Mailbox Administration;Διαχείριση Ταχυδρομείου';
$__LOCALE['MAIL_DPC'][7]='_FROM;From;Απο';
$__LOCALE['MAIL_DPC'][8]='_TO;To;Σε';
$__LOCALE['MAIL_DPC'][9]='_SUBJECT;Subject;Θέμα';
$__LOCALE['MAIL_DPC'][10]='_MESSAGE;Message;Κείμενο';
$__LOCALE['MAIL_DPC'][11]='_DATETIME;Date/Time;Ημερ./Ωρα';
$__LOCALE['MAIL_DPC'][12]='_INCLSUBS;and Subscribers;σε συνδρομητές';
$__LOCALE['MAIL_DPC'][13]='_ATTACHMENTS;Attachments;Συνημένα';
$__LOCALE['MAIL_DPC'][14]='_DELETE;Delete;Διαγραφή';
$__LOCALE['MAIL_DPC'][15]='_SEND;Send;Αποστολή';
$__LOCALE['MAIL_DPC'][16]='_ADD2OUTBOX;Add to Outbox;Προσθήκη στα εξερχόμενα';
$__LOCALE['MAIL_DPC'][17]='_ATTACH;Attach;Προσθήκη';
$__LOCALE['MAIL_DPC'][18]='_MLS1;Unable to update mailbox !;Ανικανότητα ενημέρωσης';
$__LOCALE['MAIL_DPC'][19]='_MLS2;Send mail successfully !;Επιτυχής αποστολή !';
$__LOCALE['MAIL_DPC'][20]='_MLS3;No authority !;Μη εγγεκριμένη ενέργεια';
$__LOCALE['MAIL_DPC'][21]='_MLS4;Missing data !;Ελειπή δεδομένα !';
$__LOCALE['MAIL_DPC'][22]='_MLS5;Mail added to Outbox successfully !";Το μύνημα μετακινήθηκε στα Εξερχόμενα !';
$__LOCALE['MAIL_DPC'][23]='_MLS6; mail(s) send successfully !; Μυνήματα στάλθηκαν επιτυχώς !';
$__LOCALE['MAIL_DPC'][24]='_MLS7; mail(s) deleted successfully !; Μυνήματα διαγράφτηκαν επιτυχώς !';
$__LOCALE['MAIL_DPC'][25]='_MLS8; incoming mail(s) !; εισερχόμενα μυνήματα !';
$__LOCALE['MAIL_DPC'][26]='_CLRMAIL;Clear;Καθαρισμός';
$__LOCALE['MAIL_DPC'][27]='_MLS9;Error during mail operation. Please check your entries.;Προβλημα αποστολής. Παρακαλουμε ελεγξτε τα στοιχεία αποστολής.';
$__LOCALE['MAIL_DPC'][28]='_MLS10;mail(s) send.;μηνύματα στάλθηκαν.';
$__LOCALE['MAIL_DPC'][29]='MAIL_CNF;Manage Mailbox;Διαχ. ταχυδρομείου';


class mailbox {

    var $fullink;
    var $maildir;
	var $dirmark;
	var $smtp_srv;
	var $usern;
	var $passw;
	var $mailsrvt;
	var $outpoint;
	var $mailtype;
	var $to;
	var $from;
	var $subject;
	var $depmail;
	var $mattach;
	var $domain;
	var $attachments;
	var $htmladd;
	var $com1;
	var $com2;
	var $com3;
	var $headtitle;
	var $bulkmail;
	var $capacity;
	var $bulktimeout;
	var $timeout;
	
	var $userLevelID;

	function mailbox() {
	   $UserSecID = GetGlobal('UserSecID');
	   $UserName = GetGlobal('UserName');
	   $GRX = GetGlobal('GRX');
	   
       $this->user = decode($UserName);
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	   
	  
       if ($GRX) {      
             $this->outpoint = loadTheme('point'); 			 
       }
	   else {
             $this->outpoint = "|";	   
	   }	   
       
	   $this->fullink = paramload('SHELL','urlbase') . paramload('SHELL','filename'); 
       $this->maildir = paramload('SHELL','prpath') . paramload('MAIL','maildir');
       $this->dirmark = paramload('MAIL','dirmail');
       $this->bulkmail = paramload('MAIL','bulkmailmethod');	    
       $this->usern = paramload('MAIL','mailuname'); 
       $this->passw = paramload('MAIL','mailpwd'); 
       $this->smtp_srv = paramload('MAIL','smtpserver');	   

	   switch (paramload('MAIL','mstype')) {
		   case 'imap' : 
			            if (paramload('MAIL','ssl')) $this->mailsrvt = "{" . $this->smtp_srv . ":993/imap/ssl}";
		                                        else $this->mailsrvt = "{" . $this->smtp_srv . ":143}";
			            break;
		   case 'pop3' : 
			            if (paramload('MAIL','ssl')) $this->mailsrvt = "{" . $this->smtp_srv . ":993/imap/ssl}";
		                                        else $this->mailsrvt = "{" . $this->smtp_srv . ":110/pop3}";
			            break;
	   }

       if ($this->ssl) $this->mailservertype = $this->mailsrvt . "\ssl";
	   
	   $this->mailtype = paramload('MAIL','mailtype');//'text/html';
	   $this->mattach = paramload('MAIL','mailattach');
       $this->capacity = paramload('MAIL','maxattachedsize');	   
       $this->bulktimeout = paramload('MAIL','bulktimeout');		   
	   $this->timeout = paramload('SHELL','timeout');		   
	   $this->domain = paramload('MAIL','maildomain');
       $this->from = paramload('MAIL','from');	
       $this->to = paramload('MAIL','to');
       $this->subject = paramload('MAIL','subject');		   
	   
       if (GetSessionParam('mailbody')) $this->mailbody = GetSessionParam('mailbody');
	                               else $this->mailbody = paramload('MAIL','mailbody');	   	
	   if (GetSessionParam('attachments')) $this->attachments = GetSessionParam('attachments');
	                                  else $this->attachments = array();								  
	   
	   $this->htmladd = FALSE;
	   
	   $this->com1 = trim(localize('_SEND',getlocal()));
	   $this->com2 = trim(localize('_ADD2OUTBOX',getlocal()));
	   $this->com3 = trim(localize('_CLRMAIL',getlocal()));	
	   
	   $this->headtitle = paramload('SHELL','urltitle');	   	   	   	
	   
	}
	

	function action($action) {

	    switch ($action) {
		  case 'mailadm'   : $out = $this->mailboxview('adminbox'); break;
		  case 'pop3'      : $out = $this->viewpop3(); break;		  
		  default          : $out = $this->mailboxview();
		}
		return ($out);
	}
	

    function event($sAction) {
       $sFormErr = GetGlobal('sFormErr');
	   $from = GetParam('from');
	   $to = GetParam('to');
	   $subject = GetParam('subject');
	   $mail_text = GetParam('mail_text'); 
	   $param1 = GetGlobal('param1'); //== cmd line user param
	   $info = GetGlobal('info'); //receives errors
	   $g = getReq('g');
	   $a = getReq('a');	   
	   
  
       if (!$sFormErr) {  
  
	   switch ($sAction) {
        case "mail"         : //get html entry if any (window mail command)
                              if (($a) && (GetSessionParam("window_$a"))) {
							      //get window data and create html page 
	                              $mailpage = new phtml('',decode(GetSessionParam("window_$a"),true),"<h1>$this->headtitle</h1>");
	                              $tosend = $mailpage->render();
                                  unset($mailpage);
								
								  $this->mailbody = replace_htmlsestext("",$tosend);//("***",$tosend,2);
								
								  SetPreSessionParam('mailbody',$this->mailbody);
								  $this->htmladd = TRUE;	
                              }
							  //get html entry (browser mail command) 
							  if (($g)){// && (GetSessionParam("browsemail"))) {
							      
								  //if (file_exist($g))
							  }
		                      break;
			

		case $this->com1    ://send the mail now
                             if ((checkmail($to)) && ($subject)) {
                               if (seclevel('SENDMAIL_',$this->userLevelID)) { 
							   
							     //get subs list
							     $to .= $this->get_subscribers_list(); //echo $to;
								 
	                             //BULK MAIL SELECTION METHOD
	                             switch ($this->bulkmail) {								 
								   case 1: //select send method
										   set_time_limit($this->bulktimeout);								   
							               switch (paramload('MAIL','sendmh')) {
								             default:
								             case 1 : //smtp method 
									                  $this->send_smtpmail($from,$to,$subject,$mail_text); break;
								             case 2 : //mime method 
									                  $this->send_mimemail($from,$to,$subject,$mail_text); break;
								             case 3 : //mime method 
									                  $this->send_imapmail($from,$to,$subject,$mail_text); break;											
							               }
										   set_time_limit($this->timeout);							   
							               if (!$this->addto_mailbox("senditems",$from,$to,$subject,$mail_text))
							               $sFormErr = localize('_MLS1',getlocal());
								           break;
								   case 2: $bulklist = explode(",",$to); //print_r($bulklist);
				                           $maxlist = count($bulklist)-1;
										   for ($x=0;$x<=$maxlist;$x++) {
										     set_time_limit(5);
										     //select send method
											 //echo $bulklist[$x];
							                 switch (paramload('MAIL','sendmh')) {
								               default:
								               case 1 : //smtp method 
									                    $this->send_smtpmail($from,$bulklist[$x],$subject,$mail_text); break;
								               case 2 : //mime method 
									                    $this->send_mimemail($from,$bulklist[$x],$subject,$mail_text); break;
								               case 3 : //mime method 
									                    $this->send_imapmail($from,$bulklist[$x],$subject,$mail_text); break;											
							                 }
                                           }
										   set_time_limit($this->timeout);
										   $sFormErr =  $x . " " . localize('_MLS10',getlocal());
								           break;
								   default://select send method
										   set_time_limit($this->bulktimeout);								   
							               switch (paramload('MAIL','sendmh')) {
								             default:
								             case 1 : //smtp method 
									                  $this->send_smtpmail($from,$to,$subject,$mail_text); break;
								             case 2 : //mime method 
									                  $this->send_mimemail($from,$to,$subject,$mail_text); break;
								             case 3 : //mime method 
									                  $this->send_imapmail($from,$to,$subject,$mail_text); break;											
							               }
   										   set_time_limit($this->timeout);
							               if (!$this->addto_mailbox("senditems",$from,$to,$subject,$mail_text))
							               $sFormErr = localize('_MLS1',getlocal());
								 }			
								  	  	
							     if (!$info) SetGlobal('sFormErr', $sFormErr . localize('_MLS2',getlocal()));	//send message ok
								        else SetGlobal('sFormErr', $sFormErr . localize('_MLS9',getlocal()));	//some kind of error (smtp error)
	 		                     $this->ClearMailForm();	//clear the form
								 
								 //destructor
								 $this->manualfree();
							    }
								else
    							   SetGlobal('sFormErr',localize('_MLS3',getlocal())); 
	                         }
	                         else 
							   SetGlobal('sFormErr',localize('_MLS4',getlocal())); 
	                        break;
												
	    case $this->com2  : //send the mail later	
                            if ((checkmail($to)) && ($subject)) {
							
							  //get subs list
							  $to .= $this->get_subscribers_list();							
														   
							  if  (!$this->addto_mailbox("outbox",$from,$to,$subject,$mail_text))
							     $sFormErr = localize('_MLS1',getlocal());
								  	  	
							  $sFormErr .= localize('_MLS5',getlocal());	
	 		                  $this->ClearMailForm();	//clear the form	
							  //destructor
							  $this->manualfree();						  
	                        }
	                        else 
							  SetGlobal('sFormErr',localize('_MLS4',getlocal())); 	  						
	                        break;

	    case $this->com3  : //clear form
		                    $this->ClearMailForm();
							//destructor
							$this->manualfree();							
		                    break; 	
							
	    case "delmails"     : //delete all the selected mails	
	                        $err = $this->deleteMails($g);	
							SetGlobal('sFormErr',$err);
	                        break; 
							
	    case "sendmails"    : //sends all the selected mails	
	                        $err = $this->sendMails($g);	
							SetGlobal('sFormErr',$err);
	                        break; 
							
	    case "delimap"      : //delete all the selected mails from server (admin)	
	                        $err = $this->deleteIMAPMails();	
							SetGlobal('sFormErr',$err);
	                        break; 		
							
        case "attach"       : //attach file 
	                        $err = $this->addAttachment(); 
							SetGlobal('sFormErr',$err);
				            break; 
							
        case "cmaccount"    : //command line create mail account
		                    if ($param1) $this->create_mail_account($param1); 
		                    break;
							
		case "pop3"         ://experimental
		                    $this->readpop3();
		                    break;																															  						
      }
    }
  }
  
  function get_subscribers_list() {
     $includesubs = GetParam('includesubs');
	     
     if ($includesubs) {
		     $mysubs = new subscriber;
             $slist = $mysubs->getmails();
			 unset ($mysubs);
			 
			 $outlist = "," . $slist;
     }  
	 return ($outlist);
  }
  
  /////////////////////////////////////////////////////////////////////
  // mailbox commands
  /////////////////////////////////////////////////////////////////////
  function mboxcom() { 

    //commands       
    if (seclevel('SENDMAIL_',$this->userLevelID))
		 $com .= seturl("t=mail&a=&g=sendmail", localize('_NEWMAIL',getlocal()) ) . $this->outpoint;
    if (seclevel('RSVMAIL_',$this->userLevelID))		    
         $com.= seturl("t=mail&a=&g=inbox", localize('_INBOX',getlocal()) ) . $this->outpoint; 
    $com.= seturl("t=mail&a=&g=outbox", localize('_OUTBOX',getlocal()) ) . $this->outpoint;  
    $com.= seturl("t=mail&a=&g=senditems", localize('_SNTITEMS',getlocal()) ). $this->outpoint; 
    $com.= seturl("t=mail&a=&g=deleted", localize('_DELITEMS',getlocal()) ) . $this->outpoint;       
	
    $data[] = $com; 
    $attr[] = "left";
	
	$swin = new window('',$com,$attr);
	$out .= $swin->render("center::100%::0::group_dir_headtitle::left::0::0::");	
	unset ($swin);	  
  
    return $out;
  }   

  //////////////////////////////////////////////////////////////////
  //send a mail via an smtp server
  //////////////////////////////////////////////////////////////////
  function send_smtpmail($from,$to,$subject,$body) {
    
       $mail = new mime_mail($this->mailtype);
  
       $mail->from = $from;
       $mail->to = $to;
       $mail->subject = $subject;
       $mail->body = $body;
	   
	   //ATTATCHMENTS
       if ($this->mattach) {
         if (seclevel('ATTACHMENTS_',$this->userLevelID)) {	   
           if ($this->attachments) { 
             reset ($this->attachments);
             //while (list ($id, $data) = each ($this->attachments)) {
	         foreach ($this->attachments as $id => $data) {
	           $attachedfiles = explode(";",$data);
		 
	           $filefpath= $attachedfiles[0];
		       $filename = $attachedfiles[1]; 			   
		       $filetype = $attachedfiles[2]; 	
			   
			   $data = $this->readAttachment($filefpath);
			   if ($data) $mail->add_attachment($data,$filename,$filetype);   
		     }
	       }
	     }
	   }
  
       $data = $mail->get_mail();
	   
       $smtp = new smtp_mail;	    
       $smtp->send_email($this->smtp_srv,$from,$to,$data);
  }


  //////////////////////////////////////////////////////////////////
  //send a mail via an mime server
  //////////////////////////////////////////////////////////////////
  function send_mimemail($from,$to,$subject,$body) {
  
      $mail = new mime_mail;
  
      $mail->from = $from;
      $mail->to = $to;
      $mail->subject = $subject;
      $mail->body = $body;
  
     $mail->send();
  }

  //////////////////////////////////////////////////////////////////
  //send a mail via an mime server
  //////////////////////////////////////////////////////////////////
  function send_imapmail($from,$to,$subject,$body) {
    
      $mail = new mime_mail;
  
      $mail->from = $from;
      $mail->to = $to;
      $mail->subject = $subject;
      $mail->body = $body;
  
      $mailserver = $this->mailsrvt ."OUTBOX";
  	 
      $link = imap_open($mailserver,$this->usern,$this->passw);  
      echo ">" , imap_ping($link) , "<";
  
      imap_mail($to,$subject,$body);
  }


  /////////////////////////////////////////////////////////////////
  // check if a user mail account is created
  /////////////////////////////////////////////////////////////////
  function check_mail_account($user) {

    $fmeter=0;
    $ret = 0;

    $mdir = dir($this->maildir);   
    while ($fileread = $mdir->read ()) {
      if (strstr ($fileread,$user)) {
	    $ret = 1;
	    break;
      }
    }
    $mdir->close ();
  
    return ($ret);
  }


  /////////////////////////////////////////////////////////////////
  // create a new folder for user mail account
  /////////////////////////////////////////////////////////////////
  function create_mail_account($user) { 
  
    //create user mail account folder
    $fmdir = $this->maildir . "/" . $user; //echo $fmdir;
  
    if (@mkdir($fmdir, 0700)) {
      $ok = 1;
      setInfo ("Mail Account Created !");
    }
    else {
      $ok = 0;
      setInfo("Mail Account NOT Created !"); 
    }
  
    //create mail subfolfders
    if ($ok) {
      mkdir($fmdir."/inbox".$this->dirmark,0700);   
      mkdir($fmdir."/outbox".$this->dirmark,0700);  
      mkdir($fmdir."/senditems".$this->dirmark,0700);  
      mkdir($fmdir."/deleted".$this->dirmark,0700);  			
    }	
  
    return $ok; 
  }

  /////////////////////////////////////////////////////////////////
  // add mail to selected mailbox user folder
  /////////////////////////////////////////////////////////////////
  function addto_mailbox($box,$from,$to,$subject,$body) {

    if ($this->user) {
	
      $ok = 0;

      $mail = $body . "\n";
  
      // save file (if properties & security policy are ok)
      $mailname = $from . "~" . $to . "~" . $subject . ".ml";
      $actfile = $this->maildir . "/" . $this->user . "/" . $box . $this->dirmark . "/" . $mailname; 
      if ($fp = fopen ($actfile , "w")) {
         fwrite ($fp, $mail);
         fclose ($fp);
         $ok = 1;	
      }
      else $ok = 0; //error  
  
      return ($ok);
	}
	else
	  return 1; //fake result for unknown users (contact mail)
  }
  
  /////////////////////////////////////////////////////////////////////
  // send selected mails
  /////////////////////////////////////////////////////////////////////
  function sendMails($box) {
   
    $d = 0;
    $msg = "";
    
    //get user mail account senditems folder
    $sndidir = $this->maildir . "/" . $this->user . "/" . $box . $this->dirmark;
    $senditems = $this->maildir . "/" . $this->user . "/" . "senditems" . $this->dirmark;    

    $mdir = dir($sndidir);   
    while ($fileread = $mdir->read ()) {
      if (strstr($fileread,".ml")) {  
	    $i+=1;
  	    $mailheader = str_replace(".ml","",$fileread);
        $fullfile = $sndidir . "/" . $fileread;	  
        $sendfile = $senditems . "/" . $fileread;		
	  	
	    //get mail data
        $headsplit = split ("~",$mailheader);
	    $from = $headsplit[0];
	    $to = $headsplit[1];
	    $subject = $headsplit[2];
	  
	    //read mail body
	    if ($fp = fopen ($fullfile , "r")) {  
	      $body = fread ($fp, filesize($fullfile));
          fclose ($fp);
        }
	    else 
	      $msg .= "<br>Error opening mail No $i.";	  	  	  
	  	  
        $ischecked = GetParam("mailno$i");
	  
        if ($ischecked) {		  
          $this->send_smtpmail($from,$to,$subject,$body);		  
		  if (copy($fullfile,$sendfile)) {
		      $d+=1;
			  unlink($fullfile);						 
		  }						   
 	    }	  
      }
    }
    $mdir->close ();	
  
    $msg = $d . localize('_MLS6',getlocal());
  
    return ($msg);  
  }  
  
  /////////////////////////////////////////////////////////////////////
  // delete selected mails
  /////////////////////////////////////////////////////////////////////
  function deleteMails($box) {

    $d = 0;
    
    //get user mail account senditems folder
    $sndidir = $this->maildir . "/" . $this->user . "/" . $box . $this->dirmark;
    $deleted = $this->maildir . "/" . $this->user . "/" . "deleted" . $this->dirmark;  

    $mdir = dir($sndidir);   
    while ($fileread = $mdir->read ()) {
      if (strstr($fileread,".ml")) {  
	    $i+=1;
  	    $mailheader = str_replace(".ml","",$fileread);	
        $ischecked = GetParam("mailno$i");
	  
        if ($ischecked) {
          $fullfile = $sndidir . "/" . $fileread;	  
          $delfile = $deleted . "/" . $fileread;			  
		  
	      if ($box!="deleted") {
		    if (copy($fullfile,$delfile)) {
		      $d+=1;
			  unlink($fullfile);						 
			}				
          }
          else {		 	 			 				   
			 if (unlink($fullfile)) $d += 1; 
		  }			   
 	    }	  
      }
    }
    $mdir->close ();	    
  
    $msg = $d . localize('_MLS7',getlocal());
  
    return ($msg);
  }  
  
  function addAttachment() {
     $attachedfile = GetGlobal('attachedfile');
     $attachedfile_size = GetGlobal('attachedfile_size');
	 $attachedfile_name = GetGlobal('attachedfile_name');
	 $attachedfile_type = GetGlobal('attachedfile_type'); //get uploaded file form data & file's attrs 

  
     $myoutbox = $this->maildir . "/" . $this->user . "/" . "outbox" . $this->dirmark;  
     $myfile = $attachedfile_name;
     $myfilepath = $myoutbox . '/' . $myfile; 
	   
     //copy it to user write-enabled directory				   
     if (copy($attachedfile,$myfilepath)) {
	 
	    $this->attachments[] = $myfilepath . ";" . $attachedfile_name . ";" . $attachedfile_type;
        SetPreSessionParam('attachments',$this->attachments);	
		
        //$out = "File $attachedfile_name successfully attached !<br> Size : $attachedfile_size <br> Type : $attachedfile_type"; 
        unlink($attachedfile);		
     }
     else {
       $out = "Failed to attach file $attachedfile_name ! (Size Error?)"; 				   
     } 
  
     return ($out);
  }
  
  function viewAttachments() {	
	   
     if ($this->attachments) {
       $att[] = "<B>" . localize('_ATTACHMENTS',getlocal()) . ":</B>";
 	   $attattr[] = "right;30%;";
	 	 
       reset ($this->attachments);
       //while (list ($id, $data) = each ($this->attachments)) {
       foreach ($this->attachments as $id => $data) {
	     $attachedfiles = explode(";",$data);
	     $atfiles .= $attachedfiles[1] . "&nbsp;";
	   }
	   
       $att[] = $atfiles;
 	   $attattr[] = "left;70%;";	   
	   
	   $fwin = new window('',$att,$attattr);
	   $out = $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	   unset ($fwin);	 	   
	 }
	 return ($out);
  }
  
  function readAttachment($attachedfile) {
  
     if ($attachedfile) {
        if ($fp = fopen($attachedfile,"rb")) {
          $contents = fread ($fp, filesize ($attachedfile));
          fclose ($fp);		
		  
		  return ($contents);
		}
		else
		  return 0;
	 }
  }
  
  function ClearMailForm() {
  
     $this->attachments = 0; 
     SetSessionparam('attachments',$this->attachments);
 	 $this->mailbody = '';
	 SetSessionparam('mailbody',$this->mailbody);	  
  }    
  
  //reads mails from mailboxes
  function ReadSelectedMail() {
    $sFormErr = GetGlobal('sFormErr');
	$a = GetReq('a');
	$g = GetReq('g');	
	  
	$selmail = $a;
	  
    if ($selmail) {
	   if ($g) {  
	     //get user mail account selected folder
         $selfolder = $this->maildir . "/" . $this->user . "/" . $g . $this->dirmark;
	     $mailfile = $selfolder . "/" . $selmail . ".ml";
	
	     //get mail header
         $headmail = explode ("~",$selmail);
		 
	     $this->from = $headmail[0];	
	     $this->to = $headmail[1];
	     $this->subject = $headmail[2];		
	
	     //read mail body
	     if ($fp = fopen ($mailfile , "r")) {  
	       $this->mailbody = fread ($fp, filesize($mailfile));
           fclose ($fp);
         }
	     else {
	       SetGlobal($sFormErr, "<br>Error opening mail.");
		 }
	  
	   }
	 } 
  }

  /////////////////////////////////////////////////////////////////////
  // send/read mail form
  /////////////////////////////////////////////////////////////////////
  function sendmail($title='') {
     $sFormErr = GetGlobal('sFormErr');	
    
     $myaction = seturl("t=mail&a=&g=");   
	 
     //read selected mail if any
     $this->Readselectedmail();
     //navigation status      
     $out = setNavigator(localize('MAIL_DPC',getlocal()));
	 
     $out .= "<FORM action=". "$myaction" . " method=post>"; 	
	 	 
     //error message
     $out .= setError($sFormErr);		 
     //commands       
     $out .= $this->mboxcom();   
	 
	 //FROM..
     if (seclevel('ADMINMAIL_',$this->userLevelID)) {
        $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
		$fromattr[] = "right;30%;";
		$from[] = "<input type=\"text\" name=\"from\" maxlenght=\"20\" value=\"$this->from\">";
		$fromattr[] = "left;70%;";		
     }
     else {
        $usermail = $this->user. "@" . $this->domain;	 
	    $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
		$fromattr[] = "right;30%;";		  
        $from[] =  $usermail;  
		$fromattr[] = "left;70%;";
		//set from value as hidden field
		$from[] = "<input type=\"hidden\" name=\"from\" value=\"$usermail\">";
		$fromattr[] = "left";					
     }
	 $fwin = new window('',$from,$fromattr);
	 $winout .= $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	 unset ($fwin);	  
	 
     //TO..
     $to[] = "<B>" . localize('_TO',getlocal()) . ":</B>";
 	 $toattr[] = "right;30%;";	 
	 $totext = "<input type=\"text\" name=\"to\" maxlenght=\"20\" value=\"$this->to\">";	
	   
     if ( (defined('SUBSCRIBE_DPC')) && (seclevel('SUBSCRIBERSMAIL_',$this->userLevelID)) ) {	
	    $totext .= "<B>" . localize('_INCLSUBS',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includesubs\">";		
     } 
	 $to[] = $totext;
 	 $toattr[] = "left;70%;";	
	  
	 $twin = new window('',$to,$toattr);
	 $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	 unset ($twin);
     
	 //SUBJECT..
     $subt[] = "<B>" . localize('_SUBJECT',getlocal()) . ":</B>";
 	 $subattr[] = "right;30%;";	 
     $subt[] = "<input style=\"width:100%\" type=\"text\" name=\"subject\" maxlenght=\"30\" value=\"$this->subject\">"; 
 	 $subattr[] = "left;70%;";
	 
	 $swin = new window('',$subt,$subattr);
	 $winout .= $swin->render("center::100%::0::group_article_selected::left::0::0::");	
	 unset ($swin);	
	 
	 //ATTACHED FILES 
	 if ($this->attachments) {
	   $winout .= $this->viewAttachments();	   
	 }
	 
	 //HTML ADD
	 if ($this->htmladd) {
	   $hwin = new window('','>HTML contents added succsessfully !');
	   $winout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");	
	   unset ($hwin);		   
	 }	 
	 	 	       
	 //MAIL BODY..		   
     $form .= "Text   :<br>"; 	 
	 if (!defined('HTMLAREA_DPC')) { 
       $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=16 cols=50 wrap=\"virtual\">";
       $mbody .= $this->mailbody; 
       $mbody .= "</TEXTAREA>";
	 }
	 else {
	   $ta = new htmlarea();
	   $mbody = $ta->show('mail_text',$this->mailbody,16,50,'virtual');
	   unset ($ta);
	 }  
	 $mb[] = $mbody;
	 $mbattr[] = "center";
	 $mbwin = new window('',$mb,$mbattr);
	 $winout .= $mbwin->render("center::100%::0::group_win_body::left::0::0::");	
	 unset ($mbwin);	  
	 
	 //main window
	 $mymwin = new window($title,$winout);
	 $out .= $mymwin->render();	
	 unset ($mymwin);	 
	 
	 
	 //BUTTONS
     $cmd = "<input type=\"hidden\" name=\"FormName\" value=\"SendMail\">"; 
     $cmd .= "<INPUT type=\"submit\" name=\"FormAction\" value=\"" . $this->com1 . "\">&nbsp;";  
     $cmd .= "<INPUT type=\"submit\" name=\"FormAction\" value=\"" . $this->com2 . "\">&nbsp;";
     $cmd .= "<INPUT type=\"submit\" name=\"FormAction\" value=\"" . $this->com3 . "\">";	 
	 $but[] = $cmd;
	 $battr[] = "left";
	 $bwin = new window('',$but,$battr);
	 $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	 unset ($bwin);
	 	     
     $out .= "</FORM>";     
    
     if ($this->mattach) {
       if (seclevel('ATTACHMENTS_',$this->userLevelID)) {	 	
         //mail attachments form
		 
		 //$aform .= $this->viewAttachments();
		 
         $aform .= "<FORM action=". "$myaction" . " method=post ENCTYPE=\"multipart/form-data\">";
		 $aform .= localize('_ATTACHMENTS',getlocal());
         $aform .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"". $this->capacity ."\">"; //max file size option in bytes 			 	   
         $aform .= "<input type=FILE name=\"attachedfile\">";		    
         $aform .= "<input type=\"hidden\" name=\"FormName\" value=\"AttachFiles\">"; 	
         $aform .= "<input type=\"hidden\" name=\"FormAction\" value=\"attach\">&nbsp;";		
         $aform .= "<input type=\"submit\" name=\"Submit2\" value=\"" . localize('_ATTACH',getlocal()) . "\">";		
         $aform .= "</FORM>";  	
	
         $data1[] = $aform;
         $attr1[] = "left";
	     $awin = new window('',$data1,$attr1);
	     $out .= $awin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($awin);	
	   }
     } 
	 	
     return ($out);
  }  
  
  /////////////////////////////////////////////////////////////////////
  // read mailbox directory
  /////////////////////////////////////////////////////////////////////
  function readMailbox($box,$title='') {
    $sFormErr = GetGlobal('sFormErr');  
  
    $myaction = seturl("t=mail&a=&g=$box");   
  
    //navigation status      
    $out .= setNavigator(localize('MAIL_DPC',getlocal()));
  
    if ($this->user) {
  
      $out .= "<form action=\"$myaction\" method=\"POST\">";  
	  
      //error message
      $out .= setError($sFormErr); 
      //commands       
      $out .= $this->mboxcom();	  
  
      //mail list headers
	  $mhead[] = "&nbsp;X&nbsp;"; 
	  $mattr[] = "left;2%;";	
	  
	  if ($box=="inbox") {
	    $mhead[] = localize('_FROM',getlocal()); 
		$mattr[] = "left;38%;";	
   	  }
	  else {
	    $mhead[] = localize('_TO',getlocal());  
		$mattr[] = "left;38%;"; 
  	  }
	  $mhead[] = localize('_SUBJECT',getlocal());  
	  $mattr[] = "left;40%;";	
	  $mhead[] = localize('_DATETIME',getlocal());  
	  $mattr[] = "left;20%;"; 
	   
	  $awin = new window('',$mhead,$mattr);
	  $winout .= $awin->render("center::100%::0::group_form_headtitle::left::0::0::");	
	  unset ($awin);	
  
      //get user mail account senditems folder
      $sndidir = $this->maildir . "/" . $this->user . "/" . $box . $this->dirmark;
	
	  if (is_dir($sndidir)) { 	
        $mdir = dir($sndidir);

	    $i = 0;  
        while ($fileread = $mdir->read ()) {
          if (strstr($fileread,".ml")) {
	        $i+=1;
	        //get header data
		    $mailheader = str_replace(".ml","",$fileread);
	        $headsplit = split ("~",$mailheader);
	  	  
	        $mailb[] = "<input type=\"checkbox\" name=\"mailno$i\">";
	        $attrb[] = "left;2%;";
	        $mailb[] = summarize(30,$headsplit[1]);
	        $attrb[] = "left;38%;";
	        $mailb[] = seturl("t=mail&a=$mailheader&g=$box" , summarize(30,$headsplit[2]) );
	        $attrb[] = "left;40%;";
	        $mailb[] = date("d/m/y, H:i",filemtime($sndidir . "/" . $fileread));
	        $attrb[] = "left;20%;";	  
	  
	        $bwin = new window('',$mailb,$attrb);
	        $winout .= $bwin->render("center::100%::0::group_form_body::left::0::0::");	
	        unset ($bwin);
	  	  	  	  
			$mailarr[] = implode("||",$mailb);				  
				  
	        unset ($mailb);
	        unset ($attrb);
	      }	
        }	
		//print_r($mailarr);
        $mdir->close ();	
	  
	    /*$mywin = new window($title,$winout);
	    $out .= $mywin->render();	
	    unset ($mywin);	  */
		
        //browse
        $browser = new browse($mailarr,$title);
	    $out .= $browser->render("mail",30,$this,1,0,1,0);
	    unset ($browser);			
	
	    if ($box=="outbox") {
          $out .= localize('_DELETE',getlocal()) . "<input type=\"radio\" name=\"FormAction\" value=\"delmails\">";		    
	      $out .= localize('_SEND',getlocal()) . "<input type=\"radio\" name=\"FormAction\" value=\"sendmails\" checked>";	
          $out .= "&nbsp;<input type=\"submit\" value=\"" . localize('_SEND',getlocal()) . "\">";	
	    }
	    else {
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"delmails\">"; 	
          $out .= "<input type=\"submit\" value=\"" . localize('_DELETE',getlocal()) . "\">";		  	  
	    }  
        $out .= "</form>";	
	  }
	  else	
	    $out = "Invalid mailbox path"; 
    }
    else
      $out = "Invalid mailbox";
	
    return $out;	
  }

  /////////////////////////////////////////////////////////////////////
  // inbox
  /////////////////////////////////////////////////////////////////////
  function inbox($title='') {
    $sFormErr = GetGlobal('sFormErr');  

    if ($this->user) {
  
      $mm = 0;

      $mailserver = $this->mailsrvt . "INBOX";
      //print "$mailserver"; 	
      $link = @imap_open($mailserver,$this->usern,$this->passw);
  
      if (@imap_ping($link)) {

	   //search criteria
	   $criteria = $this->user;// . "@" . paramload('MAIL','maildomain');
	   $mymails = imap_search($link,"TO \"$criteria\""); 
	   
	   if ($mymails) {
         for ($i=0, $max = count($mymails); $i<$max; $i++) {
           $idx = $mymails[$i];
		
           $headobj = imap_header($link,$idx,80,80);
           $bodyobj = imap_body($link,$idx);	
		
		   $sender = $headobj->from[0];
		   $suname = $sender->mailbox;
		   $shost = $sender->host;
		   $from = $suname . "@" . $shost;		
		
		   $receiver = $headobj->to[0];
		   $tuname = $receiver->mailbox;
		   $thost = $receiver->host;	
	       $to = $tuname . "@" . $thost;					
		
		   $date = $headobj->udate;			
           $subject = $headobj->fetchsubject;
		   //$subj = str_replace(":",">",$subject); forward error		
		   //echo ($subj);
		
		   if ($this->addto_mailbox("inbox",$to,$from,$subject,$bodyobj)) {
		     $mm+=1;
		     //echo ($mm);
		  		  
		     //mark message for deletion
	         imap_delete($link,$idx); 
		   }					     
		   //echo (imap_fetchheader($link,$x,FT_UID)."<BR><P>");			
		   //echo (imap_body($link,$idx,FT_UID)."<BR><P>");	
	     }	
         imap_expunge($link);
	     imap_close($link);	
	     SetGlobal('sFormErr', $mm . localize('_MLS8',getlocal()));	     	
	   }	  
	  
       //read inbox items	
       $out .= $this->readMailbox("inbox",$title); 	      	 
      }
      else { //connection
       $data8[] = "<B>" . "No connection :" . imap_last_error() . "</B>";
       $attr8[] = "center";
 	   $bwin = new window('',$data8,$attr8);
	   $out .= $bwin->render("center::100%::0::group_dir_body::left::0::0::");	
	   unset ($bwin);   
      }  
    }
 
    return ($out);
  }


  /////////////////////////////////////////////////////////////////////////////////////
  //  mail views
  /////////////////////////////////////////////////////////////////////////////////////
  function mailboxview($box=null) {
    $a = GetReq('a');
	$g = GetReq('g');
  
    //assign global values
    $mail = $a;
    if (!$box) $box  = $g;
 
    if (!$mail) {
      switch ($box) {	
	    case "sendmail"  : $out = $this->sendmail(localize('_NEWMAIL',getlocal())); break;
	    case "inbox"     : $out = $this->inbox(localize('_INBOX',getlocal())); break;
	    case "adminbox"  : $out = $this->admin_inbox(localize('_ADMMAIL',getlocal())); break;	  
	    case "outbox"    : $out = $this->readMailbox($box,localize('_OUTBOX',getlocal())); break;
	    case "senditems" : $out = $this->readMailbox($box,localize('_SNTITEMS',getlocal())); break;
	    case "deleted"   : $out = $this->readMailbox($box,localize('_DELITEMS',getlocal())); break;		 	  	  	  
	    default          : $out = $this->sendmail(localize('_NEWMAIL',getlocal()));
      }
    }
    else
      $out = $this->sendmail(localize('_NEWMAIL',getlocal()));
		
    return $out;
  }

  
  
  

  /////////////////////////////////////////////////////////////////////
  // inbox administration
  /////////////////////////////////////////////////////////////////////
  function admin_inbox($title) {
    $sFormErr = GetGlobal('sFormErr');
	$g = GetReq('g');
 
 	
    if (seclevel('ADMINMAIL_',decode(GetSessionParam('UserSecID')))) { 
	
      $myaction = seturl("t=mail&a=&g=adminbox");    
	  
      //navigation status      
      $out = setNavigator(localize('MAIL_DPC',getlocal()));     
  
      $mailserver = $this->mailsrvt ."INBOX";
      //print "$mailserver"; 	
      $link = @imap_open($mailserver,$this->usern,$this->passw);	
      if (@imap_ping($link)) {  
	 
	    //get headers 
        $headers = imap_headers($link);	
	    $numheaders = count($headers);
	
	    if ($numheaders) {
	      //form
          $out .= "<form action=\"$myaction\" method=\"POST\">"; 
		  
          //error message
          $out .= setError($sFormErr); 
          //commands       
          $out .= $this->mboxcom(); 		  	
		  
          //mail list headers
	      $mhead[] = "&nbsp;X&nbsp;"; $mattr[] = "left;2%;";	
          $mhead[] = localize('_FROM',getlocal());  $mattr[] = "left;19%;";	
          $mhead[] = localize('_TO',getlocal());  $mattr[] = "left;19%;";		
	      $mhead[] = localize('_SUBJECT',getlocal());  $mattr[] = "left;40%;";	
	      $mhead[] = localize('_DATETIME',getlocal());  $mattr[] = "left;20%;";  
		  
	      $awin = new window('',$mhead,$mattr);
	      $winout .= $awin->render("center::100%::0::group_form_headtitle::left::0::0::");	
	      unset ($awin);	  
		
          for ($x=1; $x<=$numheaders; $x++) {
            //$idx = $x-1;
		    $headobj = imap_header($link,$x,80,80);			
		
		    $sender = $headobj->from[0];
		    $suname = $sender->mailbox;
		    $shost = $sender->host;
		
		    $receiver = $headobj->to[0];
		    $tuname = $receiver->mailbox;
		    $thost = $receiver->host;				
		
		    $date = $headobj->udate;			
            $subject = $headobj->fetchsubject; 
		
	        $mailb[] = "<input type=\"checkbox\" name=\"mailno$x\">";
	        $attrb[] = "left;2%;";
	        $mailb[] = summarize(20,$suname . "@" . $shost);
	        $attrb[] = "left;19%;";
	        $mailb[] = summarize(20,$tuname . "@" . $thost);
	        $attrb[] = "left;19%;";		
	        $mailb[] = summarize(30,$subject);
	        $attrb[] = "left;40%;";
	        $mailb[] = date("d/m/y, H:i",$date);
	        $attrb[] = "left;20%;";	  
	   
	        $bwin = new window('',$mailb,$attrb);
	        $winout .= $bwin->render("center::100%::0::group_form_body::left::0::0::");	
			
			$mailarr[] = implode("||",$mailb);
			
	        unset ($bwin);	  	  	  
	        unset ($mailb);
	        unset ($attrb);		
	      }		  
		  
	      //main window
	      $mywin = new window($title,$winout);
	      $out .= $mywin->render();	
	      unset ($mywin);		  
		  
          //browse
          $browser = new browse($mailarr,$title);
	      $out .= $browser->render("mail",30,$this,1,0,1,0);
	      unset ($browser);				  
	  
	      //form
          $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"delimap\">"; 	
          $out .= "<input type=\"submit\" value=\"" . localize('_DELETE',getlocal()) . "\">";		  	    
          $out .= "</form>";		  
	    }
	    else
	      $out .= "No incoming mails";
			     
      }
      else { //connection
        $out .= setError("No connection :" . imap_last_error());   
      }
	 
      @imap_close($link);  	 		  
	  
      return ($out);	  
    } //admin mails  
  }
  

  /////////////////////////////////////////////////////////////////////
  // delete selected mails from server (administrator only)
  /////////////////////////////////////////////////////////////////////
  function deleteIMAPMails() {

    $d = 0;
    
    $mailserver = $this->mailsrvt ."INBOX";
  	
    $link = imap_open($mailserver,$this->usern,$this->passw);
  
    //get headers 
    $headers = imap_headers($link);	
	  
    for ($x=1, $max = count($headers); $x<=$max; $x++) {
  
      //$idx = $x-1;
      $ischecked = GetParam("mailno$x");
	  
      if ($ischecked) {
        $d+=1;
	    //mark message for deletion
	    imap_delete($link,$x);    	   
 	  }	  
    }
    imap_expunge($link);
    imap_close($link);	
  	      
    $msg = $d . localize('_MLS7',getlocal());
  
   return ($msg);
  }
  
  
    //TEST POP3
    function readpop3() {
      $server = "195.97.2.42";
      $port = "110";

      $conn_timeout = "20";  // Connection Timeout
      $pop3_timeout = "10,500"; // Socket Timeout

      $username = "vasilis";
      $password = "123qwe%$";
      $apop = "0";

      $log = TRUE;
      $log_file = paramload('SHELL','prpath') . "pop3.log";

      $pop3 = new POP3($log,$log_file);	
	  
      if($pop3->connect($server,$port,$conn_timeout,$pop3_timeout)){
       if($pop3->login($username,$password,$apop)){
        // Send "NOOB" command !!!
        // Server alive ??
        if(!$pop3->noob()){
            echo $pop3->error;

        }
        // Get office status array with mail info's
        $msg_array = $pop3->get_office_status();
        if($msg_array["error"]){
            echo $msg_array["error"];
            return;
        }
        // Get mail
        $msgnum = "1";
        $delete = FALSE;
        $savetofile = TRUE;
        $message = $pop3->get_mail($msgnum);
        // print_r($message);
        if($message["error"]){
            echo $message["error"];
            return;
        }
        // Delete mail
        if($delete){
            if(!$pop3->delete_mail($msgnum)){
                echo $pop3->error;
                return;
            }
        }
        // Save mail to given filename !!!
        if($savetofile){
            $filename = $this->maildir . "/".base64_encode($msg_array[$msgnum]["uid"]).".txt";
            if(!is_file($filename)){
                $filesize = $pop3->save2file($message,$filename);
                if(!$filesize){
                    echo $pop3->error;
                    return;
                }
                echo "message save to ".$filename." (".$filesize." Bytes written)";
            }else{
                echo "message \"$filename\" already saved !!";
            }

        }
        
        // Close Connection
        if(!$pop3->close()){
            echo $pop3->error;
            return;
        }
        
       }else{
        echo $pop3->error;
        return;
       }
      }else{
       echo $pop3->error;
       return;
      }	  
	}
	
	function viewpop3() {
	   
	   $out = 'POP3 test!';
	   
	   return ($out);
	}
  
  
  
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	
       $out = $this->viewmbox($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7]);

	   return ($out);
	}		
	
    function viewmbox($id,$f1,$f2,$f3,$f4) {
	   $a = GetReq('a');
	   $g = GetReq('g');	   
											
	   switch ($g) {	
	   
	     case 'sendbox'   :	
		 case 'inbox'     :   
	                        $data[] = $id;
	                        $attr[] = "left;2%;";
	                        $data[] = $f1;
	                        $attr[] = "left;38%;";
	                        $data[] = $f2;
	                        $attr[] = "left;40%;";		
	                        $data[] = $f3;
	                        $attr[] = "left;20%;";
							break;			 
	     case 'outbox'    :
	     case 'senditems' :
	     case 'deleted'   :
	                        $data[] = $id;
	                        $attr[] = "left;2%;";
	                        $data[] = $f1;
	                        $attr[] = "left;38%;";
	                        $data[] = $f2;
	                        $attr[] = "left;40%;";		
	                        $data[] = $f3;
	                        $attr[] = "left;20%;";
							break;		 
		 
	     case 'adminbox'  :		 		 						  
	                        $data[] = $id;
	                        $attr[] = "left;2%;";
	                        $data[] = $f1;
	                        $attr[] = "left;19%;";
	                        $data[] = $f2;
	                        $attr[] = "left;19%;";		
	                        $data[] = $f3;
	                        $attr[] = "left;40%;";
	                        $data[] = $f4;
	                        $attr[] = "left;20%;";
							break;	
	   }						
	   
	   $myarticle = new window('',$data,$attr);
	   
	   $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}	
	
	function headtitle() {
	   $a = GetReq('a');
	   $g = GetReq('g');	
	   $p = GetReq('p');
	   $t = GetReq('t');	
	   $sort = GetReq('sort');		   	   	
	
	   $data[] = "X";//seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=0",  "X" );
	   $attr[] = "left;2%";		
	   
	   switch ($g) {
	     case 'sendbox'   :	
		 case 'inbox'     :				  				 
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_FROM',getlocal()) );
	                        $attr[] = "left;38%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_SUBJECT',getlocal()) );
	                        $attr[] = "left;40%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_DATETIME',getlocal()) );
	                        $attr[] = "left;20%";							
	                        break;
	     case 'outbox'    :
	     case 'senditems' :
	     case 'deleted'   :				 
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_TO',getlocal()) );
	                        $attr[] = "left;38%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_SUBJECT',getlocal()) );
	                        $attr[] = "left;40%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_DATETIME',getlocal()) );
	                        $attr[] = "left;20%";							
	                        break;
	     case 'adminbox'  :	
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=1" , localize('_FROM',getlocal()) );
	                        $attr[] = "left;19%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=2" , localize('_TO',getlocal()) );
	                        $attr[] = "left;19%";							
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=3" , localize('_SUBJECT',getlocal()) );
	                        $attr[] = "left;40%";
	                        $data[] = seturl("t=$t&a=$a&g=$g&p=$p&sort=$sort&col=4" , localize('_DATETIME',getlocal()) );
	                        $attr[] = "left;20%";							
	                        break;		 						
	   }										 
	   
   	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	  
    
	function manualfree() {
	   //session_unregister("mailbody");
	   //session_unregister("attachments");	
	   DeleteSessionParam("mailbody");
	   DeleteSessionParam("attachments");	   
	}
	
	function free() {//DELETE ATTACHMENTS AFTER SUBMIT
	   //session_unregister("mailbody");
	   //session_unregister("attachments");	
	}	
  
};
}
?>