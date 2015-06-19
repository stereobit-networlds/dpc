<?php

$__DPCSEC['LOCKMYTRANSACT_DPC']='1;1;1;2;2;2;2;2;9';

if ((!defined("LOCKMYTRANSACT_DPC")) && (seclevel('LOCKMYTRANSACT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOCKMYTRANSACT_DPC",true);

$__DPC['LOCKMYTRANSACT_DPC'] = 'lockmytransact';

$d = GetGlobal('controller')->require_dpc('shop/shtransactions.dpc.php');
require_once($d);

GetGlobal('controller')->get_parent('SHTRANSACTIONS_DPC','LOCKMYTRANSACT_DPC');

$__LOCALE['LOCKMYTRANSACT_DPC'][0]='LOCKMYTRANSACT_CNF;Record Transaction List;Λίστα Συναλλαγών εγγραφων';	   
	   
class lockmytransact extends shtransactions {

    var $path;
    var $initial_word;

    function lockmytransact() {
   
       shtransactions::shtransactions();
    }
	
	function get_map_status($cid,$startdate=null) {
       $db = GetGlobal('db');
	   //sum
	   $sSQL = "select sum(tmap) from transactions" . 
	           " where cid=" . $db->qstr($cid);
       $result = $db->Execute($sSQL,2);	
	   
	   //echo $sSQL;
	   $ret = $result->fields[0];
	   //echo $ret,'>';
	   //print_r($result);
	   return ($ret);	   	
	}
	
	function set_map_status($trid,$state=0) {
       $db = GetGlobal('db');		     
	   
	   $sSQL = "update transactions set tmap=" . $state .
	           " where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL,1);
	   //echo $sSQL;	
       if ($db->Affected_Rows()) 
		 return true;
		 
	   return false;   	   						  
	}
	
	function get_qty($trid) {
       $db = GetGlobal('db');		     
	   
	   $sSQL = "select qty from transactions" . 
	           " where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL,2);	
	   
	   //echo $sSQL;
	   $ret = $result->fields[0];

	   return ($ret);	   						  
	}
	
	//get contact days from transactions
	function get_days($trid=null,$cid=null) {
       $db = GetGlobal('db');		     
	   
	   if ($trid) {//gat contract days of selected no of trans
	     $sSQL = "select type1 from transactions" . 
	             " where tid='" . $this->initial_word. $trid ."'";
         $result = $db->Execute($sSQL,2);	
	   
	     //echo $sSQL;
	     $ret = $result->fields[0]; 
	   }
	   elseif ($cid) {//get contract days of last pay of apptype order by paypal paied (tstatus>0) older unmapped trans
	     $sSQL = "select type1 from transactions" .   //////////=0 desc ...for test
	             " where cid=" . $db->qstr($cid) . " and tstatus>0 and tmap>0 order by recid asc limit 1";
         $result = $db->Execute($sSQL,2);	
	   
	     //echo $sSQL;
	     $ret = $result->fields[0]; 	   
	   }	 
	   
	   return ($ret); 	
	}			
	
	//override
    function saveTransaction($data='',$user='',$payway=null,$roadway=null,$qty=null,$cost=null,$costpt=null,$map=null,$paydays=null) {
       $db = GetGlobal('db');
       $myqty = $qty?$qty:0;
       $mycost = $cost?$cost:0;
       $mycostpt = $costpt?$costpt:0;
       $ret = 0;
	   $myuser = $user?$user:$this->userid;
	   $tmap = $map?$map:0;
	   
	   
       $theid   = $this->generate_id();
	   
	   if (($theid) && ($myuser)) {
          $id = $theid + $this->tcounter;
		  $myid = $this->initial_word . $id;  
	      //$mydate = date('d/m/Y');//get_date("d/m/y");
          $mydate = date('Y/m/d'); //mysql...
	      $mytime = date('h:i:s A');//get_date("h:n");
	      $mydata = $data;
		  
		  $pd = $this->get_contract_days($mydata);
		  $paydays = $paydays?intval($paydays):intval($pd);
		  
          $sSQL = "insert into transactions (tid,cid,tdate,ttime,tdata,tstatus,payway,roadway,qty,cost,costpt,tmap,type1) values " .
                 "(" .
		         $db->qstr($myid) . "," .
		         $db->qstr($myuser) . "," .
		         $db->qstr($mydate) . "," .
		         $db->qstr($mytime) . "," .
		         $db->qstr($mydata) . "," . 
		         "0," .
		         $db->qstr($payway) . "," . 
		         $db->qstr($roadway) . "," .
		         $myqty . "," .
		         $mycost . "," .
		         $mycostpt . "," .
	             $tmap . "," .				 
		         $paydays . ")";				 				 				 				 				 

	      $res = $db->Execute($sSQL,1);
          //echo $sSQL;
		  
          if ($db->Affected_Rows()) 
		    $retid = $id;
	      else 
		    $retid = 0;		  	   	    	   	
       }
   
       //save xml file
       $xml = new pxml();

	   $xml->addtag('ORDER',null,null,"id=".$retid);							
	   $xml->addtag('XUL','ORDER',null,null); 
       $xml->addtag('GTKWINDOW','XUL',null,null);
							
	   $ret = $xml->getxml();
	   $this->save2disk($retid,$ret);
	  
	   unset($xml);   
							
	   return ($retid);						
    }
	
	//1 element array read id = paydays
	function get_contract_days($data) {
	
	   $mdata = (array) unserialize($data);
	   //print_r($mdata);
	   foreach ($mdata as $bufdata) {
	     $d = explode(';',$bufdata);
		 return $d[0];
	   }
	   
	   return '0';
	}
		
};
}
?>