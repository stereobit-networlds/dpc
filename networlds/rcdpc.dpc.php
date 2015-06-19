<?php

$__DPCSEC['RCDPC_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCDPC_DPC")) && (seclevel('RCDPC_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCDPC_DPC",true);

$__DPC['RCDPC_DPC'] = 'rcdpc';
 
$d = GetGlobal('controller')->require_dpc('dpcmodules/dpctree.dpc.php');
require_once($d); 

//GetGlobal('controller')->get_parent('DPCTREE_DPC','RCDPC_DPC');
$__EVENTS['RCDPC_DPC'][0]='cpdpc';
$__EVENTS['RCDPC_DPC'][1]='cpdpcverify';
$__EVENTS['RCDPC_DPC'][2]='cpdpcselect';
$__EVENTS['RCDPC_DPC'][3]='cpdpcshow';
$__EVENTS['RCDPC_DPC'][4]='cpsavedpc';

$__ACTIONS['RCDPC_DPC'][0]='cpdpc';
$__ACTIONS['RCDPC_DPC'][1]='cpdpcverify';
$__ACTIONS['RCDPC_DPC'][2]='cpdpcselect';
$__ACTIONS['RCDPC_DPC'][3]='cpdpcshow';
$__ACTIONS['RCDPC_DPC'][4]='cpsavedpc';

$__LOCALE['RCDPC_DPC'][0]='RCDPC_DPC;Dpc selection;Dpc selection';

class rcdpc extends dpctree {

	var $title,$dbvalue,$db;
	var $dbgpath,$prpath;
		
	function rcdpc() {
	
	    dpctree::dpctree();
		
	    $this->title = localize('RCDPC_DPC',getlocal());	
	    $this->dbvalue=0;				
		$this->dbgpath = paramload('SHELL','dbgpath');
		$this->prpath = paramload('SHELL','prpath');		
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////	
	
        //dpctree::event($sAction); 
		
		switch ($sAction) {
		  case 'cpsavedpc'   : $this->save_dpc(); 
		                       $this->read_dpc2show();
		                       break;	                       
		  case 'cpdpc'       : break;
		  case 'cpdpcverify' : $this->verify_dpcs_from_db(); break;
		  case 'cpdpcselect' : $this->select_dpcs_to_db(); break;	
		  case 'cpdpcshow'   : $this->read_dpc2show(); break;	  
		}
    }
  
    function action($action) {

	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);  
	 //$out .= $this->changeform();
	 
	 switch ($action) {
	   case 'cpsavedpc' :
	   case 'cpdpcshow' : $out .= $this->show_dpc(); break; 
	   default          : $out .= $this->dpcform();//dpctree::action($action);
	 }
	 
	 return ($out);
    } 
	
	function dpcform() {
	   
	   if (is_array($this->dbvalue)) {
	   
         $myaction = seturl("t=cpdpcselect");		   
	
	     $form = new form(localize('RCDPC_DPC',getlocal()), "RCDPC", FORM_METHOD_POST, $myaction, true);
	
	     foreach ($this->dpcs as $division=>$modules) {
	       $form->addGroup			($division,			"Select from $division.");
	   
	       foreach ($modules as $id=>$name) {
		     $dbname = $division . "_" . str_replace(".","_",$name);
		     $dbvalue = $this->dbvalue[$dbname];
             $form->addElement		($division, new form_element_text($name,  $name,		"details",			"forminput",			90,				255,	1));		   
		     $form->addElement		($division, new form_element_checkbox("select",  $dbname,	$dbvalue,				"forminput"));		   
		   
		   } 
	   		   
	     }
	   
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpdpcselect"));	   	   
	   
	     $ret = $form->getform ();	
	   }
	   else {//get personal data to query db
	   
         $myaction = seturl("t=cpdpcverify");		   
	
	     $form = new form(localize('RCDPC_DPC',getlocal()), "RCDPC", FORM_METHOD_POST, $myaction, true);
		 
         $form->addGroup  ("x",	"Please enter your username and password.");		 
         $form->addElement("x", new form_element_text("Username",  "name",		"",			"forminput",			50,				255,	0));		   		     
         $form->addElement("x", new form_element_password("Password",  "pwd",		"",			"forminput",			50,				255,	0));		   		 
		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpdpcverify"));	   	   
	   
	     $ret = $form->getform ();			 
	   }
	   return ($ret);
	}
	  
    function verify_dpcs_from_db() {
       $db = GetGlobal('db');	
	   
	   $user = GetParam('name');
	   $pwd = GetParam('pwd');
	   //save credenctials
	   SetSessionParam('dpcuser',$user);
	   SetSessionParam('dpcpwd',$pwd);
	   	   
	   $sSQL .= "select * from dpcmodules where user='$user' and pwd='$pwd'";
       $result = $db->Execute($sSQL,2);	
	   //echo $sSQL;
	   
	   $this->dbvalue = (array) $result->fields;	
	   //print_r($this->dbvalue);
  	   //$this->dbvalue=1;
	   if (!empty($this->dbvalue)) {
	   
	     $this->dpcs = $this->dtree->read_dpcs();
	   }
	   
	   //$this->reset_db();
	   //$this->upgrade_db();
	}
	
	function select_dpcs_to_db() {
       $db = GetGlobal('db');	
	   
	   //save credenctials
	   $user = GetSessionParam('dpcuser');
	   $pwd = GetSessionParam('dpcpwd');
	   	   
	   if (($user) && ($pwd)) {  
	   
	     $this->dpcs = $this->dtree->read_dpcs();
	   
	     $sSQL = "update dpcmodules set ";
		 
		 foreach ($this->dpcs as $division=>$modules) {
	       foreach ($modules as $id=>$name) {
		     $dbname = $division . "_" . str_replace(".","_",$name);
			 
			 if (GetParam($dbname)) 
			   $mods[] = $dbname."=".GetParam($dbname);
			 else
			   $mods[] = $dbname."=0";  
		   }	 		 
		 }
		 $sSQL .= implode(",",$mods);
		 
		 $sSQL .= " where user='$user' and pwd='$pwd'";
		 //echo $sSQL;
		 $result = $db->Execute($sSQL,1);
	   }	
	}
	
	function insert($data) {
       $db = GetGlobal('db');	
	   //echo $data; 
	   $mydata = unserialize($data);
	   //print_r($mydata);
	     
	   if (is_array($mydata)) {
   	     $sSQL = "insert into dpcmodules ";
		 foreach ($mydata as $recid=>$recval) {
		 
		   $partA[] = $recid;
		   $partB[] = "'" .$recval. "'";
		 }
		 $sSQL .= "(" . implode(",",$partA) . ") values ";
		 $sSQL .= "(" . implode(",",$partB) . ")";
		 
		 $result = $db->Execute($sSQL,1);
		 $ret = null;
	   }
	   else
	     $ret = "Invalid data";
	   //echo $sSQL;
	   
	   return ($ret);
		 
	}
	
	function read_dpc2show() {
       $db = GetGlobal('db');	
	   $search = GetParam('findpc'); //echo '>',$search;
	
	   if ($id=GetParam('id')) {	   
	
        $dpcs = $this->dtree->read_dpcs();
	   
	    $sSQL .= "select * from dpcmodules where appname='$id'";
		//if ($search)
		  //$sSQL .= " where ";
        $result = $db->Execute($sSQL,2);	
	    //echo $sSQL;	
	    foreach ($result as $n=>$rec) {	   
	      foreach ($rec as $recname=>$value) {
	   
	       if (stristr($recname,"_dpc")) {
		     if ($search) { 
		       if (stristr($recname,$search)) //only search results
		         $this->showdpcarray[$recname] = $value;
		     }	   
		     else //all
			   $this->showdpcarray[$recname] = $value;
			 
		     //create opt file for all dpc
		     $dpclist[] = ';'; //none selection
		     $p = explode('_',$recname);
		     $dpc_folder = $p[0];
		     if (!in_array($dpc_folder,$dpclist))
		       $dpclist[] = $dpc_folder.';'.$dpc_folder;
		   }
	      }
		}
		sort($dpclist);
		$optfile = file_put_contents($this->prpath.'dpclist.opt',implode(',',$dpclist));				
	   }
	}
	
	function show_dpc() {	
	   //get selected dpcs to submit 
	   $fdpc = GetParam('findpc');
	   //echo $fdpc,'>>>>';
	
       $myaction = seturl("t=cpsavedp&id=".GetReq('id'));
	   	
	   if ($this->showdpcarray) {		
	
	    $form = new form(localize('RCSAPP_DPC',getlocal()), "RCSAPP", FORM_METHOD_POST, $myaction, true);		
	    $i=1;
		
	    $form->addGroup			('a',			'Select Dpc');			
	    $form->addGroup			('b',			'Dpc List');			
		
	    $form->addElement("a",	new form_element_combo_file ('Find Dpc',"findpc",$fdpc,"forminput",0,0,'dpclist',1));			 		
		$form->addElement('a', new form_element_checkbox('Set All',  'setall',	0,		"forminput"));	
		$form->addElement('a', new form_element_checkbox('Unset All',  'unsetall',	0,		"forminput"));	
		
	    foreach ($this->showdpcarray as $n=>$v) {
			 
	         //$form->addGroup			($n,			$n);	
		   		
             //$form->addElement		($n, new form_element_onlytext	($i,  $n,""));	   	   
		     $form->addElement		('b', new form_element_checkbox($i.' '.$n,  $n,	$v,		"forminput"));			
			 $i+=1;
		}
		
        $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsavedpc"));	   	   		
	    $toprint .= $form->getform ();						
	   }
	   else
	     $toprint .= "No dpc selected !<br>";							 
		 
			
       $mywin = new window("Update Application Modules",$toprint);
       $out .= $mywin->render("center::90%::0::group_win_body::left::0::0::");		
	   
	   return ($out);		   
	}
	
	function save_dpc() {
       $db = GetGlobal('db');	
	   //print_r($_POST);
	   $appname = GetReq('id');	
	   
	   if (GetParam('setall')) {
   	       //set to 1				 
		   $this->set_dpc($appname);			   	   
	   }
	   elseif (GetParam('unsetall')) {
   	       //set to 0				 
		   $this->unset_dpc($appname);	   
	   }
	   else { //manual selection
	     //get selected dpc
	     foreach ($_POST as $i=>$v) {
	       if ($v=='1') {
		     $d[] = $i; 
		   }
		   //echo $i,'=',$v,'<br>';
	     }	      	   
	   
	     if (!empty($d)) {	   
		 
   	       //reset to 0				 
		   $this->unset_dpc($appname);
	   
	       $sSQL3 = "update dpcmodules set " . implode("='1',",$d) . 
		            "='1' where appname=" . $db->qstr($appname) ;
				  
           $result = $db->Execute($sSQL3,1);			   
		   //echo $sSQL3;
         }
	   }		
	}
	
	//all selected dpc =0
	function unset_dpc($appname) {
         $db = GetGlobal('db');	
	
  	     $this->read_dpc2show(); 
		 //echo '<pre>';
		 //print_r($this->showdpcarray);
		 //echo '</pre>';	
	     if ($this->showdpcarray) {
	       foreach ($this->showdpcarray as $n=>$v)
		     $r[] = $n;
		   
	       $sSQL2 = "update dpcmodules set " . implode("='0',",$r) . 
		           "='0' where appname=" . $db->qstr($appname) ;
		   //echo $sSQL2;		  
           $result = $db->Execute($sSQL2,1);			   
	     }		
	}

	//all selected dpc =1
	function set_dpc($appname) {
         $db = GetGlobal('db');	
	
   	     $this->read_dpc2show(); 
		 //echo '<pre>';
		 //print_r($this->showdpcarray);
		 //echo '</pre>';		
	     if ($this->showdpcarray) {
	       foreach ($this->showdpcarray as $n=>$v)
		     $r[] = $n;
		   
	       $sSQL2 = "update dpcmodules set " . implode("='1',",$r) . 
		           "='1' where appname=" . $db->qstr($appname) ;
		   //echo $sSQL2;		  
           $result = $db->Execute($sSQL2,1);			   
	     }		
	}		
    
};
}
?>