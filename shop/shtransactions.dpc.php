<?php

$__DPCSEC['SHTRANSACTIONS_DPC']='1;1;1;2;2;2;2;2;9';

if ((!defined("SHTRANSACTIONS_DPC")) && (seclevel('SHTRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHTRANSACTIONS_DPC",true);

$__DPC['SHTRANSACTIONS_DPC'] = 'shtransactions';

//require_once("transactions.dpc.php");
//GetGlobal('controller')->include_dpc('transactions/transactions.dpc.php');
$d = GetGlobal('controller')->require_dpc('transactions/transactions.dpc.php');
require_once($d);

//in case of page cntrl pxml not exist so load
$d = GetGlobal('controller')->require_dpc('shell/pxml.lib.php');
require_once($d);
/*$e = GetGlobal('controller')->require_dpc('gui/datepick.dpc.php');
require_once($e);
$f = GetGlobal('controller')->require_dpc('libs/browserSQL.lib.php');
require_once($f);*/

//this transfer all actions,commands,attr from parent to child and parent disabled(=null)
//it is important for inherit to still procced the commands of parent
GetGlobal('controller')->get_parent('TRANSACTIONS_DPC','SHTRANSACTIONS_DPC');
//print_r($__ACTIONS['SHTRANSACTIONS_DPC']);

$__EVENTS['SHTRANSACTIONS_DPC'][6]='transviewhtml';

$__ACTIONS['SHTRANSACTIONS_DPC'][6]='transviewhtml';

//overwrite for cmd line purpose
$__LOCALE['SHTRANSACTIONS_DPC'][0]='SHTRANSACTIONS_CNF;Transaction List;Λίστα Συναλλαγών';	   
$__LOCALE['SHTRANSACTIONS_DPC'][1]='_COST;Cost;Κόστος';	
	   
class shtransactions extends transactions {

   var $path;
   var $initial_word;

   function shtransactions() {
   
       transactions::transactions();
	   //override if exist
	   if ($tpath = paramload('SHTRANSACTIONS','path'))
	     $this->path = paramload('SHELL','prpath') . $tpath;	

       $this->initial_word = remote_paramload('SHTRANSACTIONS','trid',paramload('SHELL','prpath'));  
//echo $this->initial_word,'>';

   }
   
   //override
   function event($event=null) {
   
       switch ($event) {
	     case 'transviewhtml' : $this->viewTransactionHtml();
		                        die();
		                        break;
								
		 default              : transactions::event($event);						
	   }
   }
   
   //overwrite
   function saveTransaction($data='',$user='',$payway=null,$roadway=null,$qty=null,$cost=null,$costpt=null) {
   
      //execute default save and get id
      $id = transactions::saveTransaction($data,$user,$payway,$roadway,$qty,$cost,$costpt);
   
      //save xml file
      $xml = new pxml();

	  $xml->addtag('ORDER',null,null,"id=".$id);							
	  $xml->addtag('XUL','ORDER',null,null); 
      $xml->addtag('GTKWINDOW','XUL',null,null);
							
	  $ret = $xml->getxml();
	  $this->save2disk($id,$ret);
	  
	  unset($xml);   
							
	  return ($id);						
   }
   
   function save2disk($id,$data) {
   
      $file = $this->path . $id . ".xml"; 
	  //echo $file,$data;
      $fd = fopen($file, 'w');
      fwrite($fd, $data);
      fclose($fd);   
   }

    //override use tid instead of recid in db mode
	function setTransactionStatus($trid,$state) {
       $db = GetGlobal('db');
	   
	   //if ($this->storetype=='DB') {  //db		   
	     
	   
	     $sSQL = "update transactions set tstatus=" . $state .
	             " where tid='" . $this->initial_word. $trid ."'";
         $result = $db->Execute($sSQL);
		
	     //print $sSQL.'>';
	     //print $db->Affected_Rows() . ">>>>";
         if ($db->Affected_Rows()) return true;
	                          else return false;   	   
	  /* }
	   else {//echo "XML";  //xml and txt
	   
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.'.$this->storetype) && 
												(strstr($fileread,'.'.$this->storetype))) {	   
	     
	           if (stristr($fileread,$trid)) {
			     //echo $fileread;
				 $parts = explode("_",$fileread);
				 $parts[2] = $state . "." . $this->storetype;
				 $newname = implode("_",$parts);
				 //echo $newname;
				 rename($this->path.$fileread,$this->path.$newname);
				 $mydir->close();		   
				 return (true);
		       }
		     }	 
		   }  
	     }
         $mydir->close();		     
	   }	*/					  
	}
	
	function getTransactionStatus($trid) {
       $db = GetGlobal('db');
	   
	   $sSQL = "select tstatus from transactions" . 
	           " where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL);	
	   
	   $ret = $result->field['tstatus'];
	   return ($ret);
	}
	
	function setTransactionStoreData($trid,$fieldname,$value=null) {
       $db = GetGlobal('db');
	   	   
	   $sSQL = "update transactions set $fieldname='" . $value .
	           "' where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL);
		
	     //print $sSQL.'>';
	     //print $db->Affected_Rows() . ">>>>";
       if ($db->Affected_Rows()) 
	     return true;
	   else 
	     return false;   	   
	}
	
	function getTransactionStoreData($fieldname,$trid) {
       $db = GetGlobal('db');
	   	   
	   $sSQL = "select $fieldname from transactions " .
	           "where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL);
		
	     //print $sSQL.'>';
		 //print_r($result);
	     //print $db->Affected_Rows() . ">>>>";
       $ret = $result->fields[$fieldname]; 
	   return $ret;   	   
	}	 
	
	//called by shpaypal to check txn_id
	function checkPaypalTXNID($txnid) {
       $db = GetGlobal('db');
	   	   
       $sSQL = "select type1 from transactions where payway='PAYPAL' and type1=";
	   $sSQL .= $db->qstr($txnid);
       $result = $db->Execute($sSQL);
		
       if ($result->fields['type1']) 
	     return false;//exist ???
	   else 
	     return true;//not exist ok  	
	} 
	
	//called by shpiraeus to check txn_id
	function checkPiraeusTicket($txnid) {
       $db = GetGlobal('db');
	   	   
       $sSQL = "select type1 from transactions where payway='PIRAEUS' and type1=";
	   $sSQL .= $db->qstr($txnid);
       $result = $db->Execute($sSQL);
		
       if ($result->fields['type1']) 
	     return false;//exist ???
	   else 
	     return true;// not exist ok  	
	} 
	
	//replace 2 func above
	function is_unique($id,$fieldnametocheck=null,$valtocheck=null,$field=null) {
       $db = GetGlobal('db');
	   
	   $f = $field ? 'type2':'type1';
	
       $sSQL = "select $f from transactions where ";
	   
	   if ($fieldnametocheck)
	     $sSQL .= $fieldnametocheck."=" . $db->qstr($valtocheck) . " and ";
	   
	   $f . "=" . $db->qstr($id);
		 
	   $sSQL .= $db->qstr($txnid);
       $result = $db->Execute($sSQL);
		
       if ($result->fields[$f]) 
	     return false;//exist ???
	   else 
	     return true;// not exist ok  		
	}	 
	
	function saveTransactionHtml($id,$data) {
	    $d = unserialize($data);
        $file = $this->path . $id . ".html"; 
	    //echo $file;//,$data;
		
	    $headtitle = paramload('SHELL','urltitle');			
		$hpage = new phtml('../themes/style.css',$d,"<B><h1>$headtitle</h1></B>");
		$dd = $hpage->render();
		unset($printpage);		
		
        $fd = fopen($file, 'w');
        fwrite($fd, $dd, strlen($dd));
        fclose($fd);   		
	} 
	
	function getTransactionHtml($id) {
        $file = $this->path . $id . ".html"; 
	    //echo $file;//,$data;
		
	    if (is_readable($file)) {
		
		  $ret = file_get_contents($file);
		}
		else
		  $ret = 'file not exist!';  
		
		return ($ret);		
	} 	
	
	//override
	function getTransaction($trid) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db	
	   	   
	     $sSQL = "select * from transactions where tid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print_r ($res->fields[5]);
	     if ($res) { 
	       $out = $res->fields[5]; 
		   return ($out);
	     }
	   }
	} 
	
	
	//return array of relative sales id's
	function getRelativeSales($limit=null,$id=null) {
       $db = GetGlobal('db');
	   $id = $id?$id:GetReq('id');
	   
	   //search serialized data for id
	   $sSQL = "select tid,tdata from transactions " .
	           "where tdata like'%" . $id ."%' order by tid desc";
       $result = $db->Execute($sSQL,2);
	   //echo $sSQL;
	   
	   foreach ($result as $n=>$rec) {	
         $tdata = $rec['tdata'];
		 
		 if ($tdata) {
		   $cdata = unserialize($tdata);
		   if (count($cdata)>1) {//if many items
		     foreach ($cdata as $i=>$buffer_data) {
		 
		       $param = explode(";",$buffer_data);
		       if ($param[0] != $id) 
		         $ret[] = $param[0]; //save code
			 
		       if (count($ret)>$limit) break; //limit to fetch	 
		     }	 
		   }
		 } 
	   }
	   return $ret;   	   	
	}	
	
	
	function getTransactionsList() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   $name = $UserName?decode($UserName):null;		   
	   
	   if (!$name) return;
	   	
	   if ($this->storetype=='DB') {  //db	
	   	   
	     $sSQL = "select tid,tdate,ttime,tstatus,payway,roadway,cost,costpt from transactions where cid=" . $db->qstr($name) . 
		         "order by tid DESC";
				 
		 /*$browser = new browseSQL(localize('_TRANSLIST',getlocal()));
	     $out .= $browser->render($db,$sSQL,"transactions","transview",15,$this,1,0,1,0); //do not search internal because of form conflict
	     unset ($browser);*/					 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res->fields[5]);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
			
           /* if ($this->admint==1) {
			   //print checkbox 
			   $checkbox = "<input type=\"checkbox\" name=\"" . $rec[0] . 
			                                     "\" value=\"" . $rec[0] . "\">"; 
			}
			elseif ($this->admint==2) {
			   //print checkbox only if status!=1
			   $checkbox = "<input type=\"checkbox\" name=\"" . $rec[0] . 
			                                    "\" value=\"" . $rec[0] . "\">"; 
			}												  
			else $checkbox = "";*/			
			
            $transtbl[] = //$checkbox . $i . ";" . 
                         $rec[0] . ";" .
			             $rec[0] . ";" .
						 /*$rec[3] .*/ $rec[4] . "/" . $rec[5] . ";" .
			             $rec[1] . " / " . $rec[2] . ";" .	
			             number_format($rec[7],2,',','.');// . ";" .						 					 
			             //number_format($rec[7],2,',','.')/*str_replace(".",",",$rec[7])*/;		   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):10;
           $browser = new browse($transtbl,/*localize('_TRANSLIST',getlocal())*/null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("transview",$ppager,$this,1,0,0,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(/*localize('_CART',getlocal())*/null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 
	   }	
	   
	   return ($out);
	} 	
	
	//override
	function viewTransactions() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');	   
	   
	   if (!$UserName) {
	     if (defined('SHLOGIN_DPC')) {
		   $out = GetGlobal('controller')->calldpc_method("shlogin.quickform use +transview+shtransactions>viewTransactions");
		 }
	     else
	       $out = ("You must be logged in to view this page.");
		   
		 return ($out);  
	   }	 
	   
	   $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos;   

       $myaction = seturl("t=transview");	   
	   
       if (seclevel('TRANSADMIN_',$this->userLevelID)) {
	     $this->admint=1;
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Transview\">";		 
	   }
	   elseif (seclevel('TRANSCANCEL_',$this->userLevelID)) { 
	     $this->admint=2;	   
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Transview\">";		   
	   }
	   else {
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Transview\">";		   
	   }

	 
	   $out .= $this->getTransactionsList();	 
		 
	   if ($this->admint) {
		/*     if ($this->admint==1) {
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status0\">&nbsp;";		 
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status1\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";			   
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }
			 elseif ($this->admint==2) {
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }*/
			 
             $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Transview\">";
             $out .= "</FORM>";			 		   
			 	
	   }  
	   		 

       /*$out .= $this->searchform();	    
		 
	   $dater = new datepicker();	
	   $out .= $dater->renderspace(seturl("t=transview&a=$a"),"transview");		 
	   unset($dater);
       */
						
	   
	   return ($out);
	}	
	
	//overide
	function details($id,$storebuffer=null) {
	   
	   if (defined('SHCART_DPC')) 
	     $ret = GetGlobal('controller')->calldpc_method('shcart.previewcart use '.$id.'+transview');
		 
	   return ($ret);
	}
	
	function viewTransactionHtml($id=null) {
	    $id = $id?$id:GetReq('tid');
	
        $file = $this->path . $id . ".html"; 
	    //echo $file;
		if (is_readable($file)) {
		  $ret = file_get_contents($file);
		
		  //return ($ret);	
		  echo $ret;
		  die();
		}
		else
		  return false;
	} 		
	
	//override
    function viewtrans($id,$fname,$lname,$status,$ddate,$dtime) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $link = seturl("t=loadcart&tid=$id" , $id);
	   
       if ($this->admint>0) {//==1) {
			   //print checkbox 
			   $data[] = "<input type=\"checkbox\" name=\"" . $fname . 
			                                  "\" value=\"" . $fname . "\">"; 
	           $attr[] = "left;1%";											  
	   }
	   /*elseif ($this->admint==2) {
			   //print checkbox only if status!=1
			   $data[] = "<input type=\"checkbox\" name=\"" . $fname . 
			                                  "\" value=\"" . $fname . "\">"; 
	           $attr[] = "left;1%";											  
	   }	*/											  	   
	   
							  
	   	   
	   $data[] = $link;   
	   $attr[] = "left;10%";
	   
	   /*switch ($status) {
			  case 0 : $data[] = $this->status0; break;
			  case 1 : $data[] = $this->status1; break;	
			  case 2 : $data[] = $this->status2; break;				  		  
			  case 3 : $data[] = $this->status3; break;
			  case 4 : $data[] = $this->status4; break;
	   }	
	   $data[] = $fname;       
	   $attr[] = "left;10%";		   
	   */
	   
	   if (is_readable($this->path . $id . ".html")) {	
	     $lnk = seturl('t=transviewhtml&tid='.$id,$lname);
       }
	   else 
	     $lnk = $lname;		   
	   
	   $data[] = $lnk;   
	   $attr[] = "left;50%";   
	   
	   $data[] = $status;   
	   $attr[] = "left;20%";	      
	   
	   $data[] = $ddate /*. '/' . $dtime*/;   
	   $attr[] = "right;10%";	
	   
	   //$data[] = $dtime;   
	   //$attr[] = "right;1%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line . '<br/>' . $this->details($id);
		 
		 if (defined('WINDOW2_DPC')) {
	       $cartwin = new window2($id . '/' . $status,$mydata,null,1,null,'HIDE',null,1);
	       $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	       unset ($cartwin);		   
		 }
		 else {
	       $cartwin = new window($id . '/' . $status,$mydata);
	       $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	       unset ($cartwin);		 
		 }
	   }	
	   else {   
		 $out .= $line . '<hr>';
	   }	   
	   

	   return ($out);
	}
		
	//override
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $sort = GetReq('sort');  
	
       //$data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=0" ,  "Id" );
	   //$attr[] = "left;5%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=1" , localize('_TRANSACTION',getlocal()) );
	   $attr[] = "center;20%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=2" , localize('_TRANSTAT',getlocal()) );
	   $attr[] = "center;50%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=3" , localize('_DATE',getlocal()) );
	   $attr[] = "center;20%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=4" , localize('_COST',getlocal()) );
	   $attr[] = "center;10%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}			
	
};
}
?>