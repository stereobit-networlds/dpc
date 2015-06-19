<?php

$__DPCSEC['SCOL_DPC']='2;1;1;1;1;1;1;2;9';
$__DPCSEC['SCOL0_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL1_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL2_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL3_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL4_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL5_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL6_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL7_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL8_']  ='2;1;1;1;1;2;2;2;9';
$__DPCSEC['SCOL9_']  ='2;1;1;1;1;2;2;2;9';

if ((!defined("SCOL_DPC")) && (seclevel('SCOL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SCOL_DPC",true);

$__DPC['SCOL_DPC'] = 'scol';

$__EVENTS['SCOL_DPC'][0] = 'scol';

$__ACTIONS['SCOL_DPC'][0]= 'scol';

$__LOCALE['SCOL_DPC'][0]='SCOL_DPC;Scol;Scol';
$__LOCALE['SCOL_DPC'][1]='_SCOL0;Photo;Φωτο';
$__LOCALE['SCOL_DPC'][2]='_SCOL1;Name;Όνομα';
$__LOCALE['SCOL_DPC'][3]='_SCOL2;Avatar;Εικόνα';
$__LOCALE['SCOL_DPC'][4]='_SCOL3;Pager;Ειδοποιήση';
$__LOCALE['SCOL_DPC'][5]='_SCOL4;WebCam;Κάμερα';
$__LOCALE['SCOL_DPC'][6]='_SCOL5;Ignore;Μάυρη Λιστα';
$__LOCALE['SCOL_DPC'][7]='_SCOL6;Speak;Ομιλία';
$__LOCALE['SCOL_DPC'][8]='_SCOL7;Sound Dialog;Ηχητικός Διάλογος';
$__LOCALE['SCOL_DPC'][9]='_SCOL8;Sound ON;Ηχοσ ΝΑΙ';
$__LOCALE['SCOL_DPC'][10]='_SCOL9;Sound OFF;Ηχος ΟΧΙ';


class scol {
   
    var $datadir;
	var $enter; 
	
	var $scolurl;
	var $scolport;
	var $scolwindow;
	var $scolobject;

    function scol() {
	
	   $this->datadir = paramload('SHELL','prpath');	
	   
       $this->enter = seturl('#','ENTER SCOL',0,"onClick=\"loadScol()\""); 
	   $this->scolurl = paramload('SCOL','url');
	   $this->scolport = paramload('SCOL','port');
	   $this->scolwindow = paramload('SCOL','scolwin');  
	   
	}
	
    function event($action) { 
	
       if (iniload('JAVASCRIPT')) {	
		 
	       $script = $this->callScol($this->scolurl,$this->scolport,$this->scolwindow);
	   
		   $js = new jscript;
	   		 
		   //load code
           $js->load_js($script,"",1);
		  
		   unset ($js); 
	   }	
	
    } 		
	
    function action($action) {
	
	   $out = $this->view();
	   
	   return ($out);
    }	
	  
    function view() {
			  
		$out = $this->enter . "<br>" .
		       seturl('#','HIDE SCOL',0,"onClick=\"MM_showHideLayers('Scol','','hide')\"") . "<br>" .
			   seturl('#','SHOW SCOL',0,"onClick=\"MM_showHideLayers('Scol','','show')\"") . "<br>";    
		
		$out .= $this->scolbar("horizontal");
		
	    $out .= $this->scol_object("100%","60%");	  
			  
        return ($out);	  
	}
	
	//under construction
	function launche($title,$scolurl,$scolport,$scolwindow) {
	
	       $script = $this->callScol($scolurl,$scolport,$scolwindow);
	   
		   $js = new jscript; 
           $js->load_js($script,"",1);
		   unset ($js); 	
		   
		   $ret = "<A href=\"javascript:loadScol()\">$title</A>";
		   //echo $title;
		   return ($ret);
	}
	
	function callscol($url,$port,$winon=0) {
	
       $javascript =
	   "\nfunction loadScol()\n
       {\n
         var ret;\n

         if(navigator.appName=='Netscape')\n
         {\n
           //ret=document.scol.LaunchMachine(\"\$browser$%5fload+%22locked%2flink%2epkg%22%0amain+%22scol.$url:$port%22%0a CSDMRWK 1000000\",1,0);\n
		   ret=document.scol.LaunchMachine(\"\$browser$%5fload+%22locked%2flib%2fconst%2epkg%22%0a%5fload+%22locked%2fstduser%2epkg%22%0amain+%22$url%3a$port%22+ffffffff+NIL%0a CSDMRWK 262144\",1,0);\n
         }\n					
         else\n
         {\n";
		 
           if ($winon) $javascript .= "ret=scol.LaunchMachine(\"\$browser$%5fload+%22locked%2flink%2epkg%22%0amain+%22scol.$url:$port%22%0a CSDMRWK 1000000\",1,0); \n";
		          else $javascript .= "ret=scol.LaunchMachine(\"\$browser$%5fload+%22locked%2flib%2fconst%2epkg%22%0a%5fload+%22locked%2fstduser%2epkg%22%0amain+%22$url%3a$port%22+ffffffff+NIL%0a CSDMRWK 262144\",1,0);\n";
		   
         $javascript .= "}\n
   
       }\n 
	   
	   \nfunction SendMsg(msg)
       {\n
	      if(navigator.appName=='Netscape')\n
	      {\n
		     document.scol.SendMessage(\"_ActiveX \"+msg);\n
	      }\n
	      else\n
	      { \n
		     scol.SendMessage(\"_ActiveX \"+msg);\n
	      }\n
       }\n";	 
	   
	   return ($javascript);
	}
	
	function scol_object($xmax='100%',$ymax='100%') {
	
	   $scolobject = "<DIV id=\"Scol\">
	                 <object 
                     id=\"scol\" 
                     name=\"scol\" 
                     classid=\"clsid:7A96FF35-4937-11D1-8F2C-00609779BDA3\"
                     codebase=\"http://www.cryo-networks.com/files/atlscol.dll\" 
                     align=\"middle\" 
                     border=\"0\"
                     width=\"$xmax\" 
                     height=\"$ymax\">
                     <embed 
                     align=\"baseline\" 
                     border=\"0\" 
                     width=\"$xmax\" 
                     height=\"$ymax\" 
                     name=\"scol\"
                     pluginurl=\"http://www.cryo-networks.com/files/scp10.exe\"
                     pluginspage=\"http://www.cryo-networks.com/files/scp10.exe\" 
                     type=\"application/x-scol\">
                     </embed> 
                     </object></DIV";	
					 
		return ($scolobject);			 
	}
	
	
	/*
function selectmsg(msg)
{
  //start
  if (msg=="strt") { MM_timelineGoto('Timeline4','2');MM_timelineGoto('Timeline3','2'); } 
  //square
  if (msg=="logo") { MM_openBrWindow(msg,'','width=300,height=500'); }
  if (msg=="sqwin1") { MM_openBrWindow('Img/photo.jpg','','width=300,height=500'); }
  if (msg=="sqwin2") { MM_openBrWindow('Img/photo.jpg','','width=300,height=500'); }

  if (msg=="taxi") { MM_timelineGoto('Timeline1','2');MM_timelineGoto('Timeline2','1'); }
  if (msg=="tina") { MM_timelineGoto('Timeline1','2');MM_timelineGoto('Timeline2','1'); }

  //deadend
  if (msg=="pic01") { MM_openBrWindow(msg,'Img/photo.jpg','width=300,height=500'); }
  if (msg=="pic02") { MM_openBrWindow(msg,'Img/photo.jpg','width=300,height=500'); }
}

function SendMsg(msg)
{
	if(navigator.appName=='Netscape')
	{
		document.scol.SendMessage("_ActiveX "+msg);
	}
	else
	{
		scol.SendMessage("_ActiveX "+msg);
	}
}	*/

    function scolbar($vhtype="vertical",$icontype=1) {
	  $UserSecID = GetGlobal('UserSecID');
	  $GRX = GetGlobal('GRX');
       
      $this->userLevelID = decode($UserSecID); ;
	  
      if ($GRX) {
	         $scol_photo     = seturl("#",localize('_SCOL0',getlocal()),0,"onClick=\"SendMsg(567)\"");
             $scol_name      = seturl("#",localize('_SCOL1',getlocal()),0,"onClick=\"SendMsg(234)\"");  
             $scol_avatar    = seturl("#",localize('_SCOL2',getlocal()),0,"onClick=\"SendMsg(678)\"");
             $scol_pager     = seturl("#",localize('_SCOL3',getlocal()),0,"onClick=\"SendMsg(345)\"");
             $scol_webcam    = seturl("#",localize('_SCOL4',getlocal()),0,"onClick=\"SendMsg(456)\"");
	         $scol_ignore    = seturl("#",localize('_SCOL5',getlocal()),0,"onClick=\"SendMsg(123)\"");
	         $scol_speaker   = seturl("#",localize('_SCOL6',getlocal()),0,"onClick=\"SendMsg(902)\"");
	         $scol_sdialog   = seturl("#",localize('_SCOL7',getlocal()),0,"onClick=\"SendMsg(901)\"");  
	         $scol_soundon   = seturl("#",localize('_SCOL8',getlocal()),0,"onClick=\"SendMsg(111)\"");
	         $scol_soundoff  = seturl("#",localize('_SCOL9',getlocal()),0,"onClick=\"SendMsg(110)\"");
			 /*	  
             $link_userlogin     = icon("/icons/login.gif","t=login&a=&g=" , localize('_LOGIN',getlocal()) ,$icontype,1);	  
             $link_userlogout    = icon("/icons/logout.gif","t=logout&a=&g=" , localize('_LOGOUT',getlocal()) ,$icontype);	  
             $link_usersmng      = icon("/icons/usermng.gif","t=signup&a=&g=" , localize('_ADM2',getlocal()) ,$icontype,1);
             $link_accountmng    = icon("/icons/account.gif","t=signup&a=$uid&g=" , localize('_ADM1',getlocal()) ,$icontype,1);
             $link_contentmng    = icon("/icons/cmanager.gif","t=admin&a=$a&g=$g" , localize('_ADM6',getlocal()) ,$icontype);
	         $link_user_desktop  = icon("/icons/deskmng.gif","t=modeXwin&a=&g=" , localize('_ADM3',getlocal()) ,$icontype);
	         $link_subscribers   = icon("/icons/subscribe_dpc.gif","t=subscribe&a=&g=" , localize('_ADM4',getlocal()) ,$icontype);
	         $link_mailboxes     = icon("/icons/mailadmin.gif","t=mail&a=&g=adminbox" , localize('_ADM5',getlocal()) ,$icontype);	  
	         $link_inbox         = icon("/icons/inbox.gif","t=mail&a=&g=inbox" , localize('_ADM7',getlocal()) ,$icontype);
	         $link_transactions  = icon("/icons/transactions.gif","t=transview&a=&g=" , localize('_TRANSLIST',getlocal()) ,$icontype); 
			 */
	  }
      else {
	         $scol_photo     = seturl("#",localize('_SCOL0',getlocal()),0,"onClick=\"SendMsg(567)\"");
             $scol_name      = seturl("#",localize('_SCOL1',getlocal()),0,"onClick=\"SendMsg(234)\"");  
             $scol_avatar    = seturl("#",localize('_SCOL2',getlocal()),0,"onClick=\"SendMsg(678)\"");
             $scol_pager     = seturl("#",localize('_SCOL3',getlocal()),0,"onClick=\"SendMsg(345)\"");
             $scol_webcam    = seturl("#",localize('_SCOL4',getlocal()),0,"onClick=\"SendMsg(456)\"");
	         $scol_ignore    = seturl("#",localize('_SCOL5',getlocal()),0,"onClick=\"SendMsg(123)\"");
	         $scol_speaker   = seturl("#",localize('_SCOL6',getlocal()),0,"onClick=\"SendMsg(902)\"");
	         $scol_sdialog   = seturl("#",localize('_SCOL7',getlocal()),0,"onClick=\"SendMsg(901)\"");  
	         $scol_soundon   = seturl("#",localize('_SCOL8',getlocal()),0,"onClick=\"SendMsg(111)\"");
	         $scol_soundoff  = seturl("#",localize('_SCOL9',getlocal()),0,"onClick=\"SendMsg(110)\"");	 
	  } 	    

	  switch ($vhtype)  {	  
 	    default : 
		case "vertical"  :	  
                          if (seclevel('SCOL0_',$this->userLevelID)) $out .= $scol_photo;
                          if (seclevel('SCOL1_',$this->userLevelID)) $out .= $scol_name;
                          if (seclevel('SCOL2_',$this->userLevelID)) $out .= $scol_avatar;
                          if (seclevel('SCOL3_',$this->userLevelID)) $out .= $scol_pager;
                          if (seclevel('SCOL4_',$this->userLevelID)) $out .= $scol_webcam;
                          if (seclevel('SCOL5_',$this->userLevelID)) $out .= $scol_ignore;
                          if (seclevel('SCOL6_',$this->userLevelID)) $out .= $scol_speaker;
                          if (seclevel('SCOL7_',$this->userLevelID)) $out .= $scol_sdialog;	
                          if (seclevel('SCOL8_',$this->userLevelID)) $out .= $scol_soundon;
                          if (seclevel('SCOL9_',$this->userLevelID)) $out .= $scol_soundoff;						  
                          break;
		case "horizontal":	  				 
                          if (seclevel('SCOL0_',$this->userLevelID)) $hout[] = $scol_photo;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL1_',$this->userLevelID)) $hout[] = $scol_name;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL2_',$this->userLevelID)) $hout[] = $scol_avatar;
						  $hattr[] = "left";						  
                          if (seclevel('SCOl3_',$this->userLevelID)) $hout[] = $scol_pager;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL4_',$this->userLevelID)) $hout[] = $scol_webcam;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL5_',$this->userLevelID))  $hout[] = $scol_ignore;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL6_',$this->userLevelID))  $hout[] = $scol_speaker;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL7_',$this->userLevelID))  $hout[] = $scol_sdialog;	
						  $hattr[] = "left";						  
                          if (seclevel('SCOL8_',$this->userLevelID))  $hout[] = $scol_soundon;
						  $hattr[] = "left";						  
                          if (seclevel('SCOL9_',$this->userLevelID))  $hout[] = $scol_soundoff;	
						  $hattr[] = "left";							  
                          break;
	  }
	  				
	  if ($vhtype=="horizontal") {
            $icobar = new window('',$hout,$hattr);
            $out = $icobar->render("center::100%::0::group_icons::left::0::0::");
            unset ($icobar);	
	  }
	  	  		  
	  return ($out);

	}	
	  
};
}	  
?>