<?php

$__DPCSEC['CMDBAR_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['_CMDBAR']='2;1;1;1;1;1;1;1;9';
$__DPCSEC['_CMDICONS']='2;2;2;2;2;2;2;2;9';


if ( (!defined("CMDBAR_DPC")) && (seclevel('CMDBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CMDBAR_DPC",true);

$__DPC['CMDBAR_DPC'] = 'cmdbar';

$__EVENTS['CMDBAR_DPC'][1]='cmdmenu';

$__ACTIONS['CMDBAR_DPC'][1]='cmdmenu';

$__PARSECOM['CMDBAR_DPC']['iconize']='_CMDBAR_';

class cmdbar {

	var $pic;

	function cmdbar() {
	  $UserSecID = GetGlobal('UserSecID');
	  $GRX = GetGlobal('GRX'); 	  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

      if ($GRX)
          $this->pic = loadTheme('point'); 
	  else 
		  $this->pic = "|";	  
	}
	

    function event($event=null) {        
    }
   
    function action($action=null) {   

	   switch ($action) {
	     case 'cmdmenu' : $out = $this->render(); break;
	   }
	   
	   return ($out);
    }	

    function render($pw='100%',$pal='',$viewtype=0) {
	    $__USERAGENT = GetGlobal('__USERAGENT');	

      //if (seclevel('_CMDBAR',$this->userLevelID))  {	
		
        switch ($__USERAGENT) {
	         case 'HTML' :	  	
                           $data = $this->getcom($viewtype);
                           $comwin = new window('',$data);
                           $out = $comwin->render("$pal::$pw::0::groupcomm::left;100%;::0::0");
                           unset ($comwin);	  
						   break;
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=cmdbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=cmdmenu|label=Commands");
					       $xml->addtag('menupopup','menu',null,"id=popup2");				   					   
						   $this->getcom($viewtype,'XML',&$xml,'menupopup');						   								 
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;							   
	    }
	    return ($out);
	 // }	
    }
	
	function getcom($viewtype=0,$source='HTML',$xmlobj=null,$intag=null) {
	  $__ACTIONS = GetGlobal('__ACTIONS');
  
      if ($viewtype) $lf = "<br>";
	            else $lf = "&nbsp;";
  
	  reset($__ACTIONS); //print_r($__ACTIONS);
      //while (list ($dpc_name,$command) = each ($__ACTIONS)) {
      foreach ($__ACTIONS as $dpc_name => $command) {			   
	  
              $command =  $__ACTIONS[$dpc_name][0];
		      //print $dpc_name;
              $alias = localize($dpc_name,getlocal()); 
			  
		      if (!strstr($alias,'_DPC')) { //it means that alias is a valid name else is not translated
		        if (defined($dpc_name)) { //if it dpc is defined 
	      	      if (seclevel($dpc_name,$this->userLevelID))
				   
					switch ($source) {
					  case 'XML'   :
					  case 'GTK'   :
					  case 'XUL'   : $xmlobj->addtag('menuitem','menupopup','/',"label=$alias|cmd=$command");
					                 break;
					  case 'CLI'   :
					  case 'TEXT'  : break;  
					  case 'HTML'  :
				      default      : $out .= seturl("t=$command",$alias) . $lf; 
									   
					}					
				}
			  }
	  }

	  return ($out);
	}
	
	//used bydfxslide65
	function getcom_array() {
	  $__ACTIONS = GetGlobal('__ACTIONS');
  
	  reset($__ACTIONS);
      foreach ($__ACTIONS as $dpc_name => $command) {			   
	  
              $command =  $__ACTIONS[$dpc_name][0];
		      //print $dpc_name;
              $alias = localize($dpc_name,getlocal()); 
			  
		      if (!strstr($alias,'_DPC')) { //it means that alias is a valid name else is not translated
		        if (defined($dpc_name)) { //if it dpc is defined 
	      	      if (seclevel($dpc_name,$this->userLevelID))
					
					$ret_array[$alias] = seturl("t=$command");				
				}
			  }
	  }

	  return ($ret_array);	
	}		
	
	function iconize($vhtype="vertical",$icontype=1,$align="left") {
	    $__USERAGENT = GetGlobal('__USERAGENT');	  
	  
      if (seclevel('_CMDICONS',decode(GetSessionParam('UserSecID')))) {	 
	  
         switch ($__USERAGENT) {
	         case 'XML'  : 
			 case 'XUL'  :	
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=cmdbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=cmdmenu|label=Commands");
					       $xml->addtag('menupopup','menu',null,"id=popup2");				   					   
						   $this->getcom($viewtype,'XML',&$xml,'menupopup');						   								 
					       $out = $xml->getxml();
					       unset($xml);
						   break;	   
  
	         case 'HTML' : $out = $this->_iconize($vhtype,$icontype,$align);	  
		                   break; 
		 }
		 
	     return ($out);	
  	  }
	}
	
	function _iconize($vhtype="vertical",$icontype=1,$align="left") {
	    $GRX = GetGlobal('GRX');
	    $__ACTIONS = GetGlobal('__ACTIONS');	
	    $controller = GetGlobal('controller');				
	
	    reset($__ACTIONS); //print_r($__ACTIONS);
        //while (list ($dpc_name,$command) = each ($__ACTIONS)) {		   
        foreach ($__ACTIONS as $dpc_name => $command) {	
	  
              $command =  $__ACTIONS[$dpc_name][0];
              $alias = localize($dpc_name,getlocal()); 
			  
		      if (!strstr($alias,'_DPC')) { //it means that alias is a valid name else is not translated
		        //if (defined($dpc_name)) { //if it dpc is defined 
				if (isset($command)) {
	      	      if (seclevel($dpc_name,$this->userLevelID)) {
				    
                    if ($GRX) 
					  $ico = icon("/icons/".$dpc_name.".gif","t=$command",
					              $alias,
								  $icontype,
								  $controller->get_attribute($dpc_name,$command,1));
					else 
					  $ico = icon("","t=$command",
					              $alias,
								  1,
								  $controller->getdpc_attribute($dpc_name,$command,1));	
					  
					$icons[$alias] = $ico;  					
				  }
				}
			  }	
		 }
		 
		 if (is_array($icons)) {
		   //sort icons  
	       reset($icons);
		   ksort($icons);	   
           foreach ($icons as $name => $ico) {				 
			    	
					switch ($vhtype)  {
					  default : 
					  case "vertical"  : $out .= $ico;
					                     break;
					  case "horizontal": $icbar[] = $ico;
					                     $icatr[] = "right;";// . floor(100/count($__ACTIONS)) . "%;"; 
					                     break;				                
					  case "line"      : $winout .= $ico . "|";
					                     break;										 
					}		   
		   }
			  
		   switch ($vhtype) {
			    case "horizontal" :
                                     $icobar = new window('',$icbar,$icatr);
                                     $out = $icobar->render("center::100%::0::group_icons::$align::0::0::");
                                     unset ($icobar);	
									 break;
			    case "line" :
                                     $icobar = new window('',$winout);
                                     $out = $icobar->render("center::100%::0::group_icons::$align::0::0::");
                                     unset ($icobar);	
									 break;									 		  
  	       }
		 }  
   
         return ($out);	
	}	
	
};
}
?>