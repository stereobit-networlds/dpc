<?php
$__DPCSEC['RCSAPPMAP_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSAPPMAP_DPC")) && (seclevel('RCSAPPMAP_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSAPPMAP_DPC",true);

$__DPC['RCSAPPMAP_DPC'] = 'rcsappmap';

$a = GetGlobal('controller')->require_dpc('shop/rcusers.dpc.php');
require_once($a);

$__EVENTS['RCSAPPMAP_DPC'][0]='cpsappmap';
$__EVENTS['RCSAPPMAP_DPC'][1]='delappmap';
$__EVENTS['RCSAPPMAP_DPC'][2]='insappmap';
$__EVENTS['RCSAPPMAP_DPC'][3]='editappmap';
 
$__ACTIONS['RCSAPPMAP_DPC'][0]='cpsappmap';
$__ACTIONS['RCSAPPMAP_DPC'][1]='delappmap';
$__ACTIONS['RCSAPPMAP_DPC'][2]='insappmap';
$__ACTIONS['RCSAPPMAP_DPC'][3]='editappmap';

$__DPCATTR['RCSAPPMAP_DPC']['RCSAPPMAP'] = 'cpsappmap,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSAPPMAP_DPC'][0]='RCSAPPMAP_DPC;Application Mapping;Application Mapping';

class rcsappmap extends rcusers {

    var $title;
	var $msg;
	var $path, $urlpath;
	var $carr;	
	var $record;
	var $system_message;

    function rcsappmap() {
	
	  $this->title = localize('RCSAPPMAP_DPC',getlocal());	
	  $this->path = paramload('SHELL','prpath');	
	  $this->urlpath = paramload('SHELL','urlpath');		  
	  
	  $this->carr = array();
	  $this->post = false;		
    }
	
