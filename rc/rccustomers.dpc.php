<?php

$__DPCSEC['RCCUSTOMERS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCCUSTOMERS_DPC")) && (seclevel('RCCUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCCUSTOMERS_DPC",true);

$__DPC['RCCUSTOMERS_DPC'] = 'rccustomers';


$__EVENTS['RCCUSTOMERS_DPC'][0]='cpcustomers';
$__EVENTS['RCCUSTOMERS_DPC'][1]='delcustomer';
$__EVENTS['RCCUSTOMERS_DPC'][2]='regcustomer';
 
$__ACTIONS['RCCUSTOMERS_DPC'][0]='cpcustomers';
$__ACTIONS['RCCUSTOMERS_DPC'][1]='delcustomer';
$__ACTIONS['RCCUSTOMERS_DPC'][2]='regcustomer';

$__DPCATTR['RCCUSTOMERS_DPC']['cpcustomers'] = 'cpcustomers,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCCUSTOMERS_DPC'][0]='RCCUSTOMERS_DPC;Customers;Customers';

class rccustomers  {

    var $title;
	var $carr;
	var $msg;
	var $path;
	var $post;
	var $remotedb;
	var $remoteuser;
	
	function rccustomers() {
	
	  $this->title = localize('RCCUSTOMERS_DPC',getlocal());	
	  $this->carr = null;
	  $this->msg = null;		
	    
	  if ($this->remoteuser=GetSessionParam('REMOTELOGIN')) {//remote parent cp
		  //must re connect to remote db and set global db to this
		  $this->path = paramload('SHELL','prpath')."instances/". $this->remoteuser ."/";	
		  //echo "<br>>",$this->path;
		  $this->remotedb = new sqlite($this->path . "mysqlitedb");			  

		  SetGlobal('db',&$this->remotedb->dbp);	
	  }	
	  elseif ($this->remoteapp = GetSessionParam('REMOTEAPPSITE')) {//remote app cp
		  //must re connect to remote db and set global db to this
		  $this->path = paramload('SHELL','prpath');	
		  //echo "<br>>>",$this->path;
		  $this->remotedb = new sqlite($this->path . "mysqlitedb");			  

		  SetGlobal('db',&$this->remotedb->dbp);		    
	  }
	  else {
	     $this->path = paramload('SHELL','prpath');  
	     //echo "<br>>>>",$this->path;
	  }	 
	  //echo '>',$this->path;	  
	}
	
