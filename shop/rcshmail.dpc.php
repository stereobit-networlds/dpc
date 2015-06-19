<?php
$__DPCSEC['RCSHMAIL_DPC']='2;2;2;2;2;2;2;2;9';

if ((!defined("RCSHMAIL_DPC")) && (seclevel('RCSHMAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSHMAIL_DPC",true);

$__DPC['RCSHMAIL_DPC'] = 'rcshmail';

$d = GetGlobal('controller')->require_dpc('mail/cmail.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('CMAIL_DPC','RCSHMAIL_DPC');

$__EVENTS['RCSHMAIL_DPC'][2]="cpsubloadhtmlmail";

$__ACTIONS['RCSHCMAIL_DPC'][2]="cpsubloadhtmlmail";

$__LOCALE['RCSHMAIL_DPC'][0]='RCSHMAIL_DPC;Mail;Ταχυδρομείο';
$__LOCALE['RCSHMAIL_DPC'][1]='_FROM;From;Απο';
$__LOCALE['RCSHMAIL_DPC'][2]='_TO;To;Σε';
$__LOCALE['RCSHMAIL_DPC'][3]='_SUBJECT;Subject;Θέμα';
$__LOCALE['RCSHMAIL_DPC'][4]='_MESSAGE;Message;Κείμενο';
$__LOCALE['RCSHMAIL_DPC'][5]='_SUBMIT;Submit;Αποστολή';
$__LOCALE['RCSHMAIL_DPC'][6]='_INCLSUBS;and Subscribers;σε συνδρομητές';
$__LOCALE['RCSHMAIL_DPC'][7]='_INCLALL;or All;ή όλοι';
$__LOCALE['RCSHMAIL_DPC'][8]='_HTMLTEMPLATE;Html Template;Html Template';
$__LOCALE['RCSHMAIL_DPC'][9]='_SMTPDEBUG;Debug;Debug';

class rcshmail extends contactmail {

    var $type, $title;
	var $mailbody;

	function rcshmail() {
	  
	  contactmail::contactmail();
	  
	  $this->path = paramload('SHELL','prpath');
     
	  $this->type = paramload('RCSHMAIL','type'); 	  
	  $this->title = localize('RCSHMAIL_DPC',getlocal());
	  $this->mailbody = null;	  
	  $this->defaultfrom = remote_paramload('RCSHMAIL','from',$this->path); 		  
	}
	

	function form($action=null,$submit='Submit',$rows=null,$taction=null) {
	
	     switch ($this->type) {
		   
		   case 'sendmail'    : break;
		   case 'mailcracker' : $out = $this->mailcracker_form(); break;		   
		   case 'smtp'        :
		   default            : $out = $this->cform($action,$submit,$this->defaultfrom,$rows,$taction); 
		 }	
		 
		 return ($out);
	} 
	
	//override
    function cform($action=null,$submit='Submit',$sendto=null,$rows=null,$taction=null) {

	     $mymail = paramload('RCSHMAIL','from');
		 
		 if (!$submit)
		   $submit = localize('_SUBMIT',getlocal());
  
         if (!$rows)
		   $rows = 16;
  
         if ($action)
		   $myaction = seturl("t=".$action);   
		 else  
           $myaction = seturl("t=cmail");   
		   
	     if (defined('RCTEDIT_DPC')) {
		   //echo 'z';
		   $out .= "<B>" . localize('_HTMLTEMPLATE',getlocal()) . ":</B>";
	       $out .= GetGlobal('controller')->calldpc_method('rctedit.show_template_files use load+'.$taction);		  
		 }  
		 		   
		   
	 
         $out .= "<FORM action=". "$myaction" . " method=post>"; 	
	 	 
         //error message
         $out .= setError($sFormErr);		  
	 
	     //FROM..
		 //if ($from) {
           $from[] = "<B>" . localize('_FROM',getlocal()) . ":</B>";
           $fromattr[] = "right;10%;";
	       $from[] = "<input type=\"text\" name=\"from\" maxlenght=\"40\" value=\"".$mymail."\">";
	       $fromattr[] = "left;90%;";		
		 //}

	     $fwin = new window('',$from,$fromattr);
	     $winout .= $fwin->render("center::100%::0::group_article_selected::left::0::0::");	
	     unset ($fwin);	  
	 
         //TO..
         $to[] = "<B>" . localize('_TO',getlocal()) . ":</B>";
 	     $toattr[] = "right;10%;";	 
	     $totext = "<input type=\"text\" name=\"to\" maxlenght=\"40\" value=\"$sendto\">";	
	 
	     //get department's mails
	 /*    $totext = "<select name=\"to\">"; 
	     foreach ($this->alias as $num=>$malias) {
		   if ($department==$num)
		     $totext .= "<option selected>" . $malias ."</option>";
		   else	 
	         $totext .= "<option>" . $malias ."</option>";
	     }
	     $totext .= "</select>";*/
		 
		 //SUBSCRIBERS
         if (defined('RCSHSUBSCRIBERS_DPC'))  {	
	       $totext .= "<B>" . localize('_INCLSUBS',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includesubs\">";		
           $totext .= "<B>" . localize('_INCLALL',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"includeall\">";				   
         } 
         $totext .= "<B>" . localize('_SMTPDEBUG',getlocal()) . "&nbsp;<input type=\"checkbox\" name=\"smtpdebug\">";//debug smtp
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
		 
		 if (GetReq('t')=='cpsubloadhtmlmail') {
		   $path = GetGlobal('controller')->calldpc_var('rctedit.htmlpath');		 
		   //echo $path; print_r($_POST);
		   $data = GetGlobal('controller')->calldpc_method('rctedit.loadfromfile use '.GetParam('id').'+'.$path);
		 
		   $this->htmleditor = new tinyMCE('textareas','ADVANCEDFULL',1,'images',2);
		   $mbody = $this->htmleditor->render('mail_text','100%',$rows+10,$data);	
		 }
		 else { 
         $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";
		 if ($this->mailbody) $mbody .= $this->mailbody;
		       else $mbody .= GetParam('mail_text');		 
         //$mbody .= GetParam('mail_text');//$this->mailbody; 
         $mbody .= "</TEXTAREA></DIV>";
		 }
		 
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
           $cmd .= "<INPUT type=\"submit\" name=\"submit\" value=\"" . $submit . "\">&nbsp;";  
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
		 
	     $mywin = new window($this->title . '['.GetParam('id').']',$out);
	     $wout = $mywin->render();	
	     unset ($mywin);		 
		 
		 return ($wout);     
    }	
	
	function mailcracker_form() {
	
	  $ret = GetGlobal('controller')->calldpc_method('mailcracker.cracker_mail_new');
	  return ($ret);
	}
	
	//override and rename
    function sendit_2($from,$to,$subject,$mail_text='',$is_html=false) {
       $sFormErr = GetGlobal('sFormErr');
	   //global $info; //receives errors	 

       if ((checkmail($to)) && ($subject)) {//echo $to,'<br>';
	   
         $smtpm = new smtpmail;
		   	   
         if ((SMTP=='true') || ($method=='SMTP')) {
		   //echo 'smtp';	
		   $smtpm->to($to); 
		   $smtpm->from($from); 
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);			   	   
	     }
         elseif ((PHPMAILER=='true') || ($method=='PHPMAILER')) {	  	   
		   //echo 'phpmailer';
		   $smtpm->to($to); 
		   $smtpm->from($from); 
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);		   
		 } 
		 else {
		   //echo 'default';	
		   $smtpm->to($to); 
		   $smtpm->from($from); 
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text);			   			   	 
		 /*
		   $smtpm->to = $to; 
		   $smtpm->from = $from; 
		   $smtpm->subject = $subject;
		   $smtpm->body = $mail_text;		 
		 */		   
		 }
		 $err = $smtpm->smtpsend();
		 unset($smtpm);				 
		  			     	  	
  	     if (!$err) {
		   SetGlobal('sFormErr',localize('_MLS2',getlocal()));	//send message ok
		   return true;
		 }         
		 else { 
		   SetGlobal('sFormErr',localize('_MLS9',getlocal()).'('.$err.')');	//error
		   setInfo($err);//$info); //smtp error = global info
		 }  
       }
       else 
	     SetGlobal('sFormErr',localize('_MLS4',getlocal()));
		 
	   return (false);	  	   
    }  	
	
	//overide
	function sendit($from,$to,$subject,$mail_text='',$subscribers=null,$is_html=false) {

		 $i = 0;
		 $meter = 0;
	     //echo $subscribers,'>';
		   
		 //contactmail::sendit($from,$to,$subject,$mail_text); 
		 $one_receipinet = $this->sendit_2($from,$to,$subject,$mail_text,$is_html);  
		 
		 
         //if (defined('RCSHSUBSCRIBERS_DPC') && $subs)  {//bulk mail
		 if ($subscribers) {
		    //$mlist = GetGlobal('controller')->calldpc_method('rcshsubscribers.getmails');
			//echo $mlist;
			//send bulk mail
			$mails = explode(";",$subscribers);//$mlist);
			//print_r($mails);
			foreach ($mails as $z=>$m) {
			  //contactmail::sendit($from,$m,$subject,$mail_text); 
			  $meter += $this->sendit_2($from,$m,$subject,$mail_text,$is_html);
				
			  $i+=1;
			}
			
			SetGlobal('sFormErr',($meter?$meter:0) . ' mail(s) send from ' . ($i) . ' mail(s) readed');		 
		 }
		 else {
	       SetGlobal('sFormErr',($one_receipinet?$one_receipinet:0) . ' mail send from ' . '1 mail readed');		 
		   $i = $one_receipinet;
		 }  
		 //echo '>>>>>>>>>>>>>>>>>>>>',$i,'<<<<<<<<<<<<<<<<<<<<<<<<<br>';
		 return ($i);	
    }
	
	
	//used to pass db data to body
	function create_mail($action,$to,$id=null) {
	
	  if ($id) {
	    
		$this->mailbody = GetGlobal('controller')->calldpc_method('rcitems.create_mailbody use '.$id);
	  }
	
	  $ret = $this->cform($action,'Send',$to);
	  
	  return ($ret);
	}
	
};
}
?>