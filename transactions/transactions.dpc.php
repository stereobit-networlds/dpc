<?php
//if (defined("DATABASE_DPC")) {

$__DPCSEC['TRANSACTIONS_DPC']='1;1;1;2;2;2;2;2;9';
$__DPCSEC['TRANSADMIN_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['TRANSCANCEL_']='2;1;2;2;2;2;2;2;9';

if ((!defined("TRANSACTIONS_DPC")) && (seclevel('TRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("TRANSACTIONS_DPC",true);

$__DPC['TRANSACTIONS_DPC'] = 'transactions';

$__EVENTS['TRANSACTIONS_DPC'][0]='searchtrans';
$__EVENTS['TRANSACTIONS_DPC'][1]=localize('_TRANSEND',getlocal());
$__EVENTS['TRANSACTIONS_DPC'][2]=localize('_TRANSCANC',getlocal());
$__EVENTS['TRANSACTIONS_DPC'][3]=localize('_TRANSPROC',getlocal());
$__EVENTS['TRANSACTIONS_DPC'][4]='reset_tr';
$__EVENTS['TRANSACTIONS_DPC'][5]=localize('_TRANSDELL',getlocal());
$__EVENTS['TRANSACTIONS_DPC'][999]='transview';

$__ACTIONS['TRANSACTIONS_DPC'][0]='transview';
$__ACTIONS['TRANSACTIONS_DPC'][1]='searchtrans';
$__ACTIONS['TRANSACTIONS_DPC'][2]=localize('_TRANSEND',getlocal());
$__ACTIONS['TRANSACTIONS_DPC'][3]=localize('_TRANSCANC',getlocal());
$__ACTIONS['TRANSACTIONS_DPC'][4]=localize('_TRANSPROC',getlocal());
$__ACTIONS['TRANSACTIONS_DPC'][5]=localize('_TRANSDELL',getlocal());

$__DPCATTR['TRANSACTIONS_DPC']['transview'] = 'transview,1,0,1'; 
$__DPCATTR['TRANSACTIONS_DPC']['searchtrans'] = 'searchtrans,1,0,1';
$__DPCATTR['TRANSACTIONS_DPC'][localize('_TRANSEND',getlocal())] = '_TRANSEND,1,0,1,0,0,0,0,0,0';
$__DPCATTR['TRANSACTIONS_DPC'][localize('_TRANSCANC',getlocal())] = '_TRANSCANC,1,0,1,0,0,0,0,0,0';
$__DPCATTR['TRANSACTIONS_DPC'][localize('_TRANSPROC',getlocal())] = '_TRANSPROC,1,0,1,0,0,0,0,0,0';
$__DPCATTR['TRANSACTIONS_DPC'][localize('_TRANSDELL',getlocal())] = '_TRANSPROC,1,0,1,0,0,0,0,0,0';

$__LOCALE['TRANSACTIONS_DPC'][0]='_TRANSEND;Procceded;Εκτελεσμένη';
$__LOCALE['TRANSACTIONS_DPC'][1]='_TRANSCANC;Canceled;Ακυρη';
$__LOCALE['TRANSACTIONS_DPC'][2]='_TRANSPROC;In Procces;Εκκρεμής';
$__LOCALE['TRANSACTIONS_DPC'][3]='_TRANSTAT;Status;Κατάσταση';
$__LOCALE['TRANSACTIONS_DPC'][4]='_TRANSNUM;Order No;Αριθμός Παραγγελίας';
$__LOCALE['TRANSACTIONS_DPC'][5]='_TRANSDATA;Transaction data;Στοιχεία συναλλαγής';
$__LOCALE['TRANSACTIONS_DPC'][6]='_TRANSINFO;All data are important for a successfull transaction ! !;Όλα τα στοιχεία πρέπει να είναι σωστά συμπληρωμένα ! !';
$__LOCALE['TRANSACTIONS_DPC'][7]='_TRANSPRINT;Print Transaction;Εκτύπωση Συναλλαγής';
$__LOCALE['TRANSACTIONS_DPC'][8]='_TRANSERROR;Transaction not successfull. Please try later or inform us at ;Η συναλλαγή δεν εκτελέστηκε. Παρακαλώ δοκιμάστε αργότερα ή ενημερώστε μας στο';
$__LOCALE['TRANSACTIONS_DPC'][9]='_TRANSOK;Thank you! Your order submited successfully with Order No :;Ευχαριστούμε ! Η παραγγελία σας εκτελέστηκε επιτυχώς με αριθμό Παραγγελίας :';
$__LOCALE['TRANSACTIONS_DPC'][10]='_TRANSEARCH;Search Transaction;Αναζήτηση Συναλλαγής';
$__LOCALE['TRANSACTIONS_DPC'][11]='_TRANSLIST;Transaction List;Λίστα Συναλλαγών';
$__LOCALE['TRANSACTIONS_DPC'][12]='_TRANSACTION;Transaction;Κίνηση';
$__LOCALE['TRANSACTIONS_DPC'][13]='TRANSACTIONS_CNF;Transaction List;Λίστα Συναλλαγών';
$__LOCALE['TRANSACTIONS_DPC'][14]='_TRANSMERR;Not submited;Μη απεσταλμενη';
$__LOCALE['TRANSACTIONS_DPC'][15]='_TRANSDELL;Deleted;Διεγραμμενη';
$__LOCALE['TRANSACTIONS_DPC'][16]='_TRANSEMPTY;No Transaction;Δεν υπάρχουν κινήσεις';


class transactions {

	var $userLevelID;
	var $username;
	var $userid;
	var $pagenum;
	var $searchtext;
    var $storetype;	
	var $path;
	var $admint;
	var $status0,$status1,$status2,$status3,$status4;
	var $details, $tcounter;
        var $initial_word;

	function transactions() {
	   $UserName = GetGlobal('UserName');	
	   $UserSecID = GetGlobal('UserSecID');
	   $UserID = GetGlobal('UserID');			

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   
	   $this->pagenum = 30;
	   $this->searchtext = trim(GetParam("transnum"));
	   
	   $this->status0 = localize('_TRANSPROC',getlocal());
	   $this->status1 = localize('_TRANSEND',getlocal());
	   $this->status2 = localize('_TRANSCANC',getlocal());
	   $this->status3 = localize('_TRANSMERR',getlocal());	//not submited   
	   $this->status4 = localize('_TRANSDELL',getlocal());	//deleted 	   

	   $this->tcounter = paramload('TRANSACTIONS','counter')?paramload('TRANSACTIONS','counter'):0;
	   $this->storetype = paramload('TRANSACTIONS','storetype');
	   $this->path = paramload('SHELL','prpath') . paramload('TRANSACTIONS','path');	
	   //echo $this->path;   
	   $this->details = paramload('TRANSACTIONS','details');
	   
	   $this->admint = 0;

           $this->initial_word = paramload('TRANSACTIONS','trid');
	   
	   //extension must be already loaded
       if (($this->storetype=='xml') && (!defined(_PHPXMLDUMPER_))) 
	     die ("TRANSACTION_DPC:Extension missing!");	   
	}
	
    function action()  { 

		 //$out = $this->title();
		 //$out .= $this->form();
         $out = setNavigator(localize('_TRANSLIST',getlocal()));
         $out .= $this->viewTransactions();

	   return ($out);
	}
	
	function event($evn) {
	   $a = GetReq('a');
	   
	   switch ($evn) {
	     case "searchtrans"  : SetReq('a',$this->searchtext); break;
		 case $this->status0 : $this->modifyTransactionStatus(0); break;		 
		 case $this->status1 : $this->modifyTransactionStatus(1); break;		 
		 case $this->status2 : $this->modifyTransactionStatus(2); break;
		 //case $this->status3 : $this->modifyTransactionStatus(3); NO NEED ..PRODUCED by MAIL ERROR
		 case $this->status4 : $this->modifyTransactionStatus(4); break;		 		 
		 case "reset_tr"     : $this->reset_db(); break;			 
	   }
	}

	function generate_id() {
       $db = GetGlobal('db');

	   if ($this->storetype=='DB') {  //db
	   
	     $sSQL = "select count(*) from transactions";
	     $res = $db->Execute($sSQL,null,1);
		  
		 if ($db->model=='ADODB') 
	       $out = $res->fields[0]+1;//RecordCount()+1;
		 else //sqlite
		   //$out = $res[0]+1;//$data[0]+1;
         $out = $res->fields[0]+1;//$data[0]+1;

		 //echo $out,'>>>';
       }
	   else {//xml and txt
	   
         $dumper = new PHP_XML_Dumper('ID');
		 $id = $dumper->xml2php($this->path . "id.".$this->storetype); 
		 //WARNING: file id.xml must pre-exist as blank xml file;
		 if (!$id[0]) {
		   $res[0] = 1;
           $dumper->php2xml($res, $this->path . "id.".$this->storetype);		 
		 }
		 else {
		   $res[0] = $id[0]+1;
           $dumper->php2xml($res, $this->path . "id.".$this->storetype);		 		   
		 }   
	     unset($dumper);	
		 $out = $res[0];	 
	   }
	   //print $out;	
	   return ($out);
	}
	
	//bulk modifications
	function modifytransactionStatus($state) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db	   
	   
	     $i = 0;
	     $sSQL = "select tid from transactions";
	     $res = $db->Execute($sSQL); 	   
	   
	     while(!$res->EOF) {
	       $tran = GetParam($res->fields[0]);
	       if ($tran) {
		     //$tr[] = $tran;
		   
		     $sSQL2 = "update transactions set tstatus=" . $db->qstr($state) .
		              " where tid=" . $db->qstr($tran);
     	     $result = $db->Execute($sSQL2);
		     if ($result) $i+=1;	
		   }
	       $res->MoveNext();		 
	     }
	     setInfo($i." rows affected.");
	     //print_r($tr);
	   }
	   else { //xml and txt
	   
         if (is_dir($this->path)) {
           $i=0;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.'.$this->storetype) && 
												(strstr($fileread,'.'.$this->storetype))) {	   
	     
			   $parts = explode("_",$fileread);	 
		       $tran = GetParam($parts[1]);
	           if ($tran) {
			     $i+=1;
				 
				 $parts[2] = $state . "." . $this->storetype;
				 $newname = implode("_",$parts);
				 //echo $newname,"<br>";
				 rename($this->path.$fileread,$this->path.$newname);			 
		       }
		     }	 
		   }  
	     }
         $mydir->close();	
		 setInfo($i." transactions affected.");	   
	   }
	}

	function saveTransaction($data=null,$user=null,$payway=null,$roadway=null,$qty=null,$cost=null,$costpt=null) {
       $db = GetGlobal('db');
       $myqty = $qty?$qty:0;
       $mycost = $cost?$cost:0;
       $mycostpt = $costpt?$costpt:0;

       $ret = 0;
	   
	   $myuser = $user?$user:$this->userid; 
	   //echo $myuser,'>>';
			 
       $theid   = $this->generate_id();

	   if (($theid) && ($myuser)) {
          $id = $theid + $this->tcounter;
		  $myid = $this->initial_word . $id;  
	      //$mydate = date('d/m/Y');//get_date("d/m/y");
          $mydate = date('Y/m/d'); //mysql...
	      $mytime = date('h:i:s A');//get_date("h:n");
	      $mydata = $data;
		  
	      if ($this->storetype=='DB') { 
             $sSQL = "insert into transactions (tid,cid,tdate,ttime,tdata,tstatus,payway,roadway,qty,cost,costpt) values " .
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
		         $mycostpt . ")";				 				 				 

	         $res = $db->Execute($sSQL,1);

		     //echo $sSQL;
		     //print $db->Affected_Rows();
			 //echo '>>>>',$res;

             if ($db->Affected_Rows()) $ret = $id;
	                             else $ret = 0;

	       }
           elseif ($this->storetype=='xml') {// XML
	   
             $dumper = new PHP_XML_Dumper('transaction');
             $dumper->php2xml(unserialize($data), $this->path . $this->username . "_" . //user
			                                      $id . "_" . //transaction id
												  "0" . ".xml"); //state		 
	         unset($dumper);
		 
		     $ret = $id;
	       }
		   else { //default txt
		   
		     $tfile = $this->path . $this->username . "_" . //user
			                        $id . "_" . //transaction id
								    "0" . ".txt";
		   
	         if ($fp = fopen ($tfile , "w")) {
               fwrite ($fp, $data);
               fclose ($fp);						  
             }		   
		   
		     $ret = $id;		   
		   }
		   
	   }
	   //print $ret;

	   return ($ret);
	}
	
	function viewTransactions() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
	   
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
	   //echo $this->admint,'>>>>';
	   /*if ($admint==1) $sSQL = "select * from transactions"; //all transactions
	              else $sSQL = "select * from transactions where cid=" . $db->qstr($this->userid); //user only transactions
				
	   $res = $db->Execute($sSQL);
	   
	   if ($db->Affected_Rows()) {
         $i=1; 
		 $meter = $this->pagenum;
         while(!$res->EOF) {
		 
		    switch ($res->fields[6]) {
			  case 0 : $status = $this->status0; break;
			  case 1 : $status = $this->status1; break;	
			  case 2 : $status = $this->status2; break;				  		  
			}
 
            if ($admint==1) {
			   //print checkbox 
			   $checkbox = "<input type=\"checkbox\" name=\"" . $res->fields[1] . 
			                                     "\" value=\"" . $res->fields[1] . "\">"; 
			}
			elseif ($admint==2) {
			   //print checkbox only if status!=1
			   $checkbox = "<input type=\"checkbox\" name=\"" . $res->fields[1] . 
			                                    "\" value=\"" . $res->fields[1] . "\">"; 
			}												  
			else $checkbox = "";
					 
            $transtbl[] = $checkbox . $i . ";" . 
                         $res->fields[1] . ";" .
			             $res->fields[0] . ";" .
			             $status . ";" .						 
			             $res->fields[3] . "-" . $res->fields[4];

            //print_r($table->fields);
	        $res->MoveNext();
		    $i+=1; $meter+=1;
	     }	   
	   
         //browse
		 //print_r($transtbl); 
         $browser = new browse($transtbl,
		                       localize('_TRANSLIST',getlocal()),
							   $this->getpage($transtbl,$this->searchtext));
	     $out .= $browser->render("transview",$this->pagenum,$this,1,0,1,0);
	     unset ($browser);*/
		
	   if ($this->storetype=='DB') {  //db	 		
		 
	     if ($this->admint==1) {
		   $sSQL = "select recid,tid,recid,tstatus,tdate,ttime from transactions"; //all transactions
		   if ($apo) $sSQL .= " where tdate>='" . trim(reverse_datetime($apo)) . "'";
		   if ($eos) $sSQL .= " and tdate<='" . trim(reverse_datetime($eos)) . "'";
		 }  
	     else {
		   $sSQL = "select recid,tid,recid,tstatus,tdate,ttime from transactions where cid=" . $db->qstr($this->userid); //user only transactions
		   if ($apo) $sSQL .= " and tdate>='" . trim(reverse_datetime($apo)) . "'";
		   if ($eos) $sSQL .= " and tdate<='" . trim(reverse_datetime($eos)) . "'";		   
		 }  
				
		 
         $browser = new browseSQL(localize('_TRANSLIST',getlocal()));
	     $out .= $browser->render($db,$sSQL,"transactions","transview",$this->pagenum,$this,1,0,1,0); //do not search internal because of form conflict
	     unset ($browser);	
		 	 
		 $buttons = true;
	   }
	   else { //xml and txt
        
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.'.$this->storetype) && 
												(strstr($fileread,'.'.$this->storetype))) {
																					
			   //echo $fileread;
               $st = stat($this->path.$fileread);
			   $date = date("d-m-Y",$st[9]); //echo $date,":";
			   $time = date("H:i:s",$st[9]); //echo $time,">";
			   $datetime = $date." ".$time;
			   
			   //CHECK DATES ////////////
			   if ($apo) {
				 $checkdate=true;//enable check date
				 if (date_diff($apo,$datetime,"s")>=0) $dateOK=1;
				 else $dateOK=0;
				 
                 //echo $apo,"::::",$datetime,"<br>";				 
			   }
			   else 
			     $checkdate=false;	//disable check date
				  
 		       if ($eos) {
                 $checkdate=true; //enable check date
				 if (($dateOK) && (date_diff($datetime,$eos,"s")>=0)) $dateOK=1;				 
				 else $dateOK=0;				 
				 
				 //echo $eos,"::::",$datetime;
			   }	 
			   //not need to disable chack date becaouse of prev check on if
			   /////////////////////////
			   
			   $tdata = explode("_",str_replace(".".$this->storetype,"",$fileread));
			   $record = $i++.";".$tdata[1].";".$tdata[1].";".$tdata[2].";".$date.";".$time;
			   if ($this->admint==1) {
			   
			     if ($checkdate) {
				   if ($dateOK) $ret[]= $record; //all transactions
				 }
				 else
                   $ret[]= $record; //all transactions
			   }	 
			   else { 
			     if (($tdata[0]==$this->username) && ($tdata[2]!=4)) { 
				 
				   if ($checkdate) {
				     if ($dateOK)  $ret[] = $record; //owned transaction and not deleted
				   }
				   else
				     $ret[] = $record; //owned transaction and not deleted
				 }  
			   }	 
			 }
           }

           $mydir->close();
		   
           //browse
		   //print_r($ret); 
		   if (count($ret)>0) {
             $browser = new browse($ret,
		                           localize('_TRANSLIST',getlocal()),
				   			       $this->getpage($ret,$this->searchtext));
	         $out .= $browser->render("transview",$this->pagenum,$this,1,0,1,0);
	         unset ($browser);		
			 
   		     $buttons = true;			 	   
		   }
		   else {
             //empty message
	         //$w = new window(localize('_TRANSLIST',getlocal()),localize('_TRANSEMPTY',getlocal()));
	         //$out .= $w->render("center::100%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
             $w = new msgBox(localize('_TRANSEMPTY',getlocal()),"OKOnly",localize('_TRANSLIST',getlocal())); 
             $links = array(seturl(''));
             $w->makeLinks($links);			 
             $out .= $w->render();				 
	         unset($w);		   
		   }
         }		      
	   }		 
		 
	   if (($buttons) && ($this->admint)) {
		     if ($this->admint==1) {
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status0\">&nbsp;";		 
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status1\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";			   
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }
			 elseif ($this->admint==2) {
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }
			 
             $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Transview\">";
             $out .= "</FORM>";			 		   
			 	
	   }  
	   		 
	   if ($buttons) {
	   
	      $out .= $this->searchform();	    
		 
		  $dater = new datepicker();	
		  $out .= $dater->renderspace(seturl("t=transview&a=$a"),"transview");		 
		  unset($dater);
	   }	 
						
	   
	   return ($out);
	}
	
	function getpage($array,$id){
	
	   if (count($array)>0) {
         //while(list ($num, $data) = each ($array)) {
         foreach ($array as $num => $data) {
		    $msplit = explode(";",$data);
			if ($msplit[1]==$id) return floor(($num+1) / $this->pagenum)+1;
		 }	  
		 
		 return 1;
	   }	 
	}
	
	function getTransaction($trid) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db	
	   	   
	     $sSQL = "select * from transactions where recid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print_r ($res->fields[5]);
	     if ($res) { 
	       $out = $res->fields[5]; 
		   return ($out);
	     }
	   }
	   elseif ($this->storetype=='xml') { //xml
		 
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.xml') && 
												(strstr($fileread,'.xml'))) {	   
	     
		       //$transxmlfile = $this->path . $this->username . "_" . $trid . "_" . "0" . ".xml";
	           if (stristr($fileread,$trid)) {
			     //echo $fileread;
                 $dumper = new PHP_XML_Dumper('transaction');
                 $out = $dumper->xml2php($this->path.$fileread); 	 
	             unset($dumper);
				 $mydir->close();					 
			     return (serialize($out));	//deserialized from caller (compatibility with db)	   
		       }
		     }	 
		   }  
	     }
         $mydir->close();		 
	   }
	   else { //default txt
	   
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.xml') && 
												(strstr($fileread,'.txt'))) {	   
	     
		       //$transxmlfile = $this->path . $this->username . "_" . $trid . "_" . "0" . ".xml";
	           if (stristr($fileread,$trid)) {
			     //echo $fileread;
                 if ($fp = fopen ($this->path.$fileread , "r")) {
                   $out = fread ($fp, filesize($this->path.$fileread));
                   fclose ($fp);		
				   $mydir->close();				 	 
			       return ($out);		   
				 }
				 else
				   $mydir->close();				 	 
		       }
		     }	 
		   }  
	     }
         $mydir->close();		   
	   }	 
	}
	
	function getTransactionOwner($trid) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db		   
	   
	     $sSQL = "select * from transactions where recid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print $res->fields[2];
	     if ($res) { 
	       $out = $res->fields[2]; 
		   return ($out);
	     }
	   }	 
	   else {   //xml and txt
	   
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.'.$this->storetype) && 
												(strstr($fileread,'.'.$this->storetype))) {	   
	     
		       //$transxmlfile = $this->path . $this->username . "_" . $trid . "_" . "0" . ".xml";
	           if (stristr($fileread,$trid)) {
		         $transxml = explode("_",$fileread);
			     $out = $transxml[0];
                 $mydir->close();			   
			     return ($out);
		       }
		     }	 
		   }  
	     }
         $mydir->close();			 
	   }
	}  	
	
	function getTransactionRecord($trid) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db	   
	     $sSQL = "select * from transactions where recid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print_r ($res->fields[5]);
	     return ($res->fields); 
	   }
	   elseif ($this->storetype=='xml') { //xml
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.xml') && 
												(strstr($fileread,'.xml'))) {	   
	     
		       //$transxmlfile = $this->path . $this->username . "_" . $trid . "_" . "0" . ".xml";
	           if (stristr($fileread,$trid)) {
			   
                 $dumper = new PHP_XML_Dumper('transaction');
                 $out = $dumper->xml2php($fileread); 	 
	             unset($dumper);
                 $mydir->close();				 
			     return (serialize($out));	//deserialized from caller (compatibility with db)		   
		       }
		     }	 
		   }  
	     }
         $mydir->close();		   
	   }	
	   else { //default txt
         if (is_dir($this->path)) {
           $i=1;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;

           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.xml') && 
												(strstr($fileread,'.txt'))) {	   
	     
		       //$transxmlfile = $this->path . $this->username . "_" . $trid . "_" . "0" . ".xml";
	           if (stristr($fileread,$trid)) {
			   
                 if ($fp = fopen ($fileread , "r")) {
                   $out = fread ($fp, filesize($fileread));
                   fclose ($fp);	
                   $mydir->close();				 
			       return (serialize($out));			   
				 }
				 else
				   $mydir->close();	    
		       }
		     }	 
		   }  
	     }
         $mydir->close();		   
	   } 
	}		
	
	function setTransactionStatus($trid,$state) {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db		   
	     $sSQL = "update transactions set tstatus=" . $state .
	             " where recid=" . $trid;
         $result = $db->Execute($sSQL);
		
	     //print $sSQL;
	     //print $db->Affected_Rows() . ">>>>";
         if ($db->Affected_Rows()) return true;
	                          else return false;   	   
	   }
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
	   }						  
	}
	
	//?????
	function loadnextTransaction() {
       $db = GetGlobal('db');
	   
	   if ($this->storetype=='DB') {  //db		   
	   
	     $sSQL = "select * from transactions where tstatus=0 LIMIT 1"; 
	     $res = $db->Execute($sSQL);
     
	     //print $res->fields[0].">>>>";
	   
	     if ($res->fields) return ($res->fields[0]);	
	                  else return 0; //=end of transactions
	   }
	   else { //xml and txt
	     //.....
	   }				  
	}	
	
    function searchform()  {

      $filename = seturl("t=transview&a=&g=&p=");      

      $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
      $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	  $toprint .= localize('_TRANSEARCH',getlocal()) . ":";
	  $toprint .= "</STRONG> <INPUT name=transnum size=15></FONT>";
      $toprint .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>";

	  $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"searchtrans\">";
      $toprint .= "</FONT></FORM>";
	   
	  $data2[] = $toprint; 
  	  $attr2[] = "left";

	  $swin = new window('',$data2,$attr2);
	  $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  unset ($swin);

      return ($out);
    }	
	
     function reset_db() {
       $db = GetGlobal('db'); 
		
	   if ($this->storetype=='DB') {  //db	

         //delete table if exist
		 $sSQL = "drop table transactions";
         $db->Execute($sSQL);
		/* $sSQL = "create table transactions " .
                    "(" .
	                "recid integer auto_increment primary key," .
	                "tid varchar(64)," .
	                "cid integer," .
	                "tdate varchar(10)," .
	                "ttime varchar(5)," .
	                "tdata text," .
	                "tstatus integer," .										
	                "UNIQUE (tid)" .
                    ")";*/
					
          $sSQL = 'CREATE TABLE `transactions` ('
        . ' `recid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, '
        . ' `tid` VARCHAR(64) NOT NULL, '
        . ' `cid` INT NOT NULL, '
        . ' `tdate` DATE NOT NULL, '
        . ' `ttime` TIME NOT NULL, '
        . ' `tdata` TEXT NOT NULL, '
        . ' `tstatus` SMALLINT NOT NULL, '
        . ' `type1` VARCHAR(64) NOT NULL, '
        . ' `type2` VARCHAR(64) NOT NULL,'
        . ' INDEX (`cid`),'
        . ' UNIQUE (`tid`)'
        . ' )'
        . ' ENGINE = myisam'
        . ' CHARACTER SET greek COLLATE greek_general_ci'
        . ' COMMENT = \'transaction table\';';	
						
         $db->Execute($sSQL);   
			
		 setInfo(" Reset successfully!");
	   }
	   else { //xml and txt

	     //delete transaction files
         if (is_dir($this->path)) {
           $i=0;
           $mydir = dir($this->path); //echo 'PATH:',$fpath;
            
           while ($fileread = $mydir->read()) {//echo $fileread,"<br>";
             if ((!is_dir($fpath.$fileread)) && ($fileread!='.') && 
			                                    ($fileread!='..') && 
												($fileread!='id.'.$this->storetype) && 
												(strstr($fileread,'.'.$this->storetype))) {	
												
          												   
	            unlink($this->path.$fileread); //delete file
                $i+=1;
		     }	 
		   }  
	     }
         $mydir->close();		     
		 
		 //reset id counter...
         $dumper = new PHP_XML_Dumper('ID');
		 $res[0] = 1;
         $dumper->php2xml($res, $this->path . "id.".$this->storetype);		  
	     unset($dumper);		 
		 
		 setInfo(" Reset $i transactions successfully!");
	   }	 
    }
	
	function details($id,$storebuffer='sencart') {
	   
	   if ($storebuffer)
	     $ret = GetGlobal('controller')->calldpc_method($storebuffer.'.previewcart use '.$id.'+transview');
	   return ($ret);
	}
	
	
	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->viewtrans($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);

	   return ($out);
	}		
	
    function viewtrans($id,$fname,$lname,$status,$ddate,$dtime) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $link = seturl("t=loadcart&a=$lname&g=&p=$p" , $fname);
	   
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
	   
							  
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $this->details($lname);
	     $cartwin = new window2($id,$mydata,null,1,null,'HIDE');
	     $data[] = $cartwin->render("center::100%::0::group_dir_body::left::0::0::");
	     unset ($cartwin);		   
		 $attr[] = "left;10%";
	   }	
	   else {
	     $data[] = $id;
	     $attr[] = "left;10%";	   
	   }
	   	   
	   $data[] = $link;   
	   $attr[] = "left;30%";
	   
	   switch ($status) {
			  case 0 : $data[] = $this->status0; break;
			  case 1 : $data[] = $this->status1; break;	
			  case 2 : $data[] = $this->status2; break;				  		  
			  case 3 : $data[] = $this->status3; break;
			  case 4 : $data[] = $this->status4; break;
	   }	     
	   $attr[] = "left;30%";		   
	   
	   $data[] = $ddate;   
	   $attr[] = "left;15%";
	   
	   $data[] = $dtime;   
	   $attr[] = "left;15%";	      

	   $myarticle = new window('',$data,$attr);
	   
       if (($a) && (stristr($fname,$a))) $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
                                    else $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
	 /*  if ($this->details) {
	     $mydata = $this->details($lname);
	     $cartwin = new window2($id,$mydata,null,1,null,'HIDE');
	     $out .= $cartwin->render("center::100%::0::group_dir_body::left::0::0::");
	     unset ($cartwin);		   
	   }	 */

	   return ($out);
	}	
	
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $sort = GetReq('sort');  
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=0" ,  "A/A" );
	   $attr[] = "left;10%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=1" , localize('_TRANSACTION',getlocal()) );
	   $attr[] = "left;30%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=2" , localize('_TRANSTAT',getlocal()) );
	   $attr[] = "left;30%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=3" , localize('_DATE',getlocal()) );
	   $attr[] = "left;15%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=4" , localize('_TIME',getlocal()) );
	   $attr[] = "left;15%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	

};
}
//}
//else die("DATABASE DPC REQUIRED!");
?>