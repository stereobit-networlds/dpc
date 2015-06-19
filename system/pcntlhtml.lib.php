<?php

//load environment vars
//$environment = @parse_ini_file(getenv('SystemRoot')."/phpdac5.ini");
$environment = @parse_ini_file(getcwd()."/phpdac5.ini");
//echo getcwd()."/phpdac5.ini";
define(_APPNAME_,$environment['appname']);
define(_APPPATH_,$environment['apppath']);
define(_DPCTYPE_,$environment['dpctype']);
define(_PRJPATH_,$environment['prjpath']);
define(_DPCPATH_,_APPPATH_.$environment['dpcpath']);//echo _APPPATH_;
define(_ISAPP_,$environment['app']);

//echo _DPCPATH_; print_r($environment); echo getcwd();
require_once(_DPCPATH_."/dpclass.dpc.php");	 //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< PHP 5

require_once(_DPCPATH_."/system/sysdb.lib.php");
require_once(_DPCPATH_."/system/session.lib.php");
require_once(_DPCPATH_."/system/parser.lib.php");
require_once(_DPCPATH_."/system/ktimer.lib.php");
require_once(_DPCPATH_."/system/azdgcrypt.lib.php"); 
require_once(_DPCPATH_."/system/system.lib.php");		    
require_once(_DPCPATH_."/system/client.lib.php");

require_once(_DPCPATH_."/shell/phtml.lib.php");//HTML OUTPUT MUST BE ENABLED !!!!------------------------

require_once("controller.lib.php");

define("PCNTL_DPC",true);
$__DPC['PCNTL_DPC'] = 'pcntl'; //must exist to communicate with others dpcs as cache,supercache ...

$__ACTIONS['PCNTL_DPC'][1]='index';//dummy
$__ACTIONS['PCNTL_DPC'][2]='default';//dummy

$__DPCSEC['_PCNTLADMIN']='9;1;1;1;1;1;1;9;9';

class pcntl extends controller {

   var $mytime;
   var $myaction,$my_excluded_action;
   var $grx;
   var $css,$languange,$theme;
   var $auto;
   var $js;
   var $agent;
   var $code;
   var $myactive;
   var $file_name,$file_path,$file_extension;
   var $data,$fpdata;
   var $root_page; //root controller
   var $debug;
   var $remoteapp;
   var $fp,$lan,$cl;
   var $inpath;
   var $map;
   var $sysauth;
   var $local_security;

   function __construct($code=null,$auto=null,$locales=null,$css=null,$page=null) { 

      // CACHE CONTROL 
      //session.cache_limiter specifies cache control method to use for session pages (none/nocache/private/private_no_expire/public). 
      //session_cache_limiter('nocache'); //private_no_expire//'nocache');
  
      //set path to save sessions
      //if (GetSessionParam('REMOTEAPPSITE')) {
	    //session_save_path(paramload('SHELL','sespath')); //has no value yet..
	    //session_save_path("c:/php/webos/temp/"); 	//default at ini!!!!
        //$sespath = session_save_path();
        //print ">$spath";	  
        session_start();  //-----------------------------------------------------
	  //}	
	  
	  $this->local_security = array();
   
      //echo ">>",$_SERVER['QUERY_STRING'];
	  $this->remoteapp = null;   
	  $this->map = null;	
	 
	  $this->_loadinifiles(); 

	  if (paramload('SHELL','rewrite')) 
	   $this->rewrite();
   
      $this->mytime = $this->getthemicrotime();    
      $xtime = $this->getthemicrotime(); 
	  
	  controller::__construct('ON');//yes dac,no dacpost...
	  
	  $this->root_page = paramload('SHELL','filename');
	  
	  if ($editmode = GetReq('editmode')) {
	    if ($editmode>0) {
	      SetSessionParam('__EDITMODE',$editmode);
		  define('__EDITMODE',true);
		}
		elseif ($editmode<0) {
          SetSessionParam('__EDITMODE',null);
		  define('__EDITMODE',null);		
		}
	  }
     /*elseif ($editmode = GetSessionParam('editmode')) { //if you want to browse inside frame
		define('__EDITMODE',true);
	  }	  */	  
	  else
	    $editmode = GetSessionParam('__EDITMODE');
		
	  $this->debug = paramload('SHELL','debug');		  
	  
      //set path to save sessions
      /*  session_save_path(paramload('SHELL','sespath'));
        //$sespath = session_save_path();
        //print "$sespath";	  
        session_start(); 	  */
	  //}
	  
	  //register self as global controller and dispatcher
      SetGlobal('controller',$this);
      SetGlobal('dispatcher',$this);
	  //register this
      $__DPCMEM = GetGlobal('__DPCMEM');		    	 
      $__DPCMEM['PCNTL_DPC'] =  &$this; 
	  SetGlobal('__DPCMEM',$__DPCMEM);
	  		  
	  
	  //check if encoded url query
	  $encURL = paramload('SHELL','encodeurl');
	  if (isset($encURL)) decode_url($encURL); 

	  if (GetSessionParam('REMOTEAPPSITE'))//remote app
	    $this->inpath = null;//no path needed
	  else	
	    $this->inpath = paramload('ID','hostinpath');

	  //client preselection
      //SetGlobal('__USERAGENT','HTML');	  
	  $client = new client;	 
	  $this->agent = $client->getClient();
	  unset ($client);	
	  SetGlobal('__USERAGENT',$this->agent);
	  
	  
	  //get file info (default=php_self else $page)
	  if ($page) {
	    $this->file_info = $page;
	  }
	  else {
	    $this->file_path = pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME); 
	    if ($this->file_path=="\\") $this->file_path = null;   
	    $this->file_info = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
	    //echo $this->file_info,'++++',$this->file_path;
	  }	
	  $p = explode (".",$this->file_info);		  
	  $this->file_name = $p[0]; //echo $this->file_name;
	  $this->file_extension = $p[1];
	   	   		  
	  //thema pre-selection
	  SetGlobal('GRX',paramload('SHELL','graphics')); 
	  $this->grx = paramload('SHELL','graphics');
	  
	  $this->sysauth = paramload('SHELL','sysauth');	  
	  
