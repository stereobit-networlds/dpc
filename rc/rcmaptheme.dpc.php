<?php

$__DPCSEC['RCMAPTHEME_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCMAPTHEME_DPC")) && (seclevel('RCMAPTHEME_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCMAPTHEME_DPC",true);

$__DPC['RCMAPTHEME_DPC'] = 'rcmaptheme';
 
$__EVENTS['RCMAPTHEME_DPC'][0]='cpmaptheme';
$__EVENTS['RCMAPTHEME_DPC'][1]='cpsavemap';

$__ACTIONS['RCMAPTHEME_DPC'][0]='cpmaptheme';
$__ACTIONS['RCMAPTHEME_DPC'][1]='cpsavemap';

$__DPCATTR['RCMAPTHEME_DPC']['cpmaptheme'] = 'cpmaptheme,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCMAPTHEME_DPC'][0]='RCMAPTHEME_DPC;Map Theme;Map Theme';

class rcmaptheme {

	var $post,$title,$path;
		
	function rcmaptheme() {
		
	    $this->title = localize('RCMAPTHEME_DPC',getlocal());		
		$this->post = false; //hold successfull posting		
		
		
		//$this->path = paramload('SHELL','prpath'); 
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";	
		else
		  $this->path = paramload('SHELL','prpath');			
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
       $sFormErr = GetGlobal('sFormErr');	  	    		  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "cpmaptheme"  : break;
		case "cpsavemap"      : $this->saveopts();
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
	 
     $myaction = seturl("t=cpsavemap");
	 
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
	   
	   
	   $form = new form(localize('_RCMAPTHEME',getlocal()), "RCMAPTHEME", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("a",			"Themes file definition.");
	   $form->addGroup			("b",			"Locales file definition.");	       	   
	   $form->addGroup			("c",			"Cp Menu.");	   
	   $form->addGroup			("d",			"DPC Attributes.");	   

       $form->addElement		("a",new form_element_textarea("Themes",  "themes",$this->loadfromfile("maptheme.ini"),		"formtextarea",	80,	5));
	   $form->addElement		("b",new form_element_textarea("Locales","locales",$this->loadfromfile("locale.csv"),	"formtextarea",	80,	5));
	   $form->addElement		("c",new form_element_textarea("CPmenu",  "cpmenu",$this->loadfromfile("cpmenu.txt"),	"formtextarea",	80,	5));
	   $form->addElement		("d",new form_element_textarea("Attributes",  "attr",$this->loadfromfile("attr.csv"),	"formtextarea",	80,	5));

	   //$form->addElement		("software",new form_element_textarea("Operating systems",  "opsys",$this->loadfromfile("opersys.opt"),	"formtextarea",	80,	5));
	   //$form->addElement		("software",new form_element_textarea("Databases",  "dbases",$this->loadfromfile("dbenv.opt"),	"formtextarea",	80,	5));
       //$form->addElement		("software",new form_element_textarea("Programming languages",  "plans",$this->loadfromfile("proglan.opt"),	"formtextarea",	80,	5));
	   //$form->addElement		("software",new form_element_textarea("User interfaces",  "uint",$this->loadfromfile("userint.opt"),	"formtextarea",	80,	5));	   

	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpsavemap"));
 
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
  
    //backup
	copy($this->path.'maptheme.ini',$this->path.'maptheme.bak');
	copy($this->path.'locale.csv',$this->path.'locale.bak');
	copy($this->path.'cpmenu.txt',$this->path.'cpmenu.bak');  
	copy($this->path.'attr.csv',$this->path.'attr.bak');	
	
    $this->write2file('maptheme.ini',getParam('themes'));
	$this->write2file('locale.csv',getParam('locales'));
	$this->write2file('cpmenu.txt',getParam('cpmenu'));
	$this->write2file('attr.csv',getParam('attr'));	
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