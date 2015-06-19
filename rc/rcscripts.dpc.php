<?php

$__DPCSEC['RCSCRIPTS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSCRIPTS_DPC")) && (seclevel('RCSCRIPTS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSCRIPTS_DPC",true);

$__DPC['RCSCRIPTS_DPC'] = 'rcscripts';
 
$__EVENTS['RCSCRIPTS_DPC'][0]='cpscripts';
$__EVENTS['RCSCRIPTS_DPC'][1]='cpsavescript';
$__EVENTS['RCSCRIPTS_DPC'][2]='cppostscript';
$__EVENTS['RCSCRIPTS_DPC'][3]='cpusescript';
$__EVENTS['RCSCRIPTS_DPC'][4]='cpaddcodescript';
$__EVENTS['RCSCRIPTS_DPC'][5]='cpeditscript';
$__EVENTS['RCSCRIPTS_DPC'][6]='cpnewdpclass';
$__EVENTS['RCSCRIPTS_DPC'][7]='cpcheckclass';

$__ACTIONS['RCSCRIPTS_DPC'][0]='cpscripts';
$__ACTIONS['RCSCRIPTS_DPC'][1]='cpsavescript';
$__ACTIONS['RCSCRIPTS_DPC'][2]='cppostscript';
$__ACTIONS['RCSCRIPTS_DPC'][3]='cpusescript';
$__ACTIONS['RCSCRIPTS_DPC'][4]='cpaddcodescript';
$__ACTIONS['RCSCRIPTS_DPC'][5]='cpeditscript';
$__ACTIONS['RCSCRIPTS_DPC'][6]='cpnewdpclass';
$__ACTIONS['RCSCRIPTS_DPC'][7]='cpcheckclass';

$__DPCATTR['RCSCRIPTS_DPC']['cpscripts'] = 'cpscripts,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSCRIPTS_DPC'][0]='RCSCRIPTS_DPC;Edit Scripts;Edit Scripts';

class rcscripts {

	var $post;
	var $script_path;
	var $scriptfiles;
	var $path;
	var $standalone;
	var $infolder, $urlpath;
	var $encoding;
	var $check_class;
		
	function rcscripts() {
		
	    $this->title = localize('RCSCRIPTS_DPC',getlocal());		
		$this->post = false; //hold successfull posting	
		
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
	    if (($charset=='utf-8') || ($charset=='utf8'))
	      $this->encoding = 'utf-8';
	    else  
	      $this->encoding = $char_set[getlocal()]; 			
		
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) {
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";		  		  
		}  
		else {
		  $this->path = paramload('SHELL','prpath');	
		  $this->standalone = true;	  
		} 

	    $this->infolder = paramload('ID','hostinpath');	    
	    $this->urlpath = paramload('SHELL','urlpath').$this->infolder.'/';		
		
        $this->script_path = $this->path . paramload('RCSCRIPTS','path') . "/";		
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
       $sFormErr = GetGlobal('sFormErr');	    	    		  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	
		case "cpcheckclass" :    $this->check_class = $this->class_instance();
		                         $this->check_class->event();
		                         break;
								 
		case "cpnewdpclass" :    $myscript = $this->new_phpdpc_file(GetReq('script'));
		                         $this->html_javascript_editor($myscript);
		                         break;
								 
		case "cpeditscript" :    $this->html_javascript_editor();
		                         break;
								 
		case "cpaddcodescript" : $this->add_script();
		                         break;	
								 
		case "cpusescript"  :    $this->add_active_script();
		                         break;	  	   
	   
		case "cppostscript":     $this->postscript();
		                         break;	   
	   
		case "cpsavescript":     $this->savescript();
		                         $this->post = true;
								 $this->html_javascript_editor();
		                         break;
								 
