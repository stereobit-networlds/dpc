<?php
//if (defined("DATABASE_DPC")) {

$__DPCSEC['ALOVMAP_DPC']='2;1;1;1;1;1;1;2;9';

if ((!defined("ALOVMAP_DPC")) && (seclevel('ALOVMAP_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ALOVMAP_DPC",true);

$__DPC['ALOVMAP_DPC'] = 'alovmap';

$__EVENTS['ALOVMAP_DPC'][0]='viewmap';
$__EVENTS['ALOVMAP_DPC'][1]='searchmap';
$__EVENTS['ALOVMAP_DPC'][2]='layer';

$__ACTIONS['ALOVMAP_DPC'][0]='viewmap';
$__ACTIONS['ALOVMAP_DPC'][1]='searchmap';
$__ACTIONS['ALOVMAP_DPC'][3]='layer';

$__LOCALE['ALOVMAP_DPC'][0]='ALOVMAP_DPC;Maps;Χάρτες';
$__LOCALE['ALOVMAP_DPC'][1]='_SELECTMAP;Select;Επιλογή';
$__LOCALE['ALOVMAP_DPC'][2]='_ZOOMIN;Zoom In;Μεγένθυση';
$__LOCALE['ALOVMAP_DPC'][3]='_ZOOMOUT;Zoom Out;Σμίκρυνση';
$__LOCALE['ALOVMAP_DPC'][4]='_PANMAP;Pan;Μετακίνηση';


class alovmap {

     var $prpath;
	 var $url;
	 var $gispath;
	 var $mode;

     function alovmap() {
        $UserSecID = GetGlobal('UserSecID');
		
        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
		
	    $this->prpath = paramload('SHELL','prpath');			
	    $this->url = paramload('SHELL','urlbase');	
				
		$this->gispath = paramload('ALOVMAP','gispath');		 
		$this->mode = paramload('ALOVMAP','mode');	
		
		if (($this->mode) && (iniload('JAVASCRIPT'))) {	
		   $js = new jscript;
           $js->load_js('alovmap.js');
   		   //$js->setLoadParams("setApplet();");	 
		   unset ($js);		
		}	
		
		//global $g;
		//$this->read_xml_file($this->prpath.$this->gispath."/".$g.".xml");		
	 }
	 
     function event($sAction) {

       switch($sAction) {
	     case 'viewmap'      : break;	 	 
	     case 'searchmap'    : break;		 
	     case 'layer'        : if (($this->mode) && (iniload('JAVASCRIPT'))) {	
		                         $js = new jscript;
   		                         $js->setLoadParams("startSearch(false);");	 
		                         unset ($js);		
		                       }
		                       break;		 
       }
 	}
	
	function action($act) {
	  $param1 = GetGlobal('param1');
	  $g = GetReq('g');
	  
	  if ($param1) $sel = $param1;
	  elseif ($g) $sel=$g;
	  else $sel = paramload('ALOVMAP','startmap');
	
	  $out = setNavigator(localize('ALOVMAP_DPC',getlocal()));	
	
	   switch ($act) {
		 case 'viewmap'      : $out .= $this->render($sel); break;   
		 case 'searchmap'    : $out .= $this->render($sel); break;		 
		 case 'layer'        : $out .= $this->render('sydney'); break;		 
	   }
	   
	   return ($out);
	}
		 

	function render($map) {
	
	    if ($this->mode) $out = $this->show_enchanced_map($map,'400','400');
		            else $out = $this->show_map($map);
		
		return ($out);
	}
	
	function read_xml_file($xmlfile) {
	    //echo $xmlfile;
	
	    if (file_exists($xmlfile)) {
		  echo $xmlfile;
		  
		  $xmlp = xml_parser_create();
		  xml_set_character_data_handler($xmlp,array($this,'display'));
		  $file = fopen($xmlfile,'r') or die('can\'t open file');
		  
		  while($data=fread($file,4000)) {//filesize($xmlfile))) {
		    //xml_parse($xmlp,$data,feof($file)) or die('xml error');
			echo $data."\n\r";
		  }
		  xml_parser_free($xmlp);
		}
	}
	
	function display($xmlp,$data) {
	    print $data;
	}
	
	function getiniparams($map) {//UNDER CONSTRUCTUION !!!!
	  
	    $pathname = "/" . $this->gispath . "/" . $map . ".ini";
		print $pathname;
	  
        $param = parse_ini_file($pathname);	
		print_r($param);
	}
	
    function show_map($map,$x="100%",$y="480",$layout='') {
	
	     if ($map) {			 
	
         $out = "<DIV id=\"ALOVMAP\" z-index:0>";
	     $out .= "<applet codebase=.
		         code=org.alov.viewer.SarApplet 
                 archive = \"/$this->gispath/alovmap.jar\"  
				 width = \"$x\" 
				 height = \"$y\"
                 name = mapApplet 
				 align=center>
                 <param name=pid value=" . "$this->gispath/" . "$map.xml>";
				 
         //$out .= "<paramx name=encoding value=Cp1258>";
         //$out .= "<paramx name=langdriver value=\"org.alov.util.LangDriver_ru\">";
         //$out .= "<param name=encoding value=Cp1250>";
         //$out .= "<param name=layout value=$this->gispath/layouts/layout_gr.xml>";
         //$out .= "<param name=lang value=gr>";		
				 
         $out .= "<param name=lang value=en>";
         $out .= "<param name=\"starttool\" value=\"5\">";
         $out .= "<param name=\"tips\" value=\"yes\">";
		 
         if ($layout) 
		   $out .= "<param name=\"layout\" value=\"$this->gispath/$layout.xml\">";
				 				 
         $out .= "</applet>";
	     $out .= "</DIV>";	
		 }
		 //$this->getiniparams($map);	 
		 
	     $swin = new window(localize('ALOVMAP_DPC',getlocal())." > ".$map,$out);
         $wout = $swin->render();
         unset ($swin);			 			 
	 	 
		 return ($wout);
    }
	
    function show_enchanced_map($map,$x="100%",$y="480") {
	
	     if ($map) {	
		 
		 $out1 = "<form>
                 <INPUT TYPE=\"radio\" NAME=\"selecttool\" CHECKED onClick=\"setTool(6)\">".localize('_SELECTMAP',getlocal())."
                 &nbsp;
				 <INPUT TYPE=\"radio\" NAME=\"selecttool\" onClick=\"setTool(2)\">".localize('_ZOOMIN',getlocal())."
                 &nbsp;
				 <INPUT TYPE=\"radio\" NAME=\"selecttool\" onClick=\"setTool(3)\">".localize('_ZOOMOUT',getlocal())."
                 &nbsp;
				 <INPUT TYPE=\"radio\" NAME=\"selecttool\" onClick=\"setTool(4)\">".localize('_PANMAP',getlocal())."
                 <!--
                 &nbsp;
				 <INPUT TYPE=\"button\" VALUE=\"Info\" NAME=\"btn1\" onClick=\"startSearch(false)\">
                 -->
                 </form>";		 
	
         //$out1 .= "<DIV id=\"ALOVMAP\" z-index:0>";
	     $out1 .= "<applet codebase=.
		         code=org.alov.viewer.SarApplet 
                 archive = \"/$this->gispath/alovmap.jar\"  
				 width = \"$x\" 
				 height = \"$y\"
                 name = mapApplet 
				 align=center>
                 <param name=pid value=" . "$this->gispath/" . "$map.xml>";		
				 
         $out1 .= "<param name=lang value=en>"; 
		 $out1 .= "<param name=\"layout\" value=\"$this->gispath/$map/$map"."_layout.xml\">";
				 				 
         $out1 .= "</applet>";
	     //$out1 .= "</DIV>";	
		 
		 //-----------------------------------------------------
         $myaction = seturl("t=viewmap&a=&g=$map");
		 $out2 = "<form name=\"toolbar\" action=\"$myaction\" method=post>";//onSubmit=\"return startSearch(true)\">";

         $out2 .= "<INPUT TYPE=\"checkbox\" NAME=\"vis1\" onClick=\"setVisible('Aerial Image')\">Aerial&nbsp;Image<br>
                   <INPUT TYPE=\"checkbox\" NAME=\"vis2\" CHECKED onClick=\"setVisible('Key Landmarks')\">Landmarks<br>
                   <INPUT TYPE=\"checkbox\" NAME=\"lbl\" CHECKED onClick=\"setTheme()\">Labels";
         $out2 .= "<br><SELECT NAME=\"layers\" SIZE=3  onChange=\"setLayer(this)\">
                  <OPTION SELECTED>Key Landmarks
                  <OPTION>Streets
                  <OPTION>Parks
                  </SELECT>";

         $out2 .= "<br><INPUT TYPE=\"text\" NAME=\"srch\" SIZE=\"15\">";
         $out2 .= "<INPUT TYPE=\"button\" VALUE=\"Search\" NAME=\"btn\" onClick=\"startSearch(true)\" >"; 
         //$out2 .= "<INPUT type=\"submit\" name=\"btn\" value=\"" . "Search" . "\" onClick=\"startSearch(true)\">"; 		 	   
		 
         $out2 .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . "searchmap" . "\">";				   
         $out2 .= "</form>";
		 
		 //result area
         $out2 .= "<DIV id=\"ALOVRES\" z-index:0>";
	     $out2 .= "</DIV>";		 
		 
		 //--------------------------------------------------------------------
		 $win[] = $out1;
		 $atrw[] = "left;60%";
		 $win[] = $out2;
		 $atrw[] = "left;40%";		 
		 }	 
		 
	     $swin = new window(localize('ALOVMAP_DPC',getlocal())." > ".$map,$win,$atrw);
         $wout = $swin->render();
         unset ($swin);			 			 
	 	 
		 return ($wout);
    }	

};
}
//}
//else die("DATABASE DPC REQUIRED!");
?>