<?php

$__DPCSEC['RCPANEL_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCPANEL_DPC")) && (seclevel('RCPANEL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCPANEL_DPC",true);

$__DPC['RCPANEL_DPC'] = 'rcpanel';

$d = GetGlobal('controller')->require_dpc('cpanel/cpanel.lib.php');
require_once($d); 

$__EVENTS['RCPANEL_DPC'][0]='cppanel';
$__EVENTS['RCPANEL_DPC'][1]='cpanelx';
$__EVENTS['RCPANEL_DPC'][2]='cpanelz';

$__ACTIONS['RCPANEL_DPC'][0]='cppanel';
$__ACTIONS['RCPANEL_DPC'][1]='cpanelx';
$__ACTIONS['RCPANEL_DPC'][2]='cpanelz';

$__LOCALE['RCPANEL_DPC'][0]='RCPANEL_DPC;CpanelX;CpanelX';

class rcpanel {

    var $cpanelx,$title,$cpanel_tools;

	function rcpanel() { 
	
	  $this->title = localize('RCPANEL_DPC',getlocal());		
	
	  $usr = paramload('CPANEL','user');
	  $pwd = paramload('CPANEL','password');
	  $domain = paramload('CPANEL','domain');
	
	
      $this->cpanelx = new cpanelx();		
      //login with your hosting account
      $this->cpanelx->login($usr,$pwd,$domain);	 
	  
	  //SECOND CLASS
	  $this->cpanel_tools = new cPanelTools($usr,$pwd,$domain,'2082','x2');	  
    }
	
   function event($event=null) {
      
	  switch ($event) {
	    case 'cpabclangs' : break;
		default           : 
	  }
   }
   
   function action($action=null) {  
      
	  switch ($action) {
	    case 'cpabclangs' : break;
		default           : $ret = $this->show_stats();
	  }
	  
	  return ($ret);
   }	
   
	function title() {
       $sFormErr = GetGlobal('sFormErr');

       //navigation status            
	   if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	
 
       //error message
	   $out .= setError($sFormErr);
	   
	   return ($out);
	}
	   
	
	function show_stats() {
	
      //if you don't want to see the cPanel output commment the next line
      //$this->cpanelx->api_output();	  
	  
	  $ret = $this->title();
	  
	  //$ret .= $this->cpanelx->show_awstats();
	  $ret .= $this->cpanel_tools->show_awstats();
	  //print_r($ret);
	  return ($ret);
	}
};
}
?>