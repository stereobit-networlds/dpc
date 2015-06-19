<?php

$__DPCSEC['CMAIL_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['SENDCMAIL_']='2;1;2;2;2;2;2;2;9';

if ((!defined("CMAIL_DPC")) && (seclevel('CMAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CMAIL_DPC",true);

$__DPC['CMAIL_DPC'] = 'contactmail';
 
$__EVENTS['CMAIL_DPC'][0]="sendcmail";

$__ACTIONS['CMAIL_DPC'][0]='cmail';
$__ACTIONS['CMAIL_DPC'][1]="sendcmail";

$__DPCATTR['CMAIL_DPC']['cmail'] = 'cmail,0,0,0,0,0,0,0,0';
$__DPCATTR['CMAIL_DPC']['sendcmail'] = 'sendcmail,0,0,0,0,0,0,0,0';

$__LOCALE['CMAIL_DPC'][0]='CMAIL_DPC;Support;Υποστήριξη';
$__LOCALE['CMAIL_DPC'][1]='_SENDCMAIL;Submit;Αποστολή';
$__LOCALE['CMAIL_DPC'][2]='_CLRCMAIL;Clear;Καθαθαρισμός';
$__LOCALE['CMAIL_DPC'][3]='_MLS2;Send mail successfully !;Επιτυχής αποστολή !';
$__LOCALE['CMAIL_DPC'][4]='_MLS3;No authority !;Μη εγγεκριμένη ενέργεια';
$__LOCALE['CMAIL_DPC'][5]='_MLS4;Missing data !;Ελειπή δεδομένα !';
$__LOCALE['CMAIL_DPC'][6]='_MLS9;Error during mail operation. Please check your entries.;Προβλημα αποστολής. Παρακαλουμε ελεγξτε τα στοιχεία αποστολής.';

class contactmail {

	var $message;
	var $alias;	
	var $mailalias;
	
	var $userLevelID;	
	
	var $post;
	var $missingmail;
	var $chatbox;
	
	function contactmail() {
	   $UserSecID = GetGlobal('UserSecID');
	
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   		   
	   $this->message = paramload('CMAIL','msg');
	   $this->missingmail = paramload('CMAIL','missingmailmsg');	   
	   $this->chatbox = paramload('CMAIL','chatbox');	   
	   $this->alias = arrayload('CMAIL','departalias');	   
	   $this->depmail = arrayload('CMAIL','departmail');
	   $this->mailalias = array();	   

	   $this->title = localize('CMAIL_DPC',getlocal());	   	   	   
	   
	   $this->post = false; //hold successfull posting   
	   
	   //attach alias to real mails
	   if (!empty($this->alias)) {
		foreach ($this->alias as $num=>$malias)
			$this->mailalias[$malias] = $this->depmail[$num];
	   }	
	}
	

    function event($sAction) {  	   	   	     
    
  
	   switch ($sAction) {	

		case "sendcmail"  ://send the mail
                           $this->OnAction_sendcmail();						   	 		
	                       break; 																											  						
       }
  }
  
  function action($action) {
     $__USERAGENT = GetGlobal('__USERAGENT');
     
	 switch ($__USERAGENT) {
	   case 'HTML' : $out = $this->cmailform(); break;
	   case 'GTK'  : $out = "cmail"; break;
	 }
	 
	 return ($out);
  }
  
  function OnAction_sendcmail() {
       $sFormErr = GetGlobal('sFormErr');
       $info = GetGlobal('info');	 
	   $from = GetParam('from');
	   $to = GetParam('to');
	   $subject = GetParam('subject');
	   $mail_text = GetParam('mail_text');   
  
       if (!$sFormErr) { 
	     
                          if (seclevel('SENDCMAIL_',$this->userLevelID)) { 	
						   
	                         //get mail addr from alias name
		                     $sendto = $this->mailalias[$to]; //echo ">>>",$to, $sendto;						   	
							 
                             $this->post = $this->sendit($from,$sendto,$subject,$mail_text); 					 
						   }
						   else
    						 SetGlobal('sFormErr',localize('_MLS3',getlocal())); 
							 
	   }						   
  } 
  
  function sendit($from,$to,$subject,$mail_text='') {
       $sFormErr = GetGlobal('sFormErr');
	   //global $info; //receives errors	 

       if ((checkmail($from)) && ($subject)) {
	   
         $smtpm = new smtpmail;
		 $smtpm->to = $to; 
		 $smtpm->from = $from; 
		 $smtpm->subject = $subject;
		 $smtpm->body = $mail_text;
		 
		 $err = $smtpm->smtpsend();
		 unset($smtpm);				 
					     	  	
  	     if (!$err) {
		   SetGlobal('sFormErr',localize('_MLS2',getlocal()));	//send message ok
		   return true;
		 }         
		 else { 
		   SetGlobal('sFormErr',localize('_MLS9',getlocal()));	//error
		   setInfo($err);//$info); //smtp error = global info
		 }  
       }
       else 
	     SetGlobal('sFormErr',localize('_MLS4',getlocal()));
		 
	   return false;	  	   
  }   
  
  /////////////////////////////////////////////////////////////////////
  // contact mail form
  /////////////////////////////////////////////////////////////////////
  function cmailform($action=null,$nocheck=null) {
     $sFormErr = GetGlobal('sFormErr');
	 
	 //url params
	 $department = GetReq('department'); 
	 $subject = GetReq('subject');
	 $body = GetReq('body');
	    
     $out = setNavigator($this->title);
	 
	 if (trim($this->chatbox)!='') { //echo "chat:",$this->chatbox;
             if (iniload('JAVASCRIPT')) {		
  	            $plink = "<A href=\"" . seturl("") . "\"";	   
	            //call javascript for opening a new browser win for the img		   
	            $params = $this->chatbox . ";Chat;scrollbars=no,width=640,height=480;";

				$js = new jscript;
	            $plink .= GetGlobal('controller')->calldpc_method('javascript.JS_function use js_openwin+'.$params);
				          //comma values includes at params ?????
				          //$js->JS_function("js_openwin",$params); 
                unset ($js);

	            $plink .= ">"; 
	         }	  
			 
			 $chat = "<H3>" . $plink . "Chat" . "</A>" . "</H3>";
			         //seturl("",localize('_HOME',getlocal())) . "</H3>";
			 $win = new window('Chat',$chat);
			 $out .= $win->render("center::100%::0::group_win_body::center::0::0::");		
			 unset($win);			  
	 }
	 
	 if ($this->post==true) { //succsessfull posting
	 
       //succseffull message
       $msg = setError($sFormErr);	
	     	 
	   $mywin = new window($this->title,$msg);
	   $out .= $mywin->render("center::70%::0::group_win_body::left::0::0::");	
	   unset ($mywin);		   	 
	 }
	 else {
	   if ($nocheck) {
	   
	     $out .= setError($this->message);//info message
	     $w = $this->form($action);
	     //main window
	     $mywin = new window($this->title,$w);
	     $out .= $mywin->render();	
	     unset ($mywin);			 
	   }
	   else {//check customers mail 
         if ( (defined('SENCUSTOMERS_DPC')) && (seclevel('SENCUSTOMERS_DPC',$this->userLevelID)) ) {
           $customer_mail = GetGlobal('controller')->calldpc_method('sencustomers.getcustomerdata use 8'); //10=email of record
	     }	
	    
	     if (trim($customer_mail)!=null) {	 
		 
		   $out .= setError($this->message);//info message	 
	       $w = $this->form($action,null,$customer_mail);
	       //main window
	       $mywin = new window($this->title,$w);
	       $out .= $mywin->render();	
	       unset ($mywin);			   
	     }
	     else {
           //customer mail missing
           $msg = setError($this->missingmail);	
	   
           if ( (defined('SENCUSTOMERS_DPC')) && (seclevel('SENCUSTOMERS_DPC',$this->userLevelID)) ) 	   
             $msg .= GetGlobal('controller')->calldpc_method('sencustomers.showcustomerdata'); 
	     	 
	   
	       $mywin = new window($this->title,$msg);
	       $out .= $mywin->render("center::70%::0::group_win_body::left::0::0::");	
	       unset ($mywin);		 	   
	     }
	   }	 
	 }
	 
     return ($out);
  }  
  
  function form($action=null,$rows=null,$from=null) {
  
         if (!$rows)
		   $rows = 16;
  
         if ($action)
		   $myaction = seturl("t=".$action);   
		 else  
           $myaction = seturl("t=cmail");   
	 
         $out .= "<FORM action=". "$myaction" . " method=post>"; 	
	 	 
         //error message
         $out .= setError($sFormErr);		  
	 
	     //FROM..
		 if ($from) {
           $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
           $fromattr[] = "right;10%;";
	       $from[] = $from . "<input type=\"hidden\" name=\"from\" maxlenght=\"20\" value=\"".$customer_mail."\">";
	       $fromattr[] = "left;90%;";		
		 }

	     $fwin = new window('',$from,$fromattr);
	     $winout .= $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($fwin);	  
	 
         //TO..
         $to[] = "<B>" . localize('_TO',getlocal()) . ":</B>";
 	     $toattr[] = "right;10%;";	 
	     //$totext = "<input type=\"text\" name=\"to\" maxlenght=\"20\" value=\"$this->to\">";	
	 
	     //get department's mails
	     $totext = "<select name=\"to\">"; 
	     foreach ($this->alias as $num=>$malias) {
		   if ($department==$num)
		     $totext .= "<option selected>" . $malias ."</option>";
		   else	 
	         $totext .= "<option>" . $malias ."</option>";
	     }
	     $totext .= "</select>";
	     $to[] = $totext;
 	     $toattr[] = "left;90%;";	
	  
	     $twin = new window('',$to,$toattr);
	     $winout .= $twin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($twin);
     
	     //SUBJECT..
		 if ($subject) $sbj = $subject;
		          else $sbj = GetParam('subject');
         $subt[] = "<B>" . localize('_SUBJECT',getlocal()) . ":</B>";
 	     $subattr[] = "right;10%;";	 
         $subt[] = "<input style=\"width:100%\" type=\"text\" name=\"subject\" maxlenght=\"30\" value=\"".$sbj."\">"; 
 	     $subattr[] = "left;90%;";
	 
	     $swin = new window('',$subt,$subattr);
	     $winout .= $swin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($swin);	 
	 	       
	     //MAIL BODY..		   
         $mb[] = "<B>" . localize('_MESSAGE',getlocal()) . ":</B>";
 	     $mbattr[] = "right;10%;";	 
         $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";
		 if ($body) $mbody .= $body;
		       else $mbody .= GetParam('mail_text');		 
         //$mbody .= GetParam('mail_text');//$this->mailbody; 
         $mbody .= "</TEXTAREA></DIV>";
	     $mb[] = $mbody;
	     $mbattr[] = "left;90%";
	     $mbwin = new window('',$mb,$mbattr);
	     $winout .= $mbwin->render("center::100%::0::group_win_body::left::0::0::");	
	     unset ($mbwin);	  
	 
	     //main window
	     $mywin = new window('',$winout);
	     $out .= $mywin->render();	
	     unset ($mywin);	 
	 
	 
	     //BUTTONS
		 if ($action) {
           $cmd = "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . $action . "\">&nbsp;";  
           $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . $action . "\">";			 
		 }
		 else {
           $cmd = "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
           $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SENDCMAIL',getlocal()) . "\">&nbsp;";  
           $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "sendcmail" . "\">";	 
		 }  
	     $but[] = $cmd;
	     $battr[] = "left";
	     $bwin = new window('',$but,$battr);
	     $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($bwin);
	 	     
         $out .= "</FORM>"; 
		 
		 return ($out);     
  }
  
  function setform($subject,$body=null) {
    
	 SetParam('subject',$subject);
     SetParam('mail_text',$body);
  }	
    
};
}
?>