<?php

$__DPCSEC['RCOPTS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCOPTS_DPC")) && (seclevel('RCOPTS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCOPTS_DPC",true);

$__DPC['RCOPTS_DPC'] = 'rcopts';
 
$__EVENTS['RCOPTS_DPC'][0]='cpopts';
$__EVENTS['RCOPTS_DPC'][1]='cpsaveopts';

$__ACTIONS['RCOPTS_DPC'][0]='cpopts';
$__ACTIONS['RCOPTS_DPC'][1]='cpsaveopts';

$__DPCATTR['RCOPTS_DPC']['cpopts'] = 'cpopts,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCOPTS_DPC'][0]='RCOPTS_DPC;Option files;Option files';

class rcopts {

	var $post,$title,$path;
	var $myoptions;
	var $myoptdescr;
		
	function rcopts() {
		
	    $this->title = localize('RCOPTS_DPC',getlocal());		
		$this->post = false; //hold successfull posting		
		
		
		//$this->path = paramload('SHELL','prpath'); 
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
		else
		  $this->path = paramload('SHELL','prpath');	
		  
		$this->myoptions = remote_arrayload('RCOPTS','myoptions',$this->path);
		$this->myoptdescr = remote_arrayload('RCOPTS','myoptdescr',$this->path);
	  		
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		

       $sFormErr = GetGlobal('sFormErr');	  	    		  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "cpopts"  : break;
		case "cpsaveopts"      : $this->saveopts();
		                         $this->post = true;
		                         break;
       }
      }   
    }
  
    function action($action) {

	 $out = $this->optsform();
	 
	 return ($out);
    } 
	  
  
  function optsform() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=cpsaveopts");
	 
	 if (GetSessionParam('REMOTELOGIN')) 
	   $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
       $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
	   	 
	 	 
	 if ($this->post==true) {   	   	   
	   
	   $msg = "Data submited!";
	   
	   $swin = new window("Post",$msg);
	   $out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	   unset ($swin);
	   
	 }
	 else { //show the form plus error if any
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   
	   $form = new form(localize('_RCOPTS',getlocal()), "RCOPTS", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("options",			"Options.");       	   
	   //$form->addGroup			("cats",			"Categories."); 	   
       
	   if (!empty($this->myoptions)) {
	     foreach ($this->myoptions as $id=>$optfile) 
	              $form->addElement		("options",new form_element_textarea($this->myoptdescr[$id],  $optfile,$this->loadfromfile($optfile.".opt"),		"formtextarea",	80,	5));
	   }			

	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsaveopts"));
 
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
  
  function saveopts() {
  
	if (!empty($this->myoptions)) {
	   foreach ($this->myoptions as $id=>$optfile) 
	          $this->write2file($optfile.'.opt',getParam($optfile));
	}		  

  }
  
  function loadfromfile($filename) {
	 
	 $file = $this->path . $filename;
	 
     if ($fp = @fopen ($file , "r")) {
                 $ret = fread ($fp, filesize($file));
                 fclose ($fp);
     }
     else {
         $this->msg = "File reading error !\n";
		 echo "File reading error ($filename)!<br>";
     }	
	 
	 return ($ret);
  }

  
  function write2file($filename,$data) {
	 
	 $file = $this->path . $filename;
	 
     if ($fp = @fopen ($file , "w")) {
	    //echo $file,"<br>";
                 fwrite ($fp, $data);
                 fclose ($fp);
     }
     else {
         $this->msg = "File creation error !\n";
		 echo "File creation error ($filename)!<br>";
     }	
  }
  
};
}
?>