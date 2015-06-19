<?php

$__DPCSEC['EDITWIN_']='2;1;1;1;1;1;2;2;9';

if (!defined("WINDOW_DPC")) {
define("WINDOW_DPC",true);

$__DPC['WINDOW_DPC'] = 'window';

$__EVENTS['WINDOW_DPC'][0]='print';

$__ACTIONS['WINDOW_DPC'][0]='print';

$__LOCALE['WINDOW_DPC'][0]='_WND1;Mail;Ταχυδρόμηση';
$__LOCALE['WINDOW_DPC'][1]='_WND2;Print;Εκτύπωση';

class window {

    var $id;
	var $title;
	var $content; //can be simple html text or array of data
	var $attrib;  // in case of array this is the array attributes
	var $state;   //init the window as minimized or maximized(default)
	var $align;
	var $width;
	var $cssgroup;
	var $attr;
	var $cp;
	var $cs;

	var $userLevelID;
	var $agent;
	var $runas;
		
	function window($title='',$content='',$attribute=0,$status=1,$runasuser=null) {
	

	
        $__USERAGENT = GetGlobal('__USERAGENT');
	    $GRX = GetGlobal('GRX');	
	    $UserSecID = GetGlobal('UserSecID');
		
		static $autowinid = 0;			
		
		$this->agent = $__USERAGENT;
		
	    $this->runas = $runasuser; 	
		
	    if (isset($this->runas)) 
	  	  $this->userLevelID = $this->runas; 
	    else	  
          $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
		
		if ($title) $this->id = ++$autowinid;  
		       else $this->id = 0;
			   
		$this->title = $title;
		$this->content = $content;
		$this->attrib = $attribute;
		$this->state = $status;
		
        if ($GRX) {   
		  	 if (seclevel('EDITWIN_',$this->userLevelID)) 		
               $this->editsymbol= loadTheme('edit','',0,"onClick=\"MM_showHideLayers('window_$this->id','','show')\"",1); 		
			 else
               $this->editsymbol= loadTheme('edit','',0,'',1);			 
        } 
        else { 
             $this->editsymbol = '[E]'; 		 
        }			
    }
	

    function event($event=null) {
      $a = GetReq('a');

      switch ($event) {		
		  case "print"      : $prn = $this->printwin(decode(GetSessionParam("window_$a"),true)); 
		  					  echo $prn; 
							  exit;
							  break;	  
      }  
	  return ($ret);
    }		
	
    function action($action=null) {
	}	

    function render($attributes="center::100%::0::group_win_body::left::0::0::",$drag=0) {
	
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

        if ($this->title) $whead = $this->winhead(); 
        if ($this->state) $wbody = $this->winbody(); 

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

	function winhead() {	

		if (seclevel('EDITWIN_',$this->userLevelID)) 
		  $header0 = seturl("#",$this->editsymbol);
		else
		  $header0 = $this->editsymbol;  
		  
		$data[] = $header0;
		$attr[] = "left;1%;middle;";		

        $header = "<B>" . $this->title . "</B>";
	    $data[] = $header;
		$attr[] = $this->attr . ";99%;middle;";
        
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

	function winbody() {

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
		   case 'HTML' : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,$this->cssgroup,$data,$attr); 
						 break;
		   case 'WAP'  : $out = _WPRAGMA("card".$this->id,$this->title,$this->cssgroup,$data,$attr); 
		                 break;
           default     : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,$this->cssgroup,$data,$attr); 
		} 		

		//////////////////////////////////////////////////////////////
		//save win contents !!SLOW!!!
		if ((seclevel('EDITWIN_',$this->userLevelID)) && ($this->id)) {		
		  SetPreSessionParam("window_$this->id",encode($out,true));			  
		  $out .= $this->winedit();  
		}
		
		return $out;
    }
	
	function winedit() {
	
       //$commands = "<B>" . $this->title . "</B><br>";
       //mail
       if ( (defined('MAIL_DPC')) && (seclevel('PARTMAIL_',$this->userLevelID)) )	   
          $commands .= seturl("t=mail&a=$this->id&g=", localize('_WND1',getlocal())) . "<br>"; 
                       //print
       if (iniload('JAVASCRIPT')) {	
		     
            //$plink = "<A href=\"" . seturl("#") . "\"";	 
            //call javascript for opening a new browser win for printing		   
            $params = seturl("t=print&a=$this->id&g=") . ";Window_$a;status=yes,scrollbars=yes,width=640,height=480;";
            $js = new jscript;
            //$plink .= $js->JS_function("js_openwin",$params);    
            $jsscript = $js->JS_function("js_openwin",$params);
            unset ($js);
            //$plink .= ">";	
            //$commands .= $plink . localize('_WND2',getlocal() . "</A>") . "<br>";
		 
            $commands .= seturl("#",localize('_WND2',getlocal()),0,$jsscript) . "<br />";
        }	
	
	    switch ($this->agent) {
		  case 'HTML': 	
	                   $out = _LAYER("window_$this->id","absolute","hidden",1,"visible",
	                                 "1px","24px","10px","10px",
					                 $commands,"left::100%::0::group_win_body::center;100%;::",100);	   
	                   break;
		  //case 'XUL' :  
		  //case 'GTK' :					   
		  case 'XML' : $xhtml = _XLAYER("window_$this->id","absolute","hidden",1,"visible",
	                                    "1px","24px","10px","10px",
					                    $commands,"left::100%::0::group_win_body::center;100%;::",100);	
		               $xml = new pxml();
		               $xml->addtag('SYSMENU',null,$xhtml);
					   $out = $xml->getxml();
					   unset ($xml);			  
		               break;					   
		  case 'WAP' : break;
		  default    : break;			   
	   }				      
	   return ($out);
	}
	
    function printwin($data,$buttons=1) {
	    $a = GetReq('a');
	
	    if (($buttons) && iniload('JAVASCRIPT')) {
	      $js = new jscript;
	      $bclose = $js->JS_function("js_closewin",localize('_CLOSE',getlocal())); 
	      $bprint = $js->JS_function("js_printwin",localize('_PRINT',getlocal()));									 
          unset ($js);
	      $data .= '<br/>' . $bclose . '&nbsp;' . $bprint;
		}

		$printpage = new phtml('',$data);
		$out = $printpage->render();
		unset($printpage);

		return ($out);
	}	

};

class dialog {

    var $body;
	var $title;
	var $button;

    function dialog($title,$message,$url,$command='') {
	
	 $myaction = seturl($url);
	 
     $this->button = "<FORM name=\"dialog\" action=". "$myaction" . " method=post>";
	 $this->button .= "<INPUT type=\"submit\" name=\"submit\" value=\"Ok\">";
     if ($command) $this->button .= "<INPUT type=\"hidden\" name=\"FormAction\" value=\"" . $command . "\">";	
     $this->button .= "</FORM>"; 
	 
	 $this->body = "<div align=\"center\">" . $message . $this->button . "</div>";  
	 $this->title = $title;	
	}
	
	function render() {
	
	 $mywin = new window($this->title,$this->body);
	 $out .= $mywin->render("center::40%::0::group_win_body::left::0::0::");	
	 unset ($mywin);	
	 
	 return ($out);	
	}
};
}
?>