	  $this->theme = (getTheme() ? getTheme() : paramload('SHELL','deftheme'));//$this->setINIParams();	  
	  if ($this->theme) setTheme($this->theme);	  
	  
	  //$this->css = $this->getCSS();		  	  
	  //echo $this->theme;
	  //languange pre-selection
      $this->languange = (getlocal() ? getlocal() : 0);//paramload('SHELL','dlang'));
	  if ($this->languange) setlocal($this->languange);	 //0 LANGUNAGE CNANGE NOT CORECTLY
	        
      if ($this->debug) 
	    echo "\nconstruct elapsed: ",$this->getthemicrotime() - $xtime, " seconds<br>"; 	   	  
	  
	  //header/footers automated
	  $this->auto = $auto;  	  
	 
      //dispacth or redirect...
	  //$this->myaction = $this->_getqueue(); 	//moved in init after compile!!!!

      $this->_loadapp($code);

	  /*if ($this->auto) {
	    $this->headers();
	  } */ 	  
   }

   
   protected function _loadapp($code) {
   
	  if (isset($this->remoteapp)) {
	    $initst = GetGlobal('initst'); //print_r($initst);
		$prj = $initst['project']; //echo $prj,'>';
		$this->map = $initst['map']; //echo $this->map,';';
	
		//save real app name
		(isset($this->map)? SetSessionParam('REALREMOTEAPPSITE',$this->map):SetSessionParam('REALREMOTEAPPSITE',$this->remoteapp));
		//echo '>',GetSessionParam('REALREMOTEAPPSITE');
		
		//echo _APPPATH_,"/",_PRJPATH_,"/" , $prj ,"/scripts/", $this->file_name , ".prj";
	    if (is_file($codefile = _APPPATH_."/"._PRJPATH_."/" . $prj ."/scripts/". $this->file_name . ".prj")) {
	      $this->code = file_get_contents($codefile);	    
		  //echo $this->code;
	      $this->init($this->code);
		  
		  //get params
	      //$params = @parse_ini_file($this->remoteapp."/".$this->file_name.".conf");
		  $params = @parse_ini_file(_APPPATH_."/"._PRJPATH_."/" . $prj ."/scripts/".$this->file_name.".conf");
		  $this->fp = $params['fp'];
		  $this->lan = $params['lan'];
		  $this->cl = $params['cl'];
		  $this->theme = $params['theme'];	//echo $this->theme;		  
		  $this->auto = $params['auto']; //echo $this->auto,'>>>';

	      if ($this->my_excluded_action)
	        $this->event($this->my_excluded_action);	 
	      else
            $this->event($this->myaction);					  		
	    }
		else die("[".$this->remoteapp."]Invalid configuration!"); 	
	  }
	  elseif (isset($code)) { //if code isset

	    $this->code = $code;		  
	    $this->myaction = null;
	    $this->my_excluded_action = null;	
	    //initalize (compile ...)  
	    $this->init($this->code);  

	    //pre-defined in page locales
	    if (isset($locales)) $this->localize($locales);	  

        $etime = $this->getthemicrotime();
	    if ($this->my_excluded_action)
	      $this->event($this->my_excluded_action);	 
	    else
          $this->event($this->myaction);	 
	    if ($this->debug) echo "\nevent elapsed: ",$this->getthemicrotime() - $etime, " seconds<br>"; 		 	
	  } 
	  else {//get file of code
	    $initst = GetGlobal('initst');
		$prj = $initst['project'];
	    $codefile = _APPPATH_."/"._PRJPATH_."/" . $prj ."/scripts/". $this->file_name . ".prj";
	    $this->code = file_get_contents($codefile); 	
        $this->init($this->code);
	  }   
   }
  
   protected function _loadinifiles() {
	  /*$argc = $GLOBALS['argc'];
	  $argv = $GLOBALS['argv']; 
	  
	  
      //remote app config reset
	  //echo $_SERVER['QUERY_STRING'];
	  //if ($_SERVER['QUERY_STRING']=='!RESET')  {//to support cmds after !RESET
	  if (substr($_SERVER['QUERY_STRING'],0,6)=='!RESET')  {
	    SetSessionParam('REMOTEAPPSITE',null);
		$initst = @parse_ini_file("action.conf");
	  }	  
	  //remote app config 1st time
	  elseif (substr($_SERVER['QUERY_STRING'],0,1)=='!')  {
		//in case of have a valid app running i can jump to an invalid...
		//so delete clientdpc before re-read it!
		SetSessionParam('clientdpc',null);
		//get string
	    $ampersand = strpos($_SERVER['QUERY_STRING'],'&');
		if ($ampersand!==false) //query has defined
	      $this->remoteapp = urldecode(substr($_SERVER['QUERY_STRING'],1,$ampersand-1));
		else  
		  $this->remoteapp = urldecode(substr($_SERVER['QUERY_STRING'],1));
		//echo $this->remoteapp;
		//print_r($_GET);
        //1st method individual app conf file
		//$initst = @parse_ini_file($this->remoteapp.".conf");//print_r($initst);
		//2nd method multiple app config
		$apps = @parse_ini_file("apps.conf",1);
		$initst = $apps[$this->remoteapp]; //print_r($initst);
	  }//next times
	  elseif ($this->remoteapp = GetSessionParam('REMOTEAPPSITE')) {
	    //echo $this->remoteapp;
        //1st method individual app conf file
		//$initst = @parse_ini_file($this->remoteapp.".conf");//print_r($initst);
		//2nd method multiple app config
		$apps = @parse_ini_file("apps.conf",1); //print_r($apps);
		$initst = $apps[$this->remoteapp]; //print_r($initst);
	  }
      else 
        $initst = @parse_ini_file("./action.conf");//print_r($initst); //<<< LINUX CONF ./
		
      $prj = $initst['project']; //echo $prj;
      $prjpath=$initst['path'];
      //problem with ssl..directory
	  //if (_ISAPP_) //public app directory
	  //  $conf_path = _APPPATH_."/"._PRJPATH_."/$prj/public/app/";
	  //else //project root directory
	  //  $conf_path = _APPPATH_."/"._PRJPATH_."/$prj/";

      if (_ISAPP_)
        $conf_path = _APPPATH_.$prjpath;   //.'app/'; NOT NEEDED!!!!
      else
        $conf_path = _APPPATH_.$prjpath;
      //echo $conf_path;
		
	  //echo $conf_path,'><br>';
      $config = @parse_ini_file($conf_path . "config.ini",1); //print_r($config);
	  $theme = @parse_ini_file($conf_path . "maptheme.ini");
	  
	  
      //make these vars globals
      SetGlobal('initst',$initst);
      SetGlobal('prj',array('project'=>$argv[2]));
*/
      if (is_readable("config.ini")) {//in root	   
	    $config = @parse_ini_file("config.ini",1);
	    $myconfig = @parse_ini_file("myconfig.txt",1);			
	  }	
	  elseif (is_readable("cp/config.ini")) {//in cp
        $config = @parse_ini_file("cp/config.ini",1);  
	    $myconfig = @parse_ini_file("cp/myconfig.txt",1);		
	  }	
	  elseif (is_readable("../config.ini")) {//in subdir in cp
        $config = @parse_ini_file("../config.ini",1);  	
	    $myconfig = @parse_ini_file("../myconfig.txt",1);	
	
	  }	
	  else
        die("Configuration error, config.ini not exist!");	
	
	  //extra conf
      if (!empty($myconfig))
        $config = array_merge($config, $myconfig); 	
		
      if (is_readable("maptheme.ini")) //in root	  
	    $theme = @parse_ini_file("maptheme.ini",1); 
	  elseif (is_readable("cp/maptheme.ini")) //in cp
        $theme = @parse_ini_file("cp/maptheme.ini",1);  
	  elseif (is_readable("../maptheme.ini")) //in subdir in cp
        $theme = @parse_ini_file("../maptheme.ini",1);  	
	  else
        die("Configuration error, maptheme.ini not exist!");		
  
      SetGlobal('config',$config);
      SetGlobal('theme',$theme);   
   }   
   
   public function render($theme=null,$lan=null,$cl=null,$fp=null,$xmlns=null) {      
   
      $atime = $this->getthemicrotime();  
	  
	  if (isset($this->remoteapp)) {
	    //loaded at construct
	    //$params = @parse_ini_file($this->remoteapp."/".$this->file_name.".conf");
		$fp = $this->fp;//$params['fp'];
		$lan = $this->lan;//$params['lan'];
		$cl = $this->cl;//$params['cl'];
		$theme = $this->theme;//$params['theme'];
		SetSessionParam('REMOTEAPPSITE',$this->remoteapp);//save 1st call with !APP arg
		//echo GetSessionParam('REMOTEAPPSITE');
		//print_r($_SESSION);
	  }
	  else {//used by reset to parent root....11.../////??????????????
	    $this->theme = ($theme ? $theme : paramload('SHELL','deftheme'));
	    $theme = $this->theme;  
		//echo $theme;
	  }		  
      
	  $this->pre_render($theme,$lan,$cl,$fp);
	  
	  
	  //RENDER......

	 /* if ($this->auto) {//after action to handle js loaded in action
	    $this->headers($xmlns);
	  }*/ 		  	  
	  
	/*  if ((defined('FRONTPAGE_DPC')) && (isset($fp))) {//call theme xgi page
	  	  
        $ftime = $this->getthemicrotime();	  
	    $cfp = new frontpage($fp,0);
	    $ret .= $cfp->render($this->data);
	    unset($cfp);			
		
        if ($this->debug) echo "\nfrontpage elapsed: ",$this->getthemicrotime() - $ftime, " seconds<br>";			  
	  }
	  else {//call the html page*///----------------------------------------------------
	  
	    $appi = (isset($this->map)? $this->map:$this->remoteapp);
	    //echo $appi;
		//if splash && no action && no secont time
		//echo GetSessionParam('SPLASH'),'>',$this->myaction;
	    if ((defined('SPLASH_DPC')) && ($this->myaction=='index') && (!GetSessionParam('SPLASH'))) {
		   SetSessionParam('SPLASH','yes');
		   //echo 'splash!';
		   
		   if (!$this->auto) $this->headers();//anyway show header
	       $sfp = new splash($fp,null,$appi);
	       //$ret = $sfp->render();
		   echo $sfp->render();
	       unset($sfp);		   
		   if (!$this->auto) $this->footers();//anyway show footer		   
		   //die();
	    }	  
	    else {
		  //load edit tools at frontpage of app
		  if ((GetSessionParam('LOGIN')) && (isset($this->remoteapp))) {
            $d = GetGlobal('controller')->require_dpc('frontpage/rcfronthtmlpage.dpc.php');
            require_once($d);//'dpc/frontpage/rcfronthtmlpage.dpc.php');//$d);		  
		    $hfp = new rcfronthtmlpage($fp,null,$appi,$this->remoteapp);
		  }	
		  else //render app normal 
	        $hfp = new fronthtmlpage($fp,null,$appi);
			
		  //javascript handled inside....	  
	      $ret .= $hfp->render($this->data);
	      unset($hfp);
		}
	  //}-----------------------
	  
	  //footers moved here for other type (xml) returning
	  //not by default printit at destruct
	  //NOT NEED && (!$GLOBALS['DIE']))
	  //if ($this->auto)  $this->footers(); //-------------------------------------

	  if ($this->debug) 
	    echo "\naction elapsed: ",$this->getthemicrotime() - $atime, " seconds<br>"; 	    
	  
	  //echo $_SERVER["HTTP_AUTHORIZATION"];
	  //echo '|',GetSessionParam('authmethod'),':',GetSessionParam('authverify'),'>';
	  
	  return ($ret); //for supercache reasons	   
   }
   
   public function s_render($theme=null,$lan=null,$cl=null,$fp=null) {  	     
   
      $supertime = $this->getthemicrotime();    
	  //supercache
      if ((defined('SUPERCACHE_DPC')) && (seclevel('SUPERCACHE_DPC',$this->userLevelID))) {
	    //echo 'SUPERCACHED';
	    $this->supercache = & new supercache($_SERVER['PHP_SELF'].$this->myaction);//php-self used to identify selfname actions
	    $ret = $this->supercache->getcache_method_use_pointers('pcntl.render',array(0=>&$theme,1=>&$lan,2=>&$cl,3=>&$fp)); 
	 	unset ($this->supercache);	   
	  }
	  else {
	    //echo 'NO SUPERCACHED';
	    $ret = $this->render($theme,$lan,$cl,$fp);	
	  }	
      if ($this->debug) echo "\nsupercache elapsed: ",$this->getthemicrotime() - $supertime, " seconds<br>";			  
	  
	  return ($ret);
   }
   
   protected function pre_render($theme=null,$lan=null,$cl=null,$fp=null) {
      
	  if ($this->sysauth) {
	  if (($realm = GetParam('AUTHENTICATE')) || ($realm = GetReq('auth')) ||
	      ($this->get_attribute($this->myactive,$this->myaction,13))) {  
		  
		if (!$realm) $realm = "Generic authendication";  
	    $this->authenticate($realm,$this->myaction);
	  }	
	  }
	  //echo $_SERVER['HTTP_AUTHORIZATION'];
	  //echo '<pre>';
	  //print_r($_SERVER);	 
	  //echo '</pre>';	  
   
      //change theme manual
      if (isset($theme)) 
	    setTheme($theme);
	  //change languange
	  if (isset($lan)) 
	    setlocal($lan);
	  //change client
	  if (isset($cl)) {
	    $this->agent = $cl;
	    SetGlobal('__USERAGENT',$this->agent);	  
	  }    
				
      if ($this->get_attribute($this->myactive,$this->myaction,4)) {		
		    //$this->free();//recursion error because of registration of this as dpc	   
		    $this->init($this->code);	
			if ($this->debug) echo '......re-init.....';
  	  }			  	
	  
	  //get action
      $this->data = $this->action($this->myaction);
	  
	  //!!!! $this->data can be used as text to find words for advs !!!!!!!!!
	  
      //SOS???NO NEED?????
	  /*if (!$this->data) {//if no data redirect to root controller...(eg.login!)
		  $page = str_replace(pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME),
		                      $this->root_page,
							  $_SERVER['URL']);
		  //echo $page;					  
		  $this->redirect($_SERVER['HTTP_HOST'] . $this->inpath .$page);	  
	  }*/	     
   }
   
   
   //overwrite..
   private function compile($code='',$accelerated=0) { 
   
       $c = unserialize(GetGlobal($_SERVER['PHP_SELF'])); //get global	   
	   if (($accelerated) && (is_array($c))) {//accelerate reading...!!!!
	     $token = $c;
		 //echo 'Accelerated';
	   }
	   else {//not accelerated   
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
            SetGlobal("__COMPILE",serialize($token)); //save the global....			
	    }
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
								    $this->calldpc($lib,'lib');//if . exist select from a spec dir
								  else 
								    $this->calldpc("system.$lib",'lib'); //else libs dir = default
								}		 
				                break;			   
			   
			     case 'use'   : //include and load a set of lib dpc
				                $libs = explode(",",$part[1]);
						        //print_r($libs);
								foreach ($libs as $lid=>$lib) {
								  if (strstr($lib,'.')) 
								    $this->calldpc($lib,'lib');//if . exist select from a spec dir
								  else 
								    $this->calldpc("libs.$lib",'lib'); //else libs dir = default
								}		 
				                break;
				
				 case 'super' :	//include and load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) $this->calldpc($dpc,'dpc');
								                   else $this->calldpc("$dpc.$dpc",'dpc');//same name for dir + class
								}		 
				                break;		
								
				 case 'include' ://include NOT load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) $this->set_include($dpc,'dpc');
								                   else $this->set_include("$dpc.$dpc",'dpc');//same name for dir + class
								}		 
				                break;	
								
				 case 'instance':if (strstr(trim($part[3]),'.'))		
				                   $this->set_instance($part[3],$part[1],$part[5]);
                                 else //. not exist				 
				                   $this->set_instance(trim($part[3]).".".trim($part[3]),$part[1],$part[5]);													 	
							     break;
								 
			     case 'load_extension' : //include only NOT load a set of extensions dpc
								if (strstr(trim($part[1]),'.')) 
								  $this->set_extension(trim($part[1]),trim($part[3]),1);
								else //. not exist				 
				                  $this->set_extension(trim($part[1]).".".trim($part[1]),trim($part[3]),1);
				                break;	
								
				 case 'security': 
				                  $this->setlevel($part[1],$part[2],str_replace(':',';',$part[3]));
				                  break;									
								
				 case 'member': $dpcmods[] = $part[1];
				                break;		
								
				 case 'dpccode'  : echo $this->execute_dpc_code($part[1]);	break;																	  
				 case 'phpcode'  : echo $this->execute_php_code($part[1]);	break;	

				 case 'private'  ://loads dpc from private dir
				                  $this->set_include($part[1],'dpc',$part[2]);
				                  $dpcmods[] = $part[1];
				                  break; 					 
							  
				 default      : //only include and save dpc modules to load th objects by shell			  
			  		            if ($part[0]) {
								  $this->set_include($part[0],'dpc');
								//  calldpc_include($part[0],'dpc');	
					              $dpcmods[] = $part[0]; //hold dpc names												 
								} 
				                
			   }//switch
			   $i+=1; 
	   }//foreach
	   
	   }
	   catch (Exception $e) {
         echo 'Caught exception: ',  $e->getMessage(), "\n";
       }
		
	   return ($dpcmods); //return the array of included dpcs 

   }   
   
   function execute_dpc_code($code) {
   
      $code_cmds = explode(';',$code);
	  foreach ($code_cmds as $line=>$cmd) {
	  
	    $ret .= GetGlobal('controller')->calldpc_method($cmd,1);//no error!!!!!			
	  }
	  
	  return ($ret);
   }
   
   function execute_php_code($code) {
   
     
	  $ret = eval($code);
	  
	  
	  return ($ret);
   }   
   
   //overwrite
   public function init($code) {      
   
      //ACCELERATE modules reading...
	  $t = new ktimer;
	  
	  $t->start('compile',1);		  
      $modules = $this->compile($code); //include and load project file's dpc lib,ext,dpc'  
	  $t->stop('compile');
	  if ($this->debug) echo "compile " , $t->value('compile');	  	  
	
	  //NO NEED POST CODE...
	  /*$t->start('postcode',1);	  
      $this->read_post_code(); //get batch readed post code as array to call after...
	  $t->stop('postcode');	  
	  echo "postcode " , $t->value('postcode');	  */ 
	  //INCLUDE FIRST
	  if (!empty($modules)) {
	  $t->start('include');		  
	  foreach  ($modules as $id=>$dpc) {
	  
        if ( (!defined($dpc)) && ($this->seclevel($dpc)) ) {
         define($dpc,true);
		 $modules_to_start[] = $dpc;
	  	  
	     //echo $dpc."<br>";
		 //$this->set_include($dpc,'dpc');//MOVED TO SWItch of compile 
		 
		 //post construct code
	     if (is_array(GetGlobal('__POSTCODE'))) {		 
		   $construct_function = create_function('$dpc',$this->get_code_of('construct',$dpc));
		   $construct_function($dpc);		    
		 }
	    }   
	  }
	  $t->stop('include');
	  if ($this->debug) echo "include " , $t->value('include');	 	   	 
      }
       //ACCELERATE attributes reading...NO REASON.....
	 /*
	  $t->start('attr');	  
	  $this->load_attributes(); //overwrite build in attributes with db attr or file attr	   	 
	  $t->stop('attr');
	  echo "attr " , $t->value('attr');	  
	  */

	  //INSTANCE PROJECT CASE
	  $is_instance = paramload('ID','isinstance');
	  if ($is_instance) //must be include the clientdpc module
 	    $cdpc = new clientdpc;		  
 
      //dispacth or redirect...after include!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	  $this->myaction = $this->_getqueue(); 	//------------------------------------------------------

	  /*NO REASON...
	  //SOME DPCS MUST EXECUTE THEIR COMMANDS BEFORE OTHER DPCS CONSTRUCTION
	  //(update vals at construct of these)
	  //so give a priorty to a dpc to be newed and execute their event(?) before new of others 	  
	  $action = $this->myaction;//GetGlobal('dispatcher')->get_command(1);//unofficial
	  //echo ">>>>>",$action;	  
	  foreach ($modules as $id=>$dpc) {

	    if ($this->get_attribute($dpc,$action,11)) {
		   //new it
		   $this->_new($dpc,'dpc');
		   $this->event($action,$dpc);
		   $norenewdpc = $dpc;//used to overpass this dpc
		   //echo '()()))())(';
		}
	    //echo '.';
	  }
	  */	

	  if (is_array($modules_to_start)) {
	  $t->start('new');			  
	  //NEW AFTER (one by one as it writed in script)	 
	  //print_r($modules);
	  foreach  ($modules_to_start as $id=>$dpc) {
		/* if ($dpc!=$norenewdpc) NO NEED!!!!*/
		   //echo $dpc,"<br>";
		   if (is_object($cdpc)) {
		   
		     if ($cdpc->is_client_dpc($dpc))
		       $this->_new($dpc,'dpc');   
		     else {
			   session_start(); 
			   session_unset();
			   session_destroy();//kill the session
		       die("$dpc not supported or expired!");
			 }  
		   }
		   else
		     $this->_new($dpc,'dpc');   
		 //echo '>',$id,'=',$dpc;    
	  }	 	 
	  $t->stop('new');
	  if ($this->debug) echo "new " , $t->value('new');	
	  }  	    	
   }
   
   //overwrite
   public function event($event=null) {

     controller::event($event);
   }
   //overwrite
   public function action($action=null) {
    
     $ret = controller::action($action);  

      switch ($action) {
               case 'index' :
               case 'default':
               default         : $ret .= null;
       }
    
       return ($ret);
   }
   
   //set security level at runtime
   public function setlevel($modulename,$plafon,$colonvals) {
   
     $sec2 = GetGlobal('__DPCSEC2'); //alternative array
     $sec2[$modulename] = $plafon . ";" . $colonvals;
	 SetGlobal('__DPCSEC2',$sec2);
	 //print_r($sec2);
	 
	 $this->local_security[$modulename] = $plafon . ";" . $colonvals;
	 
   }
   
   //get security level at runtime
   protected function seclevel($modulename) {
   
     $levelofsec = decode(GetSessionParam('UserSecID'));
   
     $sec = GetGlobal('__DPCSEC');
     $sec2 = GetGlobal('__DPCSEC2');	 
     //echo count($loc),'>>>';
	 //print_r($this->local_security);
	 
	 //EXPERIMENT
	 //if (isset($sec2[$modulename])) {
	 /*if ($sec2=$this->local_security[$modulename]) {
       
       $parts = explode(";",$sec2);
	   echo "<br>",$modulename,"<br>",implode(',',$parts);   
	   if ($parts[$levelofsec+1] >= $parts[0])
	     return 1;//allow
	   else
	     return 0;//deny	 
	 }
     else*/if (isset($sec[$modulename])) {
       //echo "<br>",$modulename,"<br>";   
       $parts = explode(";",$sec[$modulename]);
	 
	   if ($parts[$levelofsec+1] >= $parts[0])
	     return 1;//allow
	   else
	     return 0;//deny
     }
	 
     return 1; //default allow
   }	 	   
   
   //dynamic locale adding  NOT overwritable
   public function locale($alias,$val) {
      
	  //static $locale_i = 0;
   
      $__DPCLOCALE = GetGlobal('__DPCLOCALE');
	  
	  if (isset($__DPCLOCALE[$alias])) {
	    echo "Locale ($alias) already defined!";
		return false;
	  }  
	  
	  $__DPCLOCALE[$alias] = $val;
	  SetGlobal('__DPCLOCALE',$__DPCLOCALE);
	  
	  return true;
   }
   
   //batch as param in construction (overwritible)
   protected function localize($array) {
      
      if (is_array($array)) {
   
        $__DPCLOCALE = GetGlobal('__DPCLOCALE');
	  
	    foreach ($array as $id=>$val)
	      $__DPCLOCALE[$id] = $val;
		
	    SetGlobal('__DPCLOCALE',$__DPCLOCALE);
      }		
   }   
   
   //page controller :: DISPATCHER
   //if event/action not in executed dpc search other page controller
   //named as event/action or go to parent controller = shell
   //private (called by init after include dpcs)
   protected function _getqueue() {
       
       if ((array_key_exists('FormAction',$_POST)) || 
	       (array_key_exists('t',$_GET))) {//GET:t{ 
               		   
		    $current_page = pathinfo($_SERVER['PHP_SELF']);
		    //echo $current_page['dirname'],">>>>",$this->file_path;
		    //if is not the root-page-controller

		      $page = str_replace($this->file_path."/".$current_page['basename'],
			                      "/".$this->root_page,
								  $this->get_server_url());
			  					  
			  //extract '?t=' due to re-queue recursive error 	
              if ($this->root_page!=$current_page['basename']) 
			    $mypage = '/'.$this->root_page;//substr($page,0,strlen($this->root_page)+1);//strlen($current_page['basename'])+1); 
              else //?????			  
			    $mypage = substr($page,0,strlen($this->root_page)+1);//echo $mypage; die();
			  //die($page.'<br>'.$mypage.'<br>'.$this->root_page.'<br>'.$current_page['basename']);
			  unset($_GET['t']);			
			  
			  //$p = parse_url();
			  //$qry = $p['query'];
			  //$frg = $p['fragment'];
			  foreach ($_GET as $id=>$val)
			    $qry .= $id.'='.$val.'&';
			  	  
			  //die($_SERVER['HTTP_HOST'] . $this->inpath . $mypage . '?t='. GetReq('t') .'&'. $qry . $fragment);	  
		      $this->redirect($_SERVER['HTTP_HOST'] . $this->inpath . $mypage . '?t='. GetReq('t') .'&'. $qry . $fragment);				  
			  //echo $_SERVER['HTTP_HOST'] , $this->inpath , $mypage . '?' . $qry . $fragment;
       }
	   
	   return ('index');
   }
   
   protected function _getqueue_old() {		   
		 
	    if ((is_array($_SESSION['dacpost'])) && 
		    (array_key_exists('FormAction',$_SESSION['dacpost']))) {//POST:formaction by other page redirection
          $ret = $_SESSION['dacpost']['FormAction'];
		  //in next post if dacpost->Formaction not exist go to real post above..
		  unset($_SESSION['dacpost']['FormAction']);		 
		  //update post 
		  $_POST = array_merge($_POST,$_SESSION['dacpost']);
		  //update request
		  $_REQUEST = array_merge($_REQUEST,$_SESSION['dacpost']);
		  //free session post
		  unset($_SESSION['dacpost']);	  
		}	   
	    elseif (array_key_exists('FormAction',$_POST)) {//POST:formaction
		  //if post has & query cut it from post
		  $postq = explode('&',$_POST['FormAction']);
		  $ret = $postq[0];// $_POST['FormAction'];
		}  
		elseif (array_key_exists('t',$_GET)) {//GET:t
	      //$ret = $cmd;
		  $t = $_GET['t']; //echo $t,'>';
		  
		  if ($t!=null) {//get t
		    $ret = $_GET['t'];
		  }	
		  else {//redirect to root controller-page	  
		    $current_page = pathinfo($_SERVER['PHP_SELF']);
		    //echo $current_page['dirname'],">>>>",$this->file_path;
		    //if is not the root-page-controller
			if ($this->root_page!=$current_page['basename']) {
			  //echo "::::",$this->file_path.$current_page['basename'];
			  //echo "++++++++<br>";
			  //echo $_SERVER['URL']; print_r($_SERVER);
		      $page = str_replace($this->file_path."/".$current_page['basename'],
			                      "/".$this->root_page,
								  $this->get_server_url());
			  //echo $page;					  
			  //extract '?t=' due to re-queue recursive error 					  
			  $mypage = substr($page,0,strlen($this->root_page)+1);//echo $mypage; die();
			  unset($_GET['t']);			
			  	  
		      $this->redirect($_SERVER['HTTP_HOST'] . $this->inpath . $mypage);				  
			}
			else {
              //echo '<br>',$this->root_page,' ',str_replace("/","",$_SERVER['PHP_SELF']),"<br>";			
			  $ret = 'index';
			}   
		  }	
		} 
		/*elseif (is_array($_GET)) {//GET:the first element of request		
		  //get the first elemnt of request array
		  $req = array_reverse($_GET,true);//reverse to get the last
		  $ret = array_pop($req); //echo $re;
		}*/  
		else {//self name is the standart action 
		  $ret = $this->file_name;
		  //die($ret);
		} 
		//print_r($_GET);print_r($_POST);
		//print_r($_REQUEST);
		//print_r($_SESSION['dacpost']);
		//echo $ret,'<br>';

		if ($ret) {
		  //if action NOT in executed dpc redirect
		  //if is an excluded cmd return basic cmd = page name
		  //cmd is the execuded from some dpc not the default
		  if ($this->get_attribute($this->active($ret),$ret,6)){//exclude cmd
		    //echo "EXCLUDE.....<br>";
			$this->my_excluded_action = $ret;//backup cmd
		    $ret = $this->file_name;//default dpc cmd (never exclude main file cmd)
			return ($ret);
		  }
		  
		  //get the active dpc = this name default
		  $this->myactive = $this->active($this->file_name);	  		  
          //if can't handled by standart=filename dpc 
		  //print_r($this->get_dpcactions_array($this->myactive));
		  if (!@in_array($ret,$this->get_dpcactions_array($this->myactive))) {
		      
		      //check if ret can't handled also by ret dpc
			  if (!@in_array($ret,$this->get_actions_array($ret))) {
		        $this->myactive = $this->active($ret);//update myactive dpc			  
			    //if ret not in this dpc authority redirect...to a page named ret
				//echo "AAAAAAA<br>"; print_r($_SERVER); echo $this->get_server_url();
				//echo $this->get_server_url(); print_r($_SERVER);
		        $page = str_replace($this->file_name.".".$this->file_extension,
				                    $ret.".".$this->file_extension,
									$this->get_server_url());
			    $this->redirect($_SERVER['HTTP_HOST'] . $page);
			  }
		  }	
		  //echo $this->myactive,'>>>>';		  
        }
		else { //goto root page
		  //echo "BBBBBBB<br>";
		  //echo $_SERVER['URL']; print_r($_SERVER);
		  $page = str_replace($this->file_info,
		                      $this->root_page,
							  $this->get_server_url());
		  $this->redirect($_SERVER['HTTP_HOST'] . $this->inpath . $page);		  
		} 
		
		return ($ret); //final return ret
   }
   	
   //public alias: used by dpcs (like frontpage!)
   public function getqueue() {
   
        return ($this->myaction);
   }
   
   protected function getthemicrotime() {
   
     list($usec,$sec) = explode(" ",microtime());
     return ((float)$usec + (float)$sec);
   }  
   
   protected function headers($xmlns=null) {
   
	 //header info
     $char_set  = arrayload('SHELL','char_set');	  
	 $charset = $char_set[getlocal()]; 	  
	 header('Content-Type: text/html; charset=' . $charset);   
     //css info
     $mycss = $this->inpath . "/themes/" . $this->theme . ".theme/" . $this->theme . ".css";
	 //echo $mycss,'>';
     $css = ((isset($this->theme)) ? $mycss : paramload('SHELL','css'));   
     //gen html
     echo "<!-- generated by phpdac5 " . paramload('SHELL','version') . " (c)2001-2005 all rights reserved. -->\n";
     echo "\n<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";	 
	 if ($xmlns)
	   echo "\n<HTML xmlns:$xmlns>";
	 else
       echo "\n<HTML>";
     echo "\n<HEAD>";
     echo "\n<TITLE>";
     echo paramload('SHELL','urltitle');
     echo  "</TITLE>";
	  
	 
     echo  "\n<LINK REL=StyleSheet HREF=\"" . $css ."\">"; 
	 
	 //call javascript 
     if (iniload('JAVASCRIPT')) {	
		   $js = new jscript;
		   
		   if ((defined('SPLASH_DPC'))) {// && ($this->myaction=='index') && (!GetSessionParam('SPLASH'))) {
		     $goto = $_SERVER['PHP_SELF'];
			 $delay = paramload('SPLASH','delay');
		     $code = "\nfunction neu() { top.frames.location.href=\"$goto\" } window.setTimeout(\"neu()\",$delay);\n";
		     $js->load_js($code,null,1);
		   }	 
			 		   
		   $onload = $js->onLoad();
		   $ret .= $js->callJavaS();	 
		   unset ($js);		 
	 }		
	 echo $ret;
	 echo "</HEAD>"; 
	 echo "\n<BODY bgcolor=\"#" . paramload('HTML','h_bkgc') . 
	            "\" text=\"#" . paramload('HTML','h_txtc') . 
		        "\" link=\"#" . paramload('HTML','h_lnkc') . 
		        "\" vlink=\"#" . paramload('HTML','h_vlnc') . 
		        "\" alink=\"#" . paramload('HTML','h_alnc') . 
		        "\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" " . 
		        $onload .">";		    
		 
	  	 
   }       
   
   protected function footers() {
     echo   "\n</BODY>\n</HTML>";
   }
   
   protected function redirect($url) {
   
	 //save virtual post (if any)
	 $_SESSION['dacpost'] = $_POST; 
	 
	 //echo 'REDIRECT:' . $url; die();
	 header("Location: http://".$url); 
     exit;  // seems to affect redirection to inpath directive!!!!!!!
   }
   
   //in case of apache webserver _SERVER attr changes 
   //so...to setup the url for redirect call this func
   protected function get_server_url() {
   
	   if (!ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {//APACHE
	     $url = $_SERVER['REQUEST_URI'];//seems to be common with IIS ?????	   
	   }     
	   else //IIS
	     $url = $_SERVER['URL'];
		 
	   return ($url);	 
   }
   
   //If an HTTP client, e.g. a web browser, requests a page that is part 
   //of a protected realm, the server responds with a 401 Unauthorized status code (below)
   //and includes a WWW-Authenticate header field in his response. 
   //This header field must contain at least one authentication challenge applicable to the requested page.   
   protected function authenticate($realm,$action=null) {
	 
     //Next, the client makes another request, this time including an Authentication 
	 //header field which contains the client's credentials applicable to the server's 
	 //authentication challenge.
	 
     //The exact contents of the WWW-Authenticate and Authentication header fields depend 
	 //on the authentication scheme being used. As of this writing, two authentication 
	 //schemes are in wide use.
	 
     //Basic Access Authentication
     //The basic authentication scheme assumes that your (the client's) credentials 
	 //consist of a username and a password where the latter is a secret known only 
	 //to you and the server.
     //The server's 401 response contains an authentication challenge consisting of 
	 //the token "Basic" and a name-value pair specifying the name of the protected realm. Example:
     //WWW-Authenticate: Basic realm="Control Panel"
	 
     //Digest Access Authentication
     //To securely prevent replay attacks, a more sophisticated procedure is obviously 
	 //neccessary: the digest access authentication scheme.
     //First, the WWW-Authenticate header of the server's initial 401 response contains 
	 //a few more name-value pairs beyond the realm string, including a value called a 
	 //nonce. It is the server's responsibility to make sure that every 401 response 
	 //comes with a unique, previously unused nonce value.
	 
	 if ($verified=GetSessionParam('authverify')==true) {
	   //echo 'H',$verified,'}}';
	   return; //already verified!!!!
	 }  
	 	 
     if ($_SERVER["AUTH_USER"] && $_SERVER["AUTH_PASSWORD"] && ereg("^Basic ", $_SERVER["HTTP_AUTHORIZATION"])) {
       list($_SERVER["AUTH_USER"], $_SERVER["AUTH_PASSWORD"]) = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));
     }
     elseif ($_SERVER["AUTH_USER"] && $_SERVER["AUTH_PASSWORD"] && ereg("^NTLM ", $_SERVER["HTTP_AUTHORIZATION"])) {
       list($_SERVER["AUTH_USER"], $_SERVER["AUTH_PASSWORD"]) = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));
     }	 
     $authenticated = false;
	 
     /*if (($_SERVER["AUTH_USER"] || !$_SERVER["AUTH_PASSWORD"]) && (isset($_SERVER["HTTP_AUTHORIZATION"]))) {
	   die('Authentiacation required!'.$_SERVER["HTTP_AUTHORIZATION"]);
	 }	*/ 
	 
     if ($_SERVER["AUTH_USER"] || $_SERVER["AUTH_PASSWORD"]) {
	   
       //If the server accepts the credentials, it returns the requested page. 
	   //Otherwise, it returns another 401 Unauthorized response to inform the 
	   //client the authentication has failed.	   
	   
       // Put the necessary code for checking u
       //     username/passwords here.	   
	   
	   //switch method = iis user / htaccess
	   //if (defined("RCHTACCESS_DPC")) {
	   if (!ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {
	     $auth_method = 'HTACCESS';
	     $authenticated = GetGlobal('controller')->calldpc_method('rchtaccess.verify_user use '.$_SERVER["AUTH_USER"].'+'.$_SERVER["AUTH_PASSWORD"]);		 
	   }	 
       else {
	     $auth_method = 'NTLM'; 
         $authenticated = ($_SERVER["AUTH_USER"] == "test" && $_SERVER["AUTH_PASSWORD"] == "123");		 	 
	   }
	   	 
	   SetSessionParam('authmethod',$auth_method);
	   SetSessionParam('authverify',$authenticated);	
     }
	 
 
	 //FIRST TIME THE SCRIPT LAND HERE
	 //..(below)the server responds with a 401 Unauthorized status code
     if (!$authenticated) {
       //header("WWW-Authenticate: Basic realm=\"$realm\"");
	   
	   switch ($auth_method) {
	     case 'NTLM' : header("WWW-Authenticate: NTLM",false); break;
		 case 'HTACCESS':
		 default   :header("WWW-Authenticate: Basic realm=\"$realm\"");
	   }
	   
	   $statico = 1;
	   
       if (ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {
         header("Status: 401 Unauthorized");
       } else {
         header("HTTP/1.0 401 Unauthorized");
         echo "Access denied";
         exit;
       }
     }
	 //else {
	 /*echo $authendicated,'>';
	 echo $_SERVER['HTTP_AUTHORIZATION'];
	 echo '<pre>';
	 print_r($_SERVER);	 
	 echo '</pre>';	 
	 echo $action;*/
	 //}
	 
	 if ($goto = GetReq('redirect'))
	   $this->redirect($_SERVER['HTTP_HOST'].'/'.$goto);
	   
	 //elseif ($action)  	 
	   //$this->redirect(seturl('t='.$action));
	 //}
	 return;
	 
	 //TESTING..........
	 
     //if (strstr(PHP_OS, 'WIN')) {//win...IIS
	 if (ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {//windiws IIS
	   list($user,$pwd) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'],6)));  
	   $param = $_SERVER['HTTP_AUTHORIZATION'];
	 }
	 else { //*nix..APACHE
       $user = $_SERVER["AUTH_USER"] ;
	   $pwd = $_SERVER["AUTH_PASSWORD"] ;	   
	   $param = $_SERVER['AUTH_USER'];
	 }	 
	 //echo $param;
	 if (!isset($param)) {

       //header('WWW-Authenticate: Basic realm="Private"');
	   //header('HTTP/1.0 401 Unauthorized');
	   //echo 'Authorization required ';//,$realm;
	   
       header("WWW-Authenticate: Basic realm=\"$realm\",stale=FALSE");
       if (ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {
         header("Status: 401 Unauthorized");
       } 
	   else {
         header("HTTP/1.0 401 Unauthorized");
         echo "Access denied";
         exit;
       }	   

	   exit; 
	 }
	 else {
	   echo '<pre>';
	   print_r($_SERVER);	 
	   echo '</pre>';
	   //echo $user,$pwd,'....',$x;
	   
	   if ($goto = GetReq('redirect'))
	     $this->redirect($goto);
	 }
	   
   }
   
   //read rewrited get parameters
   protected function rewrite() {
   
     $fpathinfo = $_SERVER['ORIG_PATH_INFO'];
	 echo '>',$fpathinfo;
	 
	 $pi = explode(".php",$fpathinfo);
	 $pathinfo = $pi[1];
	 echo '>',$pathinfo,"<br>";
	 
	 if (isset($pathinfo)) {
	 
	   $vardata = explode('/',$pathinfo);
	   $num_param = count($vardata);
	   
	   if ($num_param % 2 == 0) {
	     $vardata[] = '';
		 $num_param++;
	   } 
	   
	   for ($i=1;$i<$num_param;$i+=2) {
	   
	     $$vardata[$i] = $vardata[$i+1];
		 echo $vardata[$i] ,"=", $vardata[$i+1],"<br>";
		 $_GET[$vardata[$i]] = $vardata[$i+1];
	   }
	   //die();
	 }
   }   
   
   protected function create_log() {

	      $srv = $this->agent . "|" . 
		         $_SERVER['REQUEST_METHOD'] . "|" . 
				 $_SERVER['HTTP_HOST'] . "|" . 
				 /*$this->t_shell->value('shell') .*/ "|" . 
				 $this->myaction . "|";
				 
		  $cln = $this->agent . "|" . 
		         $_SERVER['REMOTE_ADDR'] . "|" . 
				 $_SERVER['REMOTE_HOST'] . "|" . 
				 $_SERVER['HTTP_USER_AGENT'] . "|" . 
				 $this->userLevelID . "|" . 
				 /*$this->t_shell->value('shell') .*/ "|" . 
				 $this->myaction . "|";
				 
		  return ("$srv+$cln");
   }    
   
   function __destruct() {
   
      //echo $GLOBALS['DIE'],'zzz';
	  //use die or exit call __destruct remians so...
      //in case of xml output we don't need to show footers
	  //if (($this->auto) && (!$GLOBALS['DIE'])) $this->footers(); //MOVED TO RENDER
	  
	  //////////////////////////////////////////////////////////////////////
	  //update log files
	  if (((defined('LOG_DPC')) && (seclevel('LOG_DPC',$this->userLevelID)))) {
	       //$this->create_log();
		   controller::calldpc_method('log.writelog use '. $this->create_log(),1);
	  }		  
	  
	  if (paramload('SHELL','debug')) 
	    echo "\nTime elapsed: ",$this->getthemicrotime() - $this->mytime, " seconds<br>"; 	  
	      
	  controller::__destruct();   
   }
   
}
?>