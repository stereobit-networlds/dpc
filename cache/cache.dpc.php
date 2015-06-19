<?php
GetGlobal('controller')->set_security('CACHE_DPC','1;1;1;1;1;1;1;1;1',__FILE__);
GetGlobal('controller')->set_security('RESETCACHE_','9;1;1;1;1;1;1;1;9',__FILE__);

if ((!defined("CACHE_DPC")) ){//&& (seclevel('CACHE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CACHE_DPC",true);

$__DPC['CACHE_DPC'] = 'cache';
//xcache take the name cache to simulate cache calls in code

GetGlobal('controller')->set_event('txt2sql_cache',__FILE__);
GetGlobal('controller')->set_event('reset_cache',__FILE__);

/*$cachemode = paramload('CACHE','mode');
switch ($cachemode) {

case 'D': 
//require_once("xcache_rdb.lib.php");
GetGlobal('controller')->include_dpc('cache/xcache_rdb.lib.php');


class cache extends xcache_rdb {

   private $userLeveID;
   private $mode;
   
   function __construct() {
	   global $UserSecID;   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);    			
       $this->mode = paramload('CACHE','mode'); 	
	   
	   switch($this->mode){
	   	case 'D': xcache_rdb::__construct();
	   		      break;	   
	   	case 'M': xcache_shm::__construct();
	   		      break;
	   	case 'F': xcache_dsk::__construct();
	   	  	      break;
		default : xcache_def::__construct();

	   } // switch	   						
   }

   //cacheyes = instead of user properties cache execute   
   function getcache($id,$ext,$func2run,$class='',$param1='',$param2='',$param3='',$param4='',$param5='',$param6='',$param7='',$cacheyes=0) {

       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_rdb($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_shm($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_dsk($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_def($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }


   function getcache_method($id,$ext,$method,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_rdb($id,$ext,$method,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_shm($id,$ext,$method,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_dsk($id,$ext,$method,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_def($id,$ext,$method,$cacheyes); 
	   	  	      
	   } // switch
	   
	   return ($ret);
   }   
 
   function getcache_method_use_pointers($id,$ext,$method,$varp,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_use_pointers_rdb($id,$ext,$method,$varp,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_use_pointers_shm($id,$ext,$method,$varp,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_use_pointers_dsk($id,$ext,$method,$varp,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_use_pointers_def($id,$ext,$method,$varp,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }      
   
   function __destruct() {
	   switch($this->mode){
	   	case 'D': xcache_rdb::__destruct();
	   		      break;	   
	   	case 'M': xcache_shm::__destruct();
	   		      break;
	   	case 'F': xcache_dsk::__destruct();
	   	  	      break;
		default : xcache_def::__destruct();

	   } // switch	
	   
	   unset($this->mode); 
   }
};
break;


case 'F': 
//require_once("xcache_dsk.lib.php");
* GetGlobal('controller')->include_dpc('cache/xcache_dsk.lib.php');


class cache extends xcache_dsk {

   private $userLeveID;
   private $mode;
   
   function __construct() {
	   global $UserSecID;   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);    			
       $this->mode = paramload('CACHE','mode'); 	
	   
	   switch($this->mode){
	   	case 'D': xcache_rdb::__construct();
	   		      break;	   
	   	case 'M': xcache_shm::__construct();
	   		      break;
	   	case 'F': xcache_dsk::__construct();
	   	  	      break;
		default : xcache_def::__construct();

	   } // switch	   						
   }

   //cacheyes = instead of user properties cache execute   
   function getcache($id,$ext,$func2run,$class='',$param1='',$param2='',$param3='',$param4='',$param5='',$param6='',$param7='',$cacheyes=0) {

       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_rdb($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_shm($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_dsk($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_def($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }


   function getcache_method($id,$ext,$method,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_rdb($id,$ext,$method,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_shm($id,$ext,$method,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_dsk($id,$ext,$method,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_def($id,$ext,$method,$cacheyes); 
	   	  	      
	   } // switch
	   
	   return ($ret);
   }   
 
   function getcache_method_use_pointers($id,$ext,$method,$varp,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_use_pointers_rdb($id,$ext,$method,$varp,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_use_pointers_shm($id,$ext,$method,$varp,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_use_pointers_dsk($id,$ext,$method,$varp,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_use_pointers_def($id,$ext,$method,$varp,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }      
   
   function __destruct() {
	   switch($this->mode){
	   	case 'D': xcache_rdb::__destruct();
	   		      break;	   
	   	case 'M': xcache_shm::__destruct();
	   		      break;
	   	case 'F': xcache_dsk::__destruct();
	   	  	      break;
		default : xcache_def::__destruct();

	   } // switch	
	   
	   unset($this->mode); 
   }
};
break;


case 'M': 
//require_once("xcache_shm.lib.php");
GetGlobal('controller')->include_dpc('cache/xcache_shm.lib.php');


class cache extends xcache_shm {

   private $userLeveID;
   private $mode;
   
   function __construct() {
	   global $UserSecID;   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);    			
       $this->mode = paramload('CACHE','mode'); 	
	   
	   switch($this->mode){
	   	case 'D': xcache_rdb::__construct();
	   		      break;	   
	   	case 'M': xcache_shm::__construct();
	   		      break;
	   	case 'F': xcache_dsk::__construct();
	   	  	      break;
		default : xcache_def::__construct();

	   } // switch	   						
   }

   //cacheyes = instead of user properties cache execute   
   function getcache($id,$ext,$func2run,$class='',$param1='',$param2='',$param3='',$param4='',$param5='',$param6='',$param7='',$cacheyes=0) {

       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_rdb($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_shm($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_dsk($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_def($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }


   function getcache_method($id,$ext,$method,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_rdb($id,$ext,$method,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_shm($id,$ext,$method,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_dsk($id,$ext,$method,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_def($id,$ext,$method,$cacheyes); 
	   	  	      
	   } // switch
	   
	   return ($ret);
   }   
 
   function getcache_method_use_pointers($id,$ext,$method,$varp,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_use_pointers_rdb($id,$ext,$method,$varp,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_use_pointers_shm($id,$ext,$method,$varp,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_use_pointers_dsk($id,$ext,$method,$varp,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_use_pointers_def($id,$ext,$method,$varp,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }      
   
   function __destruct() {
	   switch($this->mode){
	   	case 'D': xcache_rdb::__destruct();
	   		      break;	   
	   	case 'M': xcache_shm::__destruct();
	   		      break;
	   	case 'F': xcache_dsk::__destruct();
	   	  	      break;
		default : xcache_def::__destruct();

	   } // switch	
	   
	   unset($this->mode);  
   }
};
break;


default :*/
//require_once("xcache_def.lib.php");
//GetGlobal('controller')->include_dpc('cache/xcache_def.lib.php');
$d = GetGlobal('controller')->require_dpc('cache/xcache_def.lib.php');
require_once($d);


class cache extends xcache_def {

   protected $userLeveID;
   var $mode;
   
   private $support_languages;
   
   function __construct() {
	   $UserSecID = GetGlobal('UserSecID');   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);    			
       $this->mode = paramload('CACHE','mode'); 
	   
	   $this->support_languages = 1;	
	   
	   switch($this->mode){
	   	case 'D': xcache_rdb::__construct();
	   		      break;	   
	   	case 'M': xcache_shm::__construct();
	   		      break;
	   	case 'F': xcache_dsk::__construct();
	   	  	      break;
		default : xcache_def::__construct();

	   } // switch	   						
   }

   //cacheyes = instead of user properties cache execute   
   function getcache($id,$ext,$func2run,$class='',$param1='',$param2='',$param3='',$param4='',$param5='',$param6='',$param7='',$cacheyes=0) {

       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   if ($this->support_languages) $ext .= strval(getlocal());
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_rdb($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_shm($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_dsk($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_def($id,$ext,$func2run,$class,$param1,$param2,$param3,$param4,$param5,$param6,$param7,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }


   function getcache_method($id,$ext,$method,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   if ($this->support_languages) $ext .= strval(getlocal());	   
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_rdb($id,$ext,$method,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_shm($id,$ext,$method,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_dsk($id,$ext,$method,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_def($id,$ext,$method,$cacheyes); 
	   	  	      
	   } // switch
	   
	   return ($ret);
   }   
 
   function getcache_method_use_pointers($id,$ext,$method,$varp,$cacheyes=0) {   
       //ignore memory mode if php doesn't support memory functions
       if (($this->mode=='M') && !function_exists('shmop_open')) {
           $this->mode = 'F';
       }
	   
	   if ($this->support_languages) $ext .= strval(getlocal());   
	   
	   switch($this->mode){
	   	case 'D': $ret = $this->getcache_method_use_pointers_rdb($id,$ext,$method,$varp,$cacheyes);
	   		      break;	   
	   	case 'M': $ret = $this->getcache_method_use_pointers_shm($id,$ext,$method,$varp,$cacheyes);
	   		      break;
	   	case 'F': $ret = $this->getcache_method_use_pointers_dsk($id,$ext,$method,$varp,$cacheyes); 
	   	  	      break;
		default : $ret = $this->getcache_method_use_pointers_def($id,$ext,$method,$varp,$cacheyes); 

	   } // switch
	   
	   return ($ret);
   }      
   
   function __destruct() {
 
	   switch($this->mode){
	   	case 'D': xcache_rdb::__destruct();
	   		      break;	   
	   	case 'M': xcache_shm::__destruct();
	   		      break;
	   	case 'F': xcache_dsk::__destruct();
	   	  	      break;
		default : xcache_def::__destruct();

	   } // switch	
	   
	   unset($this->mode);   
   }
};

//}//switch

}  
?>