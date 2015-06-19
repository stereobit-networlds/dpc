<?php

$__DPCSEC['CONFBAR_DPC']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['CMDLINE_']='2;1;1;1;1;1;1;1;9';
$__DPCSEC['THEMSEL_']='2;1;1;1;1;2;2;2;9';
$__DPCSEC['LANGSEL_']='1;1;1;2;2;2;2;2;9';
$__DPCSEC['UTILITIES_']='2;1;1;1;1;1;1;1;9';

if /*(*/ (!defined("CONFBAR_DPC")) {//&& (seclevel('CONFBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CONFBAR_DPC",true);

$__DPC['CONFBAR_DPC'] = 'confbar';

$__EVENTS['CONFBAR_DPC'][0]='lang';
$__EVENTS['CONFBAR_DPC'][1]='setlanguage';

$__ACTIONS['CONFBAR_DPC'][0]='lang';
$__ACTIONS['CONFBAR_DPC'][1]='setlanguage';

$__LOCALE['CONFBAR_DPC'][0]='_LANMSG;The selected languange is ;Η επιλεγμένη γλώσσα είναι ';

$__DPCATTR['CONFBAR_DPC']['lang'] = 'lang,0,0,0,0,0,0,0,0,0,0,1';
$__DPCATTR['CONFBAR_DPC']['setlanguage'] = 'setlanguage,0,0,0,0,0,0,0,0,0,0,0';

$__PARSECOM['CONFBAR_DPC']['render']='_CONFBAR_';

//GetGlobal('controller')->set_proccess('CONFBAR_DPC',2);

class confbar {

	var $userLevelID;
	var $submit_button;
	
	var $agent;
	var $selected_lan;
	var $lan_set;

	function confbar() {
	  $UserSecID = GetGlobal('UserSecID');
	  $GRX = GetGlobal('GRX');
      $__USERAGENT = GetGlobal('__USERAGENT');	  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	  $this->agent = $__USERAGENT;
	  
      if ($GRX) { 
          $this->submit_button  = "<input type=\"image\" src=\"". loadTheme('gosearch_b','search',1) ."\" width=\"21\" height=\"21\" border=\"0\">";  
	  }	  
	  else { 
          $this->submit_button  = "<input type=\"submit\" name=\"Submit\" value=\"Ok\">";
	  } 
	  
      if (iniload('JAVASCRIPT')) {	
		 $js = new jscript;
	   		 
		 //load code
         //$js->load_js('getmouse.js','1.2');	---------------------------  	  
		 unset ($js);
	  }	
	  
	  $this->lan_set = arrayload('SHELL','languages');
	    	 
	}
	
	function event($action) {
	   $param1 = GetGlobal('param1');
  
       switch ($action) {
		  case "lang"         : $this->selected_lan = GetParam("langsel");
		                        //setlocal($this->selected_lan);//moved to action !!!!!
		                        //echo GetParam("langsel"),">>>>>"; 
                                        //echo $this->get_server_url();
                                        break;
		  case "setlanguage"  : $this->selected_lan = $param1;
		                        setlocal($this->selected_lan); break; //command line 
	   }	  	   	
	}	
	
	function action($action) {
       switch ($action) {
		  case "lang"         :setlocal($this->selected_lan);
		                       $out = /*localize('_LANMSG',getlocal()) . */$this->lan_set[$this->selected_lan]; 
		                       break; 
		                       //cli ver
		  case "setlanguage"  :echo "Current language:",$this->lan_set[$this->selected_lan],"\n";  
	   }
	   
	   return ($out);	
	}	

    function render($pw='100%',$pal='') { 
	   $__USERAGENT = GetGlobal('__USERAGENT');		
	   $a = GetReq('a');
	   $g = GetReq('g');
	   $p = GetReq('p');	   	   
		
       switch ($__USERAGENT) {	
	   
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $out = $this->commandline();
			               break;
		     case 'CLI'  :
	         case 'TEXT' : break;		   
	         case 'HTML' :	
			 default     :	  
	     					//select theme		
 	     					$data[] = $this->selectTheme(1);
		 					$attr[] = "left;20%";	

	     					//select language		
 	       					$data[] = $this->selectLocale(1);
		   					$attr[] = "left;20%";	

         					//command line tool
           					$data[] = $this->commandline(1);
		   					$attr[] = "left;30%";	     	
							
							//UTILITIES
 	     					$data[] = $this->utilities();
		 					$attr[] = "left;30%";	
								/*							 
		 					//print page
		 					if (iniload('JAVASCRIPT')) {
           					  //mouse xy values 
           					  //$data[] = 0;//$this->showMouseXY();-----------------------	
  	       					  //$attr[] = "right;20%;";		 		 					 
		 
		   					  $js = new jscript;
	       					  $data[] = $js->JS_function("js_printwin",localize('_PRINT',getlocal()).";1;");	 
           					  unset ($js);
  	       					  $attr[] = "right;8%;";					  	   		   		   		   
		 					}
         					//fullscreen
         					if (paramload('SHELL','fscreen')) { 
	       					  $fscreen = GetSessionParam("fullscreen");
           					  if ($fscreen) $data[] = seturl("t=fullscreen&a=$a&g=$g&p=$p","]["); 
   	                		  		   else $data[] = seturl("t=fullscreen&a=$a&g=$g&p=$p","[]"); 
  	       					  $attr[] = "right;2%;";					
         					}*/

	     					$confwin = new window('',$data,$attr);
	     					$out = $confwin->render("$pal::100%::0::groupconf::left;100%;::0::0");
		 					unset($confwin);	
		 					break;
		 } 

	     return ($out);
    }
	
   function utilities() {
   
     if (seclevel('UTILITIES_',$this->userLevelID)) {  
	 
		 					//print page
		 					if (iniload('JAVASCRIPT')) {
           					  //mouse xy values 
           					  $ret = '';//$this->showMouseXY();-----------------------	 		 					 
		 
		   					  $js = new jscript;
	       					  $ret .= $js->JS_function("js_printwin",localize('_PRINT',getlocal()).";1;");	 
           					  unset ($js);				  	   		   		   		   
		 					}
         					//fullscreen
         					if (paramload('SHELL','fscreen')) { 
	       					  $fscreen = GetSessionParam("fullscreen");
           					  if ($fscreen) $ret .= seturl("t=fullscreen&a=$a&g=$g&p=$p","]["); 
   	                		  		   else $ret .= seturl("t=fullscreen&a=$a&g=$g&p=$p","[]"); 				
         					}	  
							
							return ($ret);
	 }
   }	

   function commandline($title=0) {
	 $a = GetReq('a');
	 $g = GetReq('g');
	 $t = GetReq('t');	

     if ((seclevel('CMDLINE_',$this->userLevelID)) && 
	     (paramload('SHELL','comline'))) {
	 
	   $uri = seturl("t=$t&a=$a&g=$g");
	 
	   switch ($this->agent) {
	   
	     case 'XML'  : //xml 
		               $xml = new pxml();
					   $xml->addtag('FORM',null,null,"action=$uri;method=POST;class=thin");
					   $xml->addtag('LABEL','FORM','/',"for=cmd;accesskey=C");
					   $xml->addtag('INPUT','FORM','/',"type=text;name=docomm;maxlength=255;value=;size=15;id=cmd;title=".localize('_COMLINE',getlocal()));
					   $xml->addtag('INPUT','FORM','/',"type=submit;value=Ok");
					   $xml->addtag('HIDDEN','FORM','/',"name=FormAction;value=command");
					   $toprint .= $xml->getxml();
					   unset($xml);
		               break;
					   
		 case 'XUL'  :		 
	     case 'GTK'  : $xml = new pxml('XUL'); //emulate article tag as page component
		               //$xml->addtag('XUL','ARTICLE',null,null);			 		 
					   $xml->addtag('GTKFORM','XUL',null,'id=cmdline|label=Command Line');
			           $xml->addtag('label','GTKFORM','/',"control=usr|value=".localize('_COMLINE',getlocal()));			 
					   $xml->addtag('textbox','GTKFORM','/',"id=docomm|maxlength=64");					   			 		 
			           //$xml->addtag('label','GTKFORM','/',"control=usr|value=".localize('_COMLINE',getlocal()));			 
					   //$xml->addtag('textbox','GTKFORM','/',"id=FormAction|maxlength=64");	//hidden field		
					   $xml->addtag('HIDDEN','GTKFORM','/',"id=FormAction;value=command");					   		   
					   $toprint = $xml->getxml(); 
					   unset($xml);
		               break;					   
		 			   
         case 'HTML' : 
                       //print command line form
                       $toprint .= "<form action=\"$uri\" method=\"POST\" class=\"thin\">";
                       if ($title) $toprint .= "<b>" . localize('_COMLINE',getlocal()) ." :</b>";
	                   $toprint .= "<LABEL for=\"cmd\" accesskey=\"C\">";
	                   $toprint .= "<input type=\"text\" name=\"docomm\" maxlength=\"255\" value=\"\" size=\"15\" id=\"cmd\">";
                       $toprint .= $this->submit_button ;//"<input type=\"submit\" value=\"Ok\">";
                       $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"command\">";	
	                   $toprint .= "</form>\n";
					   break;
					   
		 case 'WAP' :  $data[] = '<input name="Cmd"/>' . 
		 		                 '<anchor>+ Ok<go href="'.$uri .'">' .
				                 '<postfield name="docomm" value="$(Cmd)"/>' .
			                     '</go></anchor>' . 
								 '<a href="#card2">Result</a>'; //link next page = result 
					   $attr[] = "";			  
		               $card = new window('Cmd:',$data,$attr);
					   $toprint = $card->render();
					   unset($card);
		               break; 			   
	   
	   }
     }
     return ($toprint);	 
   }

   /////////////////////////////////////////////////////////////////
   // generate themes selection list
   /////////////////////////////////////////////////////////////////
   function selectTheme($title=0) {   
   
     if (seclevel('THEMSEL_',$this->userLevelID)) {
	
      $theme = GetGlobal('theme');
      $thema = GetGlobal('thema');
      $thisname = seturl(""); 	 

      $themespath = paramload('SHELL','prpath') . $theme['path'];
      $themesdir = dir($themespath); //get directory path
      $dmeter=0;
      $themes = array(); 
   
      //parse theme directory
      while ($filename = $themesdir->read ()) {
   
       if (stristr ($filename,'.theme')) {
	     $mytheme = str_replace (".theme", "", $filename);  
         $themes[] = $mytheme;		
	     $dmeter += 1; 
	   }  
      }
      if (!$dmeter) { if (paramload('SHELL','debug')) echo "no directories"; }
	 
      $themesdir->close ();
   
      if ($dmeter) { 
       reset ($themes);
       asort ($themes);

       //print theme selection list
       $toprint .= "<form action=\"$thisname\" method=\"POST\" class=\"thin\">";
       if ($title) $toprint .= "<b>" . localize('_THEME',getlocal()) . " :</b>";
	   $toprint .= "<select name=\"themesel\">\n";

       //read theme array 
       //while (list ($theme_num, $theme_descr) = each ($themes)) {
       foreach ($themes as $theme_num => $theme_descr) {	
	     if ($theme_descr == $thema) $sel = "selected"; else $sel = "";
         $toprint .= "<OPTION $sel value=\"$theme_descr\">$theme_descr</OPTION>\n";
       }
	 
       $toprint .= $this->submit_button ;//"<input type=\"submit\" value=\"Ok\">";
       $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"theme\">";	
	   $toprint .= "</form>\n";
      }
      else {
       $toprint = "No Themes"; 
      }
   
      return ($toprint);
	 }
   }

   /////////////////////////////////////////////////////////////////
   // generate language selection list
   /////////////////////////////////////////////////////////////////
   function selectLocale($title=0,$flags=null) {
   
     if ((seclevel('LANGSEL_',$this->userLevelID)) && 
	     (paramload('SHELL','multilang'))) {   
      $lans = array();   //print_r($_SERVER);
	  $purl =parse_url($this->get_server_url());//echo $_SERVER['PHP_SELF'];
	  $query = $purl['query']; //echo '>',$query;
      $thisname = seturl($query);  
	
      $lans = getlans(); 
   
      if ($lans) { 
       reset ($lans);
       //asort ($lans);

	   if ($flags) {
	   
         foreach ($lans as $lan_num => $lan_descr) {	
		   
		   $flag  = loadTheme('flag_'.$lan_num,$lan_descr);
		   $toprint .= seturl('t=lang'.'&langsel='.$lan_num,$flag) . "&nbsp;";
		 }	     
	   }
	   else {
         //print theme selection list
         $toprint .= "<form action=\"$thisname\" method=\"POST\" class=\"thin\">";
         if ($title) $toprint .= "<b>" . localize('_LOCAL',getlocal()) . " :</b>";
	     $toprint .= "<select name=\"langsel\">\n";

         //read theme array 
	     $selected = getlocal();
         foreach ($lans as $lan_num => $lan_descr) {	
	       if ($lan_num == $selected) $sel = "selected"; else $sel = "";
           $toprint .= "<OPTION $sel value=\"$lan_num\">$lan_descr</OPTION>\n";
         }
	 
         $toprint .= $this->submit_button ;//"<input type=\"submit\" value=\"Ok\">";   
         $toprint .= "<input type=\"hidden\" name=\"FormName\" value=\"Lang\">";	   
         $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"lang\">";	
	     $toprint .= "</form>\n";
	   }
      }
      else {
       $toprint = "No Langs"; 
      }
   
      return ($toprint);
	 } 
   }
   
   function showMouseXY() {
   
       $out = '<form name="Show" class=\"thin\"><input type="text" name="MouseX" value="0" size="4">' .
	          '<input type="text" name="MouseY" value="0" size="4"></form>';	   
			  
	   return ($out);		  
   }

   function get_server_url() {
   
	   if (!ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"])) {//APACHE
	     $url = $_SERVER['REQUEST_URI'];//seems to be common with IIS ?????	   
	   }     
	   else //IIS
	     $url = $_SERVER['URL'];
		 
	   return ($url);	 
   }   

};
}
?>