    function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	   switch ($event) {
	     case 'regcustomer' : if ($this->validate_form_data()) { 
		                        $this->register_customer();
								$this->post = true;
							  }
							  else {
							    $this->post = false;
								$this->msg = "Invalid data submited!";
								SetInfo($error);
							  }	
								
		                      $this->carr = $this->select_customers('all',null,GetReq('alpha'));
		                      break;
	     case 'delcustomer' : $this->delete_customer(GetReq('id'),'id');
		                      $this->carr = $this->select_customers('all',null,GetReq('alpha'));
							  break;
	     case 'cpcustomers' :
		 default            : $this->carr = $this->select_customers('all',null,GetReq('alpha'));//dummy param
	   }
			
    }
  
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	 
	  switch ($action) {
	     case 'delcustomer' : $out .= $this->show_customers();
		                      break;
		 case 'regcustomer' : $out .= $this->form();	
		                      $out .= $this->show_customers();
							  break; 
	     case 'cpcustomers' :
		 default            : $out .= $this->show_customers();
	 }	 
	 
	 return ($out);
    }
	
	function insert($recasarray,$type=0)  {
        $db = GetGlobal('db'); 	
		
		//$this->reset_db();
		
		$data = unserialize($recasarray);
		//print_r($data);
		switch ($type) {
		  case 0 : $itemtype = "fullversion"; break;
		  case 1 : $itemtype = "shareware"; break;
		  default: $itemtype = "shareware";
		}
		
        $sSQL2 = "insert into rccustomers (
		          insdate, fname, lname, fullname, address, zip, city, state, country, email, 
				  itemcode, itemname, payment, itemtype, downtimes, invcode, expire";
		$sSQL2 .= ") values (" . $db->qstr(date('Y-m-d'));	
        foreach ($data as $key => $value) //get data from paypal
		  $sSQL2 .= ", \"" . $value . "\"";
		  
		//setup customer attributes  
		$downtimes = 0;
		$invcode = '1qqwser223';
		//expire = 1 year after = null //days+hours+min+sec
		$nextYear = time() + (1 * 24 * 60 * 60);
		$expire = date('Y-m-d',$nextYear);
		
		$sSQL2 .= "," . 
		          $db->qstr($itemtype) . "," . 
				  $db->qstr($downtimes).",".
				  $db->qstr($invcode).",".
				  $db->qstr($expire);
		$sSQL2 .= ")";
																		
        $db->Execute($sSQL2,1);  	
		//echo $sSQL2;
	}
	
	function register_customer($typeofproduct=0) {
	
        $r[0] = GetParam("company");//fname
		$r[1] = GetParam("cperson");//lname 
		$r[2] = GetParam("activities");//fullname
		$r[3] = GetParam("address");//address
		$r[4] = GetParam("zip");//zip
		$r[5] = GetParam("town");//city
		$r[6] = "";//state
		$r[7] = get_selected_option_fromfile(GetParam("country"),'country');//country
		$r[8] = GetParam("email");//email
		$r[9] = "";//itemcode
		$r[10] = GetReq("g");//itemname
		$r[11] = "0.0";//payment
		
		$data = serialize($r); //pack
		$ret = $this->insert($data,$typeofproduct);
		
		
		//save to session
		SetSessionParam("CustomerDetails",$data);		
	}
	
	function validate_form_data() {
	
       if ((GetParam("company")) && (checkmail(GetParam("email")))) 
	     return true;
	
	   return false;	  	
	}
	
	//bind application to customer  
	function bind_application_to_customer($appname,$customerid,$value) {
        $db = GetGlobal('db');	//local db    
		
	    $sSQL2 = "update rccustomers set downtimes=" . $db->qstr($appname);
		$sSQL2 .= " where $customerid='" . $value . "'";
        $db->Execute($sSQL2,1);			
		//echo $sSQL2;  
	} 
	
    function get_array_from_customer($param) {
        $db = GetGlobal('db');	//local db    
		
		//get customer without binding
	    $sSQL2 = "select fname,lname,fullname,address,zip,city,state,country,email,itemcode,itemname,payment from rccustomers where downtimes='0'";
		$sSQL2.= " and fname=" . $db->qstr($param);
        $result = $db->Execute($sSQL2,2);			
		//echo $sSQL2;  
		$ret = $db->fetch_array($result);
		//echo $ret;
		
		if (!empty($ret)) {
		  $r[0] = $ret['fname'];
		  $r[1] = $ret['lname'];
		  $r[2] = $ret['fullname'];
		  $r[3] = $ret['address'];
		  $r[4] = $ret['zip'];
		  $r[5] = $ret['city'];
		  $r[6] = $ret['state'];
		  $r[7] = $ret['country'];
		  $r[8] = $ret['email'];
		  $r[9] = $ret['itemcode'];
		  $r[10] = $ret['itemname'];		  		  		  		  		  		  		  		  		  		  
		  $r[11] = $ret['payment'];		  
		  //print_r($r);
		  $data = serialize($r); 
		  return ($data);
		}
		else //invalid customer .. already active
		  return null;	
    }			
	
	function select($product=null,$type=null,$expire=null) {
        $db = GetGlobal('db');	
	
	    $sSQL2 = "select lname,email from rccustomers where ";
		if ($product) $sSQL2 .= "itemname=" . $db->qstr($product);
		if ($type) {
		  if ($product) $sSQL2 .= " and ";
		  $sSQL2 .= "itemtype=" . $db->qstr($type);		
		}
		if ($expire) {
		  if (($product) || ($type)) $sSQL2 .= " and ";
		  $sSQL2 .= "expire>" . $db->qstr(date('Y-m-d'));//$db->qstr($expire);		
		}
		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 
	    $ret = $db->fetch_array_all($resultset);			  
		//print_r($ret);
		return ($ret);
	}
	
	//based on ivitation code
	function update_download($invcode) {
	
	    $sSQL1 = "select downtimes from rccustomers where invcode=" . $db->qstr($invcode);
        $res = $db->Execute($sSQL1,2);		
		$ret = $db->fetch_array($res);
	   
	    $times = $ret[0] + 1; 
	
	    $sSQL2 = "update rccustomers set downtimes=" . $db->qstr($times);
        $db->Execute($sSQL2,1);		
	}	 
	
    function reset_db() {
        $db = GetGlobal('db'); 

        //delete table if exist
  	    $sSQL1 = "drop table rccustomers";
        $db->Execute($sSQL1,1);
		$sSQL2 = "create table rccustomers " .
                    "(id integer auto_increment primary key,
                     insdate DATETIME, 
					 fname VARCHAR(128),
					 lname VARCHAR(128),
					 fullname VARCHAR(128),
					 address VARCHAR(128),
					 zip VARCHAR(128),
                     city VARCHAR(128),		
					 state VARCHAR(128),			 
					 country VARCHAR(128),
					 email VARCHAR(128),
					 itemcode VARCHAR(128),
					 itemname VARCHAR(128),
					 payment VARCHAR(128),
					 itemtype VARCHAR(128),
					 downtimes VARCHAR(128),
					 invcode VARCHAR(128),
					 expire DATETIME
					 )";																
        $db->Execute($sSQL2,1);   
		//echo $sSQL2;					
     }	
	
	
	function delete_customer($id,$key=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "delete from rccustomers where ";
		if ($key) 
		  $sSQL .= $key . "=" . $id;//'' must added to param
		else
		  $sSQL .= "email = " . $db->qstr($id);  
		  
        $db->Execute($sSQL,1); 		  
	    //echo $sSQL;
		
		$this->msg = "Customer with $key=$id deleted!";
	}
	
	function select_customers($id,$key=null,$letter=null) {
	 
		$db = GetGlobal('db'); 
		  
	    $apo = GetParam('apo'); //echo $apo;
	    $eos = GetParam('eos');	//echo $eos; 		
		
		$sSQL = "select id,insdate,fname,lname,country,itemname,itemtype from rccustomers ";
		
		if ($key) 
		  $sSQL .= " where ". $key . "=" . $db->qstr($id); 
		  
		if ($letter) {
		  if ($key) $sSQL .= " and ";
		       else $sSQL .= " where ";
		  $sSQL .= "(fname like '" . strtolower($letter) . "%' or " .
		            "fname like '" . strtoupper($letter) . "%')";
		}	
		
		if ($apo) {
		  if ($key) $sSQL.=' and ';
		       else $sSQL.=' where ';
		  $sSQL.= "insdate>='" . convert_date(trim($apo),"-YMD",1) . "'";
		}  
		  
		if ($eos) {
		  if ((!$key) && (!$apo)) $sSQL.=' where ';
	                         else $sSQL.=' and ';
		  $sSQL .= "insdate<='" . convert_date(trim($eos),"-YMD",1) . "'";						
		} 
		
		if ((!$key) && (!$letter) && (!$eos) && (!$apo))  //default view
		  $sSQL.= " where insdate>='" . date('Y-m-d') . "'"; 
		  
		//echo $sSQL;	
	    $resultset = $db->Execute($sSQL,2);  			 
	    $ret = $db->fetch_array_all($resultset);			   
		
		//print_r($ret); 
	  
	    return ($ret);	 		
	}
	
	function show_customers() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   $myadd = new window('',seturl("t=regcustomer","Register a new customer!"));
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  
	
	   if ($this->carr) {	
	   
	    $max  = count($this->carr[0])-1;
	    $prc = 96/$max;		
	
	    foreach ($this->carr as $n=>$rec) {
		
		   $viewdata[] = $n+1;
		   $viewattr[] = "right;4%";
		   
           $name = "customer_".$rec[0]; //echo $name."<br>";		 
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"0\">";
		   $viewattr[] = "left;1%";		
		   
		   $viewdata[] = seturl("t=delcustomer&id=".$rec[0],"X");
		   $viewattr[] = "left;5%";		      

		   $viewdata[] = ($rec[1]?convert_date($rec[1],"/YMDT"):"&nbsp;");
		   $viewattr[] = "left;10%";	
		   
		   $viewdata[] = ($rec[2]?$rec[2]:"&nbsp;");
		   $viewattr[] = "left;15%";		   
		   
		   $viewdata[] = ($rec[3]?$rec[3]:"&nbsp;");
		   $viewattr[] = "left;19%";	
		   
		   $viewdata[] = ($rec[4]?$rec[4]:"&nbsp;");
		   $viewattr[] = "left;20%";			   
		   
		   $viewdata[] = ($rec[5]?$rec[5]:"&nbsp;");
		   $viewattr[] = "left;15%";			   

		   $viewdata[] = ($rec[6]?$rec[6]:"&nbsp;");
		   $viewattr[] = "left;15%";		   
		   
		   //print_r($rec);
		   //echo "<br>";
		   /*foreach ($rec as $x=>$y) {
		     if (is_integer($x)) {
		       $viewdata[] = ($rec[$x]?$rec[$x]:"&nbsp;");
		       $viewattr[] = "left;$prc%";			   
			 }
		   }*/		   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}			
	   }
	   else
	     $toprint .= "No customers !<br>";//$this->bulkiform();
		 
	   $toprint .= $this->alphabetical();
	   
	   $dater = new datepicker("/MDYT");	
	   $toprint .= $dater->renderspace(seturl("t=cpcustomers"),"cpcustomers");		 
	   unset($dater);	 	   		 
		 
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();		 
	  
	   return ($out);			
	}	
	
	function alphabetical($command='cpcustomers') {
	
	  $preparam = GetReq('alpha');
	  
	  $ret .= seturl("t=$command","Home") . "&nbsp;|";
	
	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&alpha=".$preparam."z",$preparam."z");
	  
      //$mywin = new window('',$ret);
      //$out = $mywin->render();	  
	  
	  return ($ret);
	}
	
	function form($action=null) {
	
     $myaction = seturl("t=regcustomer");	
	
     if ($this->post==true) {
	 
	   SetSessionParam('REGISTERED_CUSTOMER',1);
	 }
	 else { //show the form plus error if any

       //if (!$action) $out = setNavigator($this->title);
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   
	   $form = new form(localize('_ADDEVENT',getlocal()), "regcustomer", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("personal",			"Tell us about your self.");
	   //$form->addGroup			("technical",			"Tell us about your technology.");	   
	   $form->addGroup			("subscribe",			"Subscribe.");      

	   $form->addElement		("personal",			new form_element_text		(localize('_COMP',getlocal())."*",     "company",		GetParam("company"),				"forminput",	        50,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_CPER',getlocal()),     "cperson",		GetParam("cperson"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_ACTV',getlocal()),     "activities",	GetParam("activities"),				"forminput",	        30,				255,	0));	   	   	   
	   $form->addElement		("personal",			new form_element_text		(localize('_ADDR',getlocal()),     "address",	    GetParam("address"),				"forminput",	        30,				255,	0));	
	   $form->addElement		("personal",			new form_element_text		(localize('_TOWN',getlocal()),     "town",	        GetParam("town"),				"forminput",	        20,				255,	0));
//	   $form->addElement		("personal",			new form_element_greekmap	(localize('_NOMOS',getlocal()),     "amail","nomos",GetParam("nomos"),"forminput",20,20,1));	   	
	   $form->addElement		("personal",			new form_element_text		(localize('_ZIP',getlocal()),      "zip",	        GetParam("zip"),				"forminput",	        20,				255,	0));
	   //$form->addElement		("personal",			new form_element_text		(localize('_CNTR',getlocal()),     "country",	    GetParam("country"),				"forminput",	        20,				255,	0));	
	   $form->addElement		("personal",			new form_element_combo_file (localize('_CNTR',getlocal()),     "country",	    $this->get_country_from_ip(),				"forminput",	        1,				0,	'country'));	
	   $form->addElement		("personal",			new form_element_text		(localize('_TEL',getlocal()),      "tel",	        GetParam("tel"),				"forminput",	        20,				255,	0));	
	   $form->addElement		("personal",			new form_element_text		(localize('_FAX',getlocal()),      "fax",	        GetParam("fax"),				"forminput",	        20,				255,	0));		   	   	   		   	   	   
	   $form->addElement		("personal",			new form_element_text		(localize('_MAIL',getlocal())."*",     "email",			GetParam("email"),				"forminput",	        30,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_WEB',getlocal()),      "web",			"http://",		"forminput",		    20,				255,	0));
	   
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_PLAN',getlocal()),     "proglan",	    GetParam("proglan"),				"forminput",	        5,				0,	'proglan'));	   
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_OSYS',getlocal()),     "opersys",	    GetParam("opersys"),				"forminput",	        5,				0,	'opersys'));	   
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_USERI',getlocal()),     "userint",	    GetParam("userint"),				"forminput",	        5,				0,	'userint'));	   
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_DBENV',getlocal()),     "dbenv",	    GetParam("dbenv"),				"forminput",	        5,				0,	'dbenv'));	   
	   
	   //$form->addElement		("subscribe",			new form_element_text		(localize('_SYBSCR',getlocal()),   "subscribe",		"",				"forminput",	        20,				255,	0));
	   $form->addElement		("subscribe",			new form_element_radio		(localize('_RCSUBSE',getlocal()),   "subscribe",      1,             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   
	   //$form->addElement		("thema",			    new form_element_text		(localize('_SUBJECT',getlocal())."*",  "subject",		GetParam("subject"),				"forminput",			60,				255,	0));	
	   //$form->addElement		("thema",			    new form_element_textarea   (localize('_MESSAGE',getlocal()),  "mail_text",		GetParam("mail_text"),				"formtextarea",			60,				9));		      
	   
	   //$form->addElement		("warning",			    new form_element_onlytext	(localize('_WARNING',getlocal()),  localize('_FORMWARN',getlocal()),""));	   
	   
	   //if ($this->info_message)	   
	     //$form->addElement		("info",			    new form_element_onlytext	("",  $this->info_message,""));	   	   

	   // Adding a hidden field
	   if ($action)
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", $action));
	   else
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "regcustomer"));
 
	   // Showing the form
	   $fout = $form->getform ();		
	   
	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();	
	   //unset ($fwin);	
	   
	   $out .= $fout;

	   //$form->checkform();	   
	 }
 
     return ($out);	
	}
	
  function get_country_from_ip() {
  
     $mycountry = GetGlobal('controller')->calldpc_method("country.find_country");
	 //return "Greece";
	 return ($mycountry);
  }
  
};
}
?>