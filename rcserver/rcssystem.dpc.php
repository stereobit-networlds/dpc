<?php

$__DPCSEC['RCSSYSTEM_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSSYSTEM_DPC")) && (seclevel('RCSSYSTEM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSSYSTEM_DPC",true);

$__DPC['RCSSYSTEM_DPC'] = 'rcssystem';

class rcssystem {

   function __construct() {
        //choose encoding
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
		if (($charset=='utf-8') || ($charset=='utf8'))
		  $this->charset = 'utf-8';
		else  
	      $this->charset = $char_set[getlocal()]; 		
   }		

   function getencoding() {

	  return ($this->charset);
   }

   function tell_by_mail($from,$to,$subject,$mail_text=null,$cc=null,$bcc=null,$is_html=false,$method=null) {
       $method = $method?$method:'SMTP';
   
	   if ($cc) {
	     if (stristr($cc,';'))
           $ccaddress = explode(';',$cc);//.';');//add ; for one ..to be array		       
		 else
		   $ccaddress = $cc;   
	   }	 
	   if ($bcc) {
  	     if (stristr($bcc,';'))
           $bccaddress = explode(';',$bcc);//.';');//add ; for one ..to be array   
		 else
		   $bccaddress = $bcc;
	   }	 
   
      if (defined('SMTPMAIL_DPC')) {   
   
        $smtpm = new smtpmail($this->encoding,$this->mailuser,$this->mailpass,$this->mailname,$this->mailserver);
		   	   
        if ((SMTP_PHPMAILER=='true') || ($method=='SMTP')) { 
		   //echo 'smtp';	
		   $smtpm->from($from,$this->mailname);		   
		   $smtpm->to($to);  
		   if (!empty($ccaddress)) {
		     foreach ($ccaddress as $cc) {
			   //echo $cc,'<br>';
			   if (trim($cc)) {
		         //$smtpm->cc($cc);//ONLY WIN32  
			     $smtpm->to($cc);
			   }
			 }  
		   }  	 
		   if (!empty($bccaddress)) {
		     foreach ($bccaddress as $bcc) {
			   //echo $bcc,'<br>';		
			   if (trim($bcc)) {	 
		         //$smtpm->bcc($bcc); //ONLY WIN32  
			     $smtpm->to($bcc);  
			   }	 
			 }  
		   }		   
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);
		   
           # Optional alternate text-only body:
           $smtpm->smtp->AltBody = GetParam('alttext');
		   
         }
         elseif ((SENDMAIL_PHPMAILER=='true') || ($method=='SENDMAIL')) {	  	   
		   //echo 'phpmailer';
		   $smtpm->from($from,$this->mailname);		   
		   $smtpm->to($to);  
		   if (!empty($ccaddress)) {
		     foreach ($ccaddress as $cc) {
			   //echo $cc,'<br>';			 
			   if (trim($cc)) {			 
		         //$smtpm->cc($cc); //ONLY WIN32  
			     $smtpm->to($cc);
			   }	 
			 }  
		   }
		   if (!empty($bccaddress)) {
		     foreach ($bccaddress as $bcc) {
 			   //echo $bcc,'<br>';
			   if (trim($bcc)) {				   
		         //$smtpm->bcc($bcc);//ONLY WIN32   
			     $smtpm->to($bcc);
			   }	 
			 }  
		   }			    
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text,$is_html);		
		   
           # Optional alternate text-only body:
           $smtpm->smtp->AltBody = GetParam('alttext');	
		 } 
		 else {
		   //echo 'default';	
		   $smtpm->to($to); 
		   $smtpm->from($from); 
		   $smtpm->subject($subject);
		   $smtpm->body($mail_text);			   			   	    
		 }
			 
		 $err .= $smtpm->smtpsend();
		 unset($smtpm);				 
		  			     	  	
  	     if (!$err) 
		   return true; 
		 else  
		   return false;	    		   		  
         
      /*if (defined('SMTPMAIL_DPC')) {
         $smtpm = new smtpmail;
         $smtpm->to = $to; 
         $smtpm->from = $from; 
         $smtpm->subject = $subject;
         $smtpm->body = $mail_text;
         $mailerror = $smtpm->smtpsend();
         unset($smtpm);	
		 
		 if ($mailerror) 
		   $ret =  "Error sending mail!(".$mailerror.")";
		 
		 return ($ret); 
		 //null on success 
		 * */ 
	  }
	  else
	    echo "Undefined mail command!";	 
		
	  return false;			
   }
   
	//send mail based on dpc method
	function sendit($from,$to,$subject,$mail_text='',$subscribers=null,$is_html=false) {
	
	  $cc = $subscribers;
	  $bcc = null;
	
      if (defined('SMTPMAIL_DPC')) {  	
	  
		   if (defined('RCSHSUBSQUEUE_DPC')) { 
		     //echo 'a';
		     $ret = GetGlobal('controller')->calldpc_method("rcshsubsqueue.sendit use $from+$to+$subject+$mail_text+$subscribers+$is_html+1");	  
		   }	 
		   elseif (defined('RCSHSUBSCRIBERS_DPC')) {
		     //echo 'b';
		     $ret = GetGlobal('controller')->calldpc_method("rcshsubscribers.sendit use $from+$to+$subject+$mail_text+$subscribers+$is_html+1");	 		   
		   }	 
		   else {
		     //echo 'c';
		     $ret = $this->tell_by_mail($from,$to,$subject,$mail_text,$cc,$bcc,$is_html);	 	  
		   }	 
	  }
	  else
	    echo "Undefined mail command!";	 
		
	  return false;		  
	
	}	   
   
	function tell_by_sms($message) {
	
	    if (defined('SMSGUI_DPC'))
	      $ret = GetGlobal('controller')->calldpc_method('smsgui.sendsms use '.$message);		
	} 
	
    function get_message($param1,$param2,$path=null) {
   
	    $m = paramload($param1,$param2);	
		
	    $ff = $path . $m;
		
	    if (is_file($ff)) {
	      $ret = file_get_contents($ff);
	    }
	    else
	      $ret = $m; //plain text	

		  
		return ($ret);    	   
    }
	
	function write_params($text,$param0=null,$param1=null,$param2=null) {
	
	    $ret1 = str_replace ('%0',$param0,$text);
		$ret2 = str_replace ('%1',$param1,$ret1);
		$ret = str_replace ('%2',$param2,$ret2);
		
		return ($ret);
	}
	
	//put text in support window and open it
	function set_support($tosay) {
	    
		if (defined('RCSONLINESUPPORT_DPC'))
          GetGlobal('controller')->calldpc_method("rcsonlinesupport.set_show use ".$tosay);	
	}
	
    function get_country_from_ip() {
  
       if (defined('COUNTRY_DPC'))
         $mycountry = GetGlobal('controller')->calldpc_method("country.find_country");
	   //return "Greece";
	   return ($mycountry);
    }	
	
	function cpwinhelp() {
	
	   switch (GetReq('t')) {

	      case 'cpsavescript':
	      case 'cpscripts':$ret = GetGlobal('controller')->calldpc_method("rcscripts.show_directory");
		                   break;		
	      case 'cpsaveroots':					      
	      case 'cproots' : $ret = GetGlobal('controller')->calldpc_method("rcroots.show_directory");
		                   break;	
		  case 'cpsavenews':				    	   
	      case 'cpnews'  : $ret = GetGlobal('controller')->calldpc_method("rcnews.show_directory");
		                   break;	
		  case 'cptsave' :				      
	      case 'cptedit' : $ret = GetGlobal('controller')->calldpc_method("rctedit.show_template_files");
		                   break;
	      default :
	   }
	   
	   return ($ret);
	}	
	
	//local for page dpc calls
    function paramload($section,$param,$islink=null) {
      $config = GetGlobal('config');
	  //echo '>',$config[$section][$param];
	  
      if ($ret = $config[$section][$param]) {
        return ($ret);
	  } 
      else {//empty links
	    if ($islink)
		  return '#';
	  }
    }

    function arrayload($section,$array) {
      $config = GetGlobal('config');
  
      if ($data = $config[$section][$array]) 
        return(explode(",",$data));
    }	
	   
};
}   
?>