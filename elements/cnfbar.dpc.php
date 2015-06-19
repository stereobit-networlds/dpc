<?php

$__DPCSEC['CNFBAR_DPC']='2;2;2;2;2;2;2;2;9';
$__DPCSEC['_CNFBAR']='2;1;1;1;1;1;1;1;9';
$__DPCSEC['_CNFICONS']='2;2;2;2;2;2;2;2;9';


if ( (!defined("CNFBAR_DPC")) && (seclevel('CNFBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CNFBAR_DPC",true);

$__DPC['CNFBAR_DPC'] = 'cnfbar';

$__EVENTS['CNFBAR_DPC'][0]='cnfmenu';

$__ACTIONS['CNFBAR_DPC'][0]='cnfmenu';

$__PARSECOM['CNFBAR_DPC']['iconize']='_CNFBAR_';

class cnfbar {

	var $pic;

	function cnfbar() {
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
	     case 'cnfmenu' : $out = $this->render(); break;
	   }	
	   
	   return ($out);
	}

    function render($pw='100%',$pal='',$viewtype=0) {
	    $__USERAGENT = GetGlobal('__USERAGENT');		

      //if (seclevel('_CNFICONS',$this->userLevelID))  {	
		
        switch ($__USERAGENT) {
	         case 'HTML' :	  	
                           $data = $this->getcom($viewtype);
                           $comwin = new window('',$data);
                           $out = $comwin->render("$pal::$pw::0::groupcomm::left;100%;::");
                           unset ($comwin);	  
						   break;
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=cnfbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=cnfmenu|label=Tasks");
					       $xml->addtag('menupopup','menu',null,"id=popup3");				   					   
						   $this->getcom($viewtype,'XML',&$xml,'menupopup');						   								 
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;							   
	    }
	    return ($out);
	  //}
    }
	
	function getcom($viewtype=0,$source='HTML',$xmlobj=null,$intag=null) {
	  $__ACTIONS = GetGlobal('__ACTIONS');
	  $__EVENTS = GetGlobal('__EVENTS');	  
  
      if ($viewtype) $lf = "<br/>";
	            else $lf = "&nbsp;";
  
	  reset($__ACTIONS); //print_r($__ACTIONS);
      //while (list ($dpc_name,$command) = each ($__ACTIONS)) {
      foreach ($__ACTIONS as $dpc_name => $command) {			   
			  
	          if ($this->userLevelID) { //login users
                $command =  $__EVENTS[$dpc_name][999];
                $alias = localize(str_replace('_DPC','_CNF',$dpc_name),getlocal()); 
			  }
			  else { //logout users or unknown users
                $command =  $__EVENTS[$dpc_name][998];
                $alias = localize(str_replace('_DPC','_UNK',$dpc_name),getlocal());			   
			  }			  

              if (isset($command)) {
	      	    if (seclevel($dpc_name,$this->userLevelID)) {

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
	
	function iconize($vhtype="vertical",$icontype=1,$align="left") {
	  $__USERAGENT = GetGlobal('__USERAGENT');		  
	  
      if (seclevel('_CNFICONS',decode(GetSessionParam('UserSecID')))) {	 
	  
         switch ($__USERAGENT) {
	         case 'XML'  : 
			 case 'XUL'  :	
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=cnfbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=cnfmenu|label=Config");
					       $xml->addtag('menupopup','menu',null,"id=popup3");				   					   
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
	    $__ACTIONS = GetGlobal('__ACTIONS');
	    $__EVENTS = GetGlobal('__EVENTS');
	    $GRX = GetGlobal('GRX');	  
	    $controller = GetGlobal('controller');		
  
        //read commands
	    reset($__ACTIONS); //print_r($__ACTIONS);
        //while (list ($dpc_name,$command) = each ($__ACTIONS)) {		   
        foreach ($__ACTIONS as $dpc_name => $command) {	
	  
	          if ($this->userLevelID) { //login users
                $command =  $__EVENTS[$dpc_name][999];
                $alias = localize(str_replace('_DPC','_CNF',$dpc_name),getlocal()); 
			  }
			  else { //logout users or unknown users
                $command =  $__EVENTS[$dpc_name][998];
                $alias = localize(str_replace('_DPC','_UNK',$dpc_name),getlocal());			   
			  }
			  
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
								  $controller->get_attribute($dpc_name,$command,1));
					$icons[$alias] = $ico;  					
				  }
			  }
		}		
		  
		//sort icons  
		if (is_array($icons)) {
		  //print_r($icons);
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