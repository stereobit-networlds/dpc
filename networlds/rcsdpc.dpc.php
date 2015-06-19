<?php

$__DPCSEC['RCSDPC_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSDPC_DPC")) && (seclevel('RCSDPC_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSDPC_DPC",true);

$__DPC['RCSDPC_DPC'] = 'rcsdpc';
 
$d = GetGlobal('controller')->require_dpc('dpcmodules/dpctree.dpc.php');
require_once($d); 

//GetGlobal('controller')->get_parent('DPCTREE_DPC','RCSDPC_DPC');
$__EVENTS['RCSDPC_DPC'][0]='cpsdpc';
$__EVENTS['RCSDPC_DPC'][1]='cpsdpcupgrade';
//$__EVENTS['RCSDPC_DPC'][2]='cpsavedpc';  //handled by rcsappview dpc
//$__EVENTS['RCSDPC_DPC'][3]='cpdpcshow'; //handled by rcsappview dpc

$__ACTIONS['RCSDPC_DPC'][0]='cpsdpc';
$__ACTIONS['RCSDPC_DPC'][1]='cpsdpcupgrade';
//$__ACTIONS['RCSDPC_DPC'][2]='cpsavedpc'; //handled by rcsappview dpc
//$__ACTIONS['RCSDPC_DPC'][3]='cpdpcshow'; //handled by rcsappview dpc

$__LOCALE['RCSDPC_DPC'][0]='RCSDPC_DPC;Dpc upgrade;Dpc upgrade';

class rcsdpc extends dpctree {

	var $title,$dbvalue,$centraldbpath;
	var $dpcarr,$db;
	var $apppath, $urlpath;
		
	function rcsdpc() {
	
	    dpctree::dpctree();
		
	    $this->title = localize('RCSDPC_DPC',getlocal());	
	    $this->dbvalue=0;	
		
        $this->apppath = paramload('SHELL','prpath');
        $this->urlpath = paramload('SHELL','urlpath');		
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////	
	
        //dpctree::event($sAction); 
		
		$this->dpcs = $this->dtree->read_dpcs();
		
		switch ($sAction) {
	      case 'cpsavedpc'   : $this->save_dpc(); 
		                       $this->read_dpc2show();
							   break;
		  case 'cpdpcshow'   : $this->read_dpc2show(); 
		                       break;		  
		  		
		  case 'cpsdpc'       : 
		                        $this->dpcarr = $this->get_modules_from_db(); 
								break;
		  case 'cpsdpcupgrade': $this->upgrade_dpcs_to_db(); 
		                        $this->dpcarr = $this->get_modules_from_db();
		                        break;		  
		}
    }
  
    function action($action) {

	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);  
	 //$out .= $this->changeform();
	 
	 //dpctree::action($action);
	 switch ($action) {	 
	      case 'cpsavedpc'    :	 
		  case 'cpsdpcupgrade': $out .= "Ok!"; 		  
	 	  case 'cpsdpc'       : 
		  default             : $out .= $this->dpcform(); 	
	 }
	 
	 return ($out);
    } 
	
	function dpcform() {
	   $dm = 0;
	   
	   if (is_array($this->dpcs)) {
	     //print_r($this->dpcarr);
         $myaction = seturl("t=cpsdpcupgrade");		   
	     $form = new form(localize('RCSDPC_DPC',getlocal()), "RCSDPC", FORM_METHOD_POST, $myaction, true);
		 
	     foreach ($this->dpcs as $division=>$modules) {
	       $form->addGroup			($division,			"Select from $division.");
	   
	       foreach ($modules as $id=>$name) {
		     $dbname = $division . "_" . str_replace(".","_",$name);
			 
			 if (isset($this->dpcarr[$dbname])) {//dont show
		       $dbvalue = 1;  
			   //$newdpc = null;
			 }    
			 else {
			   $dbvalue = 0;
	   		   //$newdpc = "[NEW!!!]";
			   
			   //show it
               $form->addElement		($division, new form_element_text($name,  $name,		$newdpc."details",			"forminput",			90,				255,	1));		   
		       $form->addElement		($division, new form_element_checkbox("select",  $dbname,	$dbvalue,				"forminput"));		     
			   $dm += 1;
			 }  
		   }   
	     }
		 if ($dm) //has modules...ok
           $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsdpcupgrade"));	   	   
		 //else
		   //message nothing to alter
		     
	     $ret = $form->getform ();	
	   }
	   else {//get personal data to query db
         $ret = "Dpc tree not exists!";			 
	   }
	   return ($ret);
	}
	  
    function get_modules_from_db() {
      $db = GetGlobal('db');	
	   
	  $sSQL .= "select * from dpcmodules";
      $result = $db->Execute($sSQL,2);	
	  //echo $sSQL;   	
	  
	  if (!empty($result)) {
	    foreach ($result as $n=>$rec) {	  
	      foreach ($rec as $id=>$dpc) {
	  
	        if (is_string($id)) {
		      $p = explode("_",$id);
		      if (($p[0]) && ($p[1]))
			    $dpcarr[$id] = 1;
	        }
	      }
	    }
	  }
	  //print_r($dpcarr);
	  return ($dpcarr);
	}
	
	function upgrade_dpcs_to_db() {
	
	     $this->upgrade_db();

	}
	
	function alter_db() {
        $db = GetGlobal('db');	
	 
	    foreach ($this->dpcs as $division=>$modules) {
	       foreach ($modules as $id=>$name) {			
		   
		      $dbname = $division."_".str_replace(".","_",$name);
			  
			  if ($_POST[$dbname]) {//if checked  
			    $sSQL2 = "ALTER TABLE dpcmodules ADD " . $dbname . " BOOL NULL";
		        echo $sSQL2,';';																
                $result = $db->Execute($sSQL2,1); 
				print_r($result);  
			  } 	
		   }
		}   	
					 		
	}	
	
	function upgrade_db() {
		
		$this->alter_db();	
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
	
	//used to rollback added records in case of successfull user registration
	//but un-successfull application creation....
	function rollback($appname) {
      $db = GetGlobal('db');	
	  
	  $sSQL = "delete from dpcmodules where appname=" . $db->qstr($appname);
      $result = $db->Execute($sSQL,2);  	
      //echo $sSQL2;		
	}	
	
	function insert_application_modules($apptype,$appname) {
       $db = GetGlobal('db');	

	   $file = $this->apppath . $apptype . ".sql";
	   //echo $file;
	   
	   
       if ($fp = @fopen ($file , "r")) {
                 $result = fread ($fp, filesize($file));
                 fclose ($fp);
				 
				 $sqls = explode(";",$result);
				 
				 foreach ($sqls as $id=>$sql) {
				   
				   if ($sql!="") {
				     $sSQL = null;				   
				     $sSQL = $sql;
				     $sSQL .= " where appname='" . $appname . "'";
	 	             //echo $sSQL;
		             $result = $db->Execute($sSQL,1);			 
				   }					   
				 }
       }
       else {
         $ret = "<li>File reading error ($file)!\n";
       }	
	 
	   return ($ret);
	}
	
	//used in remaping
	function update_application_modules($apptype,$oldappname,$newappname,$expiredays) {
        $db = GetGlobal('db');	
	
	    //first rollback-delete record at registration process
		$this->rollback($newappname);
	 
	    $nextYear = time() + ($expiredays * 24 * 60 * 60);
			
	    $expire = date('Y-m-d',$nextYear);	

	    $sSQL2 = "update dpcmodules set ";	
		$sSQL2 .= "expire=" . $db->qstr($expire);	
		$sSQL2 .= ",appname=" . $db->qstr(strtolower($newappname));	
		$sSQL2 .= ",user=" . $db->qstr(strtolower($newappname));	
		$sSQL2 .= ",pwd=" . $db->qstr(strtolower($newappname));	
		$sSQL2 .= " where appname='" . $oldappname . "'";
		
        $result = $db->Execute($sSQL2,1);		
		//echo $sSQL2;	
		
		//if i want to update modules...
		$this->insert_application_modules($apptype,$newappname);	       
	 
	    return ($ret);
	}	
	
	function modules_expires_at($appname,$expire) {
        $db = GetGlobal('db');	
	
	    $sSQL2 = "update dpcmodules set ";	
		$sSQL2 .= "expire=" . $db->qstr($expire);	
		$sSQL2 .= ",lastupdate=" . $db->qstr(date('d-m-Y'));		
		$sSQL2 .= " where appname='" . $appname . "'";
				
	    $result = $db->Execute($sSQL2,1);	
		//echo $sSQL2;		
		
	}
	
	function initialize_application_modules($appname,$expire,$user,$pwd,$type=null) {
        $db = GetGlobal('db');	
	
        //insert into dpcmodules (id,insdate,role,appname,user,pwd,expire) values ('1'5,'7/08/2007','user','art-time','art-time','art-time','10-08-2007')	
	
	    $sSQL2 = "insert into dpcmodules (insdate,role,appname,user,pwd,expire,active) values (";	
		$sSQL2 .= $db->qstr(date('d-m-Y')) . ','.
		          $db->qstr('user') .','. 
		          $db->qstr($appname) .','. 
		          $db->qstr($user) .','. 
		          $db->qstr($pwd) .','. 				  				  
		          $db->qstr($expire);	
		//$sSQL2 .= "," . $this->db->dbp->qstr(date('d-m-Y'));		
		$sSQL2 .= ",1)";
				
	    $result = $db->Execute($sSQL2,1);	
		//echo $sSQL2;			
		//echo $type,'>';
		//SHOP case
		switch (strtoupper($type)) {
		  case 'SHOP' :
		                $app_dpc = "shop_shtags_dpc_php='1',shop_shlangs_dpc_php='1',shop_rcvstats_dpc_php='1',shop_shlogin_dpc_php='1',shop_shcustomers_dpc_php='1',shop_rccustomers_dpc_php='1',shop_shtransactions_dpc_php='1',shop_rctransactions_dpc_php='1',shop_shcart_dpc_php='1',shop_shkatalog_dpc_php='1',shop_shkategories_dpc_php='1',shop_rckategories_dpc_php='1',shop_rccategories_dpc_php='1',shop_shusers_dpc_php='1',shop_rcusers_dpc_php='1',shop_shsubscribe_dpc_php='1',shop_rcshsubscribers_dpc_php='1',shop_shnsearch_dpc_php='1',shop_shpaypal_dpc_php='1'," .
		                           "shop_rcopts_dpc_php='1',shop_rcconfig_dpc_php='1',shop_rcitems_dpc_php='1',shop_rcsyncsql_dpc_php='1',shop_rcimportdb_dpc_php='1'";
		                break;
		  default :
		}  
		//update dpcmodules set apache_rchtaccess_dpc_php='1',database_database_dpc_php='1',dpcmodules_clientdpc_dpc_php='1',dpcmodules_rcdpc_dpc_php='1',gui_datepick_dpc_php='1',locale_country_dpc_php='1',mail_rcamail_dpc_php='1',mail_rcform_dpc_php='1',mail_smtpmail_dpc_php='1',newsrv_newsrv_dpc_php='1',rc_rccontrolpanel_dpc_php='1',rc_rccustomers_dpc_php='1',rc_rcnews_dpc_php='1',rc_rcopts_dpc_php='1',rc_rcrenew_dpc_php='1',rc_rcroots_dpc_php='1',rc_rcscripts_dpc_php='1',rc_rcsubscribers_dpc_php='1',subscribe_rcsubscribe_dpc_php='1',filesystem_filesystem_dpc_php='1',rc_rcfs_dpc_php='1',rc_rcconfig_dpc_php='1',elements_confbar_dpc_php='1',elements_rootbar_dpc_php='1' where id=15
		$sSQL3 = "update dpcmodules set " .  
		          $app_dpc . 
				  " ,rcserver_rcsidewin_dpc_php='1',forum_rcforum_dpc_php='1',rc_rcupload_dpc_php='1',rc_rcmaptheme_dpc_php='1',rc_rctedit_dpc_php='1',rc_rcedittemplates_dpc_php='1',rc_rcsubscribers_dpc_php='1',filesystem_rcexplorer_dpc_php='1',rc_rclogs_dpc_php='1',mail_cmail_dpc_php='1',dpcmodules_rcsdpc_dpc_php='1',gui_ajax_dpc_php='1',rcserver_rcschpass_dpc_php='1',rc_rclogo_dpc_php='1',rc_rcreports_dpc_php='1',rc_rcmessages_dpc_php='1',rcserver_rcssystem_dpc_php='1',apache_rchtaccess_dpc_php='1',database_database_dpc_php='1',dpcmodules_clientdpc_dpc_php='1',dpcmodules_rcdpc_dpc_php='1',gui_datepick_dpc_php='1',locale_country_dpc_php='1',mail_rcamail_dpc_php='1',mail_rcform_dpc_php='1',mail_smtpmail_dpc_php='1',newsrv_newsrv_dpc_php='1',rc_rccontrolpanel_dpc_php='1',rc_rcnews_dpc_php='1',rc_rcopts_dpc_php='1',rc_rcrenew_dpc_php='1',rc_rcroots_dpc_php='1',rc_rcscripts_dpc_php='1',filesystem_filesystem_dpc_php='1',rc_rcfs_dpc_php='1',rc_rcconfig_dpc_php='1',elements_confbar_dpc_php='1',elements_rootbar_dpc_php='1'" .
		          " where appname=" . $db->qstr($appname) ;
				  		
 	    $result = $db->Execute($sSQL3,1);	
		//echo $sSQL3;					  
	}
	
	function delete_application_modules($appname) {
       $db = GetGlobal('db');		
	  
       $sSQL = "delete from dpcmodules where appname='".$appname."'";
	   		   			
	   $result = $db->Execute($sSQL,2);	   
	}
	
	
	//used by cpsappview as pivot...........................................................................................
	
	function read_dpc2show($id=null) {
       $db = GetGlobal('db');
	   $id = $id?$id:GetReq('id');	
	   $search = GetParam('findpc'); //echo '>',$search;
	
	   if ($id) {	   
	
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
		//remark becaouse of read other apps than this
		//sort($dpclist);
		//$optfile = file_put_contents($this->prpath.'dpclist.opt',implode(',',$dpclist)); 				
	   }
	}
	
	function show_dpc() {	
	   //get selected dpcs to submit 
	   $fdpc = GetParam('findpc');
	   //echo $fdpc,'>>>>';
	
       $myaction = seturl("t=cpsavedpc&id=".GetReq('id'));
	   	
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
		 
			
       $mywin = new window("Application DPC Modules",$toprint);
       $out .= $mywin->render("center::100%::0::group_win_body::left::0::0::");		
	   
	   return ($out);		   
	}
	
	function save_dpc($id=null) {
       $db = GetGlobal('db');
	   $appname = $id?$id:GetReq('id');	
	   
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