    function event($event=null) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
	   switch ($event) {
	     case 'insappmap' : $this->_create();
		                    break;		 
	     case 'editappmap': $this->_edit();
		                    break; 		 
	     case 'delappmap' : $this->_delete();							
	     case 'cpsappmap' :
		 default          : $this->carr = $this->select_applications_maps('all',null,GetReq('alpha'));//dummy param
	   }
			
    }
  
    function action($action=null) {
	 
	  //if (GetSessionParam('REMOTELOGIN')) 
	    // $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  //else   	 
	 
	  switch ($action) {
	     case 'insappmap' : if ($this->post)
		                      $out .= $this->show_application_maps();
							else  
							  $out .= $this->create_map();
		                    break; 
	     case 'editappmap': $out .= $this->edit_map();
		                    break;		 		 
	     case 'delappmap' : 							
	     case 'cpcappmap' :
		 default          : $out .= $this->show_application_maps();
	 }	 
	 
	 return ($out);
    }
	
	public function unmapped_application_exists($oftype) {
		
	    $unmapped = $this->find_map_record($oftype);
		
		return $unmapped;		
	}
	
	public function mapped_application_expired($oftype,$role='demo') {
	
	    $remapped = $this->find_remap_record($oftype);
		
		return $remapped;	
	}
	
	//find the appropriate rec
	/*private function find_map_record($type=null) {
        $db = GetGlobal('db'); 
	
	    //fet empty map list
	    //$sSQL = "select mapname from applicationmap where appname=''";
	    $sSQL = "select lname from users where lname=''";		
		if ($type)
		  $sSQL .= " and notes=" . $db->qstr($type);
		  
	    $resultset = $db->Execute($sSQL,2);  			 		
		
		return ($resultset->fields[0]);
	}*/
	
	private function find_remap_record($type=null) {
        $db = GetGlobal('db'); 
		
		$today = date('Y-m-d');//'2006-05-05';		
	
	    //$sSQL = "select mapname from applicationmap where appname='balexiou@stereobit.com'";
		$sSQL = "select lname,expire from users,applications where ";
		$sSQL.= "users.lname=applications.appname";
		$sSQL.= " and applications.expire<'" . $today . "'";
		if ($type)
		  $sSQL .= " and applications.maptype=" . $db->qstr($type);
		$sSQL.= " order by applications.expire";   
		  
		//echo $sSQL;  
	    $resultset = $db->Execute($sSQL,2);  			 
	    //$ret = $db->fetch_array_all($resultset);//get all and first the older expiration		
		/*echo '<pre>';
		print_r($ret);
		echo '</pre>';
		
		echo '>',$ret[0][0],'>',$ret[0][1];*/
		return ($resulrset->fields[0]);//[0];	
	}
	
	
	//find the map name of an app
	function find_map_name($name,$type=null) {
         $db = GetGlobal('db'); 
	
	    //fet empty map list
	    $sSQL = "select mapname from users,applications where ";
		$sSQL .= "users.lname=applications.appname";		
		$sSQL .= "and applications.appname=" . $db->qstr($name);
				
		if ($type)
		  $sSQL .= " and applications.maptype=" . $db->qstr($type);
		  
	    $resultset = $db->Execute($sSQL,2);  			 		
		
		return $resultset->fields[0];
	}
	
	//find the name of mapped app
	function find_app_name($name) {
        $db = GetGlobal('db'); 
	
	    $sSQL = "select lname from users,applications where ";
		$sSQL .= "users.lname=applications.appname and applications.appname=";		
		$sSQL .= $db->qstr($name);
		//echo $sSQL;  
	    $resultset = $db->Execute($sSQL,2);  			 		
		
		return ($resultset->fields[0]);	
	}	
 	
	//map application
	function map_application($appname,$user) {
        $db = GetGlobal('db'); 	
		
		//once only ... no refresh
		if (GetSessionParam('MAPPINGHASDONE')) {
		  $this->msg = "<li>Re-mapping not allowed!";
		  return ($this->msg);
		}
		
	    if (isset($user)) {    

            $sSQL2 = "update users set 
		              lname=" . $db->qstr($appname);	
			$sSQL2 .= " where username=" . $db->qstr($user);
			//echo $sSQL2;		  
																		
            $db->Execute($sSQL2,1);	
			
			$this->msg = null;
			SetSessionParam('MAPPINGHASDONE',true);//beware of dublication (..refresh from browser )
	    }
	    else
	        $this->msg = "<li>Can't find record to map the application!";
		 
	    return ($this->msg);	 		
	} 		
	
	//re-map application
	function re_map_application($appname,$user) {
        $db = GetGlobal('db'); 	
		
		//once only ... no refresh
		if (GetSessionParam('MAPPINGHASDONE')) {
		  $this->msg = "<li>Re-mapping not allowed!";
		  return ($this->msg);
		}
		  

	    if (isset($user)) {    

            $sSQL2 = "update users set 
		              lname=" . $db->qstr($appname);	
			$sSQL2 .= " where username=" . $db->qstr($user);
			//echo $sSQL2;		  
																		
            $db->Execute($sSQL2,1);	
			
			$this->msg = null;
			SetSessionParam('MAPPINGHASDONE',true);//beware of dublication (..refresh from browser )
	    }
	    else
	        $this->msg = "<li>Can't find record to map the application!";
		 
	    return ($this->msg);	 		
	} 				
	
	function select_applications_maps($id,$key=null,$letter=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "select users.id,applications.appname,applications.maptype,users.startdate from users,applications where ";
		$sSQL .= "users.lname=applications.appname";		
		
		if ($key) 
		  $sSQL .= " and ". $key . "=" . $db->qstr($id); 
		  
		if ($letter) {
		  $sSQL .= " and ";
		  $sSQL .= "(appname like '" . strtolower($letter) . "%' or " .
		            "appname like '" . strtoupper($letter) . "%')";
		}			
		  
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);  			 
	  
	    return ($resultset);	 		
	}	
	
	function show_application_maps() {
	   
       $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);		   
	
	   //if ($this->msg) $out .= $this->msg;
	   
	   $links = seturl("t=insappmap","Create map!");
	   
	   $myadd = new window($this->msg,$links);
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  	   
	     
	
	   if ($this->carr) {	
	   
	    $max  = count($this->carr[0])-1;
	    $prc = 96/$max;		
	
	    foreach ($this->carr as $n=>$rec) {
		
		   $viewdata[] = $n+1;
		   $viewattr[] = "right;4%";
		   
           $name = "appmap_".$n;//$rec[0]; //id is not primary key and not automatically set
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"0\">";
		   $viewattr[] = "left;1%";		
		   
		   $viewdata[] = seturl("t=delappmap&id=".$rec[0],"X");
		   $viewattr[] = "left;1%";		      
		   
		   $viewdata[] = seturl("t=editappmap&id=".$rec[0],$rec[1]);
		   $viewattr[] = "left;30%";		   
		   
		   $viewdata[] = ($rec[1]?$rec[1]:"&nbsp;");
		   $viewattr[] = "left;30%";	
		   
		   $viewdata[] = ($rec[2]?$rec[2]:"&nbsp;");
		   $viewattr[] = "left;20%";
		   
		   $viewdata[] = ($rec[3]?$rec[3]:"&nbsp;");
		   $viewattr[] = "left;20%";		   			   
		   	   	   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}			
	   }
	   else
	     $toprint .= "No applications maps!<br>";//$this->bulkiform();
		 
	   $toprint .= browse_alphabetical('cpsappmap');		 
		 
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
	  
	   return ($out);		
	}
	
	function _create($appname=null) {
       $db = GetGlobal('db'); 	
	   $mydate = date('Y-m-d');
       $appname = $appname?$appname:GetParam('appname');
		
	   if (!$_POST) return false; //if not post data return					  
		
	   $validapp = GetGlobal('controller')->calldpc_method("rcsapp.is_not_expired use ". $appname);
	   
	   if (!$validapp) {
	   
	      $this->msg = "Unknown or Expired Application!";	   
	      $this->post = false; //user exist //!!??	   
	   }
       elseif ($this->username_exist($username)) {
	   
	      $this->msg = "Username exists!";	   
	      $this->post = false; //user exist //!!??
	   }
	   else {
		$userapp_code = 'KLHKASDFKJE4R';
	    $uname = GetParam("username");		
		$upass = GetParam("pwd");
		$vupass = GetParam("pwd2");
		
		if (($uname) && ($upass === $vupass)) {		
					  
          $sSQL = "insert into users" . " (" . "code1,code2,fname,lname,username,password,vpass,email,timezone,subscribe,notes";
          $sSQL .= ")" .  " values (" .
				"'" . addslashes($uname) . "'," .		
				"'" . addslashes($userapp_code) . "'," . 
                "'" . addslashes($uname) . "'," .
			    "'" . addslashes($appname) . "'," .
                "'" . addslashes($uname) . "'," .
                "'" . addslashes(GetParam("pwd")) . "'," .
                "'" . addslashes(GetParam("pwd2")) . "'," . 
				"'" . addslashes($uname) . "'," .
                "'" . addslashes(GetParam("tmz")) . "'," .
				"1,'ACTIVE'";			
	       $sSQL .= ")";					  
																		
           $db->Execute($sSQL2,1);  	
		   //echo $sSQL2;
			
	       $this->msg = "Success!";		
			
		   //create win users	
		   //$groupname = paramload('SHELL','urltitle');
           //$this->create_windows_users($users2create,$users2group,$groupname);
		   //create directories in instances
		   //$this->create_instance_directories($dirnames,$mytype);
           //create directories in main public
		   //$this->create_local_directories($dirnames,$mytype);		  
			
		   $this->post = true;
		  }
		  else {
	   
	        $this->msg = "invalid data!";	   
	        $this->post = false; 	 		  
		  }
	   }
	   
	   return ($this->post);	  			
	}
	
	function create_map() {
	
	    $out = setNavigator(seturl("t=cpsappmap",$this->title),"Create Map(s)");
	
	    if ($this->post) {
		
		 $ret .= $this->post . " records added!"; 
		 $ret .= "<br>" . $this->system_message;
		}
		else {
		
         $myaction = seturl("t=insappmap");		   
	
	     $form = new form(localize('RCSAPPMAP_DPC',getlocal()), "RCSAPPMAP", FORM_METHOD_POST, $myaction, true);
		 
         $form->addGroup  ("x", "Please enter elements.");		 
         $form->addElement("x", new form_element_text("Application name",  "appname",		"",			"forminput",			50,				50,	0));		   		     		 
         $form->addElement("x", new form_element_text("Username",  "username",		"",			"forminput",			50,				50,	0));		   		     
         $form->addElement("x", new form_element_text("Password",  "pwd",		"",			"forminput",			10,				10,	0));		   		 
         $form->addElement("x", new form_element_text("Verify password",  "pwd2",		"",			"forminput",			10,				10,	0));			 
         $form->addElement("x", new form_element_text("Timezone",  "tmz",		"",			"forminput",			10,				10,	0));			 
		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "insappmap"));	   	   
	   
	     $ret .= $form->getform ();			
		}
		
		$title = $this->msg?$this->msg:"Create user and map application";		
		
        $mywin = new window($title,$ret);
        $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
	  		
		
		return ($out);
	}
	
	function _edit() {
        $db = GetGlobal('db'); 
		
	    if (($id = GetParam('id')) && (GetParam('FormAction')=='editappmap')) {//posted id = save
		
		  if ($appname=GetParam('mapapp')) {
		
            $sSQL2 = "update users set ";
		    $sSQL2 .=  "lname=" . $db->qstr($appname);
		    $sSQL2 .=  ",username=" . $db->qstr(GetParam('uname'));			
	        $sSQL2 .=  ",password=" . $db->qstr(GetParam('pwd'));			
					  	
		    $sSQL2 .= " where lname=" 	. $db->qstr($id);
		    //echo $sSQL2;		  
																		
            $db->Execute($sSQL2,1);			
		
		    $this->post = true; 
		  }
		  else
		    $this->post = false;
		}
		elseif ($id = GetReq('id')) {//get id = read
		
		  $sSQL = "select lname,startdate,username,password from users "; 
		  $sSQL .= " where id=". $db->qstr($id); 		       
		  //echo $sSQL;
		  $resultset = $db->Execute($sSQL,2);  			 
		  
		  $this->record = $resultset->fields;
		  $this->post = false;
		}
		else {
		  $this->post = false;
		}  
	}
	
	function edit_map() {
	
	    $out = setNavigator(seturl("t=cpsappmap",$this->title),"Edit Map");
		
	    if ($this->post) {
		
		 $ret .= $this->post . " records edited!"; 
		}
		elseif (is_array($this->record)) {
		
         $myaction = seturl("t=editappmap");		   
	
	     $form = new form(localize('RCSAPPMAP_DPC',getlocal()), "RCSAPPMAP", FORM_METHOD_POST, $myaction, true);
		 
         $form->addGroup  ("x",	"Please enter elements.");		 
         //$form->addElement("x", new form_element_text("Date",  "mdate",		$this->record[3],			"forminput",			50,				50,	1));			 
         $form->addElement("x", new form_element_text("Name",  "mname",		$this->record[0],			"forminput",			50,				50,	1));		   		     
         //$form->addElement("x", new form_element_text("Type",  "mtype",		$this->record[2],			"forminput",			50,				50,	1));		   		 
         //$form->addElement("x", new form_element_text("Map to",  "mapapp",	$this->record[0],			"forminput",			50,				50,	0));			 
         $form->addElement("x", new form_element_text("Username",  "uname",	$this->record[2],			"forminput",			50,				50,	0));			 
         $form->addElement("x", new form_element_text("Password",  "upwd",	$this->record[3],			"forminput",			50,				50,	0));			 
		 		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("id", GetReq('id')));	   	   
	   		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "editappmap"));	   	   
	   
	     $ret .= $form->getform ();			
		}
		else
		  $ret = "Invalid record or missing data!";
		
        $mywin = new window("Edit",$ret);
        $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
	  		
		
		return ($out);		
	}
	
	//overrride
	function _delete() {
        $db = GetGlobal('db'); 
		
	    if ($id = GetParam('id')) {//posted id = save
		
            $sSQL2 = "update users set lname='SUBSCRIBER'"; //became subscriber	  	
		    $sSQL2 .= " where id=" . $db->qstr($id);
		    //echo $sSQL2;		  
																		
            $db->Execute($sSQL2,1);			
			
			$this->msg = "1 record unmaped!";
			
			//delete user
			//$users[] = $id.",,,,,,,"; 
			//$this->msg .= $this->delete_windows_users($users);
			
			//delete from filesystem
			//$this->msg .= $this->delete_directories($id);			
		}	
	}
	 
	 function get_username_password($app) {
		$db = $this->cdb->dbp;	
			 
		$sSQL = "select username,password from users "; 
		$sSQL .= " where lname=". $db->qstr($app); 		       
		//echo $sSQL;
		$resultset = $db->Execute($sSQL,2);  			 
		  
		return ($resultset->fields[0].":".$resultset->fields[1]);			 
	 } 
	 
	 
	 /////////////////////////////////////////////////////////////////////////// not used
	 
	 function create_windows_users($users,$group,$groupname) {
	 
	    //create batch file
	    $file = $this->path . "create_users.csv";
	    //echo $file,"<br>";
		
		if ((is_array($users)) && (!empty($users))) {
	      $data = "[USERS]\r\n";
	      $data .= implode("\r\n",$users);	
		  $procced = 1;	
		}
		
		if ((is_array($group)) && (!empty($group))) {
		  $data .= "\r\n[LOCAL]\r\n";
		  $data .= "$groupname,$groupname," . implode(",",$group) . ",";
		  $procced = 1;
		}
		
	    if ($procced) {		
		
          if ($fp = fopen ($file , "w")) {
                 fwrite ($fp, $data);
                 fclose ($fp);
				 
				 //run command
				 $winfile = "C:" . str_replace("/","\\",$file);
				 $command = "addusers /c $winfile /p:lce"; 
				 //$command = "netstat -na";
				 echo $command;
				 $this->system_message = system($command);
				 //echo $this->system_message;
          }
          else {
            $this->system_message = "File creation error ($file)!\n";
		    //echo $this->system_message;
          }	
	    }
	    return ($this->msg);	    
	 }
	 
	 function delete_windows_users($users) {
	 
	    $file = $this->path . "delete_users.csv";	
		
		if ((is_array($users)) && (!empty($users))) {
	      $data = "[USERS]\r\n";
	      $data .= implode("\r\n",$users);	
		  $procced = 1;	
		}	
		
	    if ($procced) {		
		
          if ($fp = fopen ($file , "w")) {
                 fwrite ($fp, $data);
                 fclose ($fp);
				 
				 //run command
				 $winfile = "C:" . str_replace("/","\\",$file);
				 $command = "addusers /e $winfile"; 
				 //echo $command;
				 $system_message = system($command);
				 //echo $this->system_message;
          }
          else {
            $system_message = "File creation error ($file)!\n";
		    //echo $this->system_message;
          }
		}   
		
		return ($system_message);				 
	 }
	 
	 function create_instance_directories($dirs,$oftype) {

		if (!$oftype) return 0;
		
        $dirsource = $this->path . "isetup/". $oftype . "/project";	
		$windirsource = "C:" . str_replace("/","\\",$dirsource);
		
		//echo $windirsource,"<br>";
		
	    if ((is_array($dirs)) && (!empty($dirs))) {
		  $i=0;
		  foreach ($dirs as $id=>$name) {
		    $i+=1;
			
			$dirtarget = $this->path . "instances/" . $name;
			/*$windirtarget = "C:" . str_replace("/","\\",$dirtarget);
			
			$ok = mkdir($windirtarget); //echo 'OK',$ok;
			if ($ok) {
			  //echo "copy " .$oftype . " to " . $windirtarget . "<br>";
			  $command = "xcopy.exe $windirsource $windirtarget /Y/E/I/C/Q/R/K/X"; 
			  echo $command;
			  //exec($command,$a,$a1);;
			  //$this->system_message .= implode("::",$a). ">" . implode("::",$a1);//system($command)			  
			  $this->system_message .= system($command);
			}*/
			
			copyr($dirsource,$dirtarget);
		  }
		}
		
		return ($i);
	 }
	 
	 function create_local_directories($dirs,$oftype) {
	 
		if (!$oftype) return 0;
		
        $dirsource = $this->path . "isetup/". $oftype . "/local";	
		$windirsource = "C:" . str_replace("/","\\",$dirsource);
		
		//echo $windirsource,"<br>";
		
	    if ((is_array($dirs)) && (!empty($dirs))) {
		  $i=0;
		  foreach ($dirs as $id=>$name) {
		    $i+=1;
			
			$dirtarget = $this->path . "public/" . $name;
			
			/*$windirtarget = "C:" . str_replace("/","\\",$dirtarget);
			
			$ok = mkdir($windirtarget); //echo 'OK',$ok;
			if ($ok) {
			  //echo "copy " .$oftype . " to " . $windirtarget . "<br>";
			  $command = "xcopy $windirsource $windirtarget /Y/E/C/Q/R/K/X"; 
			  echo $command . "<br>";
			  //exec($command,$a,$a1);			  
			  //$this->system_message .= implode("::",$a). ">" . implode("::",$a1);//system($command);
			  $this->system_message .= system($command);
			}*/
			
			copyr($dirsource,$dirtarget);
		  }
		}
		
		return ($i);	 
	 }
	 
	 function delete_directories($name) {
	 
	    $dirtarget = $this->path . "instances/" . $name;
        $windirtarget = "C:" . str_replace("/","\\",$dirtarget);
		//echo $windirtarget;		
		$ok1 = unlink($windirtarget); //must be empty and perms ok
		
		
	    $dirtarget = $this->path . "public/" . $name;
        $windirtarget = "C:" . str_replace("/","\\",$dirtarget);
		//echo $windirtarget;	
		$ok2 = unlink($windirtarget);	//must be empty	and perms ok
		
		
		if (($ok1) && ($ok2))
		  $ret = "Direcories deleted!";
		else
		  $ret = "Directories NOT deleted!"; 
		   			
	    return ($ret);
	 } 
		

};
}
?>