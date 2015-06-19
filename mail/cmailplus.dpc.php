<?php
$__DPCSEC['CMAILPLUS_DPC']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['ALLOWFROM_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ALLOWTO_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ALLOWATTACH_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['ALLOWSUBS_']='2;1;1;1;1;1;2;2;9';

if ((!defined("CMAILPLUS_DPC")) && (seclevel('CMAILPLUS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CMAILPLUS_DPC",true);

$__DPC['CMAILPLUS_DPC'] = 'contactmailplus';

//require_once("cmail.dpc.php");
//GetGlobal('controller')->include_dpc('mail/cmail.dpc.php');
$d = GetGlobal('controller')->require_dpc('mail/cmail.dpc.php');
require_once($d);

//require_once("mime.lib.php");
//GetGlobal('controller')->include_dpc('mail/mime.lib.php');
$d = GetGlobal('controller')->require_dpc('mail/mime.lib.php');
require_once($d);

GetGlobal('controller')->get_parent('CMAIL_DPC','CMAILPLUS_DPC');
//print_r($__ACTIONS['CMAILPLUS_DPC']);

//extend events & actions
$__EVENTS['CMAILPLUS_DPC'][10]="cattach";
$__ACTIONS['CMAILPLUS_DPC'][10]="cattach";

//overwrite for cmd line purpose	
$__LOCALE['CMAILPLUS_DPC'][0]='CMAILPLUS_DPC;Support;Υποστήριξη';

$__LOCALE['CMAILPLUS_DPC'][1]='_INCLSUBS;and Subscribers;σε συνδρομητές';	
$__LOCALE['CMAILPLUS_DPC'][2]='_ATTACHMENTS;Attachments;Συνημένα';

class contactmailplus extends contactmail {

  var $capacity;
  var $path2save;
  var $mailtype;
  var $attachments;  
  var $timeout;
	
  function contactmailplus() {
	
	 contactmail::contactmail();
	 
     $this->timeout = paramload('SHELL','timeout');		 
     $this->capacity = paramload('CMAILPLUS','maxattachedsize');	 
     $this->path2save = paramload('CMAILPLUS','path2save');	 
	 
     $this->mailtype = paramload('CMAILPLUS','mailtype');//'text/html';
	 
	 $at = GetSessionParam('attachments');
     if ($at) 
	   $this->attachments = $at;
	 else 
	   $this->attachments = array();		 
  }
  
  function event($event) {
  
     contactmail::event($event); 
	 
	 switch ($event) {
 
        case "cattach"  : //attach file 
	                        $err = $this->addAttachment(); 
							SetGlobal('sFormErr',$err);
				            break; 	 
	 }
  }
  
  function action($action) {
  
     $out = contactmail::action($action);
	 //$out = $this->cmailform(); ????
	 
	 return ($out);
  }  
   
	
  //overwrite	
  function cmailform($action=null) {
     $sFormErr = GetGlobal('sFormErr');
	 
	 //url params
	 $department = GetReq('department'); 
	 $subject = GetReq('subject');
	 $body = GetReq('body');
	 
	 if ($action)
	   $myaction = seturl("t=".$action);   
	 else 
       $myaction = seturl("t=cmail");   
	    
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
	 
	   //get email from masterdetail of user > level2 user
       if ( (defined('SENCUSTOMERS_DPC')) && (seclevel('SENCUSTOMERS_DPC',$this->userLevelID)) ) {
         $customer_mail = GetGlobal('controller')->calldpc_method('sencustomers.getcustomerdata use 8'); //10=email of record 
	   }
	   //get email from registered users <level2 user
	   if (!$customer_mail) { 
         if ( (defined('SENUSERS_DPC')) && (seclevel('SENUSERS_DPC',$this->userLevelID)) ) {	   	
	       $customer_mail = GetGlobal('controller')->calldpc_method('senusers.getuserdata use 5');
	       //echo $customer_mail,">>>>>>>>>>>>";
		 }  
	   }
	    
	   if (trim($customer_mail)!=null) {	 	 
	     $out .= setError($this->message);//info message
	 
         $out .= "<FORM action=". "$myaction" . " method=post>"; 	
	 	 
         //error message
         $out .= setError($sFormErr);		  
	 
	     //FROM..(CHANGED))
         $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
         $fromattr[] = "right;10%;";
		 if (seclevel('ALLOWFROM_',$this->userLevelID)) {
	       $from[] = "<input type=\"text\" name=\"from\" maxlenght=\"40\" value=\"".$customer_mail."\">";
	       $fromattr[] = "left;90%;";			 
		 }
		 else {
	       $from[] = $customer_mail . "<input type=\"hidden\" name=\"from\" maxlenght=\"20\" value=\"".$customer_mail."\">";
	       $fromattr[] = "left;90%;";		
		 }  

	     $fwin = new window('',$from,$fromattr);
	     $winout .= $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($fwin);	  
	 
         //TO..
         $to[] = "<B>" . localize('_TO',getlocal()) . ":</B>";
 	     $toattr[] = "right;10%;";	 
		 
		 if (seclevel('ALLOWTO_',$this->userLevelID)) {		 
	       $totext = "<input type=\"text\" name=\"to\" maxlenght=\"30\" value=\"\">";	
	     }
		 else { 
	       //get department's mails
	       $totext = "<select name=\"to\">"; 
	       foreach ($this->alias as $num=>$malias) {
		     if ($department==$num)
		       $totext .= "<option selected>" . $malias ."</option>";
		     else	 
	           $totext .= "<option>" . $malias ."</option>";
	       }
	       $totext .= "</select>";
		 }
		 
		 //SUBSCRIBERS
         if ( (defined('SENSUBSCRIBE_DPC')) && (seclevel('ALLOWSUBS_',$this->userLevelID)) ) {	
	       $totext .= "<B>" . localize('_INCLSUBS',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includesubs\">";		
         } 		 
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
		 
		 
		 if ($body) $contentbody .= $body;
		       else $contentbody .= GetParam('mail_text');		 
		 
	     if (defined('HTMLAREA_DPC')) { 	
	       $ta = new htmlarea();
	       $mbody = $ta->show('mail_text',$contentbody,16,50,'virtual');
	       unset ($ta);		  
		 }
		 else {
           $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=16 cols=60 wrap=\"virtual\">";
           $mbody .= $contentbody; 		 
           //$mbody .= GetParam('mail_text');//$this->mailbody; 
           $mbody .= "</TEXTAREA></DIV>";
		 }   
		 
		 
	     $mb[] = $mbody;
	     $mbattr[] = "left;90%";
	     $mbwin = new window('',$mb,$mbattr);
	     $winout .= $mbwin->render("center::100%::0::group_win_body::left::0::0::");	
	     unset ($mbwin);	  
	 
	     //main window
	     $mywin = new window($this->title,$winout);
	     $out .= $mywin->render();	
	     unset ($mywin);	 
	 
	 
	     //BUTTONS
         $cmd = "<input type=\"hidden\" name=\"FormName\" value=\"SendCMail\">"; 
         $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . localize('_SENDCMAIL',getlocal()) . "\">&nbsp;";  
		 if ($action)
		   $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . $action . "\">";	 
		 else
           $cmd .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "sendcmail" . "\">";	 
	     $but[] = $cmd;
	     $battr[] = "left";
	     $bwin = new window('',$but,$battr);
	     $out .= $bwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($bwin);
	 	     
         $out .= "</FORM>";   
		 
		 
		 //ATTACHMENTS
         if (seclevel('ALLOWATTACH_',$this->userLevelID)) {	 	
           //mail attachments form
		 
		   $aform = $this->viewAttachments();
		 
           $aform .= "<FORM action=". "$myaction" . " method=post ENCTYPE=\"multipart/form-data\">";
		   $aform .= localize('_ATTACHMENTS',getlocal());
           $aform .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" VALUE=\"". $this->capacity ."\">"; //max file size option in bytes 			 	   
           $aform .= "<input type=FILE name=\"attachedfile\">";		    
           $aform .= "<input type=\"hidden\" name=\"FormName\" value=\"AttachFiles\">"; 	
           $aform .= "<input type=\"hidden\" name=\"FormAction\" value=\"cattach\">&nbsp;";		
           $aform .= "<input type=\"submit\" name=\"Submit2\" value=\"" . localize('_ATTACH',getlocal()) . "\">";		
           $aform .= "</FORM>";  	
	
           $data1[] = $aform;
           $attr1[] = "left";
	       $awin = new window('',$data1,$attr1);
	       $out .= $awin->render("center::100%::0::group_article_selected::left::0::0::");	
	       unset ($awin);	
	     }	   
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
	 
     return ($out);
  } 
  
  function addAttachment() {
     $attachedfile = $_FILES['attachedfile']['tmp_name'];
     $attachedfile_size = $_FILES['attachedfile']['size'];
	 $attachedfile_name = $_FILES['attachedfile']['name'];
	 $attachedfile_type = $_FILES['attachedfile']['type']; 
	 $attachedfile_error = $_FILES['attachedfile']['error'];
  
     $myfile = $attachedfile_name;
     $myfilepath = paramload('SHELL','prpath') . $this->path2save . $myfile; 
	 //echo $myfilepath;
	   
     //copy it to user write-enabled directory				   
     if (copy($attachedfile,$myfilepath)) {
	 //if ($attatchedfile_error==0) {
	 
	    $this->attachments[] = $myfilepath . ";" . $attachedfile_name . ";" . $attachedfile_type;
        SetSessionParam('attachments',$this->attachments);	
		
        //$out = "File $attachedfile_name successfully attached !<br> Size : $attachedfile_size <br> Type : $attachedfile_type"; 
        //unlink($attachedfile);		
     }
     else {
       $out = "Failed to attach file $attachedfile_name ! (Size Error?)"; 				   
     } 
	 
	 //print_r($_FILES);
  
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
  
  function viewAttachments() {	
	   
     if ($this->attachments) {
	 
       $att[] = "<B>" . localize('_ATTACHMENTS',getlocal()) . ":</B>";
 	   $attattr[] = "right;30%;";
	 	 
       reset ($this->attachments);
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
  
  //overwrite
  function OnAction_sendcmail() {
       $sFormErr = GetGlobal('sFormErr');
       $info = GetGlobal('info');	 
	   $from = GetParam('from');
	   $to = GetParam('to');
	   $subject = GetParam('subject');
	   $mail_text = GetParam('mail_text'); 
	   
       $includesubs = GetParam('includesubs');	     
  
       if (!$sFormErr) { 
	     
                          if (seclevel('SENDCMAIL_',$this->userLevelID)) { 	
						   
	                         //get mail addr from alias name
				 		     if (seclevel('ALLOWTO_',$this->userLevelID)) {
							   $sendto = $to; 
							 }
							 else
		                       $sendto = $this->mailalias[$to]; //echo ">>>",$to, $sendto;							 
							 
                             if ($includesubs) {					
							   $subs = GetGlobal('controller')->calldpc_method('sensubscribe.getmails use ,');
							   if ($subs)		 
							     $sendto .= "," . $subs;
							 }
                             //echo ">>>",$sendto;							 						   	
							 
                             $this->post = $this->sendit($from,$sendto,$subject,$mail_text,$recips); 					 
						   }
						   else
    						 SetGlobal('sFormErr',localize('_MLS3',getlocal())); 
							 
	   }						   
  }  
  
  //overwrite
  function sendit($from,$to,$subject,$body=null) {
  
     $to_array = explode(",",$to);
	 //print_r($to_array);
	   
	 foreach ($to_array as $tid=>$to_addr) {
	 
	  set_time_limit(1005); 
	   
	  if ($to_addr) {
	    //ATTATCHMENTS
        if ((seclevel('ALLOWATTACH_',$this->userLevelID)) &&
	       ($this->attachments)) { 
		   
             $mail = new mime_mail($this->mailtype);
  
             $mail->from = $from;
             $mail->to = $to_addr;
             $mail->subject = $subject;
             $mail->body = $body;
	   
	   		   
             reset ($this->attachments);
	         foreach ($this->attachments as $id => $data) {
	           $attachedfiles = explode(";",$data);
		 
	           $filefpath= $attachedfiles[0];
		       $filename = $attachedfiles[1]; 			   
		       $filetype = $attachedfiles[2]; 	
			   
			   $data = $this->readAttachment($filefpath);
			   if ($data) $mail->add_attachment($data,$filename,$filetype);   
		     }
			 
			 //set body
	         $body = $mail->get_mail(); 
			 //use this method with mime suport to send
             $ret = $this->cmail_sendit($from,$to_addr,$subject,$body); //use mime send method	   			 
			 //reset attachments
			 DeleteSessionParam('attachments');
	     }
	     else //just use parent mehtod to send
           $ret = contactmail::sendit($from,$to_addr,$subject,$body);
	   }
	 }
	 
	 set_time_limit($this->timeout);
	 
	 return ($ret);
  }   
  
  //copied from cmail. The only chnage the send method ...
  function cmail_sendit($from,$to,$subject,$mail_text='') {
       $sFormErr = GetGlobal('sFormErr');
	   //global $info; //receives errors	 

       if ((checkmail($from)) && ($subject)) {
	   
         $smtpm = new smtpmail;
		 $smtpm->to = $to; 
		 $smtpm->from = $from; 
		 $smtpm->subject = $subject;
		 $smtpm->body = $mail_text;
		 
		 $err = $smtpm->smtpsend_mime(); //changed the send method
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
   	
};	
}
?>