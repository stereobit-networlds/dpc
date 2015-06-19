<?php
if (!defined("PHTML_DPC")) {
define("PHTML_DPC",true); 

class phtml {  

   var $urltitle;
   var $urladdr;
   var $css;
   var $mdescr;
   var $mkeys;
   var $version;
   var $charset;

   var $bkcolor;
   var $textcol;
   var $linkcol;
   var $vlnkcol;
   var $alnkcol;
   var $bkimg;

   var $content;
   var $header;
   var $footer;
   
   var $drag;
   var $userLevelID;   
   
   var $cache = "no-store, no-cache, must-revalidate, post-check=0, pre-check=0";   
   var $exptag;
   
   var $csspath;
   
   function phtml($css='',$content='',$header='',$footer='') {
	  $UserSecID = GetGlobal('UserSecID');  
   
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);    	  
	  
	  $dragbyuser = arrayload('SHELL','drag'); 
	  $this->drag = $dragbyuser[$this->userLevelID];	  

      $this->urltitle = paramload('SHELL','urltitle');

	  $ip = $_SERVER['HTTP_HOST'];
      $pr = paramload('SHELL','protocol');	
      $this->urladdr  = $pr . $ip;// paramload('SHELL','urlbase'); //echo $this->urladdr;	  
	   
      $this->version  = paramload('SHELL','version');
      $this->charset  = paramload('SHELL','charset');
	  
      $this->mdescr   = paramload('HTML','metadescr');
      $this->mkeys    = paramload('HTML','metakeys');
      $this->mexpire  = paramload('HTML','metaexpire');
      $this->mrestype = paramload('HTML','metarestype');
      $this->mdist    = paramload('HTML','metadistribution');
      $this->mauthor  = paramload('HTML','metaauthor');
      $this->mcpright = paramload('HTML','metacopyright');
      $this->mrobot   = paramload('HTML','metarobot');	
      $this->mrev     = paramload('HTML','metarevafter');
      $this->mrat     = paramload('HTML','metarating');  	    	  	  	  

      $this->bkcolor = paramload('HTML','h_bkgc');
      $this->textcol = paramload('HTML','h_txtc');
      $this->linkcol = paramload('HTML','h_lnkc');
      $this->vlnkcol = paramload('HTML','h_vlnc');
      $this->alnkcol = paramload('HTML','h_alnc');

      if (!$css) $this->css = /*$this->urladdr .*/ paramload('SHELL','css'); //relative
	        else $this->css = $css; 
			
	  //absolute or relative path ?????	relative for ssl abs for mail html	
      $this->csspath = /*$this->urladdr .*/ $this->css;

	  $this->header = $header;	  
	  $this->footer = $footer;		  
	  $this->content = $content;	
	  
	  //calculate expire date based on meta description (metaexpire)
	  //$aftertoday = mktime (0,0,0,date("m")  ,date("d")+$this->mexpire,date("Y"));
	  //$this->exptag = date("D, d M Y H:i:s",$aftertoday) . " GMT";
	  //echo $this->exptag;	  
	  
	  //check if headers allready send>>>>>>>>>>>>
	  //if (!headers_sent()) echo "headers not send"; else echo "headers allready send";  
	  
	  //header info
		
      //choose encoding
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
		$this->charset = 'utf-8';
	  else  
	    $this->charset = $char_set[getlocal()]; 	  			  
	  
	  
	  header('Content-Type: text/html; charset=' . $this->charset);
	  
      //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
	  //$tomorrow = mktime (0,0,0,date("m")  ,date("d")+1,date("Y"));
	  //$expdate = date("D, d M Y H:i:s",$tomorrow) . " GMT";
	  //echo $expdate;
      //header("Expires: $expdate");    // expire tomorrow
	  	  
      //header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
      //header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
      //header("Cache-Control: private",false) ;//post-check=0, pre-check=0", false);
      //if ($this->cache != "") header("Cache-Control: ".$this->cache);		  
	  
      //header("Pragma: no-cache");                          // HTTP/1.0  
      //header("Pragma: public");
	  
   }

   function render() { 

	  $out = $this->startHTML();
	  
	  if (($this->header) && ($this->header!='_TPLHEAD')) {
	    $out .= $this->header;	  
	  }
	  elseif ($this->header=='_TPLHEAD') { //default header.xgi file read
        if ( (defined("HEADER_DPC")) && (seclevel('HEADER_DPC',decode(GetSessionParam('UserSecID')))) ) {	  
          $head = new _header();
          $out .= $head->render();
	      unset ($head);	  
		}  
	  }
	  
	  if ($this->content) $out .= $this->content;
	  
	  if (($this->footer) && ($this->footer!='_TPLFOOT')) {
	    $out .= $this->footer;	  
	  }
	  elseif ($this->footer=='_TPLFOOT') { //default footer.xgi file read
        if ( (defined("FOOTER_DPC")) && (seclevel('FOOTER_DPC',decode(GetSessionParam('UserSecID')))) ) {	  
          $foot = new footer();
          $out .= $foot->render();
	      unset ($foot);	  
		}  
	  }	  
	  
      $out .= $this->endHTML();
	  
	  return ($out);
   }

   ///////////////////////////////////////////////////////////////////////
   // initialize html
   ///////////////////////////////////////////////////////////////////////
   function startHTML() {
	 $GRX = GetGlobal('GRX');
   
     $toprint .= "<!-- generated by phpdac5 " . $this->version . " (c)2001-2010 all rights reserved. -->\n";
     $toprint .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";	 
     $toprint .= "<HTML>";
     $toprint .= "<HEAD>";
     $toprint .= "<TITLE>";
     $toprint .= $this->urltitle;
     $toprint .=  "</TITLE>";
	 
     $toprint .= "<META HTTP-EQUIV=\"CONTENT-TYPE\" CONTENT=\"text/html; charset=" . $this->charset ."\">\n";
	 //$toprint .= "<META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"". $this->mexpire ."\">\n"; ??? TAG for expiration
	 $toprint .= "<META NAME=\"RESOURCE-TYPE\" CONTENT=\"" . $this->mrestype . "\">\n";
	 $toprint .= "<META NAME=\"DISTRIBUTION\" CONTENT=\"" . $this->mdist . "\">\n";
	 $toprint .= "<META NAME=\"AUTHOR\" CONTENT=\"". $this->mauthor . "\">\n";
	 $toprint .= "<META NAME=\"COPYRIGHT\" CONTENT=\"" . $this->mcpright . "\">\n";
	 $toprint .= "<META NAME=\"KEYWORDS\" CONTENT=\"" . $this->mkeys . "\">\n";
	 $toprint .= "<META NAME=\"DESCRIPTION\" CONTENT=\"". $this->mdescr ."\">\n";
	 $toprint .= "<META NAME=\"ROBOTS\" CONTENT=\"" . $this->mrobot . "\">\n";
	 $toprint .= "<META NAME=\"REVISIT-AFTER\" CONTENT=\"" . $this->mrev . "\">\n";
	 $toprint .= "<META NAME=\"RATING\" CONTENT=\"" . $this->mrat . "\">\n";
	 $toprint .= "<META NAME=\"GENERATOR\" CONTENT=\"webOS " . $this->version . "\">\n";	 
	 
     $toprint .=  "<LINK REL=StyleSheet HREF=\"" .$this->csspath ."\">";
	 
	 // call used javascript code 
     if (iniload('JAVASCRIPT')) {	
		 $js = new jscript;
	   		 
		 //load code
         if ($this->drag) $js->load_js('drag.js','1.2');
         //$js->load_js('chromeless/chromeless_35.js');		 
		 
		 //$js->load_js('ts_picker.js');	
		 $onload = $js->onLoad();//'OnLoad="setVariables();checkLocation()"';
		 
		 $toprint .= $js->callJavaS();	 
		 unset ($js);
	 }
	 	 
     $toprint .=  "</HEAD>\n";
  
     if ($GRX) {

	   $this->bkimg = loadTheme("bk",'',1); //cant put bk because of css

       $toprint .=  "<body bgcolor=\"#" . $this->bkcolor . "\" text=\"#" . $this->textcol . "\" link=\"#" . $this->linkcol . "\" vlink=\"#" . $this->vlnkcol . "\" alink=\"#" . $this->alnkcol . "\" background=\"" . $this->bkimg . "\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" $onload>\n";
     }
     else 
       $toprint .=  "<body bgcolor=\"#" . $this->bkcolor . "\" text=\"#" . $this->textcol . "\" link=\"#" . $this->linkcol . "\" vlink=\"#" . $this->vlnkcol . "\" alink=\"#" . $this->alnkcol . "\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" $onload >\n";

     //$toprint .=  "<meta name=\"description\" content=\"" . $this->mdescr . "\">\n";
     //$toprint .=  "<meta name=\"keywords\" content=\"" . $this->mkeys . "\">\n"; 	 


     return ($toprint);
   }

   ///////////////////////////////////////////////////////////////////////
   // end html
   ///////////////////////////////////////////////////////////////////////
   function endHTML() {
      
     //$hint = new hint;
     //$toprint .= $hint->div("c'est l'ensemble des produits materiels qui ont un corp physique. exemple : le stylot, la voiture ....","biens","absolute",30,30,187,86,1,"#000000","#66FF00","hidden");
     //$toprint .= $hint->link("Biens","biens");  

     //mouse xy values 
     //$toprint .= '<form name="Show"><input type="text" name="MouseX" value="0" size="4">' .
	   //        '<input type="text" name="MouseY" value="0" size="4"></form>';
     //$toprint .= BannerR::rotate();//"<table><tr><td><!-- BANNER --><img src=" . BannerR::rotate() . " width=\"480\" height=\"80\" alt=\"BANNER\" border=\"0\"><!-- /BANNER --></td></tr></table>";
	 //$b = new BannerR;
	 //$toprint .= $b->rotate();
	 
	 //$x = new vgamixer;
	 //$toprint .= $x->render('white'); 
	 
     $toprint .= "</BODY>\n";
     $toprint .= "</HTML>\n";
  
     return ($toprint);
   }
   
};
}
?>