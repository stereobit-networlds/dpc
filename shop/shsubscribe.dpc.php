<?php
$__DPCSEC['SHSUBSCRIBE_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHSUBSCRIBE_DPC")) && (seclevel('SHSUBSCRIBE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHSUBSCRIBE_DPC",true);

$__DPC['SHSUBSCRIBE_DPC'] = 'shsubscribe';

//$d = GetGlobal('controller')->require_dpc('subscribe/subscribe.dpc.php');
//require_once($d);

$__EVENTS['SHSUBSCRIBE_DPC'][0]='shsubscribe';
$__EVENTS['SHSUBSCRIBE_DPC'][1]='unsubscribe';
$__EVENTS['SHSUBSCRIBE_DPC'][2]='subscribe';
$__EVENTS['SHSUBSCRIBE_DPC'][3]='advsubscribe';

$__ACTIONS['SHSUBSCRIBE_DPC'][0]='shsubscribe';
$__ACTIONS['SHSUBSCRIBE_DPC'][1]='unsubscribe';
$__ACTIONS['SHSUBSCRIBE_DPC'][2]='subscribe';
$__ACTIONS['SHSUBSCRIBE_DPC'][3]='advsubscribe';

$__LOCALE['SHSUBSCRIBE_DPC'][0]='SHSUBSCRIBE_DPC;Subscribe;Εγγραφή';
$__LOCALE['SHSUBSCRIBE_DPC'][1]='_SUBSCR;Subscribe;Εγγραφή';
$__LOCALE['SHSUBSCRIBE_DPC'][2]='_USUBSCR;Unsubscribe;Διαγραφή απο την λίστα';
$__LOCALE['SHSUBSCRIBE_DPC'][3]='_SUBSLIST;Subscribers List;Λίστα Συνδρομών';
$__LOCALE['SHSUBSCRIBE_DPC'][4]='_MSG2;Enter your e-mail:;Εισάγετε το e-mail σας:';
$__LOCALE['SHSUBSCRIBE_DPC'][5]='_MSG4;Advance subscription;Περισσότερα';
$__LOCALE['SHSUBSCRIBE_DPC'][6]='_MSG5;Invalid e-mail;Ακυρο e-mail';
$__LOCALE['SHSUBSCRIBE_DPC'][7]='_MSG6;Subscription successfull !;Επιτυχής εισαγωγή !';
$__LOCALE['SHSUBSCRIBE_DPC'][8]='_MSG7;Subscription is active !;Είστε ήδη καταχωρημένος';
$__LOCALE['SHSUBSCRIBE_DPC'][9]='_MSG8;Unsubscription successfull !;Επιτυχής εξαγωγή !';
$__LOCALE['SHSUBSCRIBE_DPC'][10]='_ERROR;Error !;Λάθος !';
$__LOCALE['SHSUBSCRIBE_DPC'][11]='_SUBSCRTEXT;Please send me mail informations about new products;Θέλω να λαμβάνω πληροφορίες για νέα προϊόντα μέσω ηλεκτρονικού ταχυδρομείου';
$__LOCALE['SHSUBSCRIBE_DPC'][12]='_SUBSCRWARN;Please check below to subscribe;Ενεργοποίηση συνδρομής';
$__LOCALE['SHSUBSCRIBE_DPC'][13]='_DERROR;Database Error;Δεν είναι δυνατή η εργασία αυτή τη στιγμή, προσπαθήστε αργότερα';
$__LOCALE['SHSUBSCRIBE_DPC'][14]='_SUBID;A/A;A/A';
$__LOCALE['SHSUBSCRIBE_DPC'][15]='_SUBMAIL;Mail Address;Ταχυδρομείο';
$__LOCALE['SHSUBSCRIBE_DPC'][16]='_SUBDATE;Subscription date;Ημερ. Εισαγωγής';
$__LOCALE['SHSUBSCRIBE_DPC'][17]='SUBSCRIBE_CNF;Subscribers List;Λίστα Συνδρομών';
$__LOCALE['SHSUBSCRIBE_DPC'][18]='_CLICKHERE; click here.; πατηστε εδω.';
$__LOCALE['SHSUBSCRIBE_DPC'][19]='Subscription enabled;Subscription enabled;Ενεργοποίηση συνδρομητή';
$__LOCALE['SHSUBSCRIBE_DPC'][20]='Subscription disabled;Subscription disabled;Απενεργοποίηση συνδρομητή';


$__PARSECOM['SHSUBSCRIBE_DPC']['quickform']='_QUICKSHSUBSCRIBE_';

class shsubscribe {
    var $path, $urlpath, $inpath;
    var $title,$msg;
	var $subject,$body;
	var $subject2,$body2;	
	var $tell_it, $tell_from;
	var $tell_user;

	function shsubscribe() {
	
	  $this->title = localize('SHSUBSCRIBE_DPC',getlocal());	
	  $this->msg = null;	
      $this->path = paramload('SHELL','prpath');  	
	  
	  $this->urlpath = paramload('SHELL','urlpath');
	  $this->inpath = paramload('ID','hostinpath');		   
	  
	  $this->t_advsubscr = localize('_MSG4',getlocal());
	  $this->mesout = paramload('SHSUBSCRIBE','umsg');	
	  $this->t_entermail = paramload('SHSUBSCRIBE','say');
	  
	  $this->domain = paramload('SHSUBSCRIBE','domain');
	  $this->tell_it = remote_paramload('SHSUBSCRIBE','tellsubscriptionto',$this->path);
	  $this->tell_from = remote_paramload('SHSUBSCRIBE','tellsubscriptionfrom',$this->path);
	   
      $s1 = remote_paramload('SHSUBSCRIBE','subjecttotell',$this->path);//'New Subscription' 	   
	  $this->subject = localize($s1, getlocal()); 		    	    	   
	  $s2 = remote_paramload('SHSUBSCRIBE','subjecttotellatdel',$this->path);//'New Subscription' 	   
	  $this->subject2 = localize($s2, getlocal());
	  
	  $this->body = remote_paramload('SHSUBSCRIBE','bodytotell',$this->path);	  
	  $this->body2 = remote_paramload('SHSUBSCRIBE','bodytotellatdel',$this->path);		
	  
	  $this->tell_user = remote_paramload('SHSUBSCRIBE','telluser',$this->path);  
	}
	
    function event($sAction) {	

       if (!$this->msg) {
  
	     switch ($sAction) {
	        //case localize('_SUBSCR',getlocal()): 
	        case 'subscribe'  ://subscribe 
			                          $this->dosubscribe();
	                                  break;							
			//case localize('_USUBSCR',getlocal()) :				
	        case 'unsubscribe' ://unsubscribe
		                              $this->dounsubscribe();
	                                  break;				  								  
         }
      }
    }	

    function action($action)  { 


	     //$this->reset_db();
		 $act = GetParam('act');
		 if (!$act) $act = 'subscribe';
	  
		 //$out = $this->title($action); //disabled msg ibto form
		 $out .= $this->form();//$act);


	     return ($out);
	}


	function title($action=null) {
       $sFormErr = GetGlobal('sFormErr');
       //navigation status 
	   /*$sub = localize('_SUBSCR',getlocal());
	   $usub = localize('_USUBSCR',getlocal());	     
		 
	   switch ($action) {
		   
		   case 'unsubscribe' :	 $out = setNavigator($usub); break;	             
		   case 'subscribe'   :	 $out = setNavigator($sub); break;		   
	       default            :  $out = setNavigator($this->title); 
       }*/
       //error message //????????????
	   //if (($act = GetParam('act')) && ($act!='unsubscribe'))
	   if (GetParam('act')!='unsubscribe') {
  	     if ((GetReq('t')) || (GetReq('act')))
		   $out .= setError($sFormErr);
	   } 
	   
	   return ($out);
	}



    function form($action=null, $ctemplate=null)  { 	
	   $action = $action?$action:GetReq('t');
       $sFormErr = GetGlobal('sFormErr');  	   
	   //echo '>',$action; //handled by act
	   //template
       switch ($action) {	   
	    case 'unsubscribe' : $stemplate= $ctemplate ? $ctemplate : "unsubscribe.htm"; break;
	    case 'subscribe'   :
		           default : $stemplate= $ctemplate ? $ctemplate : "subscribe.htm";
	   }
	   $template = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$stemplate) ;
	   //echo $template;
	   if (is_readable($template)) {
	     $tmpl = 1;
		 $tokens = array();
		 $mytemplate = file_get_contents($template);
	   }		
	
       //subscription form
	   if ($action)
	     $filename = seturl("t=$action");      
	   else 
         $filename = seturl("t=subscribe");      
       
	
	   if ($tmpl) {
		 $tokens[] =  $sFormErr . "<FORM action=". "$filename" . " method=post>";		
		 $tokens[] = "<INPUT type=\"input\" name=\"submail\" maxlenght=\"64\" size=\"25\" class=\"myf_input\"  onfocus=\"this.style.backgroundColor='#F5F5F5'\" onblur=\"this.style.backgroundColor='#FFFFFF'\" style=\"background-color: rgb(255, 255, 255);\" >";
	   }
	   else {	 
         $toprint  = $sFormErr . "<FORM action=". "$filename" . " method=post>";
         $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	     $toprint .= $this->t_entermail;
	     $toprint .= "</STRONG><br><INPUT type=\"text\" name=\"submail\" maxlenght=\"64\" size=25><br>"; 	   
	   }
	   
	   if ($action) {
	   
		 $sub = localize('_SUBSCR',getlocal());
		 $usub = localize('_USUBSCR',getlocal());	     
		 
		 switch ($action) {
		   
		   case 'unsubscribe' :
					 if ($tmpl) {
		               $tokens[] = "<input type=\"submit\" class=\"myf_button\" value=\"$usub\"><input type=\"hidden\" name=\"FormAction\" value=\"$action\">";		   
					 }
					 else {  
		     	       $toprint .= "<input type=\"submit\" value=\"$usub\">";
	    	 	       $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";	   
					 }
					 break;
		   case 'subscribe' :
		   default :
					 if ($tmpl) {
		               $tokens[] = "<input type=\"submit\" class=\"myf_button\" value=\"$sub\"><input type=\"hidden\" name=\"FormAction\" value=\"$action\">";		   
					 }
					 else { 		   
		     	       $toprint .= "<input type=\"submit\" value=\"$sub\">";
	    	 	       $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"$action\">";
					 }  
		 } 
	   }	 
	   else {
		 if ($tmpl) {
		   $tokens[] = "<input type=\"submit\" class=\"myf_button\" name=\"FormAction\" value=\"subscribe\">&nbsp;<input type=\"submit\" class=\"myf_button\" name=\"FormAction\" value=\"unsubscribe\">";
		 }
		 else { 		   
	       $toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"subscribe\">&nbsp;"; 
           $toprint .= "<input type=\"submit\" name=\"FormAction\" value=\"unsubscribe\">";
		 }  
	   }
	   
	   if ($tmpl) {
	     $tokens[] = "</FORM>";
		 
		 $out .= $this->combine_tokens($mytemplate,$tokens);
	   }	   	 
	   else {
         $toprint .= "</FONT></FORM>";
	   
	     $data2[] = $toprint; 
  	     $attr2[] = "left";

	     $swin = new window(localize('_SUBSCR',getlocal()),$data2,$attr2);
	     $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	     unset ($swin);	 
       }

       return ($out);
   }

	function message2go() {
	   
	    $out = $this->mesout . seturl("t=advsubscribe",localize('_CLICKHERE',getlocal()));
		
		return ($out);
	}	


	
	function dosubscribe($mail=null,$bypasscheck=null,$telltouser=null) {
	   /*if ((isset($telltouser)) && ($telltouser>0))
	     $mail_tell_user = $telltouser;
	   else
	     $mail_tell_user = $this->tell_user;	*/
       $mail_tell_user = isset($telltouser)?$telltouser:$this->tell_user;	
	   
	   $mail = $mail?$mail:GetParam('submail');	 
	   if (!$mail) return;
	
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');	
	   
       $dtime = date('Y-m-d h:i:s');	   	
	   
	   if (checkmail($mail))  {
          //check if mail is in database
		  $sSQL = "SELECT email,subscribe FROM users where email=". $db->qstr($mail); 
	      $ret = $db->Execute($sSQL,2);
          //print_r($ret);
		  //echo $sSQL;
		  //echo '>',$ret->fields['email'];
		  $mymail = $ret->fields['email'];
		  
          if (!isset($mymail)) {

			   $sSQL = "insert into users (email,startdate,lname,fname,subscribe,notes) " .
			           "values (" .
					   $db->qstr(strtolower($mail)) . "," . $db->qstr($dtime) . "," .
					   $db->qstr('SUBSCRIBER') . "," . $db->qstr('UNKNOWN') . ",1," . $db->qstr('ACTIVE') .
		               ")";  
			   $db->Execute($sSQL,1);	
			   
			   if (!$bypasscheck)	    
			     SetGlobal('sFormErr', localize('_MSG6',getlocal()));	
				 
			   //echo $sSQL;
               if ($this->tell_it) //tell to me
			     $this->mailto($this->tell_from,$this->tell_it,$this->subject,$mail);
				 			     							  
			   //tell to subscriber
	           /*$template= "subinsert.htm";
	           $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
	           if (is_readable($t)) {			   
		         $mytemplate = file_get_contents($t);
			
		         $tokens[] = $mail;		  
			
		         $mailbody = $this->combine_tokens($mytemplate,$tokens);	*/		   
		       $tokens[] = $mail;	
               $tokens[] = $mail; //dummy at leats 2 elements				   					 
               $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
		       if ($mailbody = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use subinsert.htm+".$sd."+1")) { 
				 
			     $this->mailto($this->tell_from,$mail,$this->subject,$mailbody);	 
			   }
			   else
			     $this->mailto($this->tell_from,$mail,$this->subject,$this->body);	 	 
		  }
		  elseif (isset($mymail)) {//is in db but disabled  ..enable subscription
			   $sSQL = "update users set subscribe=1 where email=" . $db->qstr(strtolower($mail));  
			   $db->Execute($sSQL,1);		
			   if (!$bypasscheck)    
			     SetGlobal('sFormErr', localize('_MSG6',getlocal()));	
				 
			   //echo $sSQL;
               if ($this->tell_it) //tell to me
			     $this->mailto($this->tell_from,$this->tell_it,$this->subject,$mail);
				 			     							  
			   //tell to subscriber
		      if ($mail_tell_user>0) {		   
		         $tokens[] = $mail;	
                 $tokens[] = $mail; //dummy at leats 2 elements				   	
                 $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
		         if ($mailbody = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use subinsert.htm+".$sd."+1")) { 
				 				 
			       $this->mailto($this->tell_from,$mail,$this->subject,$mailbody);	 
			     }
			     else			   
			       $this->mailto($this->tell_from,$mail,$this->subject,$this->body);					 	  
			  }	   
		  }
		  else {
    	    if (!$bypasscheck)
		      SetGlobal('sFormErr', localize('_MSG7',getlocal()));
		  }	
	   }
	   else {
	     if (!$bypasscheck)
	       SetGlobal('sFormErr', localize('_MSG5',getlocal()));		   	 
	   }	 
	}
	
	function dounsubscribe($mail=null,$telltouser=null) {
	   $mail_tell_user = isset($telltouser)?$telltouser:$this->tell_user;
	   $ulistname = GetParam('ulistname') ? GetParam('ulistname') : null;	   
	   $remove = false;	 
	   $mamail = $mail?$mail:GetParam('submail');	 
	   $mail = $mamail?$mamail:GetReq('submail');	 
	   if (!$mail) return;		 
	   	
       $db = GetGlobal('db');
       $sFormErr = GetGlobal('sFormErr');	   
	   
	   if (checkmail($mail))  {

          //remove from ulists
		  if ($this->isin_ulists($mail)) {
			//$sSQL = "delete from ulists where email=" . $db->qstr($mail);
			$sSQL = "update ulists set listname='deleted' where email=" . $db->qstr($mail);
			if ($ulistname)
				$sSQL .= ' and listname=' . $db->qstr($ulistname); 
			$result = $db->Execute($sSQL,1);
			$remove = true;
		  }
		  //standart remove
		  if ($this->isin($mail)) {
			$sSQL = "update users set subscribe=0 where email=" . $db->qstr($mail) ; 
			$result = $db->Execute($sSQL,1);
			$remove = true;
		  }
		  
		  if ($remove) {
			SetGlobal('sFormErr',localize('_MSG8',getlocal()));
			setInfo(localize('_MSG8',getlocal()));
		  
			if ($this->tell_it) //tell to me
				$this->mailto($this->tell_from,$this->tell_it,$this->subject2,$mail);
				 			     							  
			//tell to subscriber   
			if ($mail_tell_user>0) {	
				$tokens[] = $mail;				
				$tokens[] = $mail; //dummy at leats 2 elements					
				$sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
				if ($mailbody = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use subdelete.htm+".$sd."+1")) { 
				 			 
					$this->mailto($this->tell_from,$mail,$this->subject2,$mailbody);	 
				}
				else			   
					$this->mailto($this->tell_from,$mail,$this->subject2,$this->body2);			  
			}
          }//remove	
          //echo '>',$remove;		  
	   }
	   else { 
	     SetGlobal('sFormErr', localize('_MSG5',getlocal()));	  
		 setInfo(localize('_MSG5',getlocal()));
	   }	
	}
	
    function isin($mail) {
       $db = GetGlobal('db');
	   
       $sSQL = "SELECT id,email,startdate FROM users";	
	   $sSQL .= " WHERE email=" . $db->qstr($mail) . " and subscribe>0"; 
	   $resultset = $db->Execute($sSQL,2);
	   //$ret = $db->fetch_array($resultset);	   
	   //echo $mail,$sSQL;
	   if ($resultset->fields['email']==$mail) return (true);
	
       return (false);
    }	
	
    function isin_ulists($mail) {
       $db = GetGlobal('db');
	   
       $sSQL = "SELECT email FROM ulists";	
	   $sSQL .= " WHERE email=" . $db->qstr($mail);// . " and subscribe>0"; 
	   $resultset = $db->Execute($sSQL,2);

	   if ($resultset->fields['email']==$mail) return (true);
	
       return (false);
    }		
	
	function getmails() {
	
       $db = GetGlobal('db');	
	   
       $resultset = $db->Execute("select email from users where subscribe>0",2);   


	   $ret = $db->fetch_array_all($resultset);
	   $out = implode(',',$ret);


	   return $out;	
	}
	
	function mailto($from,$to,$subject=null,$body=null,$ishtml=false,$instant=false) {
	
	    if ((defined('RCSSYSTEM_DPC')) && (!$instant)) { //no queue when no instant
		  //echo 'z',$to,'>',$from,'<br>';
		  $ret = GetGlobal('controller')->calldpc_method("rcssystem.sendit use $from+$to+$subject+$body++$ishtml");
        }
		else {
		  //echo 'AAAA',$to,'>',$from,'<br>';
		     if ((defined('SMTPMAIL_DPC')) &&
				 (seclevel('SMTPMAIL_DPC',$this->UserLevelID)) ) {
		       $smtpm = new smtpmail;
			   
		       $smtpm->to($to); 
		       $smtpm->from($from); 
		       $smtpm->subject($subject);
		       $smtpm->body($body);			   

			   $mailerror = $smtpm->smtpsend();
			   unset($smtpm);
			   
			   if (!$mailerror) return (true);
			 }
		}
		
	    return (false);  			 
	}			
	
	//tokens method
	function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i,$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<10;$x++)
		  $ret = str_replace("$".$x,'',$ret);
		//echo $ret;
		return ($ret);
	} 				

};
}
?>