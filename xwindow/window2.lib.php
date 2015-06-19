<?php

$__DPCSEC['WINDOW2_DPC']='1;1;1;1;1;1;1;2;9';
//$__DPCSEC['EDITWIN_']='2;1;1;1;1;1;2;2;9';

if (!defined("WINDOW2_DPC")) {
define("WINDOW2_DPC",true);

$__DPC['WINDOW2_DPC'] = 'window2';

$__EVENTS['WINDOW2_DPC'][0]='print';

$__ACTIONS['WINDOW2_DPC'][0]='print';

$__LOCALE['WINDOW2_DPC'][0]='_WND1;Mail;Ταχυδρόμηση';
$__LOCALE['WINDOW2_DPC'][1]='_WND2;Print;Εκτύπωση';
$__LOCALE['WINDOW2_DPC'][3]='_SHOW;Show;Εμφανιση';
$__LOCALE['WINDOW2_DPC'][4]='_HIDE;Hide;Αποκρυψη';

//include_once("window.lib.php");
//GetGlobal('controller')->include_dpc('xwindow/window.lib.php');
require_once(GetGlobal('controller')->require_dpc('xwindow/window.lib.php'));

class window2 extends window {

    var $showhide;
	var $sh_symbol,$hd_symbol;
	var $noheader,$titlelink;
		
	function window2($title='',$content='',$attribute=0,$status=1,$runasuser=null,$showhide='SHOW',$noheader=null,$titlelink=null) {
	  $GRX = GetGlobal('GRX');		

      window::window($title,$content,$attribute,$status,$runasuser);	
	  
	  $this->showhide = $showhide;
	  $this->noheader = $noheader;
	  $this->titlelink = $titlelink;
	  
        if ($GRX) {    
          $this->hd_symbol = loadTheme('minimize',localize('_HIDE',getlocal())); 
          $this->sh_symbol = loadTheme('maximize',localize('_SHOW',getlocal()));		  
        } 
        else { 
          $this->sh_symbol = '[^]';
		  $this->hd_symbol = '[V]';
        }	
		
        if (iniload('JAVASCRIPT')) {	
	   
	      $code = $this->javascript();
	   
		  $js = new jscript;
          $js->load_js($code,"",1);	
		  unset ($js);		
	    }			  

    }
	
	function javascript() {
	    
		$out = <<<EOF
               //expand/show element
               function expand(listID) {
	             listID = document.getElementById(listID);
                 if (listID.style.display == "none") {
                   listID.style.display = "";
                 }
                 else {
                   listID.style.display = "none";
                 }
               }

               //contract/hide element
               function contract(listID) {
	             listID = document.getElementById(listID);
	             if (listID.style.display == "show") {
		           listID.style.display = "";
	             }
	             else {
		           listID.style.display = "none";
	             }
               }
	   
EOF;
    return ($out);
    }	
	
    //overwrite
    function render($attributes="center::100%::0::group_win_body::left::0::0::",$drag=0) {

	    $showhide = $this->showhide;
		$title = $this->title;
	
		if ($attributes) {
             
           $split = explode ("::", $attributes); 

		   $this->align  = $split[0];
		   $this->width  = $split[1];
		   $this->border = $split[2];
		   $this->cssgroup = $split[3];
		   $this->attr   = $split[4];
		   $this->cp     = $split[5];
		   $this->cs     = $split[6];
		}

        //if ($this->title) !!!!!!
		if ($this->state) //header based on state not body
		$whead = $this->winhead();  
        //if ($this->state)!!!!!!!!!!!!!!!!!! 
		$wbody = $this->winbody(); //allways (hidden or viewed)

        $out = $whead . $wbody;
     
	    switch ($this->agent) { 
		   case 'XUL' :  
		   case 'GTK' :  
		   case 'TEXT':
		   case 'CLI' :  return ($this->content); //as is 
		                 break;
		   case 'XML' :  $xml = new pxml();
		                 $xml->addtag('WINDOW',null,$out,"drag=$drag");
						 $xmlout = $xml->getxml();
						 unset ($xml); 
						 return ($xmlout);
						 break;				
		   case 'HTML' : if ($drag) 
		                      return "<div class=\"drag\">" . $out . "</div>";	   	  
		                 else return ($out);
						 break;
		   case 'WAP'  : return ($out);
		                 break;
		   default : return ($out);	  
	    }		  
    }
	
	//overwriten
	function winhead() {
	
	    //HIDDEN HEADER
	    if ($this->noheader) return;	
	
		if (seclevel('EDITWIN_',$this->userLevelID)) 
		  $header0 = seturl("#",$this->editsymbol);
		else
		  $header0 = $this->editsymbol;  
		  
		$data[] = $header0;
		$attr[] = "left;1%;middle;";		

		if ($this->titlelink) { 
          $header = "<B>" . $this->showhide($this->title,$this->title) . "</B>";
		  $data[] = $header;
		  $attr[] = $this->attr;
		}  
		else {  
		  $header = "<B>" . $this->title . "</B>";
	      $data[] = $header;
		  $attr[] = $this->attr . ";98%;";
	      $data[] = $this->showhide($this->sh_symbol,$this->hd_symbol);
		  $attr[] = $this->attr . ";1%;middle;";		
		}
        
	    switch ($this->agent) {
		   //case 'XUL'  :  
		   //case 'GTK'  :		
		   case 'XML'  : $xhtml = _XPRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,"group_win_head",$data,$attr);
		                 $xml = new pxml();
		                 $xml->addtag('WINHEAD',null,$xhtml);
						 $out = $xml->getxml();
						 unset ($xml);
		                 break;			
		   case 'HTML' : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,"group_win_head",$data,$attr); break;
		   case 'WAP'  : /*$out = _WPRAGMA($this->title,"card".$this->id,"group_win_head",$data,$attr);*/ break;
           default     : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,"group_win_head",$data,$attr);break;
		}    
		return $out;
    }	
	
	//overwititen
	function winbody() {
	
	    $title = $this->title;
	
	    switch ($this->showhide) {
		  case 'HIDE' : $style = "id=\"$title\" style=\"display:none\"";
		                break;	    
		  case 'SHOW' : 
		  default     : $style = "id=\"$title\""; 		  
		}  

		$body = $this->content;
		if (is_array($body)) { 
		    $data = (array) $body;
		    $attr = (array) $this->attrib;
		}
		else {
			$data[] = $body;
			$attr[] = $this->attr;
		}

	    switch ($this->agent) {
		   //case 'XUL'  :  
		   //case 'GTK'  :		
		   case 'XML'  : $xhtml = _XPRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,$this->cssgroup,$data,$attr); 
		                 $xml = new pxml();
		                 $xml->addtag('WINBODY',null,$xhtml);
						 $out = $xml->getxml();
						 unset ($xml);		   
		                 break;			
		   case 'HTML' : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,$this->cssgroup,$data,$attr,$style); break;
		   case 'WAP'  : $out = _WPRAGMA("card".$this->id,$this->title,$this->cssgroup,$data,$attr); break;
           default     : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,$this->cssgroup,$data,$attr,$style); break;
		} 		
		
		//////////////////////////////////////////////////////////////
		//save win contents!!!SLOW!!!! ...NO REASON
		/*if ($this->id) {
		  SetPreSessionParam("window_$this->id",encode($out,true));
		  $out .= $this->winedit();  
		}*/
		return $out;
    }	

	
	function showhide($showname=null,$hidename=null) {
	
		$title = $this->title;
	    $myshowname = (isset($showname) ? $showname : $title); 
		$myhidename = (isset($hidename) ? $hidename : $title); 		
	
	    switch ($this->showhide) {
		  case 'HIDE' :
		               $hide_url = setjsurl($myhidename,"javascript:expand('show_$title');contract('hide_$title');contract('$title')","hide_$title","style=\"display:none\"");
	 	               $show_url = setjsurl($myshowname,"javascript:expand('$title');expand('hide_$title');contract('show_$title')","show_$title");	
		               break;
		  case 'SHOW' :
		               $hide_url = setjsurl($myhidename,"javascript:expand('show_$title');contract('hide_$title');contract('$title')","hide_$title");
		               $show_url = setjsurl($myshowname,"javascript:expand('$title');expand('hide_$title');contract('show_$title')","show_$title","style=\"display:none\"");	
		  break;
		}  		
		$urldouble = $hide_url . $show_url; 
		
		return ($urldouble);
	}	

};
}
?>