<?php

$__DPCSEC['RCNETMAIL_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCNETMAIL_DPC")) && (seclevel('RCNETMAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCNETMAIL_DPC",true);

$__DPC['RCNETMAIL_DPC'] = 'rcnetmail';

$__EVENTS['RCNETMAIL_DPC'][0]='cpnetmail';

$__ACTIONS['RCNETMAIL_DPC'][0]='cpnetmail';

$__DPCATTR['RCNETMAIL_DPC']['cpnetmail'] = 'cpnetmail,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCNETMAIL_DPC'][0]='RCNETMAIL_DPC;Net Mail;Net Mail';

class rcnetmail {

	var $title,$domain;
	var $mailurlpath,$path, $url, $urlpath, $inpath;
	var $encoding;
		
	function rcnetmail() {
	
	    //awstats::awstats();
		
	    $this->title = localize('RCNETMAIL_DPC',getlocal());	
		$this->domain = paramload('RCNETMAIL','domain');
		
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";		  
		else 
		  $this->path = paramload('SHELL','prpath');	
		  
		$this->url = paramload('SHELL','url'); 
	    $this->urlpath = paramload('SHELL','urlpath');
	    $this->inpath = paramload('ID','hostinpath');				 
		  		
		$this->awpath = $this->path . paramload('RCNETMAIL','awpath');	 
		$this->awfile = null;
        $this->aw = new awfile($this->awfile); 
		
		$appname = paramload('ID','instancename');
		$app = $appname?$appname.'.':null;//null is the root app
		
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
	    if (($charset=='utf-8') || ($charset=='utf8'))
	      $this->encoding = 'utf-8';
	    else  
	      $this->encoding = $char_set[getlocal()]; 	
				
		$this->mailurlpath = "http://www.networlds.org/webmail";
		//echo '>',$this->awurlpath;
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		

		switch ($sAction) {
		
		  case 'cpnetmail' :
		  default          :
		}
    }
  
    function action($action) {

	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
		  
		switch ($action) {
		
		  case 'cpnetmail' :
		  default          :$out .= $this->show_mail_online();
		}		  
	 
	 return ($out);
    } 
	
	function show_mail_online() {
	
      $bodyurl = $this->mailurlpath;
  
      $ret = '<br><iframe src ="'.$bodyurl.'" width="100%" height="450px"><p>Your browser does not support iframes .</p></iframe>';  
      return ($ret);	
	}
	
  
};
}
?>