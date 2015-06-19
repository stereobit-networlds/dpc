<?php
$__DPCSEC['SHDATALOGICBJ_DPC']='1;1;1;1;1;1;2;2;9';
$__DPCSEC['SHDATALOGICBJ_CART']='1;1;1;1;1;1;2;2;9';

if ( (!defined("SHDATALOGICBJ_DPC")) && (seclevel('SHDATALOGICBJ_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHDATALOGICBJ_DPC",true);

$__DPC['SHDATALOGICBJ_DPC'] = 'shdatalogicbj';

$d = GetGlobal('controller')->require_dpc('shop/shkatalog.dpc.php');
require_once($d);


$__EVENTS['SHDATALOGICBJ_DPC'][0]='datalogic';

$__ACTIONS['SHDATALOGICBJ_DPC'][0]='datalogic';

$__LOCALE['SHDATALOGICBJ_DPC'][0]='SHDATALOGICBJ_DPC;Shop Scanner;Shop Scanner';

class shdatalogicbj extends shkatalog {

    var $userLevelID;	
	var $result, $pc;
	var $path;

	function shdatalogicbj() {
	  $UserSecID = GetGlobal('UserSecID');	
	
	  shkatalog::shkatalog();
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  

      $this->path = paramload('SHELL','prpath');	
	  $this->title = localize('SHDATALOGICBJ_DPC',getlocal());		
	}
	
	function event($event=null) {
	
	    switch ($event) {

		  case 'datalogic'    : 
		  default             : //$this->read_item(); 							  
        }	
	}
	
	function action($action=null) {
	
		/*if (GetReq('cat')) 
		  $out = $this->tree_navigation('klist','',1);  
		else
		  $out .= setNavigator($this->title,localize('_item',getlocal()));	
		  */  		
	  
	    switch ($action) {
	  
		  case 'datalogic'    : 
		  default             : $out .= 'zzz';//$this->show_item();
		                       
        }	  
	  
	    return ($out);
	}	
		
}
}	
?>