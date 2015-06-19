<?php

$__DPCSEC['WISHLIST_DPC']='1;1;1;2;2;2;2;2;9';
$__DPCSEC['WSADMIN_']='2;1;1;1;1;1;2;2;9';
$__DPCSEC['WSCANCEL_']='2;1;2;2;2;2;2;2;9';

if ((!defined("WISHLIST_DPC")) && (seclevel('WISHLIST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("WISHLIST_DPC",true);

$__DPC['WISHLIST_DPC'] = 'wishlist';

$__EVENTS['WISHLIST_DPC'][0]='wishview';
$__EVENTS['WISHLIST_DPC'][1]='searchws';
$__EVENTS['WISHLIST_DPC'][2]=localize('_WSEND',getlocal());
$__EVENTS['WISHLIST_DPC'][3]=localize('_WSCANC',getlocal());
$__EVENTS['WISHLIST_DPC'][4]=localize('_WSPROC',getlocal());
$__EVENTS['WISHLIST_DPC'][5]=localize('_WSDELL',getlocal());

$__ACTIONS['WISHLIST_DPC'][0]='wishview';
$__ACTIONS['WISHLIST_DPC'][1]='searchws';
$__ACTIONS['WISHLIST_DPC'][2]=localize('_WSEND',getlocal());
$__ACTIONS['WISHLIST_DPC'][3]=localize('_WSCANC',getlocal());
$__ACTIONS['WISHLIST_DPC'][4]=localize('_WSPROC',getlocal());
$__ACTIONS['WISHLIST_DPC'][5]=localize('_WSDELL',getlocal());

$__DPCATTR['WISHLIST_DPC']['wishview'] = 'wsview,1,0,1'; 
$__DPCATTR['WISHLIST_DPC']['searchws'] = 'searchws,1,0,1';
$__DPCATTR['WISHLIST_DPC'][localize('_WSEND',getlocal())] = '_WSEND,1,0,1,0,0,0,0,0,0';
$__DPCATTR['WISHLIST_DPC'][localize('_WSCANC',getlocal())] = '_WSCANC,1,0,1,0,0,0,0,0,0';
$__DPCATTR['WISHLIST_DPC'][localize('_WSPROC',getlocal())] = '_WSPROC,1,0,1,0,0,0,0,0,0';
$__DPCATTR['WISHLIST_DPC'][localize('_WSDELL',getlocal())] = '_WSDELL,1,0,1,0,0,0,0,0,0';

$__LOCALE['WISHLIST_DPC'][0]='_WSEND;Procceded;Εκτελεσμένη';
$__LOCALE['WISHLIST_DPC'][1]='_WSCANC;Canceled;Ακυρη';
$__LOCALE['WISHLIST_DPC'][2]='_WSPROC;In Procces;Εκκρεμής';
$__LOCALE['WISHLIST_DPC'][3]='_WSSTAT;Status;Κατάσταση';
$__LOCALE['WISHLIST_DPC'][4]='_WSNUM;List No;Αριθμός Πρόχειρου';
$__LOCALE['WISHLIST_DPC'][5]='_WSDATA;Wish List data;Στοιχεία πρόχειρου';
$__LOCALE['WISHLIST_DPC'][6]='_WSINFO;All data are important for a successfull transaction ! !;Όλα τα στοιχεία πρέπει να είναι σωστά συμπληρωμένα ! !';
$__LOCALE['WISHLIST_DPC'][7]='_WSPRINT;Print Wish List;Εκτύπωση πρόχειρου';
$__LOCALE['WISHLIST_DPC'][8]='_WSERROR;Transaction not successfull. Please try later or inform us at ;Η συναλλαγή δεν εκτελέστηκε. Παρακαλώ δοκιμάστε αργότερα ή ενημερώστε μας στο';
$__LOCALE['WISHLIST_DPC'][9]='_WSOK;Thank you! Your wishlist submited successfully with No :;Ευχαριστούμε ! Η ενημέρωση εκτελέστηκε επιτυχώς με αριθμό :';
$__LOCALE['WISHLIST_DPC'][10]='_WSSEARCH;Search WishList;Αναζήτηση πρόχειρου';
$__LOCALE['WISHLIST_DPC'][11]='_WSLIST;Wish List;Λίστα Συναλλαγών';
$__LOCALE['WISHLIST_DPC'][12]='_WSACTION;Transaction;Κίνηση';
$__LOCALE['WISHLIST_DPC'][13]='WISHLIST_CNF;Wish List;Λίστα Συναλλαγών';
$__LOCALE['WISHLIST_DPC'][14]='_WSMERR;Not submited;Μη απεσταλμενη';
$__LOCALE['WISHLIST_DPC'][15]='_WSDELL;Deleted;Διεγραμμενη';
$__LOCALE['WISHLIST_DPC'][16]='_WSEMPTY;No wishlist;Δεν υπάρχουν κινήσεις';


class wishlist {

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

	function __construct() {
	   $UserName = GetGlobal('UserName');	
	   $UserSecID = GetGlobal('UserSecID');
	   $UserID = GetGlobal('UserID');		

       $this->path = paramload('SHELL','prpath');	   

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

	   $cc = remote_paramload('WISHLIST','counter', $this->path); 
	   $this->tcounter = $cc ? $cc : 0; 
	   $this->details = remote_paramload('WISHLIST','details',$this->path);
	   
	   $this->admint = 0;

       $this->initial_word = remote_paramload('WISHLIST','wsid',$this->path);
	   	   
	}
	
    function action()  { 

		 //$out = $this->title();
		 //$out .= $this->form();
         $out = setNavigator(localize('_WISHLIST',getlocal()));
         $out .= $this->viewWishList();

	   return ($out);
	}
	
	function event($evn) {
	   $a = GetReq('a');
	   
	   switch ($evn) {
	     case "searchws"  : SetReq('a',$this->searchtext); break;
		 case $this->status0 : $this->modifyWishListStatus(0); break;		 
		 case $this->status1 : $this->modifyWishListStatus(1); break;		 
		 case $this->status2 : $this->modifyWishListStatus(2); break;
		 //case $this->status3 : $this->modifyWishListStatus(3); NO NEED ..PRODUCED by MAIL ERROR
		 case $this->status4 : $this->modifyWishListStatus(4); break;		 		 
		 //case "reset_tr"     : $this->reset_db(); break;			 
	   }
	}

	function generate_id() {
       $db = GetGlobal('db');

	   //if ($this->storetype=='DB') {  //db
	   
	     $sSQL = "select count(*) from wishlist";
	     $res = $db->Execute($sSQL,null,1);
		  
		 if ($db->model=='ADODB') 
	       $out = $res->fields[0]+1;//RecordCount()+1;
		 else //sqlite
		   //$out = $res[0]+1;//$data[0]+1;
         $out = $res->fields[0]+1;//$data[0]+1;

		 //echo $out,'>>>';
       //}

	   //print $out;	
	   return ($out);
	}
	
	function getWishListStatus($trid) {
       $db = GetGlobal('db');
	   
	   $sSQL = "select tstatus from wishlist" . 
	           " where tid='" . $this->initial_word. $trid ."'";
       $result = $db->Execute($sSQL);	
	   
	   $ret = $result->field['tstatus'];
	   return ($ret);
	}	
	
	//bulk modifications
	function modifyWishListStatus($state) {
       $db = GetGlobal('db');
	   
	   //if ($this->storetype=='DB') {  //db	   
	   
	     $i = 0;
	     $sSQL = "select tid from wishlist";
	     $res = $db->Execute($sSQL); 	   
	   
	     while(!$res->EOF) {
	       $tran = GetParam($res->fields[0]);
	       if ($tran) {
		     //$tr[] = $tran;
		   
		     $sSQL2 = "update wishlist set tstatus=" . $db->qstr($state) .
		              " where tid=" . $db->qstr($tran);
     	     $result = $db->Execute($sSQL2);
		     if ($result) $i+=1;	
		   }
	       $res->MoveNext();		 
	     }
	     setInfo($i." rows affected.");
	     //print_r($tr);
	   //}
	}

	function saveWishList($data=null,$user=null) {
       $db = GetGlobal('db');

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
		  
	      //if ($this->storetype=='DB') { 
             $sSQL = "insert into wishlist (tid,cid,tdate,ttime,tdata,tstatus) values " .
                 "(" .
		         $db->qstr($myid) . "," .
		         $db->qstr($myuser) . "," .
		         $db->qstr($mydate) . "," .
		         $db->qstr($mytime) . "," .
		         $db->qstr($mydata) . "," . 
		         "0" . ")";				 				 				 

	         $res = $db->Execute($sSQL,1);

		     //echo $sSQL;
		     //print $db->Affected_Rows();
			 //echo '>>>>',$res;

             if ($db->Affected_Rows()) $ret = $id;
	                              else $ret = 0;

	       //}
   
	   }
	   //print $ret;

	   return ($ret);
	}
	
	function getWSLists() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   $name = $UserName?decode($UserName):null;		   
	   
	   if (!$name) return;
	   	
	   //if ($this->storetype=='DB') {  //db	
	   	   //,payway,roadway,cost,costpt
	     $sSQL = "select tid,tdate,ttime,tstatus from wishlist where cid=" . $db->qstr($name) . 
		         "order by tid DESC";				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res->fields[5]);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;		
			
            $transtbl[] = $rec[0] . ";" .$rec[0] . ";" .$rec[1] . ";" .$rec[2] . ";" .$rec[3];
			             //$checkbox . $i . ";" . 
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
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("wishview",$ppager,$this,1,0,0,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 
	   //}	
	   
	   return ($out);
	} 		
	
	function viewWishList() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');	   
	   
	   if (!$UserName) {
	     if (defined('SHLOGIN_DPC')) {
		   $out = GetGlobal('controller')->calldpc_method("shlogin.quickform use +wsview+wishlist>viewWishList");
		 }
	     else
	       $out = ("You must be logged in to view this page.");
		   
		 return ($out);  
	   }	 
	   
	   $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos;   

       $myaction = seturl("t=wsview");	   
	   
       if (seclevel('WSADMIN_',$this->userLevelID)) {
	     $this->admint=1;
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Wishview\">";		 
	   }
	   elseif (seclevel('WSCANCEL_',$this->userLevelID)) { 
	     $this->admint=2;	   
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Wishview\">";		   
	   }
	   else {
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Wishview\">";		   
	   }

	 
	   $out .= $this->getWSLists();	 
		 
	   if ($this->admint) {
			 
             $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Wishview\">";
             $out .= "</FORM>";			 		   
			 	
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
	
	function getWishList($trid) {
       $db = GetGlobal('db');
	   
	   //if ($this->storetype=='DB') {  //db	
	   	   
	     $sSQL = "select * from wishlist where tid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print_r ($res->fields[5]);
	     if ($res) { 
	       $out = $res->fields[5]; 
		   return ($out);
	     }
	   //}
	} 
	
	function getWishListOwner($trid) {
       $db = GetGlobal('db');
	   
	   //if ($this->storetype=='DB') {  //db		   
	   
	     $sSQL = "select * from wishlist where recid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print $res->fields[2];
	     if ($res) { 
	       $out = $res->fields[2]; 
		   return ($out);
	     }
	   //}	 
	}  	
	
	function getWishListRecord($trid) {
       $db = GetGlobal('db');
	   
	   //if ($this->storetype=='DB') {  //db	   
	     $sSQL = "select * from wishlist where recid=" . $db->qstr($trid);
	     $res = $db->Execute($sSQL);
	     //print_r ($res->fields[5]);
	     return ($res->fields); 
	   //}
	}		
	
	function setWishListStatus($trid,$state) {
       $db = GetGlobal('db');
	   
	   	$sSQL = "update wishlist set tstatus=" . $state .
	             " where tid='" . $this->initial_word. $trid ."'";
         $result = $db->Execute($sSQL);
		
	     //print $sSQL.'>';
	     //print $db->Affected_Rows() . ">>>>";
         if ($db->Affected_Rows()) return true;
	                          else return false; 		  
	}
	
	//?????
	function loadnextWishList() {
       $db = GetGlobal('db');
	   	   
	   
	     $sSQL = "select * from wishlist where tstatus=0 LIMIT 1"; 
	     $res = $db->Execute($sSQL);
     
	     //print $res->fields[0].">>>>";
	   
	     if ($res->fields) return ($res->fields[0]);	
	                  else return 0; //=end of transactions

				  
	}	
	
    function searchform()  {

      $filename = seturl("t=wishview&a=&g=&p=");      

      $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
      $toprint .= "<P><FONT face=\"Arial, Helvetica, sans-serif\" size=1><STRONG>";
	  $toprint .= localize('_TRANSEARCH',getlocal()) . ":";
	  $toprint .= "</STRONG> <INPUT name=transnum size=15></FONT>";
      $toprint .= "<FONT face=\"Arial, Helvetica, sans-serif\" size=1>";

	  $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
      $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"searchws\">";
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
		

         //delete table if exist
		 $sSQL = "drop table wishlist";
         $db->Execute($sSQL);

					
          $sSQL = 'CREATE TABLE `wishlist` ('
        . ' `recid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, '
        . ' `tid` VARCHAR(64) NOT NULL, '
        . ' `cid` INT NOT NULL, '
        . ' `tdate` DATE NOT NULL, '
        . ' `ttime` TIME NOT NULL, '
        . ' `tdata` TEXT NOT NULL, '
        . ' `tstatus` SMALLINT NOT NULL, '
        . ' INDEX (`cid`),'
        . ' UNIQUE (`tid`)'
        . ' )'
        . ' ENGINE = myisam'
        . ' CHARACTER SET greek COLLATE greek_general_ci'
        . ' COMMENT = \'wishlist table\';';	
						
         $db->Execute($sSQL);   
			
		 setInfo(" Reset successfully!");
 
    }
	
	function _details($id,$storebuffer='sencart') {
	
	   if (defined('SHCART_DPC')) 
	     $ret = GetGlobal('controller')->calldpc_method('shcart.previewcart use '.$id.'+wishview');
		 
	   return ($ret);	   
	}
	
	
	
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->view_ws($data[0],$data[1],$data[2],$data[3],$data[4]);//,$data[5]);

	   return ($out);
	}		
	
    function view_ws($id,$did,$ddate,$dtime,$status) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   
       if ($this->admint>0) {//==1) {
			   //print checkbox 
			   $data[] = "<input type=\"checkbox\" name=\"" . $id . 
			                                  "\" value=\"" . $id . "\">"; 
	           $attr[] = "left;1%";											  
	   }										  	   
	   					  
	   $link = seturl("t=loadcart&tid=$did" , $did);
	   $data[] = $link;   
	   $attr[] = "left;19%";
	   
	   $data[] = $ddate;   
	   $attr[] = "left;40%";   
	   
	   $data[] = $dtime;   
	   $attr[] = "left;20%";	      
	   
	   $data[] = $status;   
	   $attr[] = "right;20%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line . '<br/>' . $this->_details($id);
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

	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t');
	   $sort = GetReq('sort');  
	
       //$data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=0" ,  "Id" );
	   //$attr[] = "left;5%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=1" , localize('_ID',getlocal()) );
	   $attr[] = "center;20%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=2" , localize('_DATE',getlocal()) );
	   $attr[] = "center;40%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=3" , localize('_DATE',getlocal()) );
	   $attr[] = "center;20%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=4" , localize('_WSSTAT',getlocal()) );
	   $attr[] = "center;20%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}	

};
}
?>