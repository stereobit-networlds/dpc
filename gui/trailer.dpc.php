<?php
//PHP5
$__DPCSEC['TRAILER_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("TRAILER_DPC")) {
define("TRAILER_DPC",true);

$__DPC['TRAILER_DPC'] = 'trailer';


$__EVENTS['TRAILER_DPC'][0]='TRAILER';

$__ACTIONS['TRAILER_DPC'][0]='TRAILER';

$__DPCATTR['TRAILER_DPC']['TRAILER'] = 'TRAILER,1,0,1,1,1,0,0,0,0';

//$__LOCALE['TRAILER_DPC'][0]='TRAILER_DPC;Map;Χάρτης';

class trailer {

  private $grx;
  private $control_button;
  private $speed;
  private $path,$urlpath,$hip;
  
  function __construct($redirection=null) {
  
	 $this->grx = GetGlobal('GRX'); 
         $this->path= paramload('SHELL','prpath');  
         $this->hip = paramload('ID','hostinpath'); 
         $this->urlpath= paramload('SHELL','urlpath').'/'.$this->hip;  
	  
	 
     if (iniload('JAVASCRIPT')) {	
	   
	       //$javas = $this->javascript();//used by render
	   
		   $js = new jscript;
           //$js->load_js($javas,"",1);	//loaded in html file
		   $js->load_js("trailer.js"); 
		   unset ($js);		
	 }
	 else
	   die("Javascript required!");
	   
     if ($this->grx) {   	 
         $this->control_button  = loadTheme('godate_b',localize('_GREEKMAP',getlocal()));
     }
	 else {
         $this->control_button  = "[MAP]";
	 }	
	 
	 $sp =remote_paramload('TRAILER','speed',$this->path);
	 $ps =remote_paramload('TRAILER','pixelstep',$this->path);
	 $this->speed = $sp?$sp:30;
	 $this->pixelstep = $ps?$ps:5;	   	  
  }
  
  function javascript() {
  
    $ret = "
			var TRAILER_No_TO_SHOW = 10;
			var ADDITIONAL_TO_LOAD = 5;
			var allowScrolling = true;
			var singleWidth = 180;
			var SPACE_MIDDLE = 12; // ajust according to image space

			if (w3c)
			{
				inner = document.getElementById('scrollingid');
				controls=document.getElementById('trailercontrols');
				controls.innerHTML = 'loading  &nbsp;';
				tid = setTimeout('scrollbox()',waitPerPicture);
			}";
				
    return ($ret);
  }
  
    
  public function render($title=null,$file=null,$controls=null,$speed=null,$pixelstep=null,$css=null,$space4pics=null) {
 
       $mycss = $css?$css:'themes/trailer.css';
  
       $toprint = "
	   
<style type=\"text/css\">
	
@import url($mycss);
	 

</style>";
	   
	  if ($controls) 
        $toprint .= "<div class=\"baseboxwimg2\" style=\"position:relative;\"> <img src=\"".$this->hip."/images/trailer/controls.gif\" alt=\"\">";
		
        $toprint .= "<div id=trailercontrols></div>";
		
	  if ($controls)	
        $toprint .= "</div>";
	  
		$toprint .= "<div class=\"basebox21\">
			
        <div id=\"parentofscrolling\"> 
          <div style=\"left:0px;\" id=\"scrollingid\">";
		  
		if ($file===null) {  
		    $toprint .= "<a class=lowz href=\"/show-crete-estate-4444-en.jsp\"><img id=trimg0 class=trimg style=\"left:0px;\" src='/site/images/trailer/but01.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:0px;\" class='trprotokola'>5616</div>
            <a class=lowz href=\"/show-crete-estate-5078-en.jsp\"><img id=trimg1 class=trimg style=\"left:192px;\" src='/site/images/trailer/but02.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:192px;\" class='trprotokola'>59061</div>
            <a class=lowz href=\"/show-crete-estate-5077-en.jsp\"><img id=trimg2 class=trimg style=\"left:384px;\" src='/site/images/trailer/but03.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:384px;\" class='trprotokola'>51991</div>
            <a class=lowz href=\"/show-crete-estate-4519-en.jsp\"><img id=trimg3 class=trimg style=\"left:576px;\" src='/site/images/trailer/but04.gif' width=180 height=138 alt=\"thealt\"></a> 
            <div style=\"left:576px;\" class='trprotokola'>5667</div>
            <a class=lowz href=\"/show-crete-estate-3645-en.jsp\"><img id=trimg4 class=trimg style=\"left:768px;\" src='/site/images/trailer/but05.gif' width=180 height=135 alt=\"thealt\"></a> 
            <img class=special style=\"left:916px;\" src=\"/site/images/trailer/6-en.gif\" alt=\"thespecialstate\">
            <div style=\"left:768px;\" class='trprotokola'>5177</div>
            <a class=lowz href=\"/show-crete-estate-5134-en.jsp\"><img id=trimg5 class=trimg style=\"left:960px;\" src='/site/images/trailer/but06.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:960px;\" class='trprotokola'>6145</div>
            <a class=lowz href=\"/show-crete-estate-4681-en.jsp\"><img id=trimg6 class=trimg style=\"left:1152px;\" src='/site/images/trailer/but07.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:1152px;\" class='trprotokola'>51923</div>
            <a class=lowz href=\"/show-crete-estate-4036-en.jsp\"><img id=trimg7 class=trimg style=\"left:1344px;\" src='/site/images/trailer/but01.gif' width=180 height=127 alt=\"thealt\"></a> 
            <div style=\"left:1344px;\" class='trprotokola'>5357</div>
            <a class=lowz href=\"/show-crete-estate-4896-en.jsp\"><img id=trimg8 class=trimg style=\"left:1536px;\" src='/site/images/trailer/but02.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:1536px;\" class='trprotokola'>5959</div>
            <a class=lowz href=\"/show-crete-estate-3141-en.jsp\"><img id=trimg9 class=trimg style=\"left:1728px;\" src='/site/images/trailer/but03.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:1728px;\" class='trprotokola'>4657</div>
            <a class=lowz href=\"/show-crete-estate-4444-en.jsp\"><img id=trimg10 class=trimg style=\"left:1920px;\" src='/site/images/trailer/but04.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:1921px;\" class='trprotokola'>5616</div>
            <a class=lowz href=\"/show-crete-estate-5078-en.jsp\"><img id=trimg11 class=trimg style=\"left:2112px;\" src='/site/images/trailer/but05.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:2113px;\" class='trprotokola'>59061</div>
            <a class=lowz href=\"/show-crete-estate-5077-en.jsp\"><img id=trimg12 class=trimg style=\"left:2304px;\" src='/site/images/trailer/but06.gif' width=180 height=135 alt=\"thealt\"></a> 
            <div style=\"left:2305px;\" class='trprotokola'>51991</div>
            <a class=lowz href=\"/show-crete-estate-4519-en.jsp\"><img id=trimg13 class=trimg style=\"left:2496px;\" src='/site/images/trailer/but07.gif' width=180 height=138 alt=\"thealt\"></a> 
            <div style=\"left:2497px;\" class='trprotokola'>5667</div>
            <a class=lowz href=\"/show-crete-estate-3645-en.jsp\"><img id=trimg14 class=trimg style=\"left:2688px;\" src='/site/images/trailer/but01.gif' width=180 height=135 alt=\"thealt\"></a> 
            <img class=special style=\"left:2728px;\" src=\"/site/images/trailer/6-en.gif\" alt=\"thespecialstate\">
            <div style=\"left:2689px;\" class='trprotokola'>5177</div>";
		  }
		  else
		    $toprint .= $this->get_scroll_file($file,$items);
		  //echo $items;	
		  
          $toprint .= "</div>
        </div>
		</div>";

    $picn = $space4pics?$space4pics:10;
		
    if ($items) {
	  if ($items>$picn) {
	    $no2show = $picn;
	    $add2load = $items - $picn;
	  }
	  else {
	    $no2show = $items;
	    $add2load = 0;	  
	  }
	}	
	else {
	  $no2show = 10;
	  $add2load = 5;	
	}
	
	$sp = $speed?$speed:$this->speed;
        $ps = $pixelstep?$pixelstep:$this->pixelstep;
		
    $toprint .= "
        <script language=\"javascript\" TYPE=\"text/javascript\">
		<!--	
			var TRAILER_No_TO_SHOW = $no2show;
			var ADDITIONAL_TO_LOAD = $add2load;
			var allowScrolling = true;
			var singleWidth = 180;
			var SPACE_MIDDLE = 12; // ajust according to image space
			
var controlsShown = false;			
var scrollSpeed = ($sp*5); // SPEED OF SCROLL IN MILLISECONDS (1 SECOND=1000 MILLISECONDS)..
var pixelstep=$sp;          // PIXELS STEPS PER REPITITION.			

			if (w3c)
			{
				inner = document.getElementById('scrollingid');
				controls=document.getElementById('trailercontrols');
				controls.innerHTML = 'loading  &nbsp;';
				tid = setTimeout('scrollbox()',waitPerPicture);
			}
		// -->
		</script>";		
					   
  
       //return ($toprint);
  
       $mywin = new window($title,$toprint);
       $out .= $mywin->render();	
	   unset ($mywin);
	   
	   return ($out);  
  }
  
  function get_scroll_file($file,&$objects) {
       //$basicpath = paramload('SHELL','prpath');  
  
       //must read the lines of file= no to show!!!!
       $lines = file($this->urlpath . $file); 
	   $objects = count($lines)/2;
	   
       $ret = implode("\r\n",$lines);//file_get_contents($basicpath . $file);
	   
	   return ($ret);
  }
  
  
};
}
?>