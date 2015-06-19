<?php

$__DPCSEC['MCHOICE_DPC']='1;1;1;1;1;1;1;1;9';

if ((!defined("MCHOICE_DPC")) && (seclevel('MCHOICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MCHOICE_DPC",true);

$__DPC['MCHOICE_DPC'] = 'multichoice';


class multichoice {
	
	var $userLevelID;
	var $name;
	var $texts;
	var $cform;
	var $initchecked;
	
	function multichoice($choicename='multichoice',$ctextsstring=null,$checked=null,$cform=FALSE) {
	   $UserSecID = GetGlobal('UserSecID');
	
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   
	   $this->name = $choicename;
	   $this->texts = explode(",",$ctextsstring); 
	   $this->cform = $cform;
	   $this->initchecked = $checked;    
	}
	

    function event($action) {  
  
    }
	
    function action($action) {  
  
    }	
  
    function render() {
	
	   if ($this->cform) {
           $myaction = seturl("t=$this->name"); 	   
	   
           $out .= "<form method=\"POST\" action=\"";
           $out .= "$myaction";
           $out .= "\" name=\"$this->name\">";	   
	   }	
	
	   foreach($this->texts as $num => $choice) {
	   
	     if ($this->initchecked==($num)) $chk = 'checked';
		                              else $chk = '';
	   
         $out .= "<input type=\"radio\" name=\"$this->name\" value=\"$choice\" $chk>$choice";
		 $out .= "<br>";
	   }
	   
	   if ($this->cform) { 
         $out .= "<input type=\"hidden\" name=\"FormAction\" value=\"$this->name\">";		 
         $out .= "<input type=\"submit\" name=\"submit\" value=\"Ok\">";		 
         $out .= "</FORM>";		 
	   }						   
					 
	   return ($out);
    } 
	
};
}
?>