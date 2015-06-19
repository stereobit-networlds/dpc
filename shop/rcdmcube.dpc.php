<?php
$__DPCSEC['RCDMCUBE_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCDMCUBE_DPC")) && (seclevel('RCDMCUBE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCDMCUBE_DPC",true);

$__DPC['RCDMCUBE_DPC'] = 'rcdmcube';

$a = GetGlobal('controller')->require_dpc('rc/rcbrowser.lib.php');
require_once($a);

$d = GetGlobal('controller')->require_dpc('shop/shkategories.dpc.php');
require_once($d);

$__EVENTS['RCDMCUBE_DPC'][0]='cpdmcube';
$__EVENTS['RCDMCUBE_DPC'][1]='cpdmcuberev';
$__EVENTS['RCDMCUBE_DPC'][2]='cpread';
$__EVENTS['RCDMCUBE_DPC'][3]='cpwrite';
$__EVENTS['RCDMCUBE_DPC'][4]='cpbradd';
$__EVENTS['RCDMCUBE_DPC'][5]='cpbredit';
$__EVENTS['RCDMCUBE_DPC'][6]='cpbrdel';
$__EVENTS['RCDMCUBE_DPC'][7]='cpbrsearch';

$__ACTIONS['RCDMCUBE_DPC'][0]='cpdmcube';
$__ACTIONS['RCDMCUBE_DPC'][1]='cpdmcuberev';
$__ACTIONS['RCDMCUBE_DPC'][2]='cpread';
$__ACTIONS['RCDMCUBE_DPC'][3]='cpwrite';
$__ACTIONS['RCDMCUBE_DPC'][4]='cpbradd';
$__ACTIONS['RCDMCUBE_DPC'][5]='cpbredit';
$__ACTIONS['RCDMCUBE_DPC'][6]='cpbrdel';
$__ACTIONS['RCDMCUBE_DPC'][7]='cpbrsearch';

$__LOCALE['RCDMCUBE_DPC'][0]='RCDMCUBE_DPC;DM Cbe;Dm Cube';

class rcdmcube {
  
    var $title, $path, $catpath, $catfkey, $cext, $initfile;
	var $post, $msg;
	var $browser;

	function rcdmcube() {
	  $db = GetGlobal('db');

	  $this->title = localize('RCDMCUBE_DPC',getlocal());	
	  $this->post = false;  
	  $this->msg = null;
	  
	  $this->chain[] = null;
	  
	  $this->browser4 = new rcbrowser(null,$db,'Customers','cpcustomers','customers','mail','id;code2;name;afm;address;area;attr1;voice1;voice2;fax;mail;prfdescr;attr1',0);
	  $this->browser3 = new rcbrowser(null,$db,'Users','cpusers','users','code2','id;code2;email;fname;startdate;subscribe;username;password',0,$this->browser4,'mail',1);
	  $this->browser2 = new rcbrowser(null,$db,'Stats','cpvstats','stats','tid','id;date;day;month;year;tid;attr1;attr2;vid',0,$this->browser3,'code2',7);
	  $this->browser = new rcbrowser(null,$db,'Items','cpitems','products','code5','id;code5;active;sysins;itmname;uniname1;uniname2;price0;price1;price2;pricepc;itmdescr;cat0;cat1;cat2;cat3;cat4',1,$this->browser2,'tid',0);
	}
	
	function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	    switch ($event) {
		  case 'cpbrsearch':     
		                        rcbrowser::search();
		                        break;		
		  case 'cpbradd' :     														
								break;
		  case 'cpbredit' :     														
								break;
								
		  case 'cpbrdel' :     														
								break;																
		  case 'cpread'  :     
		                        rcbrowser::getgrid_records();														
								break;
		  case 'cpwrite' :     
		                        rcbrowser::setgrid_records();
		                        break;														
          case 'cpdmcuberev'  :
		  default             : 
		                        $this->browser->nitobi_javascript();  								  
        }			
    }
	
	function action($action=null) {	
	
	   if (GetSessionParam('REMOTELOGIN')) 
	     $winout = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	   else  
         $winout = setNavigator(seturl("t=cp","Control Panel"),$this->title);		
		 		 
	
	    switch ($action) {
			
          case 'cpbradd'      : $out .= rcbrowser::add_record(); break;
          case 'cpbredit'     : $out .= rcbrowser::edit_record(); break;		  							
          case 'cpbrdel'      : $out .= rcbrowser::del_record(); break;		  
          case 'cpdmcuberev'  :
          case 'cpdmcube'     : 
		  default             :	
								$out .= $this->browser->render();							  
        }
		
        
		$win2 = new window($this->title,$out);
		$winout .= $win2->render("center::100%::0::group_dir_headtitle::left::0::0::");
		unset ($win2);
		  					
		
		return ($winout);
    }	
  
};
}
?>