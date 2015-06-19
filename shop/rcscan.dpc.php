<?php
$__DPCSEC['RCSCAN_DPC']='1;1;1;1;1;1;2;2;9';
$__DPCSEC['RCSCAN_CART']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSCAN_DPC")) && (seclevel('RCSCAN_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSCAN_DPC",true);

$__DPC['RCSCAN_DPC'] = 'rcscan';

$d = GetGlobal('controller')->require_dpc('shop/shkatalog.dpc.php');
require_once($d);


$__EVENTS['RCSCAN_DPC'][0]='cpscan';
$__EVENTS['RCSCAN_DPC'][1]='scanread';

$__ACTIONS['RCSCAN_DPC'][0]='cpscan';
$__ACTIONS['RCSCAN_DPC'][1]='scanread';

$__LOCALE['RCSCAN_DPC'][0]='RCSCAN_DPC;Scanner;Scanner';

class rcscan extends shkatalog {

    var $userLevelID;	
	var $result, $pc;
	var $path;

	function rcscan() {
	  $UserSecID = GetGlobal('UserSecID');	
	
	  shkatalog::shkatalog();
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	  

      $this->path = paramload('SHELL','prpath');	
	  $this->title = localize('RCSCAN_DPC',getlocal());	
	  $this->pc = remote_paramload('SHMK1200','pc',$this->path);
	}
	
	function event($event=null) {
	
	   //ALLOW EXPRIRED APPS
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			
	
	    switch ($event) {

		  case 'scanread'     : $this->read_item(1); break;
		  default             : //$this->read_item();
        }	
	}
	
	function action($action=null) {
	
	  /*  if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	    else  
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	  		
	  */
	    switch ($action) {
		
		  case 'scanread'     : $out .= $this->show_item(); break;
	  
		  case 'cpscan'       : 
		  default             : 
		                       
        }	  
	  
	    return ($out);
	}	
	
	//override
	function read_item($check_digit=null) {
      $db = GetGlobal('db');	
	  //$item = GetReq('id');	 
	  if ($check_digit) {
	    $bc = GetReq('listm')?GetReq('listm'):GetReq('id');
	    $item = $this->create_check_digit($bc);
	  }	
	  else
	    $item = GetReq('listm')?GetReq('listm'):GetReq('id');
		
	  $sSQL = "select mciid,itmname from stcview ";
	  $sSQL.= "where codcode='".$item . "'";
	
	  //echo $sSQL;			
	   
	  $resultset = $db->Execute($sSQL,2);
	  /*echo "<pre>";
	  print_r($resultset);  
	  echo "</pre>";*/
	  $this->result = $resultset; 
	  
      /*$test = "select codcode from sti where codcode=50000";//CAT3,CAT2 from CATEGORIES where CAT2='ΕΠΙΠΛΑ - ΕΞΟΠΛΙΣΜΟΣ' and ctgid>0";
	  $tre = $db->Execute($test,2);	
	  echo $test;
	  echo "<pre>";
	  print_r($tre);  
	  echo "</pre>";*/

	}	
	
	//override
	function show_item($template=null) {
	   $myscanneditem = GetReq('listm');
	   
	   /*echo "<pre>";
	   print_r($this->result->fields);
	   echo '</pre>';*/
	   
	   
	   //print_r($this->result); echo 'z';
	   if (!empty($this->result->fields)) {	
	      //echo 'xxx';
	      $out = $myscanneditem . "<br>";
		  $out .= $this->result->fields[0]. "&nbsp;";
		  $out .= $this->result->fields[1];
	   }
	   else
	      $out = $myscanneditem . " not found!";
		  	   
						   
	   return ($out);	
	}
	
	
	function create_check_digit($n) {	   
	  
	   $z = 0;

	   $max = strlen($n)-1;
	
	   for ($i=$max;$i>=0;$i--) {
	     
		 if ($i%2==0) {
		   $z = $z + substr($n,$i,1);
		 }
		 else {
		   $z = $z + (3*substr($n,$i,1));
		 }
	   }
	   
	   $cd = 10 - ($z % 10);
	   $cd = $cd % 10;
	   
	   //echo $n,$cd;
	   return ($n.$cd);
	}

};
}
?>