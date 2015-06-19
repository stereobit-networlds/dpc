<?php
require_once("pcntl.lib.php");

//require_once("dpc/gui/ajax.dpc.php");

define("PCNTLAJAX_DPC",true);
$__DPC['PCNTLAJAX_DPC'] = 'pcntlajax'; //must exist to communicate with others dpcs as cache,supercache ...

class pcntlajax extends pcntl {

   var $ajax_var;

   function __construct($code=null,$auto=null,$locales=null,$css=null,$page=null) { 

	  parent::__construct($code,$auto,$locales,$css,$page);	
	  
	  //indicates whenever this class will behave like ajax responder	
	  //or as common pcntl request.
	  $this->ajax_var = GetReq('ajax');   
   }
  
   //overwrite 
   public function render($theme=null,$lan=null,$cl=null,$fp=null) { 
      
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
	  
	  	  
	  if ($this->ajax_var) { //ajax response
	    
	    $hfp = new ajaxhtmlpage($fp,null,$appi);  
	    $ret = $hfp->render($this->data);
	    unset($hfp);	  
	  }
	  else {//common pcntl response
	  
	    if ($this->auto) {//after action to handle js loaded in action
	      $this->headers();
	    } 
	  	  
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
	  }
	  
	  //moved from destructor
      if ((!$this->ajax_var) && ($this->auto)) $this->footers();	  
	  
	  if ($this->debug) 
	    echo "\naction elapsed: ",$this->getthemicrotime() - $atime, " seconds<br>"; 	    
	
	  return ($ret); 	  	  
   }   
   
   //overwrite
   function __destruct() {
   
      //if ((!$this->ajax_var) && ($this->auto)) $this->footers(); //MOVED TO RENDER
	  
	  //////////////////////////////////////////////////////////////////////
	  //update log files
	  if (((defined('LOG_DPC')) && (seclevel('LOG_DPC',$this->userLevelID)))) {
	       //$this->create_log();
		   controller::calldpc_method('log.writelog use '. $this->create_log());
	  }		  
	  
	  if (paramload('SHELL','debug')) 
	    echo "\nTime elapsed: ",$this->getthemicrotime() - $this->mytime, " seconds<br>"; 	  
	      
	  controller::__destruct();   
   }   
}
?>