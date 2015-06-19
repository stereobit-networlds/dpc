<?php

$__DPCSEC['RCSAPP_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSAPP_DPC")) && (seclevel('RCSAPP_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSAPP_DPC",true);

$__DPC['RCSAPP_DPC'] = 'rcsapp';


$__EVENTS['RCSAPP_DPC'][0]='cpsapp';
$__EVENTS['RCSAPP_DPC'][1]='delapp';
$__EVENTS['RCSAPP_DPC'][2]='insapp';
$__EVENTS['RCSAPP_DPC'][3]='editapp';
$__EVENTS['RCSAPP_DPC'][4]='saveapp';
$__EVENTS['RCSAPP_DPC'][5]='existapp';
$__EVENTS['RCSAPP_DPC'][6]='newapp';
$__EVENTS['RCSAPP_DPC'][7]='installapp';
$__EVENTS['RCSAPP_DPC'][8]='cpdpcshow';
 
$__ACTIONS['RCSAPP_DPC'][0]='cpsapp';
$__ACTIONS['RCSAPP_DPC'][1]='delapp';
$__ACTIONS['RCSAPP_DPC'][2]='insapp';
$__ACTIONS['RCSAPP_DPC'][3]='editapp';
$__ACTIONS['RCSAPP_DPC'][4]='saveapp';
$__ACTIONS['RCSAPP_DPC'][5]='existapp';
$__ACTIONS['RCSAPP_DPC'][6]='newapp';
$__ACTIONS['RCSAPP_DPC'][7]='installapp';
$__ACTIONS['RCSAPP_DPC'][8]='cpdpcshow';

$__DPCATTR['RCSAPP_DPC']['rcsapp'] = 'cpsapp,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSAPP_DPC'][0]='RCSAPP_DPC;Applications;Applications';

class rcsapp  {

    var $title;
	var $carr;
	var $msg;
	var $path, $urlpath;
	var $cdb;
	var $centraldbpath;
	var $syswordsfile;
	var $record;
	var $debug_sql;
	var $error;
	
	function rcsapp() {
	
	  $this->title = localize('RCSAPP_DPC',getlocal());	
	  $this->carr = null;
	  $this->msg = null;		
		
	  $this->path = paramload('SHELL','prpath');
	  $this->urlpath = paramload('SHELL','urlpath');	  
	  $this->syswordsfile = $this->path . 'reservedappwords.txt';  
	  
	  $this->debug_sql = false;//true;
	  $this->error = null;
	}
	
    function event($event=null) {
	
	   if ($event!='existapp') {
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	   }
	   
	   //echo $this->read_applications($_POST['app2copy']);
	   
	   switch ($event) {
		 case 'cpdpcshow' : GetGlobal('controller')->calldpc_method('rcsdpc.read_dpc2show');
		                    //$this->read_dpc2show(); 
							break;	   
	     case 'installapp':
		                 $this->insert_record(); 
		                  $this->error = $this->create_application_files($_POST['type'],$_POST['name'],$this->read_applications($_POST['app2copy']),$_POST['subdir']); 
		                  $this->carr = $this->select_applications('all',null,GetReq('alpha'));
		                  break;	   
	     case 'newapp'  : break;	   
	     case 'existapp': break;
	     case 'editapp' : $this->record = $this->get_record(); break;
	     case 'saveapp' : $this->update_record(); 
		                  $this->carr = $this->select_applications('all',null,GetReq('alpha'));
		                  break;
		 
	     case 'delapp' : $this->delete_application(GetReq('id'),'appname');
		                 $this->carr = $this->select_applications('all',null,GetReq('alpha'));
		                 break;
	     case 'cpsapp' :
		 default       : $this->carr = $this->select_applications('all',null,GetReq('alpha'));//dummy param
	   }
			
    }
  
    function action($action=null) {
	 
	  //if (GetSessionParam('REMOTELOGIN')) 
	    // $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  //else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	 
	  switch ($action) {
	     case 'cpdpcshow' : $out .= GetGlobal('controller')->calldpc_method('rcsdpc.show_dpc');
		                    //$this->show_dpc(); 
							break; 	  
	     case 'newapp' : $out .= $this->insert_form();
		                 break;	  
	     case 'existapp':$out = setNavigator("Application");	
		                 $out .= $this->application_exists(); 
						 break;	  
	     case 'editapp': $out .= $this->update_form(); break;
		 case 'installapp':
	     case 'saveapp':
	     case 'delapp' :
	     case 'cpsapp' : 
		 default       : $out .= $this->error;
		                 $out .= $this->show_applications();
	 }	 
	 
	 return ($out);
    }
	
	function application_exists_form($window=0) {
	
        $filename = seturl("t=existapp",0,1);
	  	  
        $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	    if ($window) $toprint .= "<STRONG>Name:</STRONG>"; 
        $toprint .= "<input type=\"text\" name=\"application\" value=\"\" size=\"16\" maxlength=\"128\">";
		if ($window) $toprint .= "<br>";  		
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Find\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"existapp\">";
        $toprint .= "</FORM>";	
		
		$toprint .= $this->msg;   
		
		if ($window) {
	      $swin = new window("Check name availability",$toprint);
	      $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	      unset ($swin);
		}
		else
		  $out = $toprint;

        return ($out);	
	}
	
	function application_exists() {
	
	  $appname = GetParam('application');

	  if (isset($appname)) {
	    if ($this->appname_exists($appname)) 
	      $ret = writecl("The name [$appname] exist!",'#FFFFFF','#FF0000');
	    else
	      $ret = writecl("The name [$appname] is available!",'#000000','#00FF00');
	  }
	  else
	    $ret = null;//goes to fp //'Parameter required!';
		
	  $this->msg  = $ret;		
		
	  return ($ret);
	}
	
