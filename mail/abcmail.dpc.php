<?php
$__DPCSEC['ABCMAIL_DPC']='2;2;2;2;2;2;2;2;9';

if ((!defined("ABCMAIL_DPC")) && (seclevel('ABCMAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ABCMAIL_DPC",true);

$__DPC['ABCMAIL_DPC'] = 'abcmail';

$d = GetGlobal('controller')->require_dpc('mail/cmail.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('CMAIL_DPC','ABCMAIL_DPC');

$__LOCALE['ABCMAIL_DPC'][0]='ABCMAIL_DPC;Mail;Ταχυδρομείο';
$__LOCALE['ABCMAIL_DPC'][1]='_FROM;From;Απο';
$__LOCALE['ABCMAIL_DPC'][2]='_TO;To;Σε';
$__LOCALE['ABCMAIL_DPC'][3]='_SUBJECT;Subject;Θέμα';
$__LOCALE['ABCMAIL_DPC'][4]='_MESSAGE;Message;Κείμενο';
$__LOCALE['ABCMAIL_DPC'][5]='_SUBMIT;Submit;Αποστολή';
$__LOCALE['ABCMAIL_DPC'][6]='_INCLSUBS;and Subscribers;σε συνδρομητές';

class abcmail extends contactmail {

    var $type, $title;
	var $mailbody;

	function abcmail() {
	  
	  contactmail::contactmail();
     
	  $this->type = paramload('ABCMAIL','type'); 	  
	  $this->title = localize('ABCMAIL_DPC',getlocal());
	  $this->mailbody = null;	  
	}
	
	function form($action=null,$submit='Submit',$rows=null) {
	
	     switch ($this->type) {
		   
		   case 'sendmail'    : break;
		   case 'mailcracker' : $out = $this->mailcracker_form(); break;		   
		   case 'smtp'        :
		   default            : $out = $this->cform($action,$submit,$rows); 
		 }	
		 
		 return ($out);
	} 
	
	//override
    function cform($action=null,$submit='Submit',$sendto=null,$rows=null) {
	
	     $mymail = paramload('ABCMAIL','from');
		 
		 if (!$submit)
		   $submit = localize('_SUBMIT',getlocal());
  
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
         if (defined('RCABCSUBSCRIBERS_DPC'))  {	
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
         $mbody = "<DIV class=\"monospace\"><TEXTAREA style=\"width:100%\" NAME=\"mail_text\" ROWS=$rows cols=60 wrap=\"virtual\">";
		 if ($this->mailbody) $mbody .= $this->mailbody;
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
		 
	     $mywin = new window($this->title,$out);
	     $wout = $mywin->render();	
	     unset ($mywin);		 
		 
		 return ($wout);     
    }	
	
	function mailcracker_form() {
	
	  $ret = GetGlobal('controller')->calldpc_method('mailcracker.cracker_mail_new');
	  return ($ret);
	}
	
	//overide
	function sendit($from,$to,$subject,$mail_text='') {
	     $subs = GetParam('includesubs');
		 $i = 1;
	   
	     switch ($this->type) {
		   
		   case 'sendmail'    : break;
		   case 'mailcracker' : break;		   
		   case 'smtp'        :
		   default            : contactmail::sendit($from,$to,$subject,$mail_text); 
		 }
		 
         if (defined('RCABCSUBSCRIBERS_DPC') && $subs)  {//bulk mail
		    $mlist = GetGlobal('controller')->calldpc_method('rcabcsubscribers.getmails');
			//echo $mlist;
			//send bulk mail
			$mails = explode(",",$mlist);
			foreach ($mails as $mail) {
			  contactmail::sendit($from,$mail,$subject,$mail_text); 
			  $i+=1;
			}
		 }
		 
		 return ($i);		 
    }
	
	
	//used to pass db data to body
	function create_mail($action,$to,$id=null) {
	
	  if ($id) {
	    
		$this->mailbody = GetGlobal('controller')->calldpc_method('rcvehicles.create_mailbody use '.$id);
	  }
	
	  $ret = $this->cform($action,'Send',$to);
	  
	  return ($ret);
	}
	
};
}
?>