		case "cpscripts"   :     $this->scriptfiles = $this->read_directory($this->script_path); 
		                         $this->post = false;
		                         break;
		default            :						 
       }
      }   
    }
  
    function action($action) {
	 
	   switch ($action) {	
	   	case "cpcheckclass":    $class_out = $this->check_class->action(); 
		                        $out = $class_out?$class_out:'NULL';
		                        break;	   
	   	case "cpnewdpclass":    break;
		case "cpeditscript":    break;	   
		case "cpaddcodescript": break;		   
		case "cpusescript"  :   break;	
		case "cppostscript":    break;	   
	   
		case "cpsavescript":     
								 
		case "cpscripts"   :     
		default            :	 $out = $this->scriptsform();					 
       }	 
	 
	 return ($out);
    } 
	  
  
  function scriptsform() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=cpsavescript");
	 
	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	 	 
	 if ($this->post==true) {   
	   
	   (isset($this->msg)? $msg=$this->msg : $msg = "Data submited!");
	   
	   $swin = new window("Post",$msg);
	   $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	   unset ($swin);
	   
	 }
	 else { //show the form plus error if any
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   if ($this->standalone)   
	     $out .= $this->show_directory("Load");		   
	   
	   $file = getReq('id');
	   
	   $form = new form(localize('_RCSCRIPTS',getlocal()), "RCSCRIPTS", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("title",			"Title.");       	   
	   $form->addGroup			("body",			"Body.");	

       $form->addElement		("title", new form_element_text("Title",  "title",		$file,				"forminput",			90,				255,	0));	

	   $form->addElement		("body",new form_element_textarea($file,  "nobody",$this->loadfromfile($file),	"formtextarea",	80,	20));
	   
	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsavescript"));
 
	   // Showing the form
	   $fout = $form->getform ();		
	   
	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();	
	   //unset ($fwin);	
	   
	   $out .= $fout;

	   //$form->checkform();	
	   //if ($this->standalone)   
	     //$out .= $this->show_directory();	   
	 }
 
     return ($out);
  }  
  
  function savescript() {
  
    //$this->write2file(getParam('title'),getParam('body'));
	
	if ($myfile = GetParam('file')) {
	  //echo $myfile,GetParam('dpccode');
	  $this->write2file($myfile,GetParam('dpccode'));
	}
	
  }
  
  function loadfromfile($filename) {
	 
	 $file = $this->urlpath.$this->infolder.'/'.$filename;

     if ($fp = @fopen ($file , "r")) {

                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {
         $this->msg = $ret = "File reading error ($filename)!\n";
		 //echo "File reading error ($filename)!<br>";
     }
	 
	 return ($ret);
  }

  
  function write2file($filename,$data=null,$usepath=null) {
	 if ($usepath)
	   $file = $this->urlpath.$this->infolder.'/'.$filename;
	 else  
	   $file = $filename; //path included
	 
     if ($fp = @fopen ($file , "w")) {
	    //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error ($filename)!\n";
		 //echo "File creation error ($filename)!<br>";
     }	
  }
  
  function read_directory($dirname) {
  
     if (defined('RCFS_DPC')) {
	 
	    $extensions = array(0=>'.prj');
	 
	    $this->fs= new rcfs($dirname);
		$ddir = $this->fs->read_directory($dirname,$extensions); 
	 }
     else {   

	    if (is_dir($dirname)) {
          $mydir = dir($dirname);
		 
          while ($fileread = $mydir->read ()) {
	   
           //read directories
		   if (($fileread!='.') && ($fileread!='..'))  {

			        if ((stristr($fileread,".prj")) &&
				        ($fileread{0}!='c') && //no control panel files
					    ($fileread{1}!='p')) {			   
					  //$parts = explode("-",$fileread);
		              $ddir[] = $fileread;						
					}
		   } 
	      }
	      $mydir->close ();
        }
	  }	
	  return ($ddir);
  }
  
  function show_directory($combo=null)  {
  
     if (defined('RCFS_DPC')) {

		$ret = $this->fs->show_directory($this->scriptfiles,"t=cpscripts&id=","Script files"); 
	 }
     elseif ($combo){
		$myaction = seturl("t=cpscripts");	 
	    $ret = "<form method=\"post\" name=\"RCSCRIPTS\" action=\"$myaction\">";	 
		$ret .= "<select name=\"id\">"; 
	 
        foreach ($this->scriptfiles as $id=>$name) {
		  $parts = explode(".",$name);
		  $title = $parts[0];
          $ret .= "<option value=\"$name\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		}	
		
		$ret .= "</select>";	
        $ret .= "<input type=submit value=\"$combo\">";
	   
	    $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpscripts\">";   
        $ret .= "</form>";  			    
	 }	 
     else {  
      if (is_array($this->scriptfiles)) {
        foreach ($this->scriptfiles as $id=>$name) {
	
	      $ret .= seturl("t=cpscripts&id=".$name,$name) ."<br>"; 
	    }
	  }
	}
	return ($ret);
  }
  
  function use_script($sname=null,$copyto=null,$sourcefrom=null) {
    $copyto = $copyto ? $this->urlpath . $copyto : $this->urlpath . 'cgi-bin';
	$sourcefrom = $sourcefrom ? $this->urlpath . $sourcefrom : $this->urlpath . 'cp/dpc';
	$ret = false;
    //echo $sourcefrom ,'/','>',$copyto , '/';
	
    if ($sname) {
	  $p = explode('.',$sname);
	  if (is_dir($copyto)) {
	    if (!is_dir($copyto . '/' . $p[0]))
	      $makedir = mkdir($copyto . '/' . $p[0], '0744'); //0755
	  }
	  
	  if (is_dir($copyto . '/' .$p[0])) {
	    $scriptname = str_replace('.','/',$sname) . '.dpc.php';
		//echo $scriptname;
		
        if (is_readable($sourcefrom .'/' . $scriptname)) {
		  //echo '+++',$sourcefrom ,'/' , $scriptname,'>',$copyto , '/' , $scriptname;
	      //not overwrite
		  if (!is_readable($copyto .'/' . $scriptname)) 
	        $ret = copy($sourcefrom .'/' . $scriptname, $copyto . '/' . $scriptname);
	  
	      return $ret; 
        } 	  
	  }
	  //else
	    //echo $sourcefrom ,'/' , $scriptname,'>',$copyto , '/' , $scriptname;
	}
	
	return false;
  }
  
  //all copied in private area
  function show_used_scripts($combo=null,$taction=null,$script_path=null,$editmode=null,$inframe=null,$retarray=null) {
	   
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cpscripts';
	   //echo $myact,'>';
	   
       if (defined('RCFS_DPC')) {
        $path = $script_path ? $this->urlpath.$this->infolder.'/' . $script_path : $this->urlpath.$this->infolder.'/cgi-bin';	
	    $extensions = array(0=>".dpc.php");
	    //echo '<br><br>',$path;
		if (is_dir($path)) {
		
		  $fs = new rcfs($path);
		  $ddir = $fs->read_directory($path,$extensions,1);
		  //print_r($fs->dtype); echo '>>>>>>>>>>>>>.';
		  
		  foreach ($ddir as $i=>$f) {	

			if ($fs->dtype[$i]=='DIR') {

			  $subpath = '/' . $f;
			  
			  $fss = new rcfs($path.$subpath);			  
			  $subdir = $fss->read_directory($path.$subpath,$extensions);
              if (!empty($subdir)) {
			    foreach ($subdir as $si=>$sf) {
			      $newddir[] = $f . '.' . $sf;
				  $script_names[] = str_replace('.dpc.php','',$f . '.' . $sf);
			    }	
			  } 	
			  unset($fss);				
			  unset($subdir);	
			}
			else {
			  $newddir[] = $f;
			  $script_names[] = str_replace('.dpc.php','',$f);
			}  
		  }
		  
	      //print_r($script_names); 
	      if ($retarray)
	        return ($script_names);				  
		  
		  if (!$combo) {
            $ret = $fs->show_directory($newddir,"t=$myact&f=/","Files");
	      }
          else {
		  	if (!$editmode) {
		      $myaction = seturl("t=$myact");	 
	          $ret = "<form method=\"post\" name=\"RCSCRIPTS\" action=\"$myaction\">";	 
			}
			
		    $ret .= "<select name=\"id\"";
			if ($editmode) {
			  if ($inframe)
			    $ret .= " onChange=\"top.$inframe.location='$editmode'+this.options[this.selectedIndex].value\">";
			  else
			    $ret .= " onChange=\"location='$editmode'+this.options[this.selectedIndex].value\">";
			}  
			else
			  $ret .= ">"; 
	 
	        sort($newddir);
			//print_r($newddir);
			
            foreach ($newddir as $id=>$name) {
			    $title = str_replace('.dpc.php','',$name);
                $ret .= "<option value=\"$title\"".($value == GetReq('id') ? " selected" : "").">$title</option>";		
		    }
			
		
		    $ret .= "</select>";	
			if (!$editmode) {
              $ret .= "<input type=submit value=\"$combo\">";
	          $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
              $ret .= "</form>";  			    
			}  
	      }
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }
	   
       return ($ret);		
  }	  
  
  //only used in main .php file
  function show_active_scripts($combo=null,$taction=null,$script_path=null,$editmode=null,$inframe=null,$retarray=null) {
       $phpfile = GetReq('phpfile');
       //echo GetReq('phpfile'),'>';
	   
       if (stristr($phpfile,'.php')) {//in case of already decoded name
         $lan = getlocal()?getlocal():'0';
         $active_php_file = str_replace($lan.'.php','.php',$phpfile);//extract lan digit
       }	
       else		   
	     $active_php_file = urldecode(base64_decode(GetReq('phpfile')));
		 
	   //echo $active_php_file,'>>>';
	   $files = array();
	   $files = $this->scan_php_file($active_php_file, 'private');
	   //print_r($files);
	   
	   if ($taction)
	     $myact = $taction;
	   else
	     $myact = 'cpscripts';
	   //echo $myact,'>';
	   
       if (defined('RCFS_DPC')) {
        $path = $script_path ? $this->urlpath.$this->infolder.'/' . $script_path : $this->urlpath.$this->infolder.'/cgi-bin';	
	    $extensions = array(0=>".dpc.php");	
	    //echo '<br><br>',$path;
		
		if (is_dir($path)) {

		  $fs = new rcfs($path);
		  $ddir = $fs->read_directory($path,$extensions,1);
		  //print_r($fs->dtype); echo '>>>>>>>>>>>>>.';
		  
		  foreach ($ddir as $i=>$f) {	

			if ($fs->dtype[$i]=='DIR') {

			  $subpath = '/' . $f;
			  
			  $fss = new rcfs($path.$subpath);			  
			  $subdir = $fss->read_directory($path.$subpath,$extensions);
              if (!empty($subdir)) {
			    foreach ($subdir as $si=>$sf) {
			    
			      $subfile = $f . '.' . $sf;
				  //echo $subfile,'<br>';
				  if (in_array(str_replace('.dpc.php','',$subfile), $files)) 
				    $newddir[] = $subfile;
			    }	
			  }	
			  unset($fss);				
			  unset($subdir);	
			}
			else {
			  if (in_array(str_replace('.dpc.php','',$f), $files)) 
			    $newddir[] = $f;
			}  
		  }		  
		  
          //print_r($newddir); 
		  if ($retarray)
		    return ($newddir);		  
		  
		  if (!$combo) {
            $ret = $fs->show_directory($newddir,"t=$myact&f=/","Files");
	      }
          else {
		  	if (!$editmode) {
		      $myaction = seturl("t=$myact");	 
	          $ret = "<form method=\"post\" name=\"RCSCRIPTS\" action=\"$myaction\">";	 
			}
			
		    $ret .= "<select name=\"id\"";
			if ($editmode) {
			  if ($inframe)
			    $ret .= " onChange=\"top.$inframe.location='$editmode'+this.options[this.selectedIndex].value\">";
			  else
			    $ret .= " onChange=\"location='$editmode'+this.options[this.selectedIndex].value\">";
			  //$selected = urldecode(base64_decode($_GET['htmlfile'])); 
			}  
			else
			  $ret .= ">"; 
	 
	        sort($newddir);
			
            foreach ($newddir as $id=>$name) {
			  $title = str_replace('.dpc.php','',$name);
              $ret .= "<option value=\"$title\"".($title == GetReq('script') ? " selected" : "").">$title</option>";		
		    }	
		
		    $ret .= "</select>";	
			if (!$editmode) {
              $ret .= "<input type=submit value=\"$combo\">";
	          $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"$myact\">";   
              $ret .= "</form>";  			    
			}  
	      }
	    }  
	    else
	      $ret = 'Invalid directory!';		   
	   }	  
	    
	   return ($ret);		
  }	  
  
  function scan_php_file($file,$retvals=null) {
	//$pattern = "@(super)?(echo)@";  
    //echo $file,'>';
	
	if (is_readable($this->urlpath . $file)) {
	
	   $code = file_get_contents($this->urlpath . $file);
	   //echo $code,'>';
	   //preg_match_all($pattern,$code,$matches);
	   //print_r($matches);
	   
	   if ($file = explode("\n",$code)) {
			//clean code by nulls and commends and hold it as array
			foreach ($file as $num=>$line) {
			  $trimedline = trim($line);
		      if (($trimedline) && //check if empty line			  
			      ($trimedline[0]!="#")) {  //check commends        
			     //echo $trimedline."<br>";			    
				 $lines[] = $trimedline;
			  }
			}
			//print_r($lines);
			//implode lines because one line may have more than one cmds sep by ;
			$toktext = implode("",$lines);
			//tokenize
			$token = explode(";",$toktext);
            //print_r($token);  			
	   }	
	   
	   try {	
		   
	   //then...read tokens  			
	   foreach ($token as $tid=>$tcmd) {
			  
			   $part = explode(' ',$tcmd);
			   switch ($part[0]) {
			     case 'system': //include and load a set of system lib dpc
				                $syslibs = explode(",",$part[1]);
						        //print_r($syslibs);
								foreach ($syslibs as $lid=>$lib) {
								  if (strstr($lib,'.')) 
									$codeparts['system'][] = $lib;
								  else 
								    $codeparts['system'][] = 'system.'.$lib;
								}		 
				                break;			   
			   
			     case 'use'   : //include and load a set of lib dpc
				                $libs = explode(",",$part[1]);
						        //print_r($libs);
								foreach ($libs as $lid=>$lib) {
								  if (strstr($lib,'.')) 
								    $codeparts['use'][] = $lib;
								  else 
								    $codeparts['use'][] = 'libs.'.$lib;
								}		 
				                break;
				
				 case 'super' :	//include and load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) 
								    $codeparts['super'][] = $dpc;
								  else 
								    $codeparts['super'][] = $dpc.'.'.$dpc;
								}		 
				                break;		
								
				 case 'include' ://include NOT load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) 
								    $codeparts['include'][] = $dpc;
								  else 
								    $codeparts['include'][] = $dpc.'.'.$dpc;
								}		 
				                break;	
								
				 case 'instance':if (strstr(trim($part[3]),'.'))		
				                   $codeparts['instance'][] = trim($part[3]);													 	
							     break;
								 
			     case 'load_extension' : //include only NOT load a set of extensions dpc
								if (strstr(trim($part[1]),'.')) 
								  $codeparts['load_extension'][trim($part[3])] = trim($part[1]);
								else 
								  $codeparts['load_extension'][trim($part[3])] = trim($part[1]).'.'.trim($part[1]);
				                break;	
								
				 case 'security': 
				                  $codeparts['security'][] = trim($part[1]);
				                  break;									
								
				 case 'member': $codeparts['member'][] = trim($part[1]);
				                break;		
								
				 case 'dpccode'  : $codeparts['dpccode'][] = trim($part[1]);	break;																	  
				 case 'phpcode'  : $codeparts['phpcode'][] = trim($part[1]);	break;		
				 
				 case 'private'  ://loads dpc from private dir
				                  $codeparts['private'][] = trim($part[1]);
				                  break; 		 
							  
				 default      : //only include and save dpc modules to load th objects by shell			  
			  		            if ($part[0]) {
								  $codeparts['public'][] = trim($part[0]);												 
								} 
				                
			   }//switch
			   $i+=1; 
	   }//foreach
	   
	   }
	   catch (Exception $e) {
         echo 'Caught exception: ',  $e->getMessage(), "\n";
       }
	   
	   //print_r($codeparts);
	   if ($retvals) {
	     
		 if (stristr($retvals,',')) {
	       $myvals = explode(',',$retvals);//multiple sets 
		 }
		 foreach ($codeparts as $section=>$vals) {
		   if (is_array($myvals)) {
		     if (in_array($section,$myvals)) {
			   $ret[$section] = $vals;
			 }
		   }
		   else {//one set
		     if ($section==$retvals)
		       $ret = $vals; 
		   }	 
		 }
		 return ($ret);
	   }
	   else
	     return ($codeparts);
	}
	
  }
  
  function postscript() {
    $phpfile = GetReq('phpfile');
    $scripts = $this->scan_php_file($phpfile, 'private');
    //print_r($scripts);
    $inventory = $this->show_used_scripts(null,null,null,null,null,1);
	//print_r($inventory);

    foreach ($scripts as $s=>$sname) {
	  if (!in_array($sname, $inventory)) {
	    echo $sname,':', seturl('t=cpusescript&script='.$sname.'&phpfile='.$phpfile,'Add to inventory'),'<br>';
	  }	
    }	
	
    //echo 'zzz';	
  }
  
  function add_active_script() {
    $myscript = GetReq('script');
	
	$ret = $this->use_script($myscript);
	//echo $a,'yyy';	
	return ($ret);
  }
  
  function add_script() {
    $phpfile = GetReq('phpfile');
	$script = GetReq('script');
	
	//echo '>',$phpfile,$script;
	$data = $this->loadfromfile($phpfile);
	//echo $data,'>',$this->urlpath,$this->infolder,'/',$phpfile;
	if (!stristr($data,$script)) {
	  $data2save = str_replace("',0);","private $script /cgi-bin;\n',0);",$data);
	  //echo $data2save,'>';
	  //save to file
	  $this->write2file($phpfile,$data2save,1);
	}  
  }
  
  function edit_dpc_script($myscript=null,$script_path=null,$infolder=null) {
    $myarea = $infolder?$infolder:'my';
	$script = $myscript?$myscript:GetReq('script');  
    $path = $script_path ? $this->urlpath.$this->infolder.'/' . $script_path : $this->urlpath.$this->infolder.'/cgi-bin';		
	if (!stristr($script,'.'))
	  $script = $myarea .'.'. $script;
	  
	$script_file = $path . '/' . str_replace('.','/',$script) . '.dpc.php';
	//echo $script_file;
	$mydata = file_get_contents($script_file);
	
	$go_url =seturl('t=cpsavescript&phpfile='.GetReq('phpfile').'&script='.GetReq('script'));
	$ret  = "";
	$ret .= "\n<form name=\"dpcform\" action=\"".$go_url."\" method=\"post\">";  
	 
	$ret .= '<div>'; 
    $ret .= "\n <textarea wrap='virtual' id='dpceditor' name='dpccode' style='width: 100%' rows='10' autowrap>$mydata</textarea>";	 
	$ret .= '</div>';
	 
	$ret .= "<input type=\"submit\" name=\"ok\" value=\"  submit  \" />";	    
    $ret .= "<input type=\"hidden\" name=\"file\" value=\"" . $script_file . "\" />";	  
	$ret .= "<input type=\"hidden\" name=\"FormAction\" value=\"cpsavescript\" />";

	$ret .= "</form>";	
	
	return ($ret);
  }
  
  function html_javascript_editor($myscript=null,$script_path=null) {
  
    $ret = '
<html>
<head>
<title>dpc editor</title>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->encoding.'">	
<script language="Javascript" type="text/javascript" src="http://www.stereobit.gr/javascripts/edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "dpceditor"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "y"
			,allow_toggle: false
			,word_wrap: true
			,language: "en"
			,syntax: "php"
            ,toolbar: "save, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight"			
			,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
			,show_line_colors: true
			,replace_tab_by_spaces: 4
			,min_height: 650
			,save_callback: "my_save"
		});
		
		// callback functions
		function my_save(id, content){
		    submitform();
			//alert("Here is the content of the EditArea \'"+ id +"\' as received by the save callback function:\n"+content);
		}
		
		function my_load(id){
			editAreaLoader.setValue(id, "The content is loaded from the load_callback function into EditArea");
		}
		
		function test_setSelectionRange(id){
			editAreaLoader.setSelectionRange(id, 100, 150);
		}
		
		function test_getSelectionRange(id){
			var sel =editAreaLoader.getSelectionRange(id);
			alert("start: "+sel["start"]+"\nend: "+sel["end"]); 
		}
		
		function test_setSelectedText(id){
			text= "[REPLACED SELECTION]"; 
			editAreaLoader.setSelectedText(id, text);
		}
		
		function test_getSelectedText(id){
			alert(editAreaLoader.getSelectedText(id)); 
		}
        function submitform() {
            document.dpcform.submit();
        }	
		//when edit php class..reload phpdac to update config...
	    function reload_dpcdac()
	    {
          top.rightFrame.location=\'cpmdpcdac.php?encoding=' . GetReq('encoding') . '&phpfile=' . GetReq('phpfile') . '&script=' . GetReq('script') .'\';
	    }			
	</script>
