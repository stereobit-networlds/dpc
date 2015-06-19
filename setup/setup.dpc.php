<?php
if (defined("JAVASCRIPT_DPC")) {

$__DPCSEC['SETUP_DPC']='1;1;1;1;1;1;1;1;2';

if ( (!defined("SETUP_DPC")) && (seclevel('SETUP_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SETUP_DPC",true);

$__DPC['SETUP_DPC'] = 'setup';

$__EVENTS['SETUP_DPC'][0]='setup';
$__EVENTS['SETUP_DPC'][1]='info';


$__ACTIONS['SETUP_DPC'][0]='setup';
$__ACTIONS['SETUP_DPC'][1]='info';

$__LOCALE['SETUP_DPC'][0] = '_SETUP;Setup;Εγκατάσταση';
$__LOCALE['SETUP_DPC'][1] = '_WIDTH;Width;Μήκος';
$__LOCALE['SETUP_DPC'][2] = '_HEIGHT;Height;Πλάτος';
$__LOCALE['SETUP_DPC'][3] = '_cDEPTH;Color Depth;Βάθος Χρώματος.';
$__LOCALE['SETUP_DPC'][4] = '_pDEPTH;Pixel Depth;Βάθος Εικονοστοιχείου';
$__LOCALE['SETUP_DPC'][5] = '_BROWSER;Browser;Πρόγραμμα οδήγησης';
$__LOCALE['SETUP_DPC'][6] = '_OS;Client Operating System;Λειτουργικό Συστημα Χρήστη';


class setup {

    var $browserinfo;
	var $jscript_on;
	
	var $after_install_command;

	function setup() {
	
       $this->after_install_command = paramload('SETUP','nextcmd');	

	   $this->browserinfo = (array) GetSessionParam("browserinfo");
	   //print_r($this->browserinfo);	   
	   
	}

    function event($evn) {
      $__USERAGENT = GetGlobal('__USERAGENT');	
	  $a = GetReq('a'); 	
	
       switch ($evn) {		
          case "setup"    : switch ($__USERAGENT) {
	                              case 'HTML' : $this->load_jscript($a); 
								                $this->store_data($a);
                                                $data = serialize($this->browserinfo);
                                                setcookie("webOSsetup", $data);//, time() + 3600);//, "/",".stereobit.com",0);												
								                break;
                                  case 'GTK'  : break;
                                  case 'TEXT' : break;		
                                  case 'WAP'  : break;		
                                  case 'PDA'  : break;											   
	                        }	
							break;	   
          case "info"    : break;					 	  		  
       }	
	}	

	function action($act) {
      $__USERAGENT = GetGlobal('__USERAGENT');	
	  $a = GetReq('a'); 	  
	
      switch ($act) {	   					 	  
          case "setup"    : switch ($__USERAGENT) {
	                              case 'HTML' : $out = $this->html_setup($a); break;
                                  case 'GTK'  : $out = "GTK setup NOT available\n"; break;
                                  case 'TEXT' : $out = "TEXT setup NOT available\n"; break;		
                                  case 'WAP'  : $out = "WAP setup NOT available\n"; break;		
                                  case 'PDA'  : $out = "PDA setup NOT available\n"; break;											   
	                        }	
							break;		  
          case "info"     : switch ($__USERAGENT) {
		                          case 'HTML' : $out = implode(",",$this->browserinfo); break;
                                  case 'GTK'  : $out = "GTK setup info NOT available\n"; break;
                                  case 'TEXT' : $out = "TEXT setup info NOT available\n"; break;		
                                  case 'WAP'  : $out = "WAP setup info NOT available\n"; break;		
                                  case 'PDA'  : $out = "PDA setup info NOT available\n"; break;									  
		                    }
		                    break;  								
      }		
	    
	    
	  return ($out);
	}
	
	function store_data($step) {
	
		   switch ($step) {	
	        case 2 : $this->browserinfo[javascript] = GetParam("j1"); break;		
	        case 3 : $this->browserinfo[browser] = GetParam("b1"); break;
	        case 4 : $this->browserinfo[os] = GetParam("o1"); break;			
	        case 9 : $this->browserinfo[width] = GetParam("t1"); 
			         $this->browserinfo[height] = GetParam("t2");
					 $this->browserinfo[colordepth] = GetParam("t3");
					 $this->browserinfo[pixeldepth] = GetParam("t4");
			         break;		   
	       }	
		   
		   SetSessionParam("browserinfo",$this->browserinfo);
	}
	
	function load_jscript($step) {
	
        if (iniload('JAVASCRIPT')) {

		   switch ($step) {	
	        case 1 : $code = $this->jscript_javascript_detection(); break;		
	        case 2 : $code = $this->jscript_browser_detection(); break;
	        case 3 : $code = $this->jscript_os_detection(); break;			
	        case 4 : $code = $this->jscript_screen_detection(); break;		   
	       }
		   
		   if ($code) {
		     $js = new jscript;
             $js->load_js($code,"",1);
			 
		     switch ($step) {	
	           case 1 : $js->setLoadParams("browser_javascript_test()"); break;		
	           case 2 : $js->setLoadParams("browser_type()"); break;
	           case 3 : $js->setLoadParams("ostype_detect()"); break;			   
	           case 4 : $js->setLoadParams("screen_detect()"); break;		   
	         }			 
			 		   
		     unset ($js);
		   }
	    }		
	}
	
	function html_setup($step=0) { 
	  
      if ( ($step==2) && (!$this->is_javascript_enabled()) ) { //javascript detection result
	    $out = $this->raise_error("javascript error!");
		
		return ($out);
      }
	
	  switch ($step) {
        case 0 : $out = $this->wellcome_setup(); break;	  
        case 1 : $out = $this->javascript_detection();break;		
	    case 2 : $out = $this->browser_detection(); break;		
	    case 3 : $out = $this->os_detection(); break;
	    case 4 : $out = $this->screen_detection(); break;	

		//...
		//...
		case 9 : $out = $this->terminate_setup(); break; 	
	  }

	  return ($out);
	}
	
	function wellcome_setup() {
	
	   $out = "Wellcome," .
	          "\nStart setup :";
			  
	   $fwin = new dialog(localize('_SETUP',getlocal()),$out,"t=setup&a=1","setup");
	   $wout .= $fwin->render();	
	   unset ($fwin);			  
			  
	   return ($wout);		  
	}
	
	function terminate_setup() {
	
	   $out = "End of setup";// . seturl("t=$this->after_install_command&a=&g=","End");
			  
	   $fwin = new dialog(localize('_SETUP',getlocal()),$out,"t=$this->after_install_command&a=&g=");
	   $wout .= $fwin->render();	
	   unset ($fwin);			  
			  
	   return ($wout);	
	}
	
	function raise_error($error) {
	
	   $out = "Unexpected end of setup : $error";
	   
	   $fwin = new dialog(localize('_SETUP',getlocal()),$out,"t=&a=&g=");
	   $wout .= $fwin->render();	
	   unset ($fwin);	   
			  
	   return ($wout);	
	}	
	
	
	//SCREEN DETECTION
	function jscript_screen_detection() {
	
	   $out = " 
				function screen_detect(){
					if (document.all||document.getElementById||document.layers){
						document.t.t1.value=screen.width
						document.t.t2.value=screen.height
						document.t.t3.value=screen.colorDepth
						document.t.t4.value=screen.pixelDepth
					}
				}\n";	
				
	   return ($out);			
	}
	
	function screen_detection() {
	
      $myaction = seturl("t=setup&a=9");   
	    
      $out = setNavigator(localize('_SETUP',getlocal()));	
	
	  $form = new form(localize('_SETUP',getlocal()), "t", FORM_METHOD_POST, $myaction);
		
 	  $form->addGroup		("screen",			"Screen Resolution");		
	  
      $form->addElement		("screen",			new form_element_text		(localize('_WIDTH',getlocal()), "t1",			"",				"forminput",		    20,				255,	0));
	  $form->addElement		("screen",			new form_element_text		(localize('_HEIGHT',getlocal()),"t2",			"",				"forminput",	        20,				255,	0));
	  $form->addElement		("screen",			new form_element_text		(localize('_cDEPTH',getlocal()),"t3",			"",				"forminput",			20,				255,	0));
	  $form->addElement		("screen",			new form_element_text		(localize('_pDEPTH',getlocal()),"t4",			"",				"forminput",			20,				255,	0));
	 
	  // Adding a hidden field
	  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "setup"));
	
	  // Showing the form
	  $out = $form->getform ();		
	  $fwin = new window(localize('_SETUP',getlocal()),$out);
	  $wout .= $fwin->render();	
	  unset ($fwin);	     
	 
      return ($wout);	  	  
	}
	
	//BROWSER DETECTION
	function jscript_browser_detection() {
	
	  $out = "function browser_type () {
				var browser_type=navigator.appName
				var browser_version=parseInt(navigator.appVersion)

				//if NS 6
				if (browser_type==\"Netscape\"&&browser_version>=5)
				document.b.b1.value=\"Netscape 5+\";
				//if IE 4+
				else if (browser_type==\"Microsoft Internet Explorer\"&&browser_version>=4)
				document.b.b1.value=\"IExplorer 4+\";
				//if NS4+
				else if (browser_type==\"Netscape\"&&browser_version>=4)
				document.b.b1.value=\"Netscape 4+\";
				//Default goto page (NOT NS 4+ and NOT IE 4+)
				else
				document.b.b1.value=\"Unknown\";}";
				
		return ($out);			
	}
	
	function browser_detection() {
	
      $myaction = seturl("t=setup&a=3");   
	    
      $out = setNavigator(localize('_SETUP',getlocal()));	
	
	  $form = new form(localize('_SETUP',getlocal()), "b", FORM_METHOD_POST, $myaction);
		
 	  $form->addGroup			("browser",			"Browser");		
	  
      $form->addElement		("browser",			new form_element_text		(localize('_BROWSER',getlocal()), "b1",			"",				"forminput",		    20,				255,	0));
 
	  // Adding a hidden field
	  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "setup"));
	
	  // Showing the form
	  $out = $form->getform ();		
	  $fwin = new window(localize('_SETUP',getlocal()),$out);
	  $wout .= $fwin->render();	
	  unset ($fwin);	     
	 
      return ($wout);	
	}
	
	//JAVASCRIPT SUPPORT
	function jscript_javascript_detection() {
	
	  $out = "function browser_javascript_test () 
	      	  {document.j.j1.value=\"JavaScript Ok!!!\"}";
		
	  return ($out);		  
	}

	function javascript_detection() {
	  
      $myaction = seturl("t=setup&a=2");   
	    
      $out = setNavigator(localize('_SETUP',getlocal()));	
	
	  $form = new form(localize('_SETUP',getlocal()), "j", FORM_METHOD_POST, $myaction);
		
	  $form->addGroup			("javascript",			"Javascript detection");		
	  
      //$form->addElement		("javascript",			new form_element_text		(localize('_JS',getlocal()), "j1",			"",				"forminput",		    20,				255,	0));
 		
		
	  // Adding a hidden field
	  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("j1", ""));	  
	  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "setup"));
	
	  // Showing the form
	  $out = $form->getform ();		
	  $fwin = new window(localize('_SETUP',getlocal()),$out);
	  $wout .= $fwin->render();	
	  unset ($fwin);	     
	 
      return ($wout);		  
	   
	}
	
	function is_javascript_enabled() {

	  if (GetParam("j1")) return 1;
	}
	
	
	//OS DETECT
	function jscript_os_detection() {
	
	  $out = "function ostype_detect() {  
		  if((navigator.userAgent.indexOf('Win') != -1) &&
		  (navigator.userAgent.indexOf('95') != -1))
		  document.o.o1.value=\"Win\";
		  else if(navigator.userAgent.indexOf('Win') != -1)
		  document.o.o1.value=\"Win\";
		  else if(navigator.userAgent.indexOf('Mac') != -1)
		  document.o.o1.value=\"Mac\";
		  else if(navigator.userAgent.indexOf('IRIX') != -1)
		  document.o.o1.value=\"Irix\";		  
		  else document.o.o1.value=\"Unx\";
	 	  }";
		  
	   return ($out);	  
	}
	
	function os_detection() {
	  
      $myaction = seturl("t=setup&a=4");   
	    
      $out = setNavigator(localize('_SETUP',getlocal()));	
	
	  $form = new form(localize('_SETUP',getlocal()), "o", FORM_METHOD_POST, $myaction);
		
	  $form->addGroup		("os",			"Client's Operating System");		
	  
      $form->addElement		("os",			new form_element_text		(localize('_OS',getlocal()), "o1",			"",				"forminput",		    20,				255,	0));
 		
		
	  // Adding a hidden field  
	  $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "setup"));
	
	  // Showing the form
	  $out = $form->getform ();		
	  $fwin = new window(localize('_SETUP',getlocal()),$out);
	  $wout .= $fwin->render();	
	  unset ($fwin);	     
	 
      return ($wout);		  
	   
	}		
	
};
}
}
else die("JAVASCRIPT DPC REQUIRED!");
?>