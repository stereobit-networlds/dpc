<?php
$__DPCSEC['SHMESSAGES_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHMESSAGES_DPC")) && (seclevel('SHMESSAGES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHMESSAGES_DPC",true);

$__DPC['SHMESSAGES_DPC'] = 'shmessages';

$d = GetGlobal('controller')->require_dpc('mail/smtpmail.dpc.php');
require_once($d);
 
$__EVENTS['SHMESSAGES_DPC'][0]='cpmessages';

$__ACTIONS['SHMESSAGES_DPC'][0]='cpmessages';

$__DPCATTR['SHMESSAGES_DPC']['shmessages'] = 'shmessages,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHMESSAGES_DPC'][0]='SHMESSAGES_DPC;Messaging;Messaging';

class shmessages {

    var $path;
    var $tpl_path, $mail_from, $mail_to, $mail_subject;
	var $urlpath,$inpath;

	function shmessages() {

       $this->path = paramload('SHELL','prpath');	
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');		   
	   
	   if ($tpl_path = remote_paramload('SHMESSAGES','tplpath',$this->path))
	     $this->tpl_path = $this->urlpath .'/'. $this->inpath . '/cp/' . $tpl_path;
	   else	 
         $this->tpl_path = paramload('SHELL','prpath',$this->path);		 
		 
	   $this->mail_from = remote_paramload('SHMESSAGES','mailfrom',$this->path);
	   $this->mail_to = remote_paramload('SHMESSAGES','mailto',$this->path);
	   $this->mail_subject = remote_paramload('SHMESSAGES','mailsubject',$this->path);	   	   
	}
	
	function build_message($tpl=null,$message=null,$error=null) {
	
		  $template = $this->tpl_path . $tpl.".tpl";
		  $out .= str_replace("##_LINK_##",$message." ($error)",file_get_contents($template));			
		  	
		  return ($out);	
	}
	
	function mail_message($to=null,$message=null) {
	
			   $smtpm = new smtpmail;
			   $smtpm->to = $to?$to:$this->mail_to;//"balexiou@panikidis.gr"; 
			   $smtpm->from = $this->mail_from ;//"orders@panikidis.gr"; 
			   $smtpm->subject = $mail_subject;//'Internet Order No'.$this->transaction_id;
			   $smtpm->body = $message;
			   
			   $mailerror = $smtpm->smtpsend();
			   
			   unset($smtpm);	
			   
			   return ($mailerror);
	}

};
}
?>