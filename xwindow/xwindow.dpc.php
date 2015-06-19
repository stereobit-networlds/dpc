<?php

$__DPCSEC['XWINDOW_DPC']='2;1;1;1;1;1;1;2;9';

if  ( (!defined("XWINDOW_DPC")) && (seclevel('XWINDOW_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("XWINDOW_DPC",true);


$__DPC['XWINDOW_DPC'] = 'Xwindow';

$__EVENTS['XWINDOW_DPC'][0]='minimize';
$__EVENTS['XWINDOW_DPC'][1]='maximize';
$__EVENTS['XWINDOW_DPC'][2]='close';
$__EVENTS['XWINDOW_DPC'][3]='print';
$__EVENTS['XWINDOW_DPC'][999]='modeXwin';

$__ACTIONS['XWINDOW_DPC'][0]='modeXwin';
$__ACTIONS['XWINDOW_DPC'][1]='print';

$__DPCATTR['XWINDOW_DPC']['close']    = 'close,0,0,0,0,0,1,0,0,0'; 
$__DPCATTR['XWINDOW_DPC']['maximize'] = 'maximize,0,0,0,0,0,1,0,0,0';
$__DPCATTR['XWINDOW_DPC']['minimize'] = 'minimize,0,0,0,0,0,1,0,0,0';

//$__LOCALE['XWINDOW_DPC'][0]='XWINDOW_DPC;Desktop;Επιφάνεια εργασίας'; 
$__LOCALE['XWINDOW_DPC'][1]='_BLN4;Close;Κλείσιμο';
$__LOCALE['XWINDOW_DPC'][2]='_BLN5;Minimize;Ελαχιστοποίηση';
$__LOCALE['XWINDOW_DPC'][3]='_BLN6;Maximize;Μεγιστοποίηση';
$__LOCALE['XWINDOW_DPC'][4]='_MODIFY;Modify;Επεξεργασία';
$__LOCALE['XWINDOW_DPC'][5]='_UCONTENTS;Unvisible Contents;Μη Αναγνώσιμα';
$__LOCALE['XWINDOW_DPC'][6]='_VISIBLE;Visible;Ορατά';
$__LOCALE['XWINDOW_DPC'][7]='_THEMA;Thema;Θέμα;';
$__LOCALE['XWINDOW_DPC'][8]='_XWND1;Manage Desktop;Επιφάνεια εργασίας';
$__LOCALE['XWINDOW_DPC'][9]='XWINDOW_CNF;Manage Desktop;Επιφάνεια εργασίας';


//include_once("window.lib.php");
GetGlobal('controller')->include_dpc('xwindow/window.lib.php');

class Xwindow extends window {

    var $min;
	var $cls;
	var $xsymbol;
	var $minsymbol;
	var $maxsymbol;
	var $vb;      //enable/disable min/max,close buttons
	
	var $userLevelID;	
	var $agent;
	var $runas;

	function Xwindow($title='',$content='',$attribute=0,$status=1,$visible=1) {
        $__USERAGENT = GetGlobal('__USERAGENT');
	    $GRX = GetGlobal('GRX');	
	    $UserSecID = GetGlobal('UserSecID');
		
		window::window($title,$content,$attribute,$status);
		
		$this->agent = $__USERAGENT;	
		
		$this->runas = $runasuser;
					
	    if (isset($this->runas)) 
	  	  $this->userLevelID = $this->runas; 
	    else	  
          $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);			

		$this->vb = $visible;

        if ($GRX) {   
             $this->editsymbol= loadTheme('edit','',0,"onClick=\"MM_showHideLayers('window_$this->id','','show')\""); 		
             $this->xsymbol   = loadTheme('close',localize('_BLN4',getlocal())); 
             $this->minsymbol = loadTheme('minimize',localize('_BLN5',getlocal())); 
             $this->maxsymbol = loadTheme('maximize',localize('_BLN6',getlocal()));
        } 
        else { 
             $this->editsymbol = '[E]'; 		
             $this->xsymbol   = '[X]'; 
             $this->minsymbol = '[_]'; 
             $this->maxsymbol = '[^]'; 
        }

		$this->min = (array)GetSessionParam("min"); 
        $this->cls = (array)GetSessionParam("cls");
    }

    function event($event=null) {
      $a = GetReq('a');

      switch ($event) {		
		  case "modeXwin"   : $this->reupdate_windows();     	  break; 
          case "minimize"   : if ($a) $this->minimize_window($a); break;					 	
          case "maximize"   : if ($a) $this->maximize_window($a); break;
          case "close"      : if ($a) $this->close_window($a);    break;
		  case "print"      : $prn = $this->printwin(decode(GetSessionParam("window_$a"))); 
		  					  echo $prn; 
							  exit;
							  break;	  
      }  
	  return ($ret);
    }	
	
    function action($action=null) {

	   switch ($action) {
	   
		 case "modeXwin" :
         default         : $out = $this->show_closed_windows();
	   }
	   
	   return ($out);
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

        //select appearance method based on user action
        if ($this->title) {
          if ($this->closed($this->title)) {
			 $whead = "&nbsp"; 
		     $wbody = "";  
		  }
		  elseif ($this->minimized($this->title)) {
		     //if taskbar disabled ...
             if ( (!defined("TASKBAR_DPC")) || (!seclevel('TASKBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {		   
		  	   $whead = $this->winhead(); 
			 }
          }
		  else {
			 $whead = $this->winhead(); 
		     if ($this->state) $wbody = $this->winbody(); 
		  }
        }
		else { //in case of no title print body only
		   $wbody = $this->winbody(); 
		}

        $out = $whead . $wbody;	 
     
	    //if ($drag) return "<div class=\"drag\">" . $out . "</div>";
        //      else return ($out);
			  
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
		                 else 
						   return ($out);
						 break;							 		 
		   default : return ($out);	  
	    }				  
    }

    function winhead() {
        $g = GetReq('a'); 
		
		$gr=urlencode($g);

		if (seclevel('EDITWIN_',$this->userLevelID)) 
		  $header0 = seturl("#",$this->editsymbol);
		else 
		  $header0 = $this->editsymbol;		  
		  
		$data[] = $header0;
		$attr[] = "left;1%;middle;";		
		
        $header1 = "<B>" . $this->title . "</B>";

	    if ($this->minimized($this->title)) 
		   $header2 = seturl("t=maximize&a=$this->title&g=$gr",$this->maxsymbol);
        else 
		   $header2 = seturl("t=minimize&a=$this->title&g=$gr",$this->minsymbol);

		$header2 .= seturl("t=close&a=$this->title&g=$gr",$this->xsymbol);

		$data[] = $header1;
		$attr[] = "left;69%;middle;";

		if ($this->vb) {
		  $data[] = $header2;
		  $attr[] = "right;30%;middle;"; 					   
		}
		
		//$out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,"group_win_head",$data,$attr);

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
           default     : $out = _PRAGMA($this->cp,$this->cs,$this->align,$this->width,$this->border,"group_win_head",$data,$attr);break;
		}    		
		return $out;
    }
	
	//overwrite method
	function winedit() {
	 
	   //$commands = "<B>" . $this->title . "</B><br>";
	   
	   //desktop
	   $commands .= seturl("t=modeXwin&a=&g=" , localize('_XWND1',getlocal()) ) . "<br>";	   
	   
	   //min max close
	   if ($this->minimized($this->title))	      
	     $commands .= seturl("t=maximize&a=$this->title&g=$gr",localize('_BLN6',getlocal())) . "<br>";
	   else
         $commands .= seturl("t=minimize&a=$this->title&g=$gr",localize('_BLN5',getlocal())) . "<br>";		   
	   $commands .= seturl("t=close&a=$this->title&g=$gr",localize('_BLN4',getlocal())) . "<br>";
	   	   
	   //mail
       if ( (defined('MAIL_DPC')) && (seclevel('PARTMAIL_',$this->userLevelID)) )	   
   	     $commands .= seturl("t=mail&a=$this->id&g=", localize('_WND1',getlocal())) . "<br>";
	   
	   //print
        if (iniload('JAVASCRIPT')) {			   
  	     //$plink = "<A href=\"" . seturl("#") . "\"";	   
	     //call javascript for opening a new browser win for printing		   
	     $params = seturl("t=print&a=$this->id&g=") . ";Window_$a;status=yes,scrollbars=yes,width=640,height=480;";
	     $js = new jscript;
		 $jsscript = $js->JS_function("js_openwin",$params);		 
	     //$plink .= $js->JS_function("js_openwin",$params); 
         unset ($js);        
	     //$plink .= ">";			        
  	     //$commands .= $plink . localize('_WND2',getlocal() . "</A>");	
		 
		 $commands .= seturl("#",localize('_WND2',getlocal()),0,$jsscript) . "<br />";		 	   		        
       }
	   
	   //$out = _LAYER("window_$this->id","absolute","hidden",1,"visible",
	     //            "1px","24px","10px","10px",
			//		 $commands,"left::100%::0::group_win_body::center;100%;::",100);	
					 
	    switch ($this->agent) {
		   case 'HTML' : 	
	   
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

	
    function maximized($id) {

      if (in_array($id,$this->min)) return false;
      return true;
    }

    function minimized($id) {

      if (in_array($id,$this->min)) return true;
      return false;
    }

    function closed($id) {
 
      if (in_array($id,$this->cls)) return true;
      return false;
    }


    function minimize_window($id) {

      //insert to min array
      $x = 0;
      if (!in_array($id,$this->min)) {
        reset ($this->min);
        //while (list ($key,$value) = each ($this->min)) {
        foreach ($this->min as $key => $value) {		
          if ($value == "x") {
              $this->min[$key] = $id;
              $x = 1; 
              break;
          } 
        }      
        // if not found x (deleted) item add new
        if ($x==0) $this->min[] = $id;
      }

      $this->setModifiers();
    }

    function maximize_window($id) {

      //delete from min array
      if (in_array($id,$this->min)) {
        reset ($this->min);
        //while (list ($key,$value) = each ($this->min)) {
        foreach ($this->min as $key => $value) {			
          if ($value == $id) {
              $this->min[$key] = "x"; 
              break;
          } 
        }      
      }

      $this->setModifiers();
    }

    function close_window($id) {

      //insert to clr array
      $x = 0;
      if (!in_array($id,$this->cls)) {
        reset ($this->cls);
        //while (list ($key,$value) = each ($this->cls)) {
        foreach ($this->cls as $key => $value) {			
          if ($value == "x") {
              $this->cls[$key] = $id;
              $x = 1; 
              break;
          } 
        }      
        // if not found x (deleted) item add new
        if ($x==0) $this->cls[] = $id;
      }

      $this->setModifiers();
    }

    function open_window($id) {

      //delete from clr array
      if (in_array($id,$this->cls)) {
        reset ($this->cls);
        //while (list ($key,$value) = each ($this->cls)) {
        foreach ($this->cls as $key => $value) {			
          if ($value == $id) {
              $this->cls[$key] = "x"; 
              break;
          } 
        }      
      }

      $this->setModifiers();
    }

    //reset window re-initialize the window
	//delete it from arrays
    function reset_window($id) {

      //delete from clr array
      if (in_array($id,$this->cls)) {
        reset ($this->cls);
        //while (list ($key,$value) = each ($this->cls)) {
        foreach ($this->cls as $key => $value) {			
          if ($value == $id) {
              $this->cls[$key] = "x"; 
              break;
          } 
        }      
      }
      //delete from min array
      if (in_array($id,$this->min)) {
        reset ($this->min);
        //while (list ($key,$value) = each ($this->min)) {
        foreach ($this->min as $key => $value) {			
          if ($value == $id) {
              $this->min[$key] = "x"; 
              break;
          } 
        }      
      }

      $this->setModifiers();
    }

    //delete windows in closed array --inactive !!!!!!!!!!!!!
    function resetModifiers() {

      //delete from clr array
      reset ($this->cls);
      //while (list ($key,$value) = each ($this->cls)) {
      foreach ($this->cls as $key => $value) {	
        $this->cls[$key] = "x";  
      }      

      $this->setModifiers();
    }

    function setModifiers() {

      SetSessionParam("min",$this->min);  
      SetSessionParam("cls",$this->cls);  
    }
	
	function getModifiers() {
	
	  $this->min = (array)GetSessionParam("min"); 
      $this->cls = (array)GetSessionParam("cls");	
	}

	function show_closed_windows() {
	  global $a,$g;
	
      $this->getModifiers();	

      $filename = seturl("t=modeXwin&a=$a&g=$g");	  	  

      //navigation status    	  
      $printit = setNavigator(localize('_UCONTENTS',getlocal()));

      //form
      $printit .= "<form action=\"$filename\" method=\"POST\">";

      //title header	
	  $thema = localize('_THEMA',getlocal());
	  $vis = localize('_VISIBLE',getlocal());
      $data0[] = "<B>$thema</B>"; 
	  $attr0[] = "left";
      $data0[] = "<B>$vis</B>"; 
	  $attr0[] = "right";
	  $printit .= _PRAGMA(0,0,"center","100%",0,"group_form_headtitle",$data0,$attr0);

      //unvisible (cls) window list
      $check = ""; 
	  $meter = 0;
	  //while (list ($win_num, $win_id) = each ($this->cls)) {
      foreach ($this->cls as $win_num => $win_id) {	

		 if (($win_id) && ($win_id!='x')) { 
            
			$meter += 1;  
            $formoption = _with($win_id);  
						 
		    $data1[] = "$win_id";
		    $attr1[] = "left";
		    $data1[] = "<input type=\"checkbox\" name=\"$formoption\" value=\"$check \"". $check . ">";
		    $attr1[] = "right";
		    $printit .= _PRAGMA(0,0,"center","100%",0,"group_form_body",$data1,$attr1);						

		    unset ($data1);
		    unset ($attr1); 
		    unset ($win);
		 }
	  }	  

	  if ($meter) {	  
        $printit .= "<br>";
        $printit .= "<input type=\"submit\" value=\" Ok \">&nbsp;";
        $printit .= "<input type=\"reset\" value=\"Cancel\">";
        $printit .= "<input type=\"hidden\" name=\"FormAction\" value=\"modeXwin\">";	  
      }
	  else {
		$printit .= "";
	  }
      $printit .= "</form>";

      return ($printit);
	}
    
	function reupdate_windows() {

	  //while (list ($key, $value) = each ($this->cls)) {
      foreach ($this->cls as $key => $value) {	
             $myid = _with($value);  
             $myparam = GetParam("$myid"); 

		     if ($myparam) $this->cls[$key] = "x"; 		  
	  }
      $this->setModifiers();
	}
};
}
?>