	function insert($recasarray,$expiredays=null,$role=null)  {
      $db = GetGlobal('db'); 	
	  	
	  //$this->reset_db();
	  
	  if (!$role) $role = 'user';
      $message = null;
	  $mydate = date('Y-m-d');
	  //expire = 1 year after = null //days+hours+min+sec
	  if ($expiredays) 
	    $nextYear = time() + ($expiredays * 24 * 60 * 60);
	  else  
	    $nextYear = time() + (1 * 24 * 60 * 60);
			
	  $expire = date('Y-m-d',$nextYear);
	  //echo $recasarray;	  	
	  $data = (array) unserialize($recasarray);
	  //replace <SYN> symbol with +
	  $data[3] = str_replace('<SYN>','+',$data[3]);
	  //print_r($data);
	  
	  if (is_array($data)) {
	   if ($data[0]!="") {	
	    if (!$this->appname_exists($data[1])) {
		
		  //create the basic record without dpc selection
		  $dpcd = array();
		  $dpcd['insdate'] = $mydate;
		  $dpcd['appname'] = strtolower($data[1]);
		  $dpcd['user'] = strtolower($data[1]);		  
		  $dpcd['pwd'] = $data[2];
		  $dpcd['expire']  = $expire;
		  $dpcd['role'] = $role;//'user';//demo,admin
		  //print_r($dpcd);
		  
		  $serialdpcd = serialize($dpcd);
		  //echo $serialdpcd;
		  $ret = GetGlobal('controller')->calldpc_method("rcsdpc.insert use $serialdpcd");
		  
		  if (!$ret) {//no error
				
            $sSQL2 = "insert into applications (
		              insdate, apptype, appname, apppass, timezone, expire";
		    $sSQL2 .= ") values (" . $db->qstr($mydate);	
            foreach ($data as $key => $value) 
		      $sSQL2 .= ", \"" . $value . "\"";
		
		    $sSQL2 .= "," . $db->qstr($expire);
		    $sSQL2 .= ")";
																		
            $db->Execute($sSQL2,1);  	
		    //echo $sSQL2;
		  }
		  else
		    $message = $ret;
	    }
	    else 
	      $message = "Invalid Application Name!";
	   }
	   else 
	     $message = "Invalid Application Type!";	   
	  }
      else 
	    $message = "Invalid User Data!";//.$recasarray;
	  
	  return ($message);  
		
	}
	
	//used to rollback added records in case of successfull user registration
	//but un-successfull application creation....
	function rollback($appname) {
      $db = GetGlobal('db');	
	  
	  $sSQL2 = "delete from applications where appname=" . $db->qstr($appname);
      $res = $db->Execute($sSQL2,1);  	
      //echo $sSQL2;	  
	  
      GetGlobal('controller')->calldpc_method("rcsdpc.rollback use $appname");	  
	}
	
	function appname_exists($param) {
	    $db = GetGlobal('db');
	
	    $sSQL = "select appname from applications where appname='" . $param . "'";
	    $result = $db->Execute($sSQL,2);
		
		//check reserverd words
		$words = explode(",",file_get_contents($this->syswordsfile));
		//print_r($words);		

		if (($result->fields['appname']==$param) || (in_array($param,$words)==true))
		  return true;
		else
		  return false;     
	}
	
