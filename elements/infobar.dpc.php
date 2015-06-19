<?php

$__DPCSEC['INFOBAR_DPC']='2;1;2;2;2;2;2;2;9';
$__DPCSEC['MESSAGES_']='2;1;2;2;2;2;2;2;2';
$__DPCSEC['DATEMSG_']='2;1;2;2;2;2;2;2;2';

if ( (!defined("INFOBAR_DPC")) && (seclevel('INFOBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("INFOBAR_DPC",true);

$__DPC['INFOBAR_DPC'] = 'infobar';

$__PARSECOM['INFOBAR_DPC']['render']='_INFOBAR_';

class infobar {

	var $userLevelID;
	var $agent;    

	function infobar() {
	  $UserSecID = GetGlobal('UserSecID');
      $__USERAGENT = GetGlobal('__USERAGENT');		  	  

	  $this->agent = $__USERAGENT;	  
	  
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	}

    function render($pw='100%',$pal='') { 
	  $UserSecID = GetGlobal('UserSecID');
	  $info = GetGlobal('info');
		
       switch ($this->agent) {	
	   
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
                           if (seclevel('MESSAGES_',$this->userLevelID)) $text = $info;			 
			               if (seclevel('DATEMSG_',$this->userLevelID)) $text .= get_date("DdMY");
						   $xml->addtag('GTKTEXT',null,$text,null);			 		 
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;		   
	         case 'HTML' :	
			 default     :
			                $this->userLevelID = decode($UserSecID);
	  	  	  
                            //info message
                            if (seclevel('MESSAGES_',$this->userLevelID)) {
                              $data[] = "<B>" . $info . "</B>";
                              $attr[] = "left;50%;";	  
                            }
	  
                            //date
                            if (seclevel('DATEMSG_',$this->userLevelID)) {			
                              $data[] = get_date("DdMY");
                              $attr[] = "right;50%;";
                            }

                            $infowin = new window('',$data,$attr);
                            $out = $infowin->render("$pal::100%::0::groupinfo::left;100%;::0::0");
                            unset($infowin); 
	                        break;
	   } 

	   return ($out);
    }

};
}
?>