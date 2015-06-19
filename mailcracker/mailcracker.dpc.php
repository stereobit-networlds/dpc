<?php

$__DPCSEC['MAILCRACKER_DPC']='1;1;1;1;2;2;2;2;9';//<<<<<
$__DPCSEC['ATTACHMENTS_']='2;1;1;1;2;2;2;2;9';
$__DPCSEC['SUBSCRIBERSMAIL_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ADMINMAIL_']='1;1;1;1;1;1;1;2;9';

if ((!defined("MAILCRACKER_DPC")) && (seclevel('MAILCRACKER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MAILCRACKER_DPC",true);

//require_once("mailcracker.lib.php");
//GetGlobal('controller')->include_dpc('mailcracker/mailcracker.lib.php');
$d = GetGlobal('controller')->require_dpc('mailcracker/mailcracker.lib.php');
require_once($d);

//require_once("pop3.lib.php");

$__DPC['MAILCRACKER_DPC'] = 'mailcracker';

$__EVENTS['MAILCRACKER_DPC'][0]='crackermail';
$__EVENTS['MAILCRACKER_DPC'][1]='crackerviewmail';
$__EVENTS['MAILCRACKER_DPC'][2]='crackernewmail';
$__EVENTS['MAILCRACKER_DPC'][3]='crackerdeletemail';
$__EVENTS['MAILCRACKER_DPC'][4]='crackerdownload';
$__EVENTS['MAILCRACKER_DPC'][5]='crackerattach';

$__ACTIONS['MAILCRACKER_DPC'][0]='crackermail';
$__ACTIONS['MAILCRACKER_DPC'][1]='crackerviewmail';
$__ACTIONS['MAILCRACKER_DPC'][2]='crackernewmail';
$__ACTIONS['MAILCRACKER_DPC'][3]='crackerdeletemail';
$__ACTIONS['MAILCRACKER_DPC'][4]='crackerdownload';
$__ACTIONS['MAILCRACKER_DPC'][5]='crackerattach';

$__DPCATTR['MAILCRACKER_DPC']['crackermail']='crackermail,0,0,1,0,0,0,0,0,0';

$__LOCALE['MAILCRACKER_DPC'][0]='MAILCRACKER_DPC;Inbox;Εισερχόμενα';
$__LOCALE['MAILCRACKER_DPC'][1]='_NEWMAIL;New Mail;Νεο Μήνυμα';
$__LOCALE['MAILCRACKER_DPC'][2]='_INBOX;Inbox;Εισερχόμενα';
$__LOCALE['MAILCRACKER_DPC'][3]='_OUTBOX;Outbox;Εξερχόμενα';
$__LOCALE['MAILCRACKER_DPC'][4]='_SNTITEMS;Sent Items;Απεσταλμένα';
$__LOCALE['MAILCRACKER_DPC'][5]='_DELITEMS;Deleted Items;Διεγραμμένα';
$__LOCALE['MAILCRACKER_DPC'][6]='_ADMMAIL;Mailbox Administration;Διαχείριση Ταχυδρομείου';
$__LOCALE['MAILCRACKER_DPC'][7]='_FROM;From;Απο';
$__LOCALE['MAILCRACKER_DPC'][8]='_TO;To;Σε';
$__LOCALE['MAILCRACKER_DPC'][9]='_SUBJECT;Subject;Θέμα';
$__LOCALE['MAILCRACKER_DPC'][10]='_MESSAGE;Message;Κείμενο';
$__LOCALE['MAILCRACKER_DPC'][11]='_DATETIME;Date/Time;Ημερ./Ωρα';
$__LOCALE['MAILCRACKER_DPC'][12]='_INCLSUBS;and Subscribers;σε συνδρομητές';
$__LOCALE['MAILCRACKER_DPC'][13]='_ATTACHMENTS;Attachments;Συνημένα';
$__LOCALE['MAILCRACKER_DPC'][14]='_DELETE;Delete;Διαγραφή';
$__LOCALE['MAILCRACKER_DPC'][15]='_SEND;Send;Αποστολή';
$__LOCALE['MAILCRACKER_DPC'][16]='_ADD2OUTBOX;Add to Outbox;Προσθήκη στα εξερχόμενα';
$__LOCALE['MAILCRACKER_DPC'][17]='_ATTACH;Attach;Προσθήκη';
$__LOCALE['MAILCRACKER_DPC'][18]='_MLS1;Unable to update mailbox !;Ανικανότητα ενημέρωσης';
$__LOCALE['MAILCRACKER_DPC'][19]='_MLS2;Send mail successfully !;Επιτυχής αποστολή !';
$__LOCALE['MAILCRACKER_DPC'][20]='_MLS3;No authority !;Μη εγγεκριμένη ενέργεια';
$__LOCALE['MAILCRACKER_DPC'][21]='_MLS4;Missing data !;Ελειπή δεδομένα !';
$__LOCALE['MAILCRACKER_DPC'][22]='_MLS5;Mail added to Outbox successfully !";Το μύνημα μετακινήθηκε στα Εξερχόμενα !';
$__LOCALE['MAILCRACKER_DPC'][23]='_MLS6; mail(s) send successfully !; Μυνήματα στάλθηκαν επιτυχώς !';
$__LOCALE['MAILCRACKER_DPC'][24]='_MLS7; mail(s) deleted successfully !; Μυνήματα διαγράφτηκαν επιτυχώς !';
$__LOCALE['MAILCRACKER_DPC'][25]='_MLS8; incoming mail(s) !; εισερχόμενα μυνήματα !';
$__LOCALE['MAILCRACKER_DPC'][26]='_CLRMAIL;Clear;Καθαρισμός';
$__LOCALE['MAILCRACKER_DPC'][27]='_MLS9;Error during mail operation. Please check your entries.;Προβλημα αποστολής. Παρακαλουμε ελεγξτε τα στοιχεία αποστολής.';
$__LOCALE['MAILCRACKER_DPC'][28]='_MLS10;mail(s) send.;μηνύματα στάλθηκαν.';
$__LOCALE['MAILCRACKER_DPC'][29]='MAILCRACKER_CNF;Manage Mailbox;Διαχ. ταχυδρομείου';
$__LOCALE['MAILCRACKER_DPC'][30]='_RECEIVED;Received;Παραλαβή';
$__LOCALE['MAILCRACKER_DPC'][31]='_MESSAGES;Message(s);Μυνήμα(τα)';
$__LOCALE['MAILCRACKER_DPC'][32]='_NOMESSAGES;No message(s) found;Δεν βρέθηκαν μυνήμα(τα)';
$__LOCALE['MAILCRACKER_DPC'][33]='_COMPOSE;Compose;Νέο';
$__LOCALE['MAILCRACKER_DPC'][34]='_READMSG;Read;Αναγνωση';
$__LOCALE['MAILCRACKER_DPC'][35]='_DELETEMSG;Delete;Διαγραφη';
$__LOCALE['MAILCRACKER_DPC'][36]='_REPLY;Reply;Απαντηση';
$__LOCALE['MAILCRACKER_DPC'][37]='_REPLYALL;Reply All;Απαντηση σε ολους';
$__LOCALE['MAILCRACKER_DPC'][38]='_FORWARD;Forward;Προωθηση';
$__LOCALE['MAILCRACKER_DPC'][39]='_ADMINCRACKER;Administration;Διαχειρηση';
$__LOCALE['MAILCRACKER_DPC'][40]='_SENDMSG;Send;Αποστολη';
$__LOCALE['MAILCRACKER_DPC'][41]='_SENDMSGAGAIN;Send Again;Επαναληψη';
$__LOCALE['MAILCRACKER_DPC'][42]='_HIDECC;Hide CC;Αποκρυψη CC';
$__LOCALE['MAILCRACKER_DPC'][43]='_SHOWCC;Show CC;Εμφάνιση CC';
$__LOCALE['MAILCRACKER_DPC'][44]='_HIDEBCC;Hide BCC;Αποκρυψη BCC';
$__LOCALE['MAILCRACKER_DPC'][45]='_SHOWBCC;Show BCC;Εμφάνιση BCC';
$__LOCALE['MAILCRACKER_DPC'][46]='_ACLICK;Click Here;Πατήστε εδώ';
$__LOCALE['MAILCRACKER_DPC'][47]='_ADOWNLD; to begin downloading the selected attachment.; για να λαβετε το επισυναπτόμενο αρχείο.';
$__LOCALE['MAILCRACKER_DPC'][48]='_AEND;Once the attachment has completed downloading, you may;Οταν ολοκληρωθεί η διαδικασία';
$__LOCALE['MAILCRACKER_DPC'][49]='_ACLOSE; close this window; κλείστε το παραθυρο';

class mailcracker extends cracker {
	
	var $userLevelID;
	var $username;
	var $user;
	var $password;
	var $outpoint;	
	var $server;
	
	var $mbox_open;
	var $mbox;
	var $mbox_info;
	var $txtDelMsg;
	var $from;
	var $to;
	var $cc;
	var $bcc;
	var $subject;
	var $message;
	var $success;
	
	var $msgcount;
	
	var $attachments;	
	var $mattach;	
	var $capacity;	
	var $formerr;
	
    var $maildir;
	var $dirmark;	
	
	var $active;

	function mailcracker() {
	   $UserSecID = GetGlobal('UserSecID');
	   $UserName = GetGlobal('UserName');
	   $Password = GetGlobal('Password');	   
	   $GRX = GetGlobal('GRX');
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	   
	   $this->active = false;
       $this->username = decode($UserName);	   
       $this->password = decode($Password);	//echo $this->password,'////'; 
	   $this->user = decode($UserName);	// . '@'. paramload('MAILCRACKER','domain');//'admin@panikidis.com';	   
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
	}
	
    function event($event=null) {

	    switch ($event) {
		  case 'crackernewmail'  : $this->cracker_mail_send(); break; 		  
		  case 'crackerdownload' : $this->cracker_attachment_download(); break;		
		  case 'crackerattach'   : $this->err = $this->addAttachment(); break; 
		}
    }	

	function action($action=null) {

	    switch ($action) {
		
		  case 'crackerviewmail' : $out = $this->cracker_mail_read(); break; 
		  case 'crackerattach'   :
		  case 'crackernewmail'  : $out = $this->cracker_mail_new(); break; 		  
		  case 'crackerdownload' : $out = null; break; //stoped at events
		  default                : $out = $this->cracker_mailbox_read();
		}
		
		return ($out);
	}
	
	function javascript() {
	
	    $download = seturl("t=crackerdownload");
	    $action = GetReq('t');
		$out = null;
	    
		$out .= <<<EOF
               //'Loading ...' message
               function loaded() {
                 document.getElementById('wait').style.display = "none";
               }

               //expand/show element
               function expand(listID) {
	             listID = document.getElementById(listID);
                 if (listID.style.display == "none") {
                   listID.style.display = "";
                 }
                 else {
                   listID.style.display = "none";
                 }
               }

               //contract/hide element
               function contract(listID) {
	             listID = document.getElementById(listID);
	             if (listID.style.display == "show") {
		           listID.style.display = "";
	             }
	             else {
		           listID.style.display = "none";
	             }
               }

               //change element innerText
               function chgText(id, str) {
	             document.getElementById(id).innerText = str;
               }		   
EOF;

        $out .= "//open 'download.php' in the center of the screen
               var scrTop = screen.availHeight / 2 - 65;
               var	scrLeft = screen.availWidth / 2 - 165;
               function download(id, pid) {
	             open(\"" . $download . "&id=\" + id + \"&pid=\" + pid, \"winDownload\", \"width=330, height=130, top=\" + scrTop + \", left=\" + scrLeft + \", status=yes, directories=no, toolbar=no, location=no, menubar=no, scrollbars=yes, resizable=yes\")
               }";

        if ($this->mbox_info->Nmsgs > 0) { 

           //'Delete Checked' code
           $out .= "\n\nfunction delChecked() {
	       var txtMsg = new Array(".($this->mbox_info->Nmsgs + 1).");";
		   
	       //print_r($this->txtDelMsg);
	       foreach($this->txtDelMsg as $key => $val) {
		     $out .= "	txtMsg[" . $key . "] = \"" . $val . "\";\n";
	       }


           $out .= <<<EOF
		            var msg = "";
	                if (document.delForm.elements['delMsg[]'].length > 1) {
		              for (i = 0; i < document.delForm.elements['delMsg[]'].length; i++) {
			            if (document.delForm.elements['delMsg[]'][i].checked) {
				          msg = msg + txtMsg[i+1] + "";
			            }
		              }
	                }
	                else {
		              if (document.delForm.elements['delMsg[]'].checked) {
			            msg = msg + txtMsg[1] + "";
		              } 
	                }
	                if (msg != "") {
		              delConfirm = confirm("The following messages will be deleted:" + msg);
		              if (delConfirm == true) {
			            document.delForm.submit();
		              }
	                }
	                else {
		              alert("No messages selected.");
	                }
		   }			
EOF;
	    }
			
	  
	    //echo $action;
	    switch ($action) {
	
          case 'crackerviewmail' : //echo 'ZZZZ';
		   $out .= "
                //'Delete' message
                function delChecked2() {
	              delConfirm = confirm(\"This message will be deleted.Click 'OK' to confirm deletion or 'Cancel' to abort.\");
	              if (delConfirm == true) {
		            document.delForm.submit();
	              }
                }";			  
		  break;
		  
		  case 'crackerdownload' : //echo 'XXX';
		  $out .= "function submitForm() {
	                 document.download.submitted.value = \"true\";
	                 document.download.submit();
                   }";
		  break;		   
		}//switch		  
	    return ($out);			  
	}
	
	function commands() {
	
	    $action = GetReq('t');
		$msgno = GetReq('id'); //if a message is viewed
		
		$viewmailbox = seturl("t=crackermail",localize('_INBOX'));
		$composemail = seturl("t=crackernewmail",localize('_COMPOSE'));
		$viewmail = seturl("t=crackerviewmail",localize('_READMSG'));		
		$deletemail = seturl("t=crackerdeletemail",localize('_DELETEMSG'));	
		
		$adminmail = seturl("t=crackeradminmail",localize('_ADMINCRACKER'));	//NOT USED
		//$adminmail = seturl("t=crackermail",localize('_ADMINCRACKER'));	//NOT USED
		
		$reply = seturl("t=crackernewmail&action=reply&id=".$msgno,localize('_REPLY'));
		$replyall = seturl("t=crackernewmail&action=replyall&id=".$msgno,localize('_REPLYALL'));
        $forward = seturl("t=crackernewmail&action=forward&id=".$msgno,localize('_FORWARD'));
		
		//javascript commands
		$hidecc = setjsurl(localize('_HIDECC'),"javascript:expand('myshow_cc');contract('myhide_cc');contract('inputCc')","myhide_cc","style=\"display:none\"");
		$showcc = setjsurl(localize('_SHOWCC'),"javascript:expand('inputCc');expand('myhide_cc');contract('myshow_cc')","myshow_cc");
		$hidebcc = setjsurl(localize('_HIDEBCC'),"javascript:expand('myshow_bcc');contract('myhide_bcc');contract('inputBcc')","myhide_bcc","style=\"display:none\"");
		$showbcc = setjsurl(localize('_SHOWBCC'),"javascript:expand('inputBcc');expand('myhide_bcc');contract('myshow_bcc')","myshow_bcc");
		$sendmail = setjsurl(localize('_SENDMSG'),"javascript:document.compose.submit()");			
		$delchecked = setjsurl(localize('_DELETEMSG'),"javascript:delChecked()");
		$resendmail = setjsurl(localize('_SENDMSGAGAIN'),"javascript:location.reload()");
		$deletethis = setjsurl(localize('_DELETEMSG'),"javascript:delChecked2()");
		$shownormalheader = setjsurl("Show normal headers","javascript:expand('reg');expand('mylink_full');contract('full');contract('mylink_reg')","mylink_reg","style=\"display:none\"");
		$showfullheader = setjsurl("Show full headers","javascript:expand('full');expand('mylink_reg');contract('reg');contract('mylink_full')","mylink_full");		
		$separator = "&nbsp;". $this->outpoint . "&nbsp;";		
		
	    switch ($action) {	
          case 'crackernewmail'  : if (isset($this->success)) {//true or false...is sended
		                             $out = $viewmailbox . 
		                                    $separator . 
		                                    $composemail . 
										    $separator .
										    $resendmail; 
			
		                           }
								   else { //is new mail
		                             $out = $viewmailbox . 
		                                    $separator . 
		                                    $hidecc . $showcc . 
										    $separator .
										    $hidebcc . $showbcc .
										    $separator .
										    $sendmail; 
								   }		  
		                           break;		
		  case 'crackerviewmail' : $out = $viewmailbox . $separator . 
		                                  $composemail . $separator .
										  $reply . $separator .
										  $replyall . $separator .
										  $forward . $separator .										  
										  $deletethis . $separator .
										  $shownormalheader . $showfullheader;
										  break;
		  case 'crackermail'     :  		  
		  default                : $out = $viewmailbox . $separator . 
		                                  $composemail . $separator . 
										  $delchecked .  $separator . $adminmail;
		}
		
        $data[] = $out; 
        $attr[] = "center";
	
	    $swin = new window('',$data,$attr);
	    $wout .= $swin->render("center::100%::0::group_dir_headtitle::left::0::0::");	
	    unset ($swin);			
		
		return ($wout);		
	}
	
  
  
  
  
  function cracker_mailbox_read() {
  
     $mbox_open = "{" . $this->server . ":110/pop3}INBOX";
	 
     // execute if POST var delMsg contains data (i.e. there are msgs to be deleted)
     if ($_POST['delMsg']) {

	   $mbox = @imap_open($mbox_open, $this->user, $this->password);

	   if (is_array($_POST['delMsg'])) {
		  foreach($_POST['delMsg'] as $key => $val) {
			imap_delete($mbox, $val);
		  }
	   }
	   else {
		  imap_delete($mbox, $_POST['delMsg']);
	   }
	   imap_expunge($mbox);
	   //update counter
	   $mbox_info = imap_mailboxmsginfo($mbox);	 
	   $this->msgcount = $mbox_info->Nmsgs;
	   
	   imap_close($mbox);
     }

     // open mailbox and get msgs
     $mbox = @imap_open($mbox_open, $this->user, $this->password);

     if ($mbox) {

	   $this->mbox_info = imap_mailboxmsginfo($mbox);

	   if ($this->mbox_info->Nmsgs > 0) {

		  for ($msgno = 1; $msgno <= $this->mbox_info->Nmsgs; $msgno++) {

			if ($header_info = $this->__get_headers($mbox, $msgno)) {
			
			    $composeurl = seturl("t=crackernewmail&to=".$header_info['fromAddr'],$header_info['fromAddr']);

				$from[$msgno] = $header_info['fromName'] . "&lt;" . $composeurl .  "&gt;";
				$to[$msgno] = $header_info['to'];
				if (!$header_info['subject']) {
					$subject[$msgno] = "{none}";
				}
				else {
					$subject[$msgno] = $header_info['subject'];
				}
				$date[$msgno] =  @date("D n/j/Y G:i", strtotime($header_info['date']));

			}

			$this->txtDelMsg[$msgno] = "<" . htmlspecialchars($header_info['fromAddr']) . ">: '" . htmlspecialchars($subject[$msgno]) . "'";

			unset($structure); //clear var
			//get structure
			$structure = imap_fetchstructure($mbox, $msgno);

			unset($sections); //clear var
			unset($attachments); //clear var
			// if multipart, parse
			if (sizeof($structure->parts) > 1) {
				$sections = $this->parse($structure);
				$attachments = $this->get_attachments($sections);
			}

			unset($attach);
			// if attachments exist
			if (is_array($attachments)) {
				$attach[$msgno] = "<IMG src=\"../images/att.gif\" alt=\"";
				for ($x = 0; $x < sizeof($attachments)-1; $x++) {
					$attach[$msgno] .= $attachments[$x]["name"] . "\n";
				}
				$attach[$msgno] .= $attachments[sizeof($attachments)-1]["name"] . "\">";
			}
		  }

	   }
	   else {
		 $errNo = 1; // error re: no messages
	   }
	
	   imap_close($mbox);

     }
     else {
	   $errNo = 2; // error re: cannot connect
     }  
		  
	 $deleteurl = seturl("t=crackermail&delete=1");	  
     $mout .= "<FORM name='delForm' action='$deleteurl' method='post'>"; 		
	 
	 $data[] = "&nbsp;";
	 $attr[] = "left;20";
	 $data[] = "&nbsp;";
	 $attr[] = "left;11";
	 $data[] = localize('_FROM',getlocal());
	 $attr[] = "left;40%";
	 $data[] = localize('_SUBJECT',getlocal());
	 $attr[] = "left;40%";	 	 	 
	 $data[] = localize('_RECEIVED',getlocal());
	 $attr[] = "left;16%";	 
	 
	 $hwin = new window("",$data,$attr);
	 $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	 unset($hwin); unset($data); unset($attr); 		
	
     if (!$errNo) {
	
	   for ($msgno = 1; $msgno <= $this->mbox_info->Nmsgs; $msgno++) {	 
	   
    	 $viewmsgurl = seturl("t=crackerviewmail&id=".$msgno);  
		
	     $data[] = "<INPUT type=\"checkbox\" name=\"delMsg[]\" value='".$msgno."'>";
	     $attr[] = "left;20";
	     $data[] = $attach[$msgno];
	     $attr[] = "left;11";
	     $data[] = $from[$msgno];
	     $attr[] = "left;40%";
		 $data[] = "<A href='".$viewmsgurl."'>".$subject[$msgno]."</a>";
	     $attr[] = "left;40%";	 	 	 
	     //$data[] = $date[$msgno];
		 //echo $msgno," ",$date[$msgno],"<br>";
	     (isset($subject[$msgno]) ? $data[] = $date[$msgno] : $data[] = "&nbsp;NONE&nbsp;");		 
	     $attr[] = "left;16%";	
		
	     $bwin = new window("",$data,$attr);
	     $mout .= $bwin->render("center::100%::0::group_article_body::left::0::0::");
	     unset($bwin); unset($data); unset($attr); 				
	   }
     }
     else if ($errNo == 1) {		
	
	   $ewin = new window("",localize('_NOMESSAGES',getlocal()));
	   $mout .= $ewin->render("center::100%::0::group_article_body::left::0::0::");
	   unset($ewin);  				
     }
     else {		
	 
	   $ewin = new window("","Cannot connect to ".$mbox_open);
	   $mout .= $ewin->render("center::100%::0::group_article_body::left::0::0::");
	   unset($ewin); 	   
     }		
	
	 $mout .= "</form>";
	 
     $out .= setNavigator(localize('MAILCRACKER_DPC',getlocal()),seturl("t=crackermail","Refresh"));	 
     $out .= $this->commands();	 
	 
	 $mwin = new window(localize('MAILCRACKER_DPC',getlocal()).": ".$this->msgcount ."&nbsp".localize('_MESSAGES',getlocal()),$mout);
	 $out .= $mwin->render("center::100%::0::group_dir_body::left::0::0::");
	 unset($mwin);		 
	 
	 return ($out);
  }
  
  function cracker_mail_read() {
	   
	 $msgno = GetReq('id');  //echo $msgno;
	 
	 $action = seturl("t=crackermail");
     $out .= "<FORM name=\"delForm\" action=\"$action\" method=\"post\"> 
	          <INPUT type=\"hidden\" name=\"delMsg\" value=\"$msgno\"></form>";	 
	 
	 //$mout .= "<P><A class=\"std\" href=\"compose_msg.php?id=$msgno&action=reply\">Reply</a>&nbsp;|&nbsp;<A class=\"std\" href=\"compose_msg.php?id=$msgno&action=replyall\">Reply All</a>&nbsp;|&nbsp;<A class=\"std\" href=\"compose_msg.php?id=$msgno&action=forward\">Forward</a>&nbsp;|&nbsp;<A class=\"std\" href=\"javascript:delChecked()\">Delete</a>&nbsp;|&nbsp;<A id=\"link_reg\" class=\"std\" href=\"javascript:expand('reg');expand('link_full');contract('full');contract('link_reg')\" style=\"display:none\">Show Normal Headers</a><A id=\"link_full\" class=\"std\" href=\"javascript:expand('full');expand('link_reg');contract('reg');contract('link_full')\">Show Full Headers</a>&nbsp;|&nbsp;<A class=\"std\" href=\"default.php?user=$userid\">Back</a></p>";
	   
	 $mout .= "<TABLE width='100%'>
	<TR>
		<TD width='200'></td>
		<TD></td>
	</tr>";  
  
     $mbox_open = "{" . $this->server . ":110/pop3}INBOX";

     $mbox = @imap_open($mbox_open,$this->user,$this->password);

     //get full message headers
     if ($headers = @imap_fetchheader($mbox, $msgno)) {

	   //build new headers array from retrieved array
	   $fullHeaders = explode("\r\n",trim($headers));
	   $x = -1;
	   foreach ($fullHeaders as $key => $val) {

		 $str = trim($val);
		 $check = trim(substr(htmlspecialchars($val),0,1));
		 if ($check != "") {
			$x++;
			$int = strpos($str,":");
			$item = substr($str, 0, $int+1);
			$data = trim(substr($str, $int+1));
			$newHeaders[$x] = $item . "==" . htmlspecialchars($data);
		 }
		 else {
			$newHeaders[$x] = $newHeaders[$x] . "<BR>" . htmlspecialchars($str);
		 }
	   }
	   //build table section	   
	   $mout .= "	<TBODY id=\"full\" style=\"display:none\">\n";
	   //build table section from new array data
	   foreach ($newHeaders as $key => $val) {
		 $data = explode("==",$val);
		 $mout .= "	<TR>\n";
		 $mout .= "		<TD class=\"hdr1\" valign=\"top\">" . $data[0] . "</td>\n";
		 $mout .= "		<TD class=\"hdr2\">" . $data[1] . "</td>\n";
		 $mout .= "	</tr>\n";
		 /*
	     $data[] = $data[0];
	     $attr[] = "left;50%";
	     $data[] = $data[1];
	     $attr[] = "left;50%"; 
	     $hwin = new window("",$data,$attr);
	     $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	     unset($hwin); unset($data); unset($attr);*/ 
	   }
	   $mout .= "	</tbody>\n";
     }

     // get message headers
     if ($header_info = $this->__get_headers($mbox, $msgno)) {

	   $mout .= "	<TBODY id=\"reg\">\n";
	   $mout .= "	<TR>\n";
	   $mout .= "		<TD class=\"hdr1\">Sent:</td>\n";
	   $mout .= "		<TD class=\"hdr2\">" . $header_info['date'] . "</td>\n";
	   $mout .= "	</tr>\n";
	   $mout .= "	<TR>\n";
	   $mout .= "		<TD class=\"hdr1\">From:</td>\n";
	   $mout .= "		<TD class=\"hdr2\">" . $header_info['fromName'] . " (" . $header_info['fromAddr'] . ")</td>\n";
	   $mout .= "	</tr>\n";
	   $mout .= "	<TR>\n";
	   $mout .= "		<TD class=\"hdr1\">To:</td>\n";
	   $mout .= "		<TD class=\"hdr2\">" . $header_info['to'] . "</td>\n";
	   $mout .= "	</tr>\n";
	   if ($header_info['cc']) {
		  $mout .= "	<TR>\n";
		  $mout .= "		<TD class=\"hdr1\">CC:</td>\n";
		  $mout .= "		<TD class=\"hdr2\">" . $header_info['cc'] . "</td>\n";
		  $mout .= "	</tr>\n";
	   }
	   $mout .= "	<TR>\n";
	   $mout .= "		<TD class=\"hdr1\">Subject:</td>\n";
	   $mout .= "		<TD class=\"hdr2\">" . $header_info['subject'] . "</td>\n";
	   $mout .= "	</tr>\n";
	   
	   /*$data[] = localize('_SENT',getlocal());
	   $attr[] = "left;20%";
	   $data[] = $header_info['date'];
	   $attr[] = "left;80%"; 
	   $hwin = new window("",$data,$attr);
	   $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($hwin); unset($data); unset($attr); 	   

	   $data[] = localize('_FROM',getlocal());
	   $attr[] = "left;20%";
	   $data[] = $header_info['fromName'] . " (" . $header_info['fromAddr'] . ")";
	   $attr[] = "left;80%"; 
	   $hwin = new window("",$data,$attr);
	   $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($hwin); unset($data); unset($attr); 	
	     	   
	   $data[] = localize('_TO',getlocal());
	   $attr[] = "left;20%";
	   $data[] = $header_info['to'];
	   $attr[] = "left;80%"; 
	   $hwin = new window("",$data,$attr);
	   $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($hwin); unset($data); unset($attr); 	
	   
	   if ($header_info['cc']) {
	   $data[] = localize('_CC',getlocal());
	   $attr[] = "left;20%";
	   $data[] = $header_info['cc'];
	   $attr[] = "left;80%"; 
	   $hwin = new window("",$data,$attr);
	   $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($hwin); unset($data); unset($attr); 		   
	   }	
	   
	   $data[] = localize('_SUBJECT',getlocal());
	   $attr[] = "left;20%";
	   $data[] = $header_info['subject'];
	   $attr[] = "left;80%"; 
	   $hwin = new window("",$data,$attr);
	   $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	   unset($hwin); unset($data); unset($attr);*/
	   
	   //get structure
	   $structure = imap_fetchstructure($mbox, $msgno);

	   // if multipart, parse
	   if (sizeof($structure->parts) > 1) {
		  $sections = $this->parse($structure);
		  $attachments = $this->get_attachments($sections);
	   }

	   // if attachments exist
	   if (is_array($attachments)) {
		  $mout .= "	<TR>\n";
		  $mout .= "		<TD valign=\"top\">Attachments:</td>\n";
		  $mout .= "		<TD valign=\"top\">\n";
	      //$data[] = localize('_ATTACHMENTS',getlocal());
	      //$attr[] = "left;20%";		  

		  // display as list
		  for ($x = 0; $x < sizeof($attachments)-1; $x++) {
			$attachments_out .= "<A href=\"javascript:download(" . $msgno . "," . $attachments[$x]["pid"] . ")\">" . $attachments[$x]["name"] . " (" . ceil($attachments[$x]["size"]/1024) . " KB)</a>, \n"."<br>";
		  }

		  $mout .= "			<A href=\"javascript:download(" . $msgno . "," . $attachments[sizeof($attachments)-1]["pid"] . ")\">" . $attachments[sizeof($attachments)-1]["name"] . " (" . ceil($attachments[sizeof($attachments)-1]["size"]/1024) . " KB)</a>\n";
		  $mout .= "		</td>\n";
		  $mout .= "	</tr>\n";

        /*  $dnload = $attachments_out . "<br>" .
		            "<A class=\"std\" href=\"javascript:download(" . $msgno . "," . $attachments[sizeof($attachments)-1]["pid"] . ")\">" . $attachments[sizeof($attachments)-1]["name"] . " (" . ceil($attachments[sizeof($attachments)-1]["size"]/1024) . " KB)</a>\n";
	      $data[] = $dnload;
	      $attr[] = "left;80%"; 
	      $hwin = new window("",$data,$attr);
	      $mout .= $hwin->render("center::100%::0::group_article_selected::left::0::0::");
	      unset($hwin); unset($data); unset($attr);*/
	   }
	   $mout .= "</tbody>\n";

	   $mout .= "	<TBODY>\n	<TR>\n";
	   $mout .= "		<TD colspan=\"2\" height=\"12\"></td>\n";
	   $mout .= "	</tr>\n";
	   $mout .= "	<TR>\n";
	   $mout .= "		<TD class=\"std\" colspan=\"2\" style=\"border-bottom: #BFCEE0 solid 1px\">\n";
	   $mout .= "\n<!-- START MESSAGE CONTENT -->\n\n";

	   //begin get text
	   // GET TEXT BODY
	   $dataTxt = $this->get_part($mbox, $msgno, "TEXT/PLAIN");
   
	   // GET HTML BODY
	   $dataHtml = $this->get_part($mbox, $msgno, "TEXT/HTML");

	   if ($dataHtml != "") {
		  $dataHtml_lines = explode("\n", $dataHtml);
		  unset($dataHtml);
		  foreach ($dataHtml_lines as $x => $line) {
			  if (stristr(trim($line), "<BODY ") == false) {
				  $dataHtml .= $line . "\n";
			  }
			  else {
				  $dataHtml .= "<BODY>\n";
			  }
		  }
		  $msgBody = $dataHtml;
		  $mailformat = "html";
	   }
	   else {
		  //$text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $text);
		  $dataTxt_lines = explode("\n", $dataTxt);
		  unset($dataTxt);
		  foreach ($dataTxt_lines as $x => $line) {
			$dataTxt .= htmlspecialchars($line) . "<BR>\n";
		  }
		  $msgBody = $dataTxt;
		  $mailformat = "text";
	   }
	   $mout .= $msgBody;	   
	   //$hwin = new window("",$msgBody);
	   //$mout .= $hwin->render("center::100%::0::group_article_body::left::0::0::");
	   //unset($hwin);  		   
	   //end get text

	   $mout .= "\n\n<!-- END MESSAGE CONTENT -->\n";
	   $mout .= "\n		</td>\n";
	   $mout .= "	</tr>\n	</tbody>";
 
	   imap_close($mbox);

       $mout .="</table>";
	   
       $out .= setNavigator(localize('MAILCRACKER_DPC',getlocal()));
       $out .= $this->commands();	   
	   
	   $mwin = new window("Read mail",$mout);
	   $out .= $mwin->render("center::100%::0::group_form_body::left::0::0::");
	   unset($mwin);	
	   
	   //$out .= seturl("t=crackermail","Return");	   

    }
    else {
	
      $out .= setNavigator(localize('MAILCRACKER_DPC',getlocal()));
      $out .= $this->commands();	
	
	  $msg = "Message no longer exists.<br>";
	  $msg .= seturl("t=crackermail","Return");	
	  $mwin = new window("Read mail",$msg);
	  $out = $mwin->render();
	  unset($mwin);
	  
    }  
  
  
    return ($out);
  }
  
  
  function cracker_mail_new() {
	   
    if ($_POST['sent']) {
	   
	   $done = seturl("t=crackermail","Done");
	   $fromName = $this->user;//GetParam('fromName');
	   $to = $this->to;//GetParam('to');
	   $subject = $this->subject;//GetParam('subject');
	   $fromReplyTo = $this->user;//GetParam('fromReplyTo');	   	   	   

	   $msg = ($this->success == true) ? "" : " NOT";

       $mout .= "Your message was $msg sent successfully.";
	   //$mout .= seturl("t=crackermail","Return");
	   
       $mout .= "<TABLE>
	<TR>
		<TD><B>From:</b></td>
		<TD width='100%'>$fromName&lt;$fromReplyTo&gt;</td>
	</tr>
	<TR>
		<TD><B>To:</b>
		<TD>$to</td>
	</tr>
	<TR>
		<TD><B>Subject:</b></td>
		<TD>$subject</td>
	</tr>
</table>

<P>$done</p>";


	
    }
    else { 
	
	  $this->success = null; //reinitialize success
	
	  $to = GetReq('to');//($_GET['to']) ? $_GET['to'] : "";
	  $msgno = GetReq('id');//($_GET['id']) ? $_GET['id'] : "";
	  $action = GetReq('action');//($_GET['action']) ? $_GET['action'] : ""; 
	   
     /* $mout .= "<P>".
	           "<A id='link_hide_cc' class='std' href=\"javascript:expand('link_show_cc');contract('link_hide_cc');contract('inputCc')\" style=\"display:none\">Hide Cc</a>".
			   "<A id=\"link_show_cc\" class=\"std\" href=\"javascript:expand('inputCc');expand('link_hide_cc');contract('link_show_cc')\">Show Cc</a>".
			   "&nbsp;|&nbsp;".
               "<A id=\"link_hide_bcc\" class=\"std\" href=\"javascript:expand('link_show_bcc');contract('link_hide_bcc');contract('inputBcc')\" style=\"display:none\">Hide Bcc</a>".
			   "<A id=\"link_show_bcc\" class=\"std\" href=\"javascript:expand('inputBcc');expand('link_hide_bcc');contract('link_show_bcc')\">Show Bcc</a>".
			   "&nbsp;|&nbsp;".
               "<A class=\"std\" href=\"javascript:document.compose.submit()\">Send</a>".
			   "&nbsp;|&nbsp;".
               "<A class=\"std\" href=\"default.php?user=$userid\">Back</a></p>";
			   
		*/		
	   //if responding to a message $msgno will have a value, and we will need to get the info from the server
	   if ($msgno != "") {

		 $mbox_open = "{" . $this->server . ":110/pop3}INBOX";

		 $mbox = @imap_open($mbox_open,$this->user,$this->password);

		 // get message headers
		 if ($header_info = $this->__get_headers($mbox, $msgno)) {

			$subject = $header_info['subject'];

			if (strstr($action, "reply")) {
				if (!strstr(strtolower($subject), "re:")) {
					$subject_temp = "re: " . $subject;
				}
				else {
					$subject_temp = $subject;
				}
				if ($action == "reply") {
					$to = $header_info['fromAddr'];
				}
				else {
					$to = $header_info['fromAddr'];
					if (strstr($header_info['to'], strip_tags($username))) {
						$aryTo = explode(",", $header_info['to']);
						for ($i = 0; $i < sizeof($aryTo); $i++) {
							if (trim($aryTo[$i]) == strip_tags($username)) $aryTo[$i]= "";
						}
						for ($i = 0; $i < sizeof($aryTo); $i++) {
							if ($aryTo[$i] != "") $to .= ", " . trim($aryTo[$i]);
						}
					}
					else {
						$to .= ", " . $header_info['to'];
					}
					if ($header_info['cc']) {
						if (strstr($header_info['cc'], strip_tags($username))) {
							$aryCc = explode(",", $header_info['cc']);
							for ($i = 0; $i < sizeof($aryCc)-1; $i++) {
								if (trim($aryCc[$i]) == strip_tags($username)) $aryCc[$i]= "";
							}
							for ($i = 0; $i < sizeof($aryCc)-1; $i++) {
								if ($aryCc[$i] != "") $cc .= trim($aryCc[$i]) . ", ";
							}
							if ($aryCc[sizeof($aryCc)-1] != "") $cc .= trim($aryCc[sizeof($aryCc)-1]);
						}
						else {
							$cc = $header_info['cc'];
						}
					}
				}
			}
			else if ($action == "forward") {
				if (!strstr(strtolower($subject), "fwd:") && !strstr(strtolower($subject), "fw:")) {
					$subject_temp = "fwd: " . $subject;
				}
				else {
					$subject_temp = $subject;
				}
			}

			//begin get text
			// GET TEXT BODY
			$dataTxt = $this->get_part($mbox, $msgno, "TEXT/PLAIN");

			// GET HTML BODY
			$dataHtml = $this->get_part($mbox, $msgno, "TEXT/HTML");

			if ($dataHtml != "") {
				$msgBody = $dataHtml;
				$mailFormat = "html";
			}
			else {
			   	$msgBody = ereg_replace("\r","<BR>",$dataTxt);
			   	$mailFormat = "text";
			}

			if ($action) {
				if ($mailFormat == "html") {
					$msgHeader = "<P style=\"font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10pt; color: #000080\">\n\n</p>\n";
					$msgBody = "<SPAN style=\"font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10pt;\"><I>" . date("D, M j, Y G:i", strtotime($header_info['date'])) . "</i><BR>\n<B>" . $header_info['fromName'] . "</b> (" . $header_info['fromAddr'] . ")<BR>\n" . $subject . "</span>\n" . $msgBody;
					$msgBody = $msgHeader . "<DIV style=\"border-left: #800000 solid 3px; margin-left: 20px; padding: 10px\">\n" . $msgBody . "</div>\n";
					$msgBody = ereg_replace("&lt;","&amp;lt;",$msgBody);
					$msgBody = ereg_replace("&gt;","&amp;gt;",$msgBody);
				}
				else if ($mailFormat == "text") {
					$msgHeader = "---|  Original Message  |---\n" . date("D, M j, Y G:i", strtotime($header_info['date'])) . "\n" . $header_info['fromName'] . " (" . $header_info['fromAddr'] . ")\n" . $subject;
					$msgBody = ereg_replace("<BR>","",$dataTxt);
					$aryMsgBody = split("\n",$msgBody);
					foreach ($aryMsgBody as $key => $val) {
						if ($key == 0) {
							$msgBody = "\n\n\n" . $msgHeader . "\n\n> " . htmlspecialchars($val) . "\n";
						}
						else {
							if (trim($val) != "") {
								$msgBody .= "> " . htmlspecialchars($val) . "\n";
							}
						}
					}
				}
			}
			//end get text
 
		 }
		 imap_close($mbox);
	   }				
	   
	   
	   /*$mout .= "<TR>
			<TD class=\"hdr1\" width=\"100\">From:</td>
			<TD class=\"hdr2\">";
			
	   $mout .= "<SELECT name=\"from\"><OPTION value=\"$userid\">$name&lt;$replyTo&gt;</option>";
					
       foreach ($userids as $key => $mailUser) {
	      if ($mailUser != $userid) {
		    $mout .= "<OPTION value=\"" . $mailUser . "\">" . $names[$key] . " &lt;" . $replyTos[$key] . "&gt;</option>\n";
	      }
       }	
	   
	   $mout .= "</select>";
	   
	   $mout .= "</td>
		</tr>";*/		
		
	   $faction = seturl("t=crackernewmail"); 		
		
       $mout .= "<FORM name=\"compose\" action=\"$faction\" method=\"post\">
	            <TABLE width=\"100%\">";		
		
	   $mout .= "<TR><TD class=\"hdr1\" width=\"100\">To:</td>
			          <TD class=\"hdr2\"><INPUT type=\"text\" name=\"to\" value=\"$to\" size=\"90\"></td>
		         </tr>";
				  
	   $mout .= "<TR id=\"inputCc\"";
	   if (!$cc) $mout .= " style=\"display:none\">";
	   $mout .= "<TD class=\"hdr1\" width=\"100\">Cc:</td>
			     <TD class=\"hdr2\"><INPUT type=\"text\" name=\"cc\" value=\"$cc\" size=\"90\"></td>";
	   $mout .= "</tr>";
	   
	   $mout .= "<TR id=\"inputBcc\"";
	   if (!$bcc) $mout .= " style=\"display:none\">";
	   $mout .= "<TD class=\"hdr1\" width=\"100\">Bcc:</td>
			     <TD class=\"hdr2\"><INPUT type=\"text\" name=\"bcc\" value=\"$bcc\" size=\"90\"></td>";
	   $mout .=	"</tr>";
	   
	   $mout .= "<TR>
			       <TD class=\"hdr1\" width=\"100\">Subject:</td>
			       <TD class=\"hdr2\"><INPUT type=\"text\" name=\"subject\" value=\"$subject_temp\" size=\"90\"></td>
		         </tr>";
				 
	   $mout .= "<TR>
			<TD colspan=\"2\" height=\"12\"></td>
		</tr>
		<TR>
			<TD colspan=\"2\" style=\"border-bottom: #BFCEE0 solid 1px\">
				<TABLE>
					<TR>
						<TD>
							<TEXTAREA name=\"msgText\" rows=\"15\" cols=\"80\">$msgBody</textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>

	    </table>";				
		
	   if ((!$mailFormat) || ($mailFormat == "html")) {
	     $mout .= "<INPUT type=\"hidden\" name=\"mailFormat\" value=\"html\">";
	   }
	   else {
	     $mout .= "<INPUT type=\"hidden\" name=\"mailFormat\" value=\"plain\">";
	   }

	   $mout .= "<INPUT type=\"hidden\" name=\"sent\" value=\"true\">";

       $mout .= "</form>";	
	   
	   //ATTACHED FILES 
	   if ($this->attachments) {
	     $mout .= $this->viewAttachments();	   	   	
	   }	   	
	   $mout .= $this->formAttachments();	   
	   
     }//else
	 
     $out .= setNavigator(localize('MAILCRACKER_DPC',getlocal()));
     $out .= $this->commands();	
	 if ($this->formerr) { //form messages
	   $out .= $this->formerr;
	   $this->formerr = null; //init
	 }  
	 
	 $mwin = new window("New mail",$mout);
	 $out .= $mwin->render("center::100%::0::group_form_body::left::0::0::");
	 unset($mwin);		 
	 
	 return ($out);
  }	
  
  
  function cracker_mail_send() {
  
    if ($_POST['sent'] == "true") {

	  // set-up email
	  /* recipients */
	  $this->to = $_POST['to'];
	  
	  if ($_POST['cc']) {
		$this->cc = $_POST['cc'];
	  }//echo $this->cc;
	  if ($_POST['bcc']) {
		$this->bcc = $_POST['bcc'];
	  }//echo $this->bcc;

	  /* subject */
	  $this->subject = stripslashes($_POST['subject']);

	  /* message */
	  if ($_POST['mailFormat'] == "html") {
		$this->message = "<html><head><title>Message</title></head><body>" . stripslashes($_POST['msgText']) . "</body></html>";
	  }
	  else {
		$this->message = stripslashes($_POST['msgText']);
	  }

	  // additional headers 
	  $this->from = $this->user;//$_POST['from'];
	  
	 /* foreach ($userids as $key => $mailUser) {
		if ($from == $mailUser) {
			$fromReplyTo = $replyTos[$key];
			$fromName = $names[$key];
		}
	  }*/
	  $n = explode('@',$this->username);
	  $fromName=$n[0];/*'admin'*/; $fromReplyTo = $this->username;//'admin@panikidis.com';//!!!!!!!!!!!!!

	  $headers = "Return-Path: " . $fromReplyTo . "\r\n";
	  $headers .= "Reply-To: " . $fromReplyTo . "\r\n";
	  $headers .= "From: " . $fromName . " <" . $fromReplyTo . ">\r\n";
	  if ($cc != "") {
		$headers .= "Cc: " . $this->cc . "\r\n";
	  }
	  if ($bcc != "") {
		$headers .= "Bcc: " . $this->bcc . "\r\n";
	  }
	  $headers .= "Subject: " . $this->subject . "\r\n";	  
	  $headers .= "MIME-Version: 1.0\r\n";
	  $headers .= "Content-type: text/" . $_POST['mailFormat'] . "; charset=iso-8859-1\r\n";
	  $headers .= "X-Mailer: phpdac5_crackerMail v1.0\r\n";
	   
	  $headers_array = explode("\r\n",$headers);
	  
 	  // send the email 
      $ret = $this->send_smtpmail($this->from,$this->to,$this->message,$headers_array);
      //$ret = $this->send_mimesmtpmail($this->from,$this->to,$this->message,$headers);
	  //$ret = $this->send_mimeimapmail($this->from,$this->to,'xxx',$this->message,$headers);
      
	  if ($ret===true) {
	    $this->success = true;
	  }
	  else {
	    $this->success = false;
		SetInfo("Mail sending error!");
      }		
    }  
  }
  
  function cracker_attachment_download() {
  
    $id = $_REQUEST['id'];
    $pid = $_REQUEST['pid'];  
    $submitted = $_POST['submitted'];
	$action = seturl("t=crackerdownload");

    // form not submitted
    if ((!$submitted) || ($submitted == "false")) {	
      //echoing at new page
	  //<LINK rel=\"stylesheet\" href=\"crackerMail.css\" type=\"text/css\">
	  
      //echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
      //<HTML><HEAD><TITLE>Download</TITLE>
      echo "<SCRIPT>
      function submitForm() {
	    document.download.submitted.value = \"true\";
	    document.download.submit();
      }
      </script>";
	  //</HEAD><BODY>";
	  	  
	  echo "<FORM name=\"download\" action=\"$action\" method=\"post\">
	        <INPUT type=\"hidden\" name=\"user\" value=\"$userid\">
	        <INPUT type=\"hidden\" name=\"id\" value=\"$id\">
	        <INPUT type=\"hidden\" name=\"pid\" value=\"$pid\">
	        <INPUT type=\"hidden\" name=\"submitted\" value=\"false\">
	        <A class=\"std\" href=\"javascript:submitForm()\">" . 
			localize('_ACLICK',getlocal()) .
			"</a>" . 
			localize('_ADOWNLD',getlocal()) .
			"</form><P>" .
			localize('_AEND',getlocal()) .
			"<A class=\"std\" href=\"javascript:window.close()\">" .
			localize('_ACLOSE',getlocal()) .
			"</a>.</p>";		
    }
    else {	
	
	  // open POP connection
	  $mbox = imap_open("{". $this->server . "/pop3:110}", $this->user, $this->password);

	  // parse message structure
	  $structure = imap_fetchstructure($mbox, $id);
	  $sections = $this->parse($structure);

	  // look for specified part
	  for($x=0; $x < sizeof($sections); $x++) {
		if ($sections[$x]["pid"] == $pid) {
			$type = $sections[$x]["type"];
			$encoding = $sections[$x]["encoding"];
			$filename = $sections[$x]["name"];
		}
	  }
	  $attachment = imap_fetchbody($mbox, $id, $pid);

	  // send headers to browser to initiate file download
	  header ("Content-Type: $type");
	  header ("Content-Disposition: attachment; filename=$filename");
	  if ($encoding == "base64") {
		// decode and send
		echo imap_base64($attachment);
	  }
	  else {
		// add handlers for other encodings here
		echo $attachment;
	  }

	  // clean up
	  imap_close($mbox);			
	}
	
	echo "\n</body></html>";
	exit(0);  //exit!!!!!!!!!!!!!!!!!!!!!!!!!!!
  }
  
  
  
  
  
  ///////////////////////////////////////////////////////////////////////// mail

  
   function formAttachments() {
   
     $myaction = seturl("t=crackernewmail");     
   
     if ($this->mattach) {
       if (seclevel('ATTACHMENTS_',$this->userLevelID)) {	 	
         //mail attachments form
		 
		 //$aform .= $this->viewAttachments();
		 
         $aform .= "<FORM action=". "$myaction" . " method=post ENCTYPE=\"multipart/form-data\">";
		 $aform .= localize('_ATTACHMENTS',getlocal());
         $aform .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"". $this->capacity ."\">"; //max file size option in bytes 			 	   
         $aform .= "<input type=FILE name=\"attachedfile\">";		    
         $aform .= "<input type=\"hidden\" name=\"FormName\" value=\"AttachFiles\">"; 	
         $aform .= "<input type=\"hidden\" name=\"FormAction\" value=\"crackerattach\">&nbsp;";		
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
   
  
  function addAttachment() {
  
     $attachedfile = $_FILES['attachedfile'];
     $attachedfile_size = $attachedfile['size'];
	 $attachedfile_name = $attachedfile['name'];
	 $attachedfile_type = $attachedfile['type']; //get uploaded file form data & file's attrs 
     $tmpfile = $attachedfile['tmp_name'];	 

     //print_r($_FILES);
     $myoutbox = $this->maildir . "/" . $this->user . "/" . "outbox" . $this->dirmark;  
     $myfile = $attachedfile_name;
     $myfilepath = $myoutbox . '/' . $myfile; 
	 //echo $myfilepath;  
	 
     //copy it to user write-enabled directory				   
     //if (copy($attachedfile,$myfilepath)) {
	 if (copy($tmpfile,$myfilepath)) {
	 
	    $this->attachments[] = $myfilepath . ";" . $attachedfile_name . ";" . $attachedfile_type;
        SetPreSessionParam('attachments',$this->attachments);	
		
        $this->formerr = "File $attachedfile_name successfully attached !<br> Size : $attachedfile_size <br> Type : $attachedfile_type"; 
        unlink($tmpfile);		
     }
     else {
       $this->formerr = "Failed to attach file $attachedfile_name ! (Size Error?)"; 				   
     } 
  
     //return ($out);
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
  

  /////////////////////////////////////////////////////////////////////
  // send/read mail form
  /////////////////////////////////////////////////////////////////////
  function sendmail($title='') {
     $sFormErr = GetGlobal('sFormErr');	
    
     $myaction = seturl("t=mail&a=&g=");   
	 
     //read selected mail if any
     $this->Readselectedmail();
     //navigation status      
     $out = setNavigator(localize('MAILCRACKER_DPC',getlocal()));
	 
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
   

  //////////////////////////////////////////////////////////////////
  //send a mail via an smtp server
  //////////////////////////////////////////////////////////////////
  function send_smtpmail($from,$to,$body,$headers_array) {
  
	  if (class_exists('smtp_class')) { //lower - level
	  	  $smtpm = new smtp_class;
		  
	      $smtpm->host_name=paramload('SMTPMAIL','smtpserver');//"xmail.panikidis.gr"; /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
	      $smtpm->localhost=paramload('SMTPMAIL','localhost');//"daidalos"; /* Your computer address */	  	  
	      $smtpm->direct_delivery=0;     /* Set to 1 to deliver directly to the recepient SMTP server */
	      $smtpm->timeout=10;            /* Set to the number of seconds wait for a successful connection to the SMTP server */
	      $smtpm->data_timeout=0;        /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
	                                 Set to 0 to use the same defined in the timeout variable */
	      $debugpcl = arrayload('SMTPMAIL','debug');								 
	      $smtpm->debug = $debugpcl[$this->userLevelID]; //0;            /* Set to 1 to output the communication with the SMTP server */
	      $smtpm->html_debug=1;          /* Set to 1 to format the debug output as HTML */
	      $smtpm->pop3_auth_host=paramload('SMTPMAIL','pop3host');     /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
	      $smtpm->user=paramload('SMTPMAIL','user'); /* Set to the user name if the server requires authetication */
	      $smtpm->realm=paramload('SMTPMAIL','realm'); /* Set to the authetication realm, usually the authentication user e-mail domain */
	      $smtpm->password=paramload('SMTPMAIL','password');  /* Set to the authetication password */	  
	      //$smtpm->authentication_mechanism="NTLM";		
		  
		  if ($smtpm->SendMessage($from,
	                               explode(",",$to),
								   $headers_array,
								   $body)
					  			 )	
		    $success = true;
	      else {
		    $success = false;	    
			SetInfo($smtpm->error);
		  }	
		  
		  unset($smtpm);
		  return $success;
	  }
	  else
	    return false;	  
  }
  
  //////////////////////////////////////////////////////////////////
  //send a mime mail via an smtp server
  //////////////////////////////////////////////////////////////////  
  function send_mimesmtpmail($from,$to,$body,$headers) {
    
	  if (class_exists('mime_mail')) {	
       $mail = new mime_mail($this->mailtype);
  
       $mail->from = $from;
       $mail->to = $to;
       //$mail->subject = $subject;..is in headers
       $mail->body = $body;
	   $mail->headers = $headers;
	   
	   //ATTACHMENTS
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
  
       $maildata = $mail->get_mail();
	  }
	  else {
	    $msg = "Mime_mail class required!";
	    SetInfo($msg);
	  }	
	  
	  /*if (class_exists('smtp_mail')) { 
        $smtp = new smtp_mail;	    
        $smtp->send_email($this->server,$from,$to,$data);
		return true;
	  }	
	  else {
	    $msg .= "Smtp_mail class required!";
	    SetInfo($msg);
		return false;	  
	  }*/
	  $ret = $this->send_smtpmail($from,$to,$maildata,explode("\r\n",$headers));
	  
	  //remove attachments
	  if ($ret==true)
	    DeleteSessionParam("attachments");
	  
	  return ($ret);
  }

  //////////////////////////////////////////////////////////////////
  //send a mime mail via an imap server
  //////////////////////////////////////////////////////////////////
  function send_mimeimapmail($from,$to,$subject,$body,$headers='') {
    
      $mail = new mime_mail;
  
      $mail->from = $from;
      $mail->to = $to;
      $mail->subject = $subject;
      $mail->body = $body;
	  
	  $maildata = $mail->get_mail();
  
      $mbox_open = "{" . $this->server . ":110/pop3}OUTBOX";
  	 
      $link = imap_open($mbox_open,$this->user,$this->password);  
      echo ">" , imap_ping($link) , "<";
  
      $ret = imap_mail($to,$subject,$body,$headers);
	  
	  return $ret;
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
	   //DeleteSessionParam("mailbody");
	   //DeleteSessionParam("attachments");	   
	}
	
	function free() {//DELETE ATTACHMENTS AFTER SUBMIT
	   //session_unregister("mailbody");
	   //session_unregister("attachments");	
	}	
  
};
}
?>