</head>
<body onLoad="reload_dpcdac()" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">	
';	

    echo $ret;
	
	echo $this->edit_dpc_script($myscript,$script_path);
	
    echo "</body>";
    echo "</html>";	
	die();//stop here	
  }
  
  function new_phpdpc_file($name=null,$script_path=null,$infolfer=null) {
            //echo $name,'>';
			$myarea = $infolder?$infolder:'my';
			
            if (stristr($name,'.')) { 
              $pn = explode('.',strtolower($name));
			  $ppath = $pn[0];
			  $pname = $pn[1]?$pn[1]:'newclass';
			}  
            else {
			  $ppath = $myarea;
              $pname = $name?strtolower($name):'newclass';
			}  
			  
	        $myname = $name?$pname.'.dpc.php':'newclass.dpc.php';
            $path = $script_path ? $this->urlpath.$this->infolder.'/' . $script_path : $this->urlpath.$this->infolder.'/cgi-bin';			
			$subpath = $ppath?"/$ppath/":"/";//always in my.. if not defined
			
			//create dir if not exist
			//echo $path . $subpath,'>';
			if (!is_dir($path . $subpath)) {
	          $makedir = mkdir($path . $subpath, '0744'); //0755
	        }
			else
			  $makedir = true;
			
			if ($makedir==false) return false;
			
		    $file = $path . $subpath .  $myname;
			$uname = strtoupper($pname) ;
			$uname_dpc = $uname.'_DPC';
			//echo $file;
			
			$data = "
<?php

\$__DPCSEC['$uname_dpc']='1;1;1;1;1;1;1;1;1';

if (!defined(\"$uname_dpc\")) {
define(\"$uname_dpc\",true);

\$__DPC['$uname_dpc'] = '$pname';

\$__EVENTS['$uname_dpc'][0] = '$pname';

\$__ACTIONS['$uname_dpc'][0] = '$pname';

\$__LOCALE['$uname_dpc'][0]='$uname_dpc;$pname;$pname;';

class $pname {

   function __construct() {
   }
   
   function event(\$event=null) {
     
	 switch (\$event) {
	    case '$pname' :
		default       :
	 }
   }
   
   function action(\$action=null) {
   
	 switch (\$action) {
	    case '$pname' :
		default       : \$out = 'hello world!';
	 }
     return (\$out); 	 
   } 
};
}
?>";	
	        
            if ($fp = @fopen ($file , "w")) {
	             //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
            }
            else {
              $this->msg .= "File creation error ($file)!\n";
		      //echo "File creation error ($filename)!<br>";
            }

            return 	strtolower($ppath.'.'.$pname);		
					
  }	  
  
  function class_instance($class=null,$script_path=null,$retfile=null) {
    $myclass = $class?$class:GetReq('class');
	if (stristr($myclass,'.')) {
	  $s = str_replace('.','/',$myclass) . '.dpc.php';
      $p = explode('.',strtolower($myclass));
	  $objname = $p[1];	  
    }	
	else {
	  $s = $myclass . '.dpc.php';
	  $objname = $myclass;
	}  
	  
	$path = $script_path ? $this->urlpath.$this->infolder.'/' . $script_path : $this->urlpath.$this->infolder.'/cgi-bin';			  
	$file = $path . '/' .  $s; 

    //echo $file,'>',$objname;
    if ($retfile)
      return (array($file,$objname));
	else {  
	  include($file);
	  return new $objname;
	}  
  }
  
  function endoscope_class($class=null) {
    if (!$class) return;
	
	//$obj = $this->class_instance($class); //..must be include inside this func to get its global args
	$cp = $this->class_instance($class,null,1);
	include($cp[0]);
	$obj = new $cp[1];	
	
	$events = $__EVENTS;//local...GetGlobal('__EVENTS');
	$actions = $__ACTIONS;//local...GetGlobal('__ACTIONS');	
	print_r($events);
	
    //Instantiate the reflection object
    $reflector = new ReflectionClass($cp[1]);

    //Now get all the properties from class A in to $properties array
    $properties = $reflector->getProperties();

    $i =1;
    //Now go through the $properties array and populate each property
    foreach($properties as $property) {
	
      //Populating properties
      $a->{$property->getName()}=$i;
      //Invoking the method to print what was populated
      $a->{"echo".ucfirst($property->getName())}()."\n";
    
      $i++;
    }	
	
	$ret = '....';
	return ($ret);
  }
  
  function new_project_dialog() {

     $sFormErr = GetGlobal('sFormErr');
     $myaction = seturl("t=cptsave&editmode=" . $this->editmode); 
	 
	 //$dpceditor = "cpmdpceditor.php?turl=";// . urlencode(base64_encode($turl));
	 //$jsgoto = "top.bottomFrame.location='$dpceditor'"; //dpc editor
	 	 
	 if (($this->post==true) && ($name=GetParam('title')) && (!$this->msg)) {

	   
	   /*if (isset($this->msg)) {//show msg...
	     $msg = $this->msg;
	   
	     $swin = new window("Post",$msg);
	     $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	     unset ($swin);
	   }
	   else {*///redirect edit html
	     //html editor
         $htmlpage = strtolower(str_replace('.html',getlocal().'.html',$name.'.html')); 							
	     $p1 = urlencode(base64_encode($htmlpage));
		 
		 //dpc editor ..redirect cmpdpceditor->cpmhtmleditor 
		 $phppage = strtolower($name.'.php');
		 $p2 = urlencode(base64_encode($phppage));
		 
	     header("location:cpmhtmleditor.php?encoding=".$this->encoding."&htmlfile=".$p1."&phpfile=".$p2);	   		 

	     die();
	   //}
	 }
	 else { //show the form plus error if any
	   if (($this->post==true) && (!GetParam('title')))	 
	     $this->msg = "Name required";
		 
       $out .= setError($sFormErr . $this->msg);	   
	   
	   $file = getReq('id');
	   
	   $form = new form(localize('_RCSCRIPTS',getlocal()), "RCSCRIPTS", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("title",			"Name");       	   
	   $form->addGroup		    ("options",			"Options");	  	   
	   $form->addGroup			("body",			"Body");	

       $form->addElement		("title", new form_element_text("Title",  "title",$file,"forminput",			25,				25,	0));	 
       $form->addElement		("options",	new form_element_radio("Main system",   "syspath",      GetParam('syspath'),             "",   2, array ("0" => "Public", "1" => "Private")));	   
       $form->addElement		("options",new form_element_radio("Page type",   "pagetype",      GetParam('pagetype'),             "",   2, array ("pcntl" => "pcntl", "pcntlhtml" => "cntlhtml","pcntlajax" => "pcntlajax","pcntlcmd" => "cntlcmd")));	   
       $form->addElement		("options",	new form_element_radio("Database connection",   "dconnect",      GetParam('dconnect'),             "",   2, array ("0" => "No", "1" => "Yes")));		 

	   $form->addElement		("body",new form_element_textarea($file,  "nobody","","formtextarea",	80,	20));
	   
	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cptsave"));
 
	   // Showing the form
	   $fout = $form->getform ();//0,0,null,null,null,$jsgoto);not a form element submited in this state	
	   
	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();	
	   //unset ($fwin);	
	   
	   $out .= $fout;

	 }
 
     return ($out);
  }   
  
};
}
?>