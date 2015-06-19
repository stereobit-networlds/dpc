<?php
//PHP5

class xcache /*extends _dpc*/ {  

   protected $userLeveID;  
   
   protected $cachecl;
   protected $cachepath;
   protected $timexarray;
   protected $dayxarray;
   protected $cachexptime;

   function __construct() {	 
	   $UserSecID = GetGlobal('UserSecID'); 
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0); 
	   
       $this->cachecl = arrayload('CACHE','cachepcl');//print_r($this->cachecl);
	   $this->cachepath = paramload('CACHE','path');	   	
	   $this->timexarray = arrayload('CACHE','time');//print_r($this->timexarray);
	   $this->dayxarray = arrayload('CACHE','days'); //print_r($this->dayxarray);
				
	   $this->cachexptime = $this->cachecl[$this->userLevelID] * 
	                        ($this->timexarray[$this->userLevelID]*
							 $this->dayxarray[$this->userLevelID]);
							//paramload('SHELL','cachetime'); 	    
	   
   }  

   public function event($event=null) {
   
   }
   
   public function action($action=null) {
	  
	  return (null);    
   }              
  
  
   //replace html session param with $param
   //mode 1=replace all SESSID=xdsjfslkf35345jsl345kjflks with $param
   //mode 2=replace sjkah234hlk3qwih234jhr string with $param
   protected function replace_htmlsestext($param,$htmltext,$mode=1) {
   
	if (paramload('SHELL','sessionusecookie')) 
	  return ($htmltext);   
    
	switch ($mode) {
       case 1 :	//replace all sesid string
	            $out = str_replace("&SESSID=" . session_id(),$param,$htmltext);	
	            break;
	   case 2 : //replace current sessid
	            $out = str_replace(session_id(),$param,$htmltext);	
	            break;
	}			
	return ($out);
   }	

   //refill htmlsestext with current session id when htmltext has saved with
   //replace_htmlsestext mode=same as this mode & param = same as this param
   protected function refill_htmlsestext($param,$htmltext,$mode=1) {
   
	if (paramload('SHELL','sessionusecookie')) 
	  return ($htmltext);   
    
	switch ($mode) {
       case 1 :	//replace all sesid string
	            $out = str_replace($param,"&SESSID=" . session_id(),$htmltext);	
	            break;
	   case 2 : //replace current sessid
	            $out = str_replace($param,session_id(),$htmltext);	
	            break;
	}			
	return ($out);
   }	
   
   protected function escape_special_chars($str) {
   
     $res1 = str_replace("/","~",$str);
	 $res2 = str_replace("\\","_",$res1);
	 
	 return ($res2);
   }
   
   function __destruct() {
   }
}
?>