	function create_application_files($apptype,$appname,$copyfrom=null,$issubdir=null) {
	
	   $subdir = $issubdir?$issubdir.'/':null;
	   $project_dir2create = $this->path . "instances/". $appname;
	   $public_dir2create = $this->urlpath . "/". $appname; 	
	   
	   /*$param = $this->path."instances/".$appname;
				 
	   if (paramload('SHELL','os')=='WINDOWS')
	     $param2 = str_replace("/","\\",$param);
	   else
		 $param2 = str_replace("\\","/",$param);	   */
	   
	   //echo $file;
	   if ($copyfrom) {//copied app
	  	   $project_dir2copy = $this->path . "instances/". $copyfrom; //echo $project_dir2copy;
		   $public_dir2copy = $this->urlpath . '/' . $copyfrom; //echo '.',$public_dir2copy;
	       
		   if ((is_dir($project_dir2copy)) && (is_dir($public_dir2copy))) {
			  
			  //beware of overwrite!!!!!!!!!!!
		      if (is_dir($project_dir2create)) $ok1=1; else $ok1 = mkdir($project_dir2create);
			  if (is_dir($public_dir2create)) $ok2=1; else $ok2 = mkdir($public_dir2create);
			  
			  if ($ok1 && $ok2) {
			        /* cant copy dirs
					   if (!copy($project_dir2copy, $project_dir2create)) {
                         $ret = "Failed to copy project files...!";
                       }			  
					   
					   if (!copy($public_dir2copy, $public_dir2create)) {
                         $ret = "Failed to copy public files...!";
                       }	
					   */
					 $cout = exec('cp -r '.$project_dir2copy.'/* '.$project_dir2create);   					   
			         $cout.= exec('cp -r '.$public_dir2copy.'/* '.$public_dir2create); 
			  }
			  else
			    $ret = "Error creating directories!";
		   }
	       else
		     $ret = "Invalid source application!";
	   }
	   else {//new confiured app
	   
	       $file = $this->path . "isetup/". $apptype . ".cmd";
	   	   
           if ($fp = @fopen ($file , "r")) {
                 $cmd = fread ($fp, filesize($file));
                 fclose ($fp);
				 
	 	         //echo $cmd;
				 $cmds = explode(";",$cmd);
				 //print_r($cmds);
				 foreach ($cmds as $id=>$command) {
				 
				   if ($command) {
				     //echo system($command);
					 
					 $pc = explode(" ",$command);
				     $pd = $this->path . str_replace("APPNAME",$appname,$pc[1]);
					 
                     if (paramload('SHELL','os')=='WINDOWS')
	                   $dir = str_replace("/","\\",$pd);
	                 else
		               $dir = str_replace("\\","/",$pd);						 
					 
					 if ($pc[0]=='mkdir') {					 
					 					 
					   $ok = mkdir($dir);
					   echo "<br>",$ok,$pc[1],"===<br>";
					 
					   if (!$ok) $ret = "Failed to create application directories!";
					 }
					 elseif ($pc[0]=='copy') {
					   
					   $source = $dir . "myfile.txt";
					   $target = $pc[2] . "myfile.txt";
					   if (!copy($source, $target)) {
                         $ret = "Failed to copy files...!";
                       }
					 }
				   }	 
				 }
          }
          else {
            $ret = "File reading error ($file)!\n";
          }	
	   
	   }//new app	  
		   
	   if (!$ret) {
	     //at instance
	     /*$a = $this->modify_config_file("/$appname/public/action.conf",$appname,null,$copyfrom);
	     if (!$a) $ret = "Configuration can't saved!";
		 $b = $this->modify_config_file("/$appname/public/cp/action.conf",$appname,null,$copyfrom);
	     if (!$b) $ret = "Configuration can't saved!";*/
		 
		 //at public dir
		 //make for each cpDirname... dir
		 //action.conf
		 $a = $this->modify_config_file("/$appname/".$subdir."cp/cpConfig/action.conf",$appname,null,$copyfrom,1);
	     if (!$a) $ret .= "<br>[cp/cpConfig/action.conf]Configuration can't saved!";
		 $b = $this->modify_config_file("/$appname/".$subdir."cp/cpHtml/action.conf",$appname,null,$copyfrom,1);
	     if (!$b) $ret .= "<br>[cp/cpHtml/action.conf]Configuration can't saved!";
		 $b1 = $this->modify_config_file("/$appname/".$subdir."cp/cpScripts/action.conf",$appname,null,$copyfrom,1);
	     if (!$b1) $ret .= "<br>[cp/cpScripts/action.conf]Configuration can't saved!";
		 $b2 = $this->modify_config_file("/$appname/".$subdir."cp/cpTemplates/action.conf",$appname,null,$copyfrom,1);
	     if (!$b2) $ret .= "<br>[cp/cpTemplates/action.conf]Configuration can't saved!";
		 $b3 = $this->modify_config_file("/$appname/".$subdir."cp/cpVisitors/action.conf",$appname,null,$copyfrom,1);
	     if (!$b3) $ret .= "<br>[cp/cpVisitors/action.conf]Configuration can't saved!";	
		 
		 //phpdac
		 $a0 = $this->modify_config_file("/$appname/".$subdir."cp/cpConfig/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$a0) $ret .= "<br>[cp/cpConfig/phpdac5.ini]Configuration can't saved!";
		 $b0 = $this->modify_config_file("/$appname/".$subdir."cp/cpHtml/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$b0) $ret .= "<br>[cp/cpHtml/phpdac5.ini]Configuration can't saved!";
		 $b01 = $this->modify_config_file("/$appname/".$subdir."cp/cpScripts/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$b01) $ret .= "<br>[cp/cpScripts/phpdac5.ini]Configuration can't saved!";
		 $b02 = $this->modify_config_file("/$appname/".$subdir."cp/cpTemplates/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$b02) $ret .= "<br>[cp/cpTemplates/phpdac5.ini]Configuration can't saved!";
		 $b03 = $this->modify_config_file("/$appname/".$subdir."cp/cpVisitors/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$b03) $ret .= "<br>[cp/cpVisitors/phpdac5.ini]Configuration can't saved!";			 	 
		 
		 //action.conf		 		 		 		 
		 $c = $this->modify_config_file("/$appname/".$subdir."cp/action.conf",$appname,null,$copyfrom,1);
	     if (!$c) $ret .= "<br>[cp/action.conf]Configuration can't saved!"; 
         $d = $this->modify_config_file("/$appname/".$subdir."action.conf",$appname,null,$copyfrom,1);
	     if (!$d) $ret .= "<br>[/action.conf]Configuration can't saved!";
		 
		 //phpdac
		 $c0 = $this->modify_config_file("/$appname/".$subdir."cp/phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$c0) $ret .= "<br>[cp/phpdac5.ini]Configuration can't saved!";		
		 $d0 = $this->modify_config_file("/$appname/".$subdir."phpdac5.ini",$appname,null,$copyfrom,1);
	     if (!$d0) $ret .= "<br>[/phpdac5.ini]Configuration can't saved!";				 		 
		 
		 //config.ini
		 $e = $this->modify_config_file("instances/$appname/config.ini",$appname,null,$copyfrom);
	     if (!$e) $ret .= "<br>[config.ini]Configuration can't saved!";		 
	   }
	   	 
	   return ($ret);
	}
	
	private function modify_config_file($file,$appname,$map=null,$copyfrom=null,$ispublicdir=null) {	
	
	    $path = $ispublicdir?$this->urlpath:$this->path;
	    $filepath = $path . $file;
		
		if ($copyfrom) {
		  $out = str_replace($copyfrom,$appname,file_get_contents($filepath));		
		}
		else {
		  if ($map)
		    $preout = str_replace("APPNAME",$map,@file_get_contents($filepath));
		  else
		    $preout = str_replace("APPNAME",$appname,@file_get_contents($filepath));
		
		  $out = str_replace("REALNAME",$appname,$preout);
	    }
		 
        if ($fp = @fopen ($filepath , "w")) {
                   fwrite ($fp, $out);
                   fclose ($fp);
				   			   
				   return (true);
	    }
	    else {	
				   return (false);
		}
	    	  	
	}	
	
	//during remap only old realname must change
	private function update_config_file($file,$appname,$oldname) {
	
	    $filepath = $this->path . $file;	
	
		$out = str_replace($oldname,$appname,file_get_contents($filepath));
	
        if ($fp = @fopen ($filepath , "w")) {
                   fwrite ($fp, $out);
                   fclose ($fp);
				   			   
				   return (true);
	    }
	    else {	
				   return (false);
		}		
	}
	
	private function modify_action_file($file,$appname,$ispublicdir=null) {	
	
	    if ($ispublicdir)
		  $filepath = $this->urlpath . $file;
		else
	      $filepath = $this->path . $file;
		
		$out = str_replace("APPNAME",$appname,file_get_contents($filepath));
	
        if ($fp = @fopen ($filepath , "w")) {
                   fwrite ($fp, $out);
                   fclose ($fp);
				   			   
				   return (true);
	    }
	    else {	
				   return (false);
		}	
	}	
	
	//modify apps.conf in public and copy it to cp
	function add_application_registry($appname,$map=null) {
	
	    //backup registry
		$this->backup_application_registry();	
	
	    if ($map)
		  $pathname = $map;
		else
		  $pathname = $appname;  
		  
		$U_appname  = strtoupper($appname);   
	
	    $filepath = $this->path . 'apps.conf';
		$out = "\r\n[$U_appname]\r\nproject=demosoft/instances/$pathname\r\npath=projects/demosoft/instances/$pathname/\r\nmap=$map\r\n";		
	
        if ($fp = fopen ($filepath , "a+")) {
                   fwrite ($fp, $out);
                   fclose ($fp);
				   
				   //copy it to cp dir
				   if (copy($filepath,$this->urlpath.'/cp/apps.conf'))			   
				     $ret = null;
				   else
				     $ret = "<li>Registry replication failed!";	 
	    }
	    else {	
				   $ret = "<li>Registry not updated!";
		}	
		
		if ($this->modify_config_file("instances/$pathname/config.ini",$appname,$map))
		  $ret .= null;
		else
		  $ret .= "<li>Config file not updated!"; 
		  
		if (($this->modify_action_file("/$pathname/action.conf",$appname,1)) &&
		    ($this->modify_action_file("/$pathname/cp/action.conf",$appname,1)))
		  $ret .= null;
		else
		  $ret .= "<li>Action file not updated!"; 		  
		  
		return ($ret);   
	}
	
	function update_application_registry($appname,$oldname,$map=null) {
	
	    //backup registry
		$this->backup_application_registry();
	
	    //delete old app 
		$this->delete_application_from_registry($oldname);
	
	    //insert new app
	    if ($map)
		  $pathname = $map;
		else
		  $pathname = $appname;  
		  
		$U_appname  = strtoupper($appname);   
	
	    $filepath = $this->path . 'apps.conf';
		$out = "\r\n[$U_appname]\r\nproject=demosoft/instances/$pathname\r\npath=projects/demosoft/instances/$pathname/\r\nmap=$map\r\n";		
	
        if ($fp = fopen ($filepath , "a+")) {
                   fwrite ($fp, $out);
                   fclose ($fp);
				   
				   //copy it to cp dir
				   if (copy($filepath,$this->urlpath.'/cp/apps.conf'))			   
				     $ret = null;
				   else
				     $ret = "<li>Registry replication failed!";	 
	    }
	    else {	
				   $ret = "<li>Registry not updated!";
		}	
		
		if ($this->update_config_file("instances/$pathname/config.ini",$appname,$oldname))
		  $ret .= null;
		else
		  $ret .= "<li>Config file not updated!"; 
		  
		return ($ret);  			
	}
	
	function delete_application_from_registry($appname) {
	
	    $filepath = $this->path . 'apps.conf';
		$apps = @parse_ini_file($filepath,1);
		$aname = strtoupper($appname);
		
		if (array_key_exists($aname,$apps)) {
		
		  foreach ($apps as $name=>$rec) {
		    if ($name!=$aname) {
			  $ret .= "\r\n[$name]\r\n";
			  foreach ($rec as $var=>$val)
			    $ret .= "$var=$val\r\n"; 
			}
		  }
		}
		
		//echo $ret;
	    $filepath = $this->path . 'apps.conf';

        if ($fp = fopen ($filepath , "w")) {
                   fwrite ($fp, $ret);
                   fclose ($fp);
				   $out = null;  
	    }
	    else {	
				   $out = "<li>Registry not updated!";
		}
		
		return ($out);		
	}	
	
	function backup_application_registry() {
	
	    $source = $this->urlpath . '/apps.conf';
		$target = $this->urlpath . '/apps.bak.conf';
		
		if (!copy($source,$target)) {
		  echo "Failed to backup registry!";
		  $out = "<li>backup registry failed!";
		}  
		else
		  $out = null;
		  
	    return ($out);	  
	}	
	
	function select($product=null,$type=null,$expire=null) {
        $db = GetGlobal('db');
	
	    $sSQL2 = "select apptype,appname,timezone from applications where ";
		if ($product) $sSQL2 .= "appname=" . $db->qstr($product);
		if ($type) {
		  if ($product) $sSQL2 .= " and ";
		  $sSQL2 .= "apptype=" . $db->qstr($type);		
		}
		if ($expire) {
		  if (($product) || ($type)) $sSQL2 .= " and ";
		  $sSQL2 .= "expire>" . $db->qstr(date('Y-m-d'));//$db->qstr($expire);		
		}
		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2); 
		foreach ($resultset as $i=>$rec)
		  $ret[] = $rec;
		 			
		return ($ret);
	} 
	
    function reset_db() {
        $db = GetGlobal('db'); 

        //delete table if exist
  	    $sSQL1 = "drop table applications";
        $ret = $db->Execute($sSQL1,1);
		$sSQL2 = "create table applications " .
                    "(id integer auto_increment,
                     insdate DATETIME, 
					 apptype VARCHAR(128),
					 appname VARCHAR(128) primary key,
					 apppass VARCHAR(128),
					 timezone VARCHAR(128),
					 expire DATETIME,
					 UNIQUE (appname)
					 )";																
        $ret = $db->Execute($sSQL2,1);   
		//echo $sSQL2;					
     }	
	
	
	function delete_application($id,$key) {
        $db = GetGlobal('db'); 
		
		$sSQL = "delete from applications where ";
		$sSQL .= $key . "=" . "'" . $id . "'";
		  
        $ret = $db->Execute($sSQL,1); 		  
	    //echo $sSQL;
		
		//delete directories
	  	$project = $this->path . "instances/". $id; //echo $project_dir2copy;
		$public = $this->urlpath . '/' . $id; //echo '.',$public_dir2copy;		
		$cout = exec('rm -r '.$project);   					   
		$cout.= exec('rm -r '.$public); 		
		
		$this->msg = "Application with $key=$id deleted!<br>".$cout;
		//echo $this->msg;
		
        GetGlobal('controller')->calldpc_method('rcsdpc.delete_application_modules use '.GetReq('id'));				
	}
	
	function select_applications($id,$key=null,$letter=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "select id,appname,timezone,expire from applications ";
		
		if ($key) 
		  $sSQL .= " where ". $key . "=" . $db->qstr($id); 
		  
		if ($letter) {
		  if ($key) $sSQL .= " and ";
		       else $sSQL .= " where ";
		  $sSQL .= "(appname like '" . strtolower($letter) . "%' or " .
		            "appname like '" . strtoupper($letter) . "%')";
		}			
		  
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);
		//print_r($resultset);  			 
		foreach ($resultset as $i=>$rec)
		  $ret[] = $rec;
		//print_r($ret);  	  
	    return ($ret);	 		
	}
	
	function show_applications() {
	
	   if ($this->msg) $out = $this->msg; 	
	   
	   $link = seturl('t=newapp','Install new application');
	   $myadd = new window('',$link);
	   $out .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");	   
	   unset ($myadd);  		    
	
	   if ($this->carr) {	
	   
	    $max  = count($this->carr[0])-1;
	    $prc = 96/$max;		
	
	    foreach ($this->carr as $n=>$rec) {
		
		   $viewdata[] = $n+1;
		   $viewattr[] = "right;4%";
		   
           $name = "app_".$n;//$rec[0]; //id is not primary key and not automatically set
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"0\">";
		   $viewattr[] = "left;1%";		
		   
		   $viewdata[] = seturl("t=delapp&id=".$rec[1],"X");
		   $viewattr[] = "left;4%";		      
		   
		   $viewdata[] = ($rec[1]? seturl("t=cpdpcshow&id=".$rec[1],$rec[1]) : "&nbsp;");
		   $viewattr[] = "left;30%";		   
		   
		   $viewdata[] = ($rec[2]?$rec[2]:"&nbsp;");
		   $viewattr[] = "left;45%";	
		   
		   $viewdata[] = ($rec[3]? seturl("t=editapp&id=".$rec[1],$rec[3]):"&nbsp;");
		   $viewattr[] = "left;20%";			   
		   	   	   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}			
	   }
	   else
	     $toprint .= "No applications !<br>";//$this->bulkiform();
		 
	   $toprint .= $this->alphabetical();		 
		 
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
	  
	   return ($out);			
	}	
	
	function alphabetical($command='cpsapp') {
	
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
	
	function is_not_expired($appname) {
        $db = GetGlobal('db');
	    $mname = $this->mapped_as($appname);
		//echo $mname,'>>>';
	
	    $sSQL2 = "select expire from applications where ";
		$sSQL2 .= "appname=" . $db->qstr($mname);

		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 			  
		//print_r($ret);
		
		$expiration = $resultset->fields[0];
		$today = date('Y-m-d');
		echo $expiration,' ',$today,' ',$resultset->fields[0];
		
		//5.3.0
		//$d = new DateTime($today);		
        //$time_span = $d->diff(new DateTime($expiration));
        //var_dump($time_span);

		$result = $this->date_diff($today,$expiration);
		
		echo '|',$result,'>';
		
		if ($result<0)
		  return null;//expired
		else 
		  return "$result";//days " for 0"	  
	}
	
	function application_expire_at($appname,$expire) {
        $db = GetGlobal('db');	
	    $mname = $this->mapped_as($appname);
				
	    $sSQL2 = "update applications set ";	
		$sSQL2 .= "expire=" . $db->qstr($expire);	
		$sSQL2 .= " where appname='" . $mname . "'";
        $result = $db->Execute($sSQL2,1);		
		//echo $sSQL2;		
		
	    if (($this->debug_sql) && ($f = fopen($this->path."log/application.sql",'w+'))) {
	     fwrite($f,$sSQL2,strlen($sSQL2));
		 fclose($f);
	    }					
		
		GetGlobal('controller')->calldpc_method('rcsdpc.modules_expires_at use '. $mname .'+'.$expire);
			
	}
	
	function get_application_type($appname) {
        $db = GetGlobal('db');
	    $mname = $this->mapped_as($appname);
	    
	    $sSQL2 = "select apptype from applications where ";
		$sSQL2 .= "appname=" . $db->qstr($mname);

		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 			  
		//print_r($ret);
		
		return ($resultset->fields['apptype']);		   
	}
	
	function get_record($appname=null) {
        $db = GetGlobal('db');	
	    
		if (!$appname)
		  $appname = GetReq('id');//called by the list  
	
	    $sSQL2 = "select appname,apptype,apppass,timezone,expire from applications where ";
		$sSQL2 .= "appname=" . $db->qstr($appname);

		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 	
		
		$res[] = $resultset->fields['appname'];
		$res[] = $resultset->fields['apptype'];
		$res[] = $resultset->fields['apppass'];
		$res[] = $resultset->fields['timezone'];
		$res[] = $resultset->fields['expire'];
		
		return ($res);								
	}
	
	function insert_record() {
        $db = GetGlobal('db');    
		
	    $sSQL2 = "insert into applications (insdate,apptype,appname,apppass,timezone,expire) values (";
		$sSQL2 .= $db->qstr(date('Y-m-d')) .  "," . $db->qstr(GetParam('type')) . ",";
		$sSQL2 .= $db->qstr(GetParam('name')) . "," . $db->qstr(GetParam('pass')) . ",";
		$sSQL2 .= $db->qstr(get_selected_option_fromfile(GetParam('zone'),'timezones')) . ",";
		$sSQL2 .= $db->qstr(GetParam('expire')) . ")";				

        $ret = $db->Execute($sSQL2,1);		
		//echo $sSQL2;
		//echo GetParam('name')                        
        GetGlobal('controller')->calldpc_method('rcsdpc.initialize_application_modules use '. GetParam('name').'+'.GetParam('expire').'+'.GetParam('name').'+'.GetParam('pass').'+'.GetParam('type'));		
		//GetGlobal('controller')->calldpc_method('rcsdpc.modules_expires_at use '. GetParam('name').'+'.GetParam('expire'));
		
		return ($ret);
	}	
	
	function update_record() {
        $db = GetGlobal('db'); 
		
	    $sSQL2 = "update applications set";
		$sSQL2 .= " apppass=" . $db->qstr(GetParam('pass'));
		$sSQL2 .= ",timezone=" . $db->qstr(get_selected_option_fromfile(GetParam('zone'),'timezones'));//GetParam('zone'));
		$sSQL2 .= ",expire=" . $db->qstr(GetParam('expire'));				
		$sSQL2 .= " where appname='" . GetParam('name') . "'";
        $db->Execute($sSQL2,1);		
		//echo $sSQL2;
		//echo GetParam('name');
		GetGlobal('controller')->calldpc_method('rcsdpc.modules_expires_at use '. GetParam('name').'+'.GetParam('expire'));
	}
	
	function insert_form() {
	
	   //print_r($this->record);
	   //if (is_array($this->record)) {
         $myaction = seturl("t=saveapp");	
		 
		 //create opt file
		 $apps = $this->read_applications();
         //$mynewpfiles[] = '---Applications---;----Applications----';           
         foreach ($apps as $i=>$rec) {         
            $mynewpfiles[] = $rec[1].';'.$rec[1];
		 }   
         //$this->write2file('task_app_list.opt',implode(',',$mynewpfiles));				 
		 $optfile = file_put_contents($this->path.'applist.opt',implode(',',$mynewpfiles));		 
	

	     $form = new form(localize('RCSAPP_DPC',getlocal()), "RCSAPP", FORM_METHOD_POST, $myaction, true);
		 
         $form->addGroup  ("x",	"Please enter application details.");	
         $form->addGroup  ("y",	"Please enter configuration details.");		 
		 	 
         $form->addElement("x", new form_element_text("Name",  "name",		"",			"forminput",			50,				50,	0));		   		     
         $form->addElement("x", new form_element_text("Type",  "type",		"",			"forminput",			50,				10,	0));		   		 
         $form->addElement("x", new form_element_text("Password",  "pass",	"",			"forminput",			50,				10,	0));			 
         //$form->addElement("x", new form_element_text("Timezone",  "zone",		$this->record[3],			"forminput",			50,				10,	0));			 
		 //$selectedtz = get_selected_option_fromfile($this->record[3],'timezones');
	     $form->addElement("x",	new form_element_combo_file (localize('TimeZone',getlocal()),     "zone",	    "",				"forminput",	        5,				0,	'timezones'));			 
         $form->addElement("x", new form_element_text("Expire",  "expire",		"",			"forminput",			20,				10,	0));			 
		 
	     $form->addElement("y",	new form_element_combo_file ("Application to copy from",     "app2copy",	    '',				"forminput",	        5,				0,	'applist'));			 
         $form->addElement("y", new form_element_text("Directory name (instead of name)",  "maindir",		"",			"forminput",			50,				50,	0));		 
         $form->addElement("y", new form_element_text("Host in path",  "subdir",		"",			"forminput",			50,				50,	0));			 			 
         $form->addElement("y", new form_element_text("Domain name (mysite.net)",  "domainname",		"",			"forminput",			50,				50,	0));			 			 		 
         $form->addElement("y", new form_element_text("Multilingual",  "multilan",		"",			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement("y", new form_element_text("Default languange",  "dlan",		"",			"forminput",			50,				50,	0));			 			 		 		 
		 $form->addElement("y", new form_element_text("Db user",  "dbuser",		"",			"forminput",			50,				50,	0));			 			 		 
		 $form->addElement("y", new form_element_text("Db password",  "dbpass",		"",			"forminput",			50,				50,	0));			 			 		 			 		 
         $form->addElement("y", new form_element_text("e-mail",  "mail",		"",			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement("y", new form_element_text("sms",  "sms",		"",			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "installapp"));	   	   
	   
	     $ret .= $form->getform ();			

	   /*}
	   else	
	     $ret = "Invalid record!";*/
			
       $mywin = new window("Install Application",$ret);
       $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
				
		
	   return ($out);
	}	
	
	function update_form() {
	
	   //print_r($this->record);
	   if (is_array($this->record)) {
         $myaction = seturl("t=saveapp");	
	

	     $form = new form(localize('RCSAPP_DPC',getlocal()), "RCSAPP", FORM_METHOD_POST, $myaction, true);
		 
         $form->addGroup  ("x",	"Please enter application details.");		 
         //$form->addGroup  ("y",	"Please enter configuration details.");		 
         $form->addElement("x", new form_element_text("Name",  "name",		$this->record[0],			"forminput",			50,				50,	1));		   		     
         $form->addElement("x", new form_element_text("Type",  "type",		$this->record[1],			"forminput",			50,				10,	1));		   		 
         $form->addElement("x", new form_element_text("Password",  "pass",		$this->record[2],			"forminput",			50,				10,	0));			 
         //$form->addElement("x", new form_element_text("Timezone",  "zone",		$this->record[3],			"forminput",			50,				10,	0));			 
		 $selectedtz = get_selected_option_fromfile($this->record[3],'timezones');
	     $form->addElement("x",	new form_element_combo_file (localize('TimeZone',getlocal()),     "zone",	    $this->record[3],				"forminput",	        5,				0,	'timezones'));			 
         $form->addElement("x", new form_element_text("Expire",  "expire",		$this->record[4],			"forminput",			20,				10,	0));			 
		 		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "saveapp"));	   	   
	   
	     $ret .= $form->getform ();			

	   }
	   else	
	     $ret = "Invalid record!";
			
       $mywin = new window("Update Application",$ret);
       $out .= $mywin->render("center::90%::0::group_win_body::center::0::0::");		 
				
		
	   return ($out);
	}
	
	function mapped_as($name) {
	
	  /* $ret = GetGlobal('controller')->calldpc_method('rcsappmap.find_app_name use '.$name);
	   
	   if (!$ret)*/
	     return ($name);//is not mapped app..return input name
		 
	  // return ($ret);
	}
	
	//used by renew at the end of procedure
	function get_application($appname) {
	
	   $data = array();
	
	   //echo $appname ,'>';
	   if ($appname) {
	     //$realappname = $this->mapped_as($appname); /...is the opposite
	     /*$realappname = GetGlobal('controller')->calldpc_method('rcsappmap.find_map_name use '.$appname);
		 if (!$realmapnname) 
		   $realmapname = $appname; //not mapped*/ //NO NEED FOR APPLICATION TABLE
		 
		 //echo $realappname,'>';
		 
		 $data = $this->get_record($appname);
		 //print_r($data);
		 
	   }
	   //print_r($data);
	   return ($data);
	}
	
	function read_applications($id2find=null,$key=null,$value=null,$letter=null) {
        $db = GetGlobal('db');
		
		$sSQL = "select id,appname,timezone,expire from applications ";
		
		if ($key) 
		  $sSQL .= " where ". $key . "=" . $db->qstr($value); 
		  
		if ($letter) {
		  if ($key) $sSQL .= " and ";
		       else $sSQL .= " where ";
		  $sSQL .= "(appname like '" . strtolower($letter) . "%' or " .
		            "appname like '" . strtoupper($letter) . "%')";
		}			
		  
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);  			 	
				
		if ($id2find) {
		  //$appname = $resultset->fields[$id2find][1];
		  foreach ($resultset as $i=>$rec)
		    if ($rec[0]==$id2find) {
		       return ($rec[1]);
			}  
		}
		else {		  
		  foreach ($resultset as $i=>$rec)
		    $ret[] = $rec;
					
		  return ($ret);
		}  
		//print_r($ret);
		
    }
	
	//date format 2010-01-01
function date_diff($start_date, $end_date, $returntype="d") {
   if ($returntype == "s")
       $calc = 1;
   if ($returntype == "m")
       $calc = 60;
   if ($returntype == "h")
       $calc = (60*60);
   if ($returntype == "d")
       $calc = (60*60*24);   
	   
   //echo $start_date,'-',$end_date;	   
       
   $_d1 = explode(" ", $start_date);
   $_d11 = explode('-',$_d1[0]); //print_r($_d11);
   $_d12 = explode(':',$_d1[1]); //print_r($_d12);  
   $d1 = $_d11[2]; //echo '<br>',$d1;
   $m1 = $_d11[1];//echo '<br>',$m1;
   $y1 = $_d11[0];//echo '<br>',$y1;
   $h1 = $_d12[0]?$_d12[0]:0;
   $n1 = $_d12[1]?$_d12[1]:0;
   $s1 = $_d12[2]?$_d12[2]:0; 
   //echo $h1,':',$n1,':',$s1,'<br>'; 
   
   $_d2 = explode(" ", $end_date);
   $_d21 = explode('-',$_d2[0]);
   $_d22 = explode(':',$_d2[1]);   
   $d2 = $_d21[2];//echo '<br>',$d2;
   $m2 = $_d21[1];//echo '<br>',$m2;
   $y2 = $_d21[0];//echo '<br>',$y2;
   $h2 = $_d22[0]?$_d22[0]:0;
   $n2 = $_d22[1]?$_d22[1]:0;
   $s2 = $_d22[2]?$_d22[2]:0;
   //echo $h2,':',$n2,':',$s2,'<br>'; 
   
  
   if (($y1 < 1970 || $y1 > 2037) || ($y2 < 1970 || $y2 > 2037)) {
       return 0;
   } 
   else {
       $start_date_stamp    = mktime($h1,$n1,$s1,$m1,$d1,$y1); 
//echo $start_date_stamp,'<br>';
       $end_date_stamp    = mktime($h2,$n2,$s2,$m2,$d2,$y2);
//echo $end_date_stamp,'<br>';
	   
	   
       $difference = round(($end_date_stamp-$start_date_stamp)/$calc);
	   //echo $difference,"LLLLLLLL<br>";
		  
	   return $difference;  
   }
}	
	
	function create_app_database($name) {
	   $db = GetGlobal('db');
	
	   $sSQL = "
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ctgid` int(10) NOT NULL DEFAULT '0',
  `cat1` varchar(128) DEFAULT NULL,
  `cat2` varchar(128) DEFAULT NULL,
  `cat3` varchar(128) DEFAULT NULL,
  `cat4` varchar(128) DEFAULT NULL,
  `cat5` varchar(128) DEFAULT NULL,
  `cat01` varchar(128) DEFAULT NULL,
  `cat02` varchar(128) DEFAULT NULL,
  `cat03` varchar(128) DEFAULT NULL,
  `cat04` varchar(128) DEFAULT NULL,
  `cat05` varchar(128) DEFAULT NULL,
  `cat11` varchar(128) DEFAULT NULL,
  `cat12` varchar(128) DEFAULT NULL,
  `cat13` varchar(128) DEFAULT NULL,
  `cat14` varchar(128) DEFAULT NULL,
  `cat15` varchar(128) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `view` tinyint(1) DEFAULT '1',
  `search` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cat1` (`cat1`,`cat2`,`cat3`,`cat4`,`cat5`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='categories table';

CREATE TABLE IF NOT EXISTS `custaddress` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ccode` varchar(128) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `address` varchar(254) DEFAULT NULL,
  `area` varchar(254) DEFAULT NULL,
  `zip` varchar(64) DEFAULT NULL,
  `voice1` varchar(64) DEFAULT NULL,
  `voice2` varchar(64) DEFAULT NULL,
  `fax` varchar(64) DEFAULT NULL,
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ccode` (`ccode`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek;

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` smallint(6) DEFAULT '0',
  `code1` varchar(128) DEFAULT NULL,
  `code2` varchar(60) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `afm` varchar(64) DEFAULT NULL,
  `eforia` varchar(64) DEFAULT NULL,
  `prfid` int(11) DEFAULT NULL,
  `prfdescr` varchar(128) DEFAULT NULL,
  `street` varchar(128) DEFAULT NULL,
  `address` text,
  `number` varchar(64) DEFAULT NULL,
  `area` varchar(64) DEFAULT NULL,
  `zip` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `voice1` varchar(64) DEFAULT NULL,
  `voice2` varchar(64) DEFAULT NULL,
  `fax` varchar(64) DEFAULT NULL,
  `mail` varchar(64) DEFAULT NULL,
  `attr1` varchar(128) DEFAULT NULL,
  `attr2` varchar(128) DEFAULT NULL,
  `attr3` varchar(128) DEFAULT NULL,
  `attr4` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code1` (`code1`,`code2`),
  KEY `name` (`name`),
  KEY `code2` (`code2`),
  FULLTEXT KEY `attr1` (`attr1`,`attr2`,`attr3`,`attr4`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='customers table';


CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member` varchar(20) DEFAULT NULL,
  `headline` varchar(55) DEFAULT NULL,
  `body` text,
  `date_posted` datetime DEFAULT NULL,
  `views` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek COMMENT='forum posts';


CREATE TABLE IF NOT EXISTS `forum_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member` varchar(20) NOT NULL DEFAULT '',
  `headline` varchar(55) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek COMMENT='forum replies';


CREATE TABLE IF NOT EXISTS `mailqueue` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeout` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) DEFAULT '1',
  `sender` varchar(256) NOT NULL,
  `receiver` varchar(256) NOT NULL,
  `subject` varchar(256) DEFAULT NULL,
  `body` text,
  `altbody` text,
  `cc` text,
  `bcc` text,
  `ishtml` tinyint(1) DEFAULT NULL,
  `origin` text,
  `attachments` text,
  `user` varchar(256) DEFAULT NULL,
  `pass` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `server` varchar(256) DEFAULT NULL,
  `encoding` varchar(64) DEFAULT NULL,
  `reply` int(11) unsigned DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `mailstatus` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='bulk mail queue';


CREATE TABLE IF NOT EXISTS `paypal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mc_gross` varchar(64) DEFAULT NULL,
  `addres_status` varchar(64) DEFAULT NULL,
  `payer_id` varchar(64) DEFAULT NULL,
  `tax` varchar(64) DEFAULT NULL,
  `address_street` varchar(64) DEFAULT NULL,
  `payment_date` varchar(64) DEFAULT NULL,
  `payment_status` varchar(64) DEFAULT NULL,
  `charset` varchar(64) DEFAULT NULL,
  `address_zip` varchar(64) DEFAULT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `mc_fee` varchar(64) DEFAULT NULL,
  `address_country_code` varchar(64) DEFAULT NULL,
  `address_name` varchar(64) DEFAULT NULL,
  `notify_version` varchar(64) DEFAULT NULL,
  `custom` varchar(64) DEFAULT NULL,
  `payer_status` varchar(64) DEFAULT NULL,
  `business` varchar(64) DEFAULT NULL,
  `address_country` varchar(64) DEFAULT NULL,
  `address_city` varchar(64) DEFAULT NULL,
  `quantity` varchar(64) DEFAULT NULL,
  `verify_sign` varchar(64) DEFAULT NULL,
  `payer_email` varchar(64) DEFAULT NULL,
  `txn_id` varchar(64) DEFAULT NULL,
  `payment_type` varchar(64) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `address_state` varchar(64) DEFAULT NULL,
  `receiver_email` varchar(64) DEFAULT NULL,
  `receiver_id` varchar(64) DEFAULT NULL,
  `txn_type` varchar(64) DEFAULT NULL,
  `item_name` varchar(64) DEFAULT NULL,
  `mc_currency` varchar(64) DEFAULT NULL,
  `item_number` varchar(64) DEFAULT NULL,
  `test_ipn` varchar(64) DEFAULT NULL,
  `payment_gross` varchar(64) DEFAULT NULL,
  `shipping` varchar(64) DEFAULT NULL,
  `payment_fee` varchar(64) DEFAULT NULL,
  `receipt_id` varchar(64) DEFAULT NULL,
  `payer_business_name` varchar(64) DEFAULT NULL,
  `residence_country` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek COMMENT='paypal transactions';


CREATE TABLE IF NOT EXISTS `ppolicy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code1` varchar(128) DEFAULT NULL,
  `code2` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='customers price policy';

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code1` int(11) DEFAULT NULL,
  `code2` int(11) DEFAULT NULL,
  `code3` varchar(64) DEFAULT NULL,
  `code4` varchar(64) DEFAULT NULL,
  `code5` varchar(64) DEFAULT NULL,
  `itmname` varchar(128) DEFAULT NULL,
  `itmactive` tinyint(4) DEFAULT NULL,
  `itmfname` varchar(128) DEFAULT NULL,
  `itmremark` text,
  `itmdescr` text,
  `itmfdescr` text,
  `sysins` datetime DEFAULT '0000-00-00 00:00:00',
  `sysupd` datetime DEFAULT NULL,
  `uniida` int(11) DEFAULT '0',
  `uniname1` varchar(64) DEFAULT NULL,
  `uniname2` varchar(64) DEFAULT NULL,
  `uni1uni2` float DEFAULT '0',
  `uni2uni1` float DEFAULT '0',
  `ypoloipo1` float DEFAULT '0',
  `ypoloipo2` float DEFAULT '0',
  `price0` float DEFAULT '0',
  `price1` float DEFAULT '0',
  `cat0` varchar(128) DEFAULT NULL,
  `cat1` varchar(128) DEFAULT NULL,
  `cat2` varchar(128) DEFAULT NULL,
  `cat3` varchar(128) DEFAULT NULL,
  `cat4` varchar(128) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `price2` float DEFAULT '0',
  `pricepc` float DEFAULT '0',
  `p1` text,
  `p2` text,
  `p3` text,
  `p4` text,
  `p5` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code1` (`code1`,`code2`,`code3`,`code4`,`code5`),
  KEY `itmname` (`itmname`,`cat0`,`cat1`,`cat2`,`cat3`,`cat4`),
  FULLTEXT KEY `itmremark` (`itmremark`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='products table';

CREATE TABLE IF NOT EXISTS `stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `tid` varchar(64) DEFAULT NULL,
  `attr1` varchar(254) DEFAULT NULL,
  `attr2` varchar(254) DEFAULT NULL,
  `attr3` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='item statistics';

CREATE TABLE IF NOT EXISTS `syncsql` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date` datetime DEFAULT NULL,
  `execdate` datetime DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `sqlquery` text CHARACTER SET greek,
  `sqlres` text CHARACTER SET greek,
  `reference` varchar(255) CHARACTER SET greek DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='synchronize sql tables';


CREATE TABLE IF NOT EXISTS `transactions` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(64) NOT NULL DEFAULT '',
  `cid` int(11) NOT NULL DEFAULT '0',
  `tdate` date NOT NULL DEFAULT '0000-00-00',
  `ttime` time NOT NULL DEFAULT '00:00:00',
  `tdata` text NOT NULL,
  `tstatus` smallint(6) NOT NULL DEFAULT '0',
  `type1` varchar(256) NOT NULL,
  `type2` varchar(256) NOT NULL,
  `payway` varchar(64) DEFAULT NULL,
  `roadway` varchar(64) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `costpt` float DEFAULT NULL,
  PRIMARY KEY (`recid`),
  UNIQUE KEY `tid` (`tid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='transaction table';

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code1` varchar(60) DEFAULT NULL,
  `code2` varchar(60) DEFAULT NULL,
  `ageid` tinyint(4) DEFAULT NULL,
  `clogon` varchar(128) DEFAULT NULL,
  `cntryid` int(11) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `fname` varchar(128) DEFAULT NULL,
  `genid` tinyint(4) DEFAULT NULL,
  `ipins` int(11) DEFAULT NULL,
  `ipupd` int(11) DEFAULT NULL,
  `lanid` int(11) DEFAULT NULL,
  `lastlogon` datetime DEFAULT NULL,
  `lname` varchar(128) DEFAULT NULL,
  `notes` text,
  `seclevid` tinyint(4) DEFAULT NULL,
  `secparam` varchar(128) DEFAULT NULL,
  `sesid` text,
  `sesdata` text,
  `startdate` datetime DEFAULT NULL,
  `subscribe` tinyint(4) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `vpass` varchar(64) DEFAULT NULL,
  `timezone` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code1` (`code1`,`code2`,`email`,`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='customers table';

CREATE TABLE IF NOT EXISTS `wfbase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `fdescr` varchar(256) DEFAULT NULL,
  `owner` varchar(128) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `intime` datetime DEFAULT NULL,
  `outtime` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `recursive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek;

CREATE TABLE IF NOT EXISTS `wfexec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `user` varchar(256) DEFAULT NULL,
  `fuser` varchar(256) DEFAULT NULL,
  `tuser` varchar(256) DEFAULT NULL,
  `intime` datetime DEFAULT NULL,
  `outtime` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `flowindex` int(11) DEFAULT NULL,
  `params` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek;

CREATE TABLE IF NOT EXISTS `wfschema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `fdescr` varchar(256) DEFAULT NULL,
  `step` int(11) NOT NULL,
  `user` varchar(256) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `intime` datetime DEFAULT NULL,
  `outtime` datetime DEFAULT NULL,
  `permisions` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=greek;
";

        $result = $db->Execute($sSQL,1);  
		   
	    return ($result); 
	}			
  
};
}
?>