<?php

//require_once("xcache.lib.php");
//GetGlobal('controller')->include_dpc('cache/xcache.lib.php');
$d = GetGlobal('controller')->require_dpc('cache/xcache.lib.php');
require_once($d);

class xcache_def extends xcache {   

   function __construct() {	
   
      xcache::__construct();    
   }  
   //overwrite
   public function event($event=null) {
 
   }
   //overwrite
   public function action($action=null) {
	  
	  return (null);    
   }    
   
   //cacheyes = instead of user properties cache execute
   protected function getcache_def($id,$ext,$func2run,$class='',$param1='',$param2='',$param3='',$param4='',$param5='',$param6='',$param7='',$cacheyes=0) {
	  
	  $contents = null;
	  //$_id = $this->escape_special_chars($id); 
      $_id = md5($this->escape_special_chars($id)); 	  
	  
      $clid = $this->userLevelID;
	  if ((!$clid) || ($cacheyes)) $clid = 0;

      if (($this->cachecl[$clid]) || ($cacheyes)) {

        $cachefile = $this->cachepath . "/" . $_id . "." . $ext;
		//echo $cachefile;

		if (file_exists($cachefile)) {//print $cachefile."<BR>";
          //get file stats
          $st = array();
          $st = stat($cachefile);
          //print_r($st);

          $acctime = $st[9];  //printf("Last modification  : %d\n",$acctime);
          $nowtime = time();  //printf("Time     : %d\n",$nowtime);
          $timeincache = ($nowtime-$acctime); //print $timeincache . ">>";
          //if file is in cash get it..
          //"rb" for win OS "r" for linux/unix
		  $fsize = filesize ($cachefile);
          if ( ($fp = fopen ($cachefile, "r")) && ($timeincache<$this->cachexptime) && ($fsize>0) ) {//print $cachefile.">>";	
		    //$contents = unserialize(fread($fp,filesize ($cachefile))); 
		    $contents = $this->refill_htmlsestext("null",unserialize(fread($fp,$fsize)),2);			
            fclose($fp);   
          } //else write it in cache
          elseif ($fp = fopen ($cachefile, "w")) {
		    //echo ">>>W"; 
            $contents = $class->$func2run($param1,$param2,$param3,$param4,$param5,$param6,$param7);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
          }
          else { //just generate data
		    //echo ">>>C";
            $contents = $class->$func2run($param1,$param2,$param3,$param4,$param5,$param6,$param7);			  		  
		  }	
        } //else write it in cache
        elseif ($fp = fopen ($cachefile, "w")) { //print $cachefile.">>";
		    //echo ">>>>WWW1";
            $contents = $class->$func2run($param1,$param2,$param3,$param4,$param5,$param6,$param7);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
        }
        else { //just generate data
		    //echo ">>>CCC1";
            $contents = $class->$func2run($param1,$param2,$param3,$param4,$param5,$param6,$param7);		
	    }		
      }
      else { //just generate data
	    //echo ">>>CCC0";
        $contents = $class->$func2run($param1,$param2,$param3,$param4,$param5,$param6,$param7); 
  	  } 
	  //print $contents;
	  return ($contents);
   }	
   
   protected function getcache_method_def($id,$ext,$method,$cacheyes=0) {
	  
	  $contents = null;  	
	  //$_id = $this->escape_special_chars($id);	     
      $_id = md5($this->escape_special_chars($id));	  
	  
      $clid = $this->userLevelID;
	  if ((!$clid) || ($cacheyes)) $clid = 0;

      if (($this->cachecl[$clid]) || ($cacheyes)) {

        $cachefile = $this->cachepath . "/" . $_id . "." . $ext;

		if (file_exists($cachefile)) {//print $cachefile.">";
          //get file stats
          $st = array();
          $st = stat($cachefile);
          //print_r($st);

          $acctime = $st[9];  //printf("Last modification  : %d\n",$acctime);
          $nowtime = time();  //printf("Time     : %d\n",$nowtime);
          $timeincache = ($nowtime-$acctime); //print $timeincache . ">>";
          //if file is in cash get it..
          //"rb" for win OS "r" for linux/unix
		  $fsize = filesize ($cachefile);		  
          if ( ($fp = fopen ($cachefile, "r")) && ($timeincache<$this->cachexptime) && ($fsize>0) ) {				
		    //$contents = unserialize(fread($fp,filesize ($cachefile))); 
		    $contents = $this->refill_htmlsestext("null",unserialize(fread($fp,$fsize)),2);			
            fclose($fp);   
          } //else write it in cache
          elseif ($fp = fopen ($cachefile, "w")) {
            $contents = GetGlobal('controller')->calldpc_method($method);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
          }
          else  //just generate data
            $contents = calldpc_method($method);
        } //else write it in cache
        elseif ($fp = fopen ($cachefile, "w")) { //print $cachefile.">>";
            $contents = GetGlobal('controller')->calldpc_method($method);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
        }
        else  //just generate data
            $contents = GetGlobal('controller')->calldpc_method($method);		
      }
      else { //just generate data
        $contents = GetGlobal('controller')->calldpc_method($method);
  	  } 
	  //print $contents;
	  return ($contents);
   }	
   
   protected function getcache_method_use_pointers_def($id,$ext,$method,$varp,$cacheyes=0) {
   
	  //$_id = $this->escape_special_chars($id);   
      $_id = md5($this->escape_special_chars($id));	  
   
      $clid = $this->userLevelID;
	  if ((!$clid) || ($cacheyes)) $clid = 0;

      if (($this->cachecl[$clid]) || ($cacheyes)) {

        $cachefile = $this->cachepath . "/" . $_id . "." . $ext;

		if (file_exists($cachefile)) {//print $cachefile.">";
          //get file stats
          $st = array();
          $st = stat($cachefile);
          //print_r($st);

          $acctime = $st[9];  //printf("Last modification  : %d\n",$acctime);
          $nowtime = time();  //printf("Time     : %d\n",$nowtime);
          $timeincache = ($nowtime-$acctime); //print $timeincache . ">>";
          //if file is in cash get it..
          //"rb" for win OS "r" for linux/unix
  		  $fsize = filesize ($cachefile);
          if ( ($fp = fopen ($cachefile, "r")) && ($timeincache<$this->cachexptime) && ($fsize>0) ) {		
		    //$contents = unserialize(fread($fp,filesize ($cachefile))); 
		    $contents = $this->refill_htmlsestext("null",unserialize(fread($fp,$fsize)),2);			
            fclose($fp);   
          } //else write it in cache
          elseif ($fp = fopen ($cachefile, "w")) {
            $contents = GetGlobal('controller')->calldpc_method_use_pointers($method,$varp);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
          }
          else  //just generate data
            $contents = calldpc_method_use_pointers($method,$varp);	  		  
        } //else write it in cache
        elseif ($fp = fopen ($cachefile, "w")) { //print $cachefile.">>";
            $contents = GetGlobal('controller')->calldpc_method_use_pointers($method,$varp);
			$serialcon = serialize($this->replace_htmlsestext("null",$contents,2));
            fputs($fp, $serialcon);
            fclose($fp);   
        }
        else  //just generate data
            $contents = GetGlobal('controller')->calldpc_method_use_pointers($method,$varp);		
      }
      else { //just generate data
        $contents = GetGlobal('controller')->calldpc_method_use_pointers($method,$varp);
  	  } 
	  //print $contents;
	  return ($contents);
   }                   
   
   function __destruct() {
   
      xcache::__destruct();    
   }